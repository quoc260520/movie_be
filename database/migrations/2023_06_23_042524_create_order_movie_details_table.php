<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_movie_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_movie_id');
            $table->unsignedInteger('price');
            $table->unsignedInteger('no_chair');
            $table->foreign('order_movie_id')->references('id')->on('order_movies');
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
        Schema::table('order_movie_details', function (Blueprint $table) {
            $table->dropForeign(['order_movie_id']);
        });
        Schema::dropIfExists('order_movie_details');
    }
};
