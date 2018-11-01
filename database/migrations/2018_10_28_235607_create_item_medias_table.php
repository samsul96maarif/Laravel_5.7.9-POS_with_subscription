<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_medias', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('item_id')->unsigned();
          $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
          $table->string('image');
          $table->boolean('is_main')->default('1');
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
        Schema::dropIfExists('item_medias');
    }
}
