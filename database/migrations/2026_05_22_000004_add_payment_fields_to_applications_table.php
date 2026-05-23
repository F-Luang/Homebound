<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('fee_paid', 8, 2)->nullable()->after('reason');
            $table->string('payment_method')->nullable()->after('fee_paid');
            $table->string('payment_reference')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['fee_paid', 'payment_method', 'payment_reference']);
        });
    }
};
