<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->float('sum');
            $table->double('sum_in_currency');
            $table->string('currency');
            $table->float('course');
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
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
        Schema::dropIfExists('crypto_payments');
    }
}
