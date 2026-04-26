<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->json('specializations')->nullable();
            $table->json('tags')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('review_count')->default(0);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedInteger('sessions_completed')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('hourly_rate', 8, 2)->default(0.00);
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_profiles');
    }
};
