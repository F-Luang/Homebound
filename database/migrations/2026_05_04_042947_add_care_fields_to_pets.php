<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->decimal('weight_kg', 5, 2)->nullable()->after('age_months');
            $table->string('food')->nullable()->after('weight_kg');
            $table->string('feeding_time')->nullable()->after('food');
            $table->string('water')->nullable()->after('feeding_time');
            $table->string('medication')->nullable()->after('water');
            $table->string('vet')->nullable()->after('medication');
            $table->string('special_note')->nullable()->after('vet');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn([
                'weight_kg',
                'food',
                'feeding_time',
                'water',
                'medication',
                'vet',
                'special_note',
            ]);
        });
    }
};
