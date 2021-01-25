<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('product_listing_id');
            $table->foreign('product_listing_id')
                ->references('id')
                ->on('product_listings');
            $table->integer('quantity');
            $table->boolean('isPaid');
            $table->boolean('isFulfilled');
            $table->dateTime('fulfilledAt');
            $table->unsignedBigInteger('fulfilledBy');
            $table->foreign('fulfilledBy')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }
}
