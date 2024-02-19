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
        Schema::create('invites', function (Blueprint $table) {
            
            // Auto-generate ID column
            $table->id();

            // Request columns
            $table->string('unique_key')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('contact_number')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('token', 16)->unique();

            // CreatedAt & UpdatedAt columns
            $table->timestamp('invite_accepted_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
