<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->string('sku', 80)->nullable()->unique()->after('name');
            $table->string('barcode', 120)->nullable()->after('sku');
            $table->string('warehouse', 120)->nullable()->after('department');
            $table->string('shelf', 120)->nullable()->after('warehouse');
            $table->string('bin', 120)->nullable()->after('shelf');
            $table->unsignedInteger('reorder_point')->default(0)->after('bin');
            $table->unsignedInteger('safety_stock')->default(0)->after('reorder_point');
            $table->string('supplier_name', 160)->nullable()->after('safety_stock');
            $table->string('supplier_email')->nullable()->after('supplier_name');
            $table->string('ecommerce_channel', 120)->nullable()->after('supplier_email');
            $table->string('accounting_code', 120)->nullable()->after('ecommerce_channel');
        });
    }

    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn([
                'sku',
                'barcode',
                'warehouse',
                'shelf',
                'bin',
                'reorder_point',
                'safety_stock',
                'supplier_name',
                'supplier_email',
                'ecommerce_channel',
                'accounting_code',
            ]);
        });
    }
};
