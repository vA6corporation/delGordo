<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
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
            $table->integer('user_id')->nullable();
            $table->integer('customer_id');
            $table->string('channel');
            $table->date('dispatched_date')->nullable();
            $table->date('deliver_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->date('deleted_reason_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->integer('delivery_id');
            $table->integer('deliveryman_id');
            $table->double('delivery_price', 10, 3);
            $table->string('on_model')->default('otros');
            $table->softDeletes();
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
}
