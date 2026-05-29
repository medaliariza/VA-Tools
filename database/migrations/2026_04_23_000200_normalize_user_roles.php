<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'role')) {
            return;
        }

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(20) NOT NULL DEFAULT 'user'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'user'");
        }

        DB::table('users')
            ->whereIn('role', ['employee', 'hr', 'gm'])
            ->update(['role' => 'user']);
    }

    public function down(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::table('users')
            ->where('role', 'user')
            ->update(['role' => 'employee']);

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'gm', 'hr', 'employee') NOT NULL DEFAULT 'employee'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'employee'");
        }
    }
};
