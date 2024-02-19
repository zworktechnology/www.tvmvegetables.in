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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Request columns
            $table->string('unique_key')->unique();
            $table->string('name');
            $table->string('contact_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('shop_contact_number')->nullable();
            $table->string('old_balance')->nullable();
            $table->string('status')->default(0);
            $table->boolean('soft_delete')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
