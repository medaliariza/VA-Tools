<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'fullname')) {
                $table->string('fullname', 120)->nullable()->after('id');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('password');
            }

            if (!Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('role');
            }

            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department', 120)->nullable()->after('is_premium');
            }

            if (!Schema::hasColumn('users', 'organization')) {
                $table->string('organization', 120)->nullable()->after('department');
            }

            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('organization')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('created_by');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
        });

        if (Schema::hasColumn('users', 'name')) {
            DB::table('users')
                ->whereNull('fullname')
                ->update(['fullname' => DB::raw('name')]);
        }
    }

    public function down(): void
    {
        //
    }
};