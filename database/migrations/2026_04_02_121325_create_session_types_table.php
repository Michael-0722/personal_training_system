<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_profile_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('format', ['1-on-1', 'Group'])->default('1-on-1');
            $table->enum('delivery_mode', ['Online', 'In-Person'])->default('Online');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->decimal('price', 8, 2);
            $table->unsignedSmallInteger('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_types');
    }
};
