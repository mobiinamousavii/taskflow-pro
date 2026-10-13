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
        Schema::table('workspace_users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->foreignId('role_id')->after('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_users', function (Blueprint $table) {
              $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
        });
    }
};
