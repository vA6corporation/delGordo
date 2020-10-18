<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->boolean('disabled')->default(1);
            $table->double('sale_price', 10, 3)->default(0);
            $table->string('description')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('image_url', 250)->nullable();
            $table->string('unit_code');
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
        Schema::dropIfExists('products');
    }
}
