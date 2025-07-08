<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('professors')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('class_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('class_groups')->onDelete('cascade');
            $table->string('weekday');
            $table->time('start_time');
            $table->unsignedInteger('duration_in_min');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_groups');
    }
};
