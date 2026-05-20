<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('posted_by');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('pet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diary_entries');
    }
};
