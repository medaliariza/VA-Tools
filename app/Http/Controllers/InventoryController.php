<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $items = Inventory::query()->latest('id')->get();
        $lowStockCount = $items
            ->filter(fn (Inventory $item) => $item->qty <= max($item->reorder_point, $item->safety_stock))
            ->count();

        return view('inventory.index', [
            'items' => $items,
            'inventoryCount' => $items->count(),
            'lowStockCount' => $lowStockCount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:80', 'unique:inventory,sku'],
            'barcode' => ['nullable', 'string', 'max:120'],
            'qty' => ['required', 'integer', 'min:0'],
            'department' => ['required', 'string', 'max:120'],
            'warehouse' => ['required', 'string', 'max:120'],
            'shelf' => ['nullable', 'string', 'max:120'],
            'bin' => ['nullable', 'string', 'max:120'],
            'reorder_point' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'supplier_name' => ['nullable', 'string', 'max:160'],
            'supplier_email' => ['nullable', 'email', 'max:255'],
            'ecommerce_channel' => ['nullable', 'string', 'max:120'],
            'accounting_code' => ['nullable', 'string', 'max:120'],
        ]);

        $validated['sku'] = strtoupper($validated['sku']);
        $validated['barcode'] = $validated['barcode'] ?: 'BC-'.$validated['sku'];

        Inventory::create($validated);

        return back()->with('status', 'Inventory item added successfully.');
    }
}
