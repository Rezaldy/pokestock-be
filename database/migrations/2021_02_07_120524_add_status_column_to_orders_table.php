<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['new', 'paid', 'paymentConfirmed', 'completed', 'cancelled'])->default('new');
            $table->dropColumn('isPaid');
            $table->dropColumn('paymentConfirmed');
            $table->dropColumn('isCompleted');
            $table->dropColumn('isCancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('isPaid')->default(false);
            $table->boolean('paymentConfirmed')->default(false);
            $table->boolean('isCompleted')->default(false);
            $table->boolean('isCancelled')->default(false);
        });
    }
}
