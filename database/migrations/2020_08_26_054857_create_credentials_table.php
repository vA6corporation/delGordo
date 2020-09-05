<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('dashboard')->nullable();
            $table->boolean('pos')->nullable();
            $table->boolean('posAdmin')->nullable();
            $table->boolean('products')->nullable();
            $table->boolean('categories')->nullable();
            $table->boolean('subCategories')->nullable();
            $table->boolean('inventories')->nullable();
            $table->boolean('inventoriesAdmin')->nullable();
            $table->boolean('customers')->nullable();
            $table->boolean('sales')->nullable();
            $table->boolean('salesAdmin')->nullable();
            $table->boolean('users')->nullable();
            $table->boolean('dispatchedsSale')->nullable();
            $table->boolean('deliveriesSale')->nullable();
            $table->boolean('deliveries')->nullable();
            $table->boolean('paymentMethods')->nullable();
            $table->boolean('deletedReasons')->nullable();
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
        Schema::dropIfExists('credentials');
    }
}
