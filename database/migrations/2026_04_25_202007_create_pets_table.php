<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('species', ['dog', 'cat', 'rabbit', 'bird', 'other']);
            $table->string('breed')->nullable();
            $table->unsignedInteger('age_months');
            $table->enum('size', ['small', 'medium', 'large']);
            $table->enum('activity_level', ['low', 'moderate', 'high']);
            $table->boolean('good_with_kids')->default(false);
            $table->boolean('hypoallergenic')->default(false);
            $table->boolean('is_senior')->default(false);
            $table->enum('status', ['available', 'pending', 'adopted'])->default('available');
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
