<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('invoice_details', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('store_id')->unsigned();
          $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
          $table->integer('invoice_id')->unsigned();
          $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
          $table->integer('item_id')->unsigned();
          $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
          $table->integer('item_price');
          $table->integer('item_quantity')->default(1);
          $table->integer('total');
          $table->timestamps();
          $table->softDeletes();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
