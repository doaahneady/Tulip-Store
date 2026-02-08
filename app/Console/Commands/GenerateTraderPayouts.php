<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Models\Trader;
use App\Models\TraderPayout;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTraderPayouts extends Command
{
    protected $signature = 'trader:payouts:generate {--from=} {--to=}';

    protected $description = 'Generate trader payouts for delivered orders in the given period';

    public function handle(): int
    {
        $fromOpt = $this->option('from');
        $toOpt = $this->option('to');

        if ($fromOpt && $toOpt) {
            $from = \Illuminate\Support\Carbon::parse($fromOpt)->startOfDay();
            $to = \Illuminate\Support\Carbon::parse($toOpt)->endOfDay();
        } else {
            $from = now()->startOfMonth()->subMonth();
            $to = now()->subMonth()->endOfMonth();
        }

        $rows = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereNotNull('products.trader_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('orders.status', ['delivered', 'completed'])
            ->select('products.trader_id', DB::raw('SUM(order_items.total_price) as total_sales'))
            ->groupBy('products.trader_id')
            ->get();

        $count = 0;
        foreach ($rows as $row) {
            $trader = Trader::find($row->trader_id);
            if (! $trader) {
                continue;
            }
            $totalSales = (float) $row->total_sales;
            $rate = (float) $trader->commission_rate;
            $commission = round($totalSales * ($rate / 100), 2);
            $net = round($totalSales - $commission, 2);
            $bank = $trader->payout_settings['bank'] ?? null;

            TraderPayout::create([
                'trader_id' => $trader->id,
                'amount' => $net,
                'currency' => 'USD',
                'status' => 'pending',
                'bank_details' => $bank,
                'notes' => 'period: '.$from->toDateString().' to '.$to->toDateString(),
            ]);
            $count++;
        }

        $this->info('Generated '.$count.' trader payout records.');

        return Command::SUCCESS;
    }
}
