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
        Schema::create('workspace_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user-id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('workspace-id')->constrained('workspaces')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('role' , ['owner' , 'admin' , 'member' , 'viewer']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_users');
    }
};
