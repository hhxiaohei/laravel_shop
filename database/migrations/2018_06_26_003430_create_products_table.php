<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('title')->default('')->commit('商品名称');
            $table->text('description')->commit('商品详情');
            $table->string('image')->default('')->commit('商品封面图片文件路径');
            $table->boolean('on_sale')->default(true)->commit('商品是否正在售卖 true 是 false 否');
            $table->float('rating')->default(5)->commit('商品平均评分');
            $table->unsignedTinyInteger('sold_count')->default(0)->commit('销量');
            $table->unsignedTinyInteger('review_count')->default(0)->commit('评价数量');
            $table->decimal('price',10,2)->commit('SKU 最低价格');
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
