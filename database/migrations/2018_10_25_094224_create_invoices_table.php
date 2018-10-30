<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('invoices', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('store_id')->unsigned();
          $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
          $table->integer('sales_order_id')->unsigned();
          $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
          $table->integer('contact_id')->unsigned();
          $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
          $table->string('number', 20)->unique()->nullable();
          $table->integer('total')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
