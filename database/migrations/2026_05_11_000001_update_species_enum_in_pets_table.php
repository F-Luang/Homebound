<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN species ENUM('dog', 'cat', 'rabbit', 'bird', 'hamster', 'other') NOT NULL");
        }
        // SQLite does not enforce enum constraints at the DB level; validation handles it in Laravel
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pets MODIFY COLUMN species ENUM('dog', 'cat', 'rabbit', 'bird', 'other') NOT NULL");
        }
    }
};
