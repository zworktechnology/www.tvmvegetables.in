<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->unique();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('bill_no')->nullable();

            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            
            $table->string('total_amount')->nullable();
            $table->string('note')->nullable();
            $table->string('extra_cost')->nullable();
            $table->string('gross_amount')->nullable();
            $table->string('old_balance')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('paid_amount')->nullable();
            $table->string('balance_amount')->nullable();

            $table->unsignedBigInteger('sales_payment_id')->nullable();
            $table->string('sales_paymentpaidamount')->nullable();
            $table->string('sales_paymentpending')->nullable();
            $table->string('sales_order')->nullable();

            $table->string('status')->default(0);
            $table->boolean('soft_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
