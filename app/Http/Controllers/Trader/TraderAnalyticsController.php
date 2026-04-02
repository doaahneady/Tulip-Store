<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TraderAnalyticsController extends Controller
{
    protected function getApprovedTraderOrAbort(): Trader
    {
        $user = Auth::guard('trader')->user();
        abort_unless($user && ($user->is_trader ?? false), 403);

        $trader = $user instanceof Trader
            ? $user
            : Trader::where('user_id', $user->id)->first();
        abort_unless($trader, 404);
        abort_unless($trader->status === Trader::STATUS_APPROVED, 403);

        return $trader;
    }

    protected function deliveredStatuses(): array
    {
        return ['delivered', 'completed'];
    }

    protected function periodBounds(?string $range): array
    {
        $now = now();

        return match ($range) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
            default => [null, null],
        };
    }

    public function sales(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        [$from, $to] = $this->periodBounds($request->query('range', 'month'));
        $delivered = $this->deliveredStatuses();

        $base = OrderItem::query()
            ->whereHas('order', function ($q) use ($delivered) {
                $q->whereIn('status', $delivered);
            })
            ->whereHas('product', function ($q) use ($trader) {
                $q->where('trader_id', $trader->id);
            });

        if ($from) {
            $base->whereBetween('created_at', [$from, $to]);
        }

        $totalSales = (float) $base->clone()->sum('total_price');
        $unitsSold = (int) $base->clone()->sum('quantity');
        $ordersCount = (int) $base->clone()->distinct('order_id')->count('order_id');

        $trendRows = $base->clone()
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $trend = $trendRows->map(fn ($r) => ['date' => $r->d, 'revenue' => (float) $r->revenue])->values();

        $salesByCategory = [];
        if (Schema::hasTable('categories')) {
            $salesByCategory = $base->clone()
                ->select('products.category_id', DB::raw('SUM(order_items.total_price) as revenue'))
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->groupBy('products.category_id')
                ->get()
                ->map(function ($row) {
                    $name = Category::find($row->category_id)?->name ?? 'Uncategorized';

                    return ['category_id' => $row->category_id, 'category' => $name, 'revenue' => (float) $row->revenue];
                })->values();
        }

        $byDow = array_fill(0, 7, 0.0);
        $byHour = array_fill(0, 24, 0.0);
        $forTimeDist = $base->clone()->select('created_at', 'total_price')->get();
        foreach ($forTimeDist as $row) {
            $dt = \Illuminate\Support\Carbon::parse($row->created_at);
            $byDow[(int) $dt->dayOfWeek] += (float) $row->total_price;
            $byHour[(int) $dt->format('G')] += (float) $row->total_price;
        }

        return response()->json([
            'success' => true,
            'overview' => [
                'total_sales' => $totalSales,
                'units_sold' => $unitsSold,
                'orders' => $ordersCount,
            ],
            'trend' => $trend,
            'by_category' => $salesByCategory,
            'by_day_of_week' => $byDow,
            'by_hour' => $byHour,
        ]);
    }

    public function exportSales(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        [$from, $to] = $this->periodBounds($request->query('range', 'month'));
        $delivered = $this->deliveredStatuses();
        $base = OrderItem::query()
            ->whereHas('order', function ($q) use ($delivered) {
                $q->whereIn('status', $delivered);
            })
            ->whereHas('product', function ($q) use ($trader) {
                $q->where('trader_id', $trader->id);
            });
        if ($from) {
            $base->whereBetween('created_at', [$from, $to]);
        }
        $rows = $base->clone()
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('d')
            ->orderBy('d')
            ->get();
        $csv = "date,revenue\n";
        foreach ($rows as $r) {
            $csv .= $r->d.','.number_format((float) $r->revenue, 2, '.', '')."\n";
        }
        $filename = 'sales_export_'.now()->format('Ymd_His').'.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function products(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        [$from, $to] = $this->periodBounds($request->query('range', 'month'));
        $delivered = $this->deliveredStatuses();

        $base = OrderItem::query()
            ->whereHas('order', function ($q) use ($delivered) {
                $q->whereIn('status', $delivered);
            })
            ->whereHas('product', function ($q) use ($trader) {
                $q->where('trader_id', $trader->id);
            });
        if ($from) {
            $base->whereBetween('created_at', [$from, $to]);
        }

        $bestSellers = $base->clone()
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->groupBy('product_id')
            ->orderByDesc('units')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $p = Product::find($row->product_id);

                return ['product_id' => $row->product_id, 'name' => $p?->name, 'units' => (int) $row->units];
            });

        $bestRevenue = $base->clone()
            ->select('product_id', DB::raw('SUM(total_price) as revenue'))
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $p = Product::find($row->product_id);

                return ['product_id' => $row->product_id, 'name' => $p?->name, 'revenue' => (float) $row->revenue];
            });

        $traderProductIds = Product::where('trader_id', $trader->id)->pluck('id');
        $salesMap = $base->clone()
            ->select('product_id', DB::raw('COALESCE(SUM(quantity),0) as units'))
            ->groupBy('product_id')->pluck('units', 'product_id');
        $worstPerformers = Product::whereIn('id', $traderProductIds)->get()
            ->map(function ($p) use ($salesMap) {
                return [
                    'product_id' => $p->id,
                    'name' => $p->name,
                    'units' => (int) ($salesMap[$p->id] ?? 0),
                ];
            })
            ->sortBy('units')->take(10)->values();

        $returnRates = [];

        $days30 = [now()->copy()->subDays(30), now()];
        $sold30 = OrderItem::query()
            ->whereBetween('created_at', $days30)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $delivered))
            ->whereIn('product_id', $traderProductIds)
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->groupBy('product_id')->pluck('units', 'product_id');

        $turnover = Product::whereIn('id', $traderProductIds)->get()->map(function ($p) use ($sold30) {
            $sold = (int) ($sold30[$p->id] ?? 0);
            $avgInventory = max(1, (int) $p->stock_quantity);
            $rate = $sold / $avgInventory;

            return ['product_id' => $p->id, 'name' => $p->name, 'turnover_rate' => round($rate, 4)];
        })->sortByDesc('turnover_rate')->values();

        return response()->json([
            'success' => true,
            'best_sellers' => $bestSellers,
            'best_revenue' => $bestRevenue,
            'worst_performers' => $worstPerformers,
            'high_return_rate' => $returnRates,
            'stock_turnover' => $turnover,
        ]);
    }

    public function customers(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        [$from, $to] = $this->periodBounds($request->query('range', 'year'));
        $delivered = $this->deliveredStatuses();

        $ordersQ = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', $delivered)
            ->where('products.trader_id', $trader->id);
        if ($from) {
            $ordersQ->whereBetween('order_items.created_at', [$from, $to]);
        }

        $orders = $ordersQ->select('orders.id as oid', 'orders.user_id', 'order_items.total_price')->get();
        $uniqueCustomers = $orders->pluck('user_id')->filter()->unique()->count();

        $ordersByCustomer = $orders->groupBy('user_id')->map(function ($rows) {
            return $rows->pluck('oid')->unique()->count();
        });
        $repeatCustomers = $ordersByCustomer->filter(fn ($c) => $c > 1)->count();
        $repeatRate = $uniqueCustomers > 0 ? round(($repeatCustomers / $uniqueCustomers) * 100, 2) : 0.0;

        $revenueByOrder = $orders->groupBy('oid')->map(fn ($rows) => $rows->sum('total_price'));
        $aov = $revenueByOrder->count() > 0 ? round($revenueByOrder->sum() / $revenueByOrder->count(), 2) : 0.0;

        $locations = [];

        return response()->json([
            'success' => true,
            'unique_customers' => $uniqueCustomers,
            'repeat_customer_rate' => $repeatRate,
            'average_order_value' => $aov,
            'locations' => $locations,
        ]);
    }

    public function inventory(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $products = Product::where('trader_id', $trader->id)->get();

        $currentInventoryValue = $products->sum(fn ($p) => (float) $p->price * (int) $p->stock_quantity);

        $days30 = [now()->copy()->subDays(30), now()];
        $delivered = $this->deliveredStatuses();
        $sold30 = OrderItem::query()
            ->whereBetween('created_at', $days30)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $delivered))
            ->whereIn('product_id', $products->pluck('id'))
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->groupBy('product_id')->pluck('units', 'product_id');

        $overstocked = [];
        $understocked = [];
        $deadStock = [];

        foreach ($products as $p) {
            $sold = (int) ($sold30[$p->id] ?? 0);
            $stock = (int) $p->stock_quantity;
            if ($stock > max(10, 2 * $sold)) {
                $overstocked[] = ['product_id' => $p->id, 'name' => $p->name, 'stock' => $stock, 'sold_30d' => $sold];
            }
            $threshold = property_exists($p, 'low_stock_threshold') ? ($p->low_stock_threshold ?? 5) : 5;
            if ($stock <= $threshold || ($sold > 0 && $sold > $stock)) {
                $understocked[] = ['product_id' => $p->id, 'name' => $p->name, 'stock' => $stock, 'sold_30d' => $sold];
            }
        }

        $days90 = [now()->copy()->subDays(90), now()];
        $sold90 = OrderItem::query()
            ->whereBetween('created_at', $days90)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $delivered))
            ->whereIn('product_id', $products->pluck('id'))
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->groupBy('product_id')->pluck('units', 'product_id');

        foreach ($products as $p) {
            $sold = (int) ($sold90[$p->id] ?? 0);
            if ($sold === 0 && (int) $p->stock_quantity > 0) {
                $deadStock[] = ['product_id' => $p->id, 'name' => $p->name, 'stock' => (int) $p->stock_quantity];
            }
        }

        $inventoryTurnover = 0.0;
        $totalSold30 = array_sum(array_map('intval', $sold30->toArray()));
        $avgInventory = max(1, (int) $products->sum('stock_quantity'));
        $inventoryTurnover = round($totalSold30 / $avgInventory, 4);

        return response()->json([
            'success' => true,
            'current_inventory_value' => $currentInventoryValue,
            'overstocked' => $overstocked,
            'understocked' => $understocked,
            'dead_stock' => $deadStock,
            'inventory_turnover_rate' => $inventoryTurnover,
        ]);
    }

    public function competitive(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $delivered = $this->deliveredStatuses();

        $revByTrader = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $delivered)
            ->select('products.trader_id', DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('products.trader_id')
            ->get()
            ->pluck('revenue', 'trader_id');

        $platformRevenue = (float) array_sum($revByTrader->toArray());
        $traderRevenue = (float) ($revByTrader[$trader->id] ?? 0.0);

        $traders = Trader::whereIn('id', array_keys($revByTrader->toArray()))->get()->keyBy('id');
        $platformCommissionPaid = 0.0;
        foreach ($revByTrader as $tid => $rev) {
            $rate = (float) ($traders[$tid]->commission_rate ?? 0);
            $platformCommissionPaid += ((float) $rev) * ($rate / 100);
        }
        $traderCommissionPaid = round($traderRevenue * ((float) $trader->commission_rate / 100), 2);
        $platformAvgCommissionRate = $platformRevenue > 0 ? round(($platformCommissionPaid / $platformRevenue) * 100, 4) : 0.0;

        $traderApproved = Product::where('trader_id', $trader->id)->where('status', 'approved')->count();
        $traderTotal = Product::where('trader_id', $trader->id)->count();
        $traderApprovalRate = $traderTotal > 0 ? round(($traderApproved / $traderTotal) * 100, 2) : 0.0;

        $otherApproved = Product::where('trader_id', '!=', $trader->id)->where('status', 'approved')->count();
        $otherTotal = Product::where('trader_id', '!=', $trader->id)->count();
        $platformApprovalRate = $otherTotal > 0 ? round(($otherApproved / $otherTotal) * 100, 2) : 0.0;

        return response()->json([
            'success' => true,
            'sales_vs_platform' => [
                'trader_revenue' => $traderRevenue,
                'platform_revenue' => $platformRevenue,
            ],
            'commission_paid' => [
                'trader_commission' => $traderCommissionPaid,
                'platform_avg_rate' => $platformAvgCommissionRate,
            ],
            'approval_rate' => [
                'trader' => $traderApprovalRate,
                'platform' => $platformApprovalRate,
            ],
        ]);
    }
}
