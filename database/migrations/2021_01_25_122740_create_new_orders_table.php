<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('orders');
            $table->float('totalPrice');
            $table->string('paymentReference');
            $table->boolean('requiresBulk');
            $table->longText('bulkSpecifics')->nullable();
            $table->longText('futurePackRequest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
