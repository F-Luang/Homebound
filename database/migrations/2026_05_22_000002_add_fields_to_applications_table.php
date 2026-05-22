<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('home_type', ['apartment', 'house', 'condo', 'other'])->after('pet_id');
            $table->boolean('has_yard')->default(false)->after('home_type');
            $table->boolean('has_other_pets')->default(false)->after('has_yard');
            $table->string('other_pets_description')->nullable()->after('has_other_pets');
            $table->boolean('has_children')->default(false)->after('other_pets_description');
            $table->string('children_ages')->nullable()->after('has_children');
            $table->enum('experience', ['first_time', 'some', 'experienced'])->after('children_ages');
            $table->tinyInteger('hours_alone')->unsigned()->nullable()->after('experience');
            $table->text('reason')->after('hours_alone');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'home_type', 'has_yard', 'has_other_pets', 'other_pets_description',
                'has_children', 'children_ages', 'experience', 'hours_alone', 'reason',
            ]);
        });
    }
};
