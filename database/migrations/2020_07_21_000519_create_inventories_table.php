<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('office_id');
            $table->char('codigo', 10);
            $table->double('weight', 10, 3);
            $table->integer('sale_id')->nullable();
            $table->date('dispatched_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->double('sale_price', 10, 3)->default(0);
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
        Schema::dropIfExists('inventories');
    }
}
