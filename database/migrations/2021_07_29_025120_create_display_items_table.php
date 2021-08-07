<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisplayItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('display_items', function (Blueprint $table) {
            $table->string('id');
            $table->string('stock_id');
            $table->string('item_id');
            $table->integer('amount');
            $table->tinyInteger('status');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('stock_id')->references('id')->on('stock_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('display_items');
    }
}
