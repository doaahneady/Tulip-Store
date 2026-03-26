<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function alerts()
    {
        $lowStockProducts = Product::with(['store', 'trader'])
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return view('dashboards.inventory.alerts', compact('lowStockProducts'));
    }

    public function restock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        DB::transaction(function () use ($product, $request) {
            InventoryMovement::recordMovement(
                $product,
                'in',
                $request->quantity,
                'Manual Restock',
                null,
                $request->notes
            );
        });

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    public function history($id)
    {
        $product = Product::findOrFail($id);
        $movements = $product->inventoryMovements()->latest()->paginate(20);

        return view('dashboards.inventory.history', compact('product', 'movements'));
    }
}
