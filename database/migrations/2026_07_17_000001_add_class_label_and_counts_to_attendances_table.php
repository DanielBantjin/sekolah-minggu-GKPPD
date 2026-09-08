<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('class_label')->nullable()->after('date');
            $table->integer('present_count')->nullable()->after('class_label');
            $table->integer('total_count')->nullable()->after('present_count');
            $table->unsignedBigInteger('student_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['class_label', 'present_count', 'total_count']);
        });
    }
};
