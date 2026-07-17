<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('reflection_id');
            $table->timestamp('read_at');
            $table->integer('duration_seconds')->default(0)->comment('Duration in seconds');
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('reflection_id')->references('id')->on('reflections')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_tracks');
    }
};
