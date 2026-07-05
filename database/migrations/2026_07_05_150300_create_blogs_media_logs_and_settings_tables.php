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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('causer_type')->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->string('action'); // e.g. created, updated, deleted, restored
            $table->string('model')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['causer_type', 'causer_id']);
            $table->index(['model', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('blogs');
    }
};
