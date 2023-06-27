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
        Schema::create('time_movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('room_id');
            $table->timestamp('time_start');
            $table->timestamp('time_end');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('price');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_movies', function (Blueprint $table) {
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['room_id']);
        });
        Schema::dropIfExists('time_movies');
    }
};
