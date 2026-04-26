<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainify_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // booking_confirmed, booking_cancelled, payout_processed, etc.
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->json('data')->nullable(); // extra context
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainify_notifications');
    }
};
