<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surrenders', function (Blueprint $table) {
            $table->id();
            $table->string('submitter_name');
            $table->string('submitter_email');
            $table->string('submitter_phone')->nullable();
            $table->string('pet_name');
            $table->enum('species', ['dog', 'cat', 'rabbit', 'bird', 'hamster', 'other']);
            $table->string('breed')->nullable();
            $table->unsignedTinyInteger('age_years')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            $table->text('reason');
            $table->text('health_notes')->nullable();
            $table->text('behavioral_notes')->nullable();
            $table->enum('status', ['pending', 'contacted', 'accepted', 'declined'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surrenders');
    }
};
