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
        Schema::table('users', function (Blueprint $table) {
            // Drop existing timestamp columns
            $table->dropColumn(['created_at', 'updated_at', 'email_verified_at']);
            
            // Add new timestamp columns with proper type
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['created_at', 'updated_at', 'email_verified_at']);
            
            // Add back the original columns
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
        });
    }
}; 