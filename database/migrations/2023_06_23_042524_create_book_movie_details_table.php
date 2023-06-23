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
        Schema::create('book_movie_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_movie_id');
            $table->unsignedInteger('price');
            $table->unsignedInteger('no_chair');
            $table->foreign('book_movie_id')->references('id')->on('book_movies');
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
        Schema::table('book_movie_details', function (Blueprint $table) {
            $table->dropForeign(['book_movie_id']);
        });
        Schema::dropIfExists('book_movie_details');
    }
};
