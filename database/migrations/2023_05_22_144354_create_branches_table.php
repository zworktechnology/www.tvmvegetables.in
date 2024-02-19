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
        Schema::create('branches', function (Blueprint $table) {

            // Auto-generate ID column
            $table->id();

            // Request columns
            $table->string('unique_key')->unique();
            $table->string('name')->unique();
            $table->string('shop_name');
            $table->string('address');
            $table->string('contact_number');
            $table->string('mail_address')->nullable();
            $table->string('web_address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('soft_delete')->default(0);

            // CreatedAt & UpdatedAt columns
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
