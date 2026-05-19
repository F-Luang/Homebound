<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'under_review',
                'meet_greet',
                'home_check',
                'approved',
                'rejected',
                'completed',
                'cancelled',
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'under_review',
                'meet_greet',
                'home_check',
                'approved',
                'rejected',
                'completed',
            ])->default('pending')->change();
        });
    }
};
