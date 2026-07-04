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
        Schema::create('admins', function (Blueprint $table) {
    
            // Primary Key
            $table->id();
    
            // Basic Information
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
    
            // Contact Information
            $table->string('phone', 20)->nullable();
    
            // Profile
            $table->string('profile_photo')->nullable();
    
            // Account Status
            $table->boolean('status')->default(true);
    
            // Login Activity
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
    
            // Email Verification (Future)
            $table->timestamp('email_verified_at')->nullable();
    
            // Remember Me
            $table->rememberToken();
    
            // Soft Delete
            $table->softDeletes();
    
            // Created At & Updated At
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
