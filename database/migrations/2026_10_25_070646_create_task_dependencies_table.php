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
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            //  تسک وابسته
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete()->cascadeOnUpdate();
            // تسک پیش نیاز
            $table->foreignId('depends_on_task_id')->constrained('tasks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            // برای جلوگیری از  تکراری بودن وابستگی
            $table->unique(['task_id', 'depends_on_task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};
