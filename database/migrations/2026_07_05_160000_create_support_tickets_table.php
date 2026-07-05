<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['open', 'pending', 'resolved', 'closed'])->default('open');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
