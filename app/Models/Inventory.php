<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'qty',
        'department',
        'warehouse',
        'shelf',
        'bin',
        'reorder_point',
        'safety_stock',
        'supplier_name',
        'supplier_email',
        'ecommerce_channel',
        'accounting_code',
    ];
}
