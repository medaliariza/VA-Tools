@extends('layouts.app', ['title' => 'Inventory | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Inventory System</h1>
            <p>Track SKUs, barcodes, storage locations, stock thresholds, suppliers, sales channels, and accounting codes from one centralized inventory view.</p>
        </div>
        <span class="pill">{{ $inventoryCount }} Items Tracked</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Add Inventory Item</h3>
            <form method="POST" action="{{ route('inventory.store') }}" class="form-grid">
                @csrf
                <div class="field">
                    <label for="name">Item Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="field">
                    <label for="sku">SKU</label>
                    <input id="sku" type="text" name="sku" value="{{ old('sku') }}" required>
                </div>
                <div class="field">
                    <label for="barcode">Barcode</label>
                    <input id="barcode" type="text" name="barcode" value="{{ old('barcode') }}" placeholder="Auto-filled from SKU if blank">
                </div>
                <div class="field">
                    <label for="qty">Quantity</label>
                    <input id="qty" type="number" name="qty" min="0" value="{{ old('qty') }}" required>
                </div>
                <div class="field">
                    <label for="department">Department</label>
                    <input id="department" type="text" name="department" value="{{ old('department') }}" required>
                </div>
                <div class="field">
                    <label for="warehouse">Warehouse</label>
                    <input id="warehouse" type="text" name="warehouse" value="{{ old('warehouse') }}" required>
                </div>
                <div class="field">
                    <label for="shelf">Shelf</label>
                    <input id="shelf" type="text" name="shelf" value="{{ old('shelf') }}">
                </div>
                <div class="field">
                    <label for="bin">Storage Bin</label>
                    <input id="bin" type="text" name="bin" value="{{ old('bin') }}">
                </div>
                <div class="field">
                    <label for="reorder_point">Reorder Point</label>
                    <input id="reorder_point" type="number" name="reorder_point" min="0" value="{{ old('reorder_point', 0) }}" required>
                </div>
                <div class="field">
                    <label for="safety_stock">Safety Stock</label>
                    <input id="safety_stock" type="number" name="safety_stock" min="0" value="{{ old('safety_stock', 0) }}" required>
                </div>
                <div class="field">
                    <label for="supplier_name">Supplier</label>
                    <input id="supplier_name" type="text" name="supplier_name" value="{{ old('supplier_name') }}">
                </div>
                <div class="field">
                    <label for="supplier_email">Supplier Email</label>
                    <input id="supplier_email" type="email" name="supplier_email" value="{{ old('supplier_email') }}">
                </div>
                <div class="field">
                    <label for="ecommerce_channel">E-commerce Channel</label>
                    <input id="ecommerce_channel" type="text" name="ecommerce_channel" value="{{ old('ecommerce_channel') }}">
                </div>
                <div class="field">
                    <label for="accounting_code">Accounting Code</label>
                    <input id="accounting_code" type="text" name="accounting_code" value="{{ old('accounting_code') }}">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Add Inventory</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Inventory Snapshot</h3>
            <ul class="clean-list">
                <li><span>Total records</span><strong>{{ $inventoryCount }}</strong></li>
                <li><span>Low-stock alerts</span><strong>{{ $lowStockCount }}</strong></li>
                <li><span>SKU and barcode control</span><strong>Enabled</strong></li>
                <li><span>Supplier mapping</span><strong>Purchase-ready</strong></li>
            </ul>
        </article>
    </section>

    <section class="table-card">
        <h3>Current Inventory</h3>
        @if($items->isEmpty())
            <p class="helper-text">No inventory items have been added yet.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Thresholds</th>
                            <th>Supplier</th>
                            <th>Channel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ collect([$item->department, $item->warehouse, $item->shelf, $item->bin])->filter()->join(' / ') }}</td>
                                <td>Reorder {{ $item->reorder_point }} | Safety {{ $item->safety_stock }}</td>
                                <td>{{ $item->supplier_name ?: 'Not mapped' }}</td>
                                <td>{{ $item->ecommerce_channel ?: 'Not connected' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
