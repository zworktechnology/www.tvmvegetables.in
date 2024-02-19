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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->unique();

            $table->unsignedBigInteger('productlist_id');
            $table->foreign('productlist_id')->references('id')->on('productlists')->onDelete('cascade');

            $table->unsignedBigInteger('branchtable_id');
            $table->foreign('branchtable_id')->references('id')->on('branches')->onDelete('cascade');

            $table->string('description')->nullable();
            $table->string('available_stockin_bag')->nullable();
            $table->string('available_stockin_kilograms')->nullable();
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
        Schema::dropIfExists('products');
    }
};
