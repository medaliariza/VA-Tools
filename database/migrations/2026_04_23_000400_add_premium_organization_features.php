<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'is_premium')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_premium')->default(false)->after('role');
            });
        }

        if (Schema::hasTable('todos') && !Schema::hasColumn('todos', 'assigned_by')) {
            Schema::table('todos', function (Blueprint $table) {
                $table->foreignId('assigned_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            });
        }

        if (Schema::hasTable('reports')) {
            if (!Schema::hasColumn('reports', 'title')) {
                Schema::table('reports', function (Blueprint $table) {
                    $table->string('title', 160)->nullable()->after('user_id');
                });
            }

            if (!Schema::hasColumn('reports', 'requested_by')) {
                Schema::table('reports', function (Blueprint $table) {
                    $table->foreignId('requested_by')->nullable()->after('file')->constrained('users')->nullOnDelete();
                });
            }

            if (!Schema::hasColumn('reports', 'requested_for')) {
                Schema::table('reports', function (Blueprint $table) {
                    $table->foreignId('requested_for')->nullable()->after('requested_by')->constrained('users')->nullOnDelete();
                });
            }

            $driver = DB::getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement("ALTER TABLE reports MODIFY content TEXT NULL");
                DB::statement("ALTER TABLE reports MODIFY status VARCHAR(20) NOT NULL DEFAULT 'submitted'");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE reports ALTER COLUMN content DROP NOT NULL");
                DB::statement("ALTER TABLE reports ALTER COLUMN status TYPE VARCHAR(20)");
                DB::statement("ALTER TABLE reports ALTER COLUMN status SET DEFAULT 'submitted'");
            }

            DB::table('reports')->where('status', 'pending')->update(['status' => 'submitted']);
            DB::table('reports')->where('status', 'approved')->update(['status' => 'reviewed']);
        }
    }

    public function down(): void
    {
        //
    }
};
