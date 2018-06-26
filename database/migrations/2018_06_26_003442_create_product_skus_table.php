<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('SKU 名称');
            $table->text('description')->comment('SKU 描述');
            $table->decimal('price' , 10 ,3)->comment('SKU 价格');
            $table->unsignedInteger('stock')->default(0)->commit('库存');
            $table->unsignedInteger('product_id')->default(0)->commit('所属商品');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_skus');
    }
}
