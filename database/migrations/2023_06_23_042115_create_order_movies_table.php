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
        Schema::create('order_movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('time_id');
            $table->tinyInteger('status');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('time_id')->references('id')->on('time_movies');
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
        Schema::table('order_movies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['coupon_id']);
            $table->dropForeign(['time_id']);
        });
        Schema::dropIfExists('order_movies');
    }
};
