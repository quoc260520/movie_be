<?php

namespace App\Repositories;

use App\Models\OrderMovie;

class OrderMovieRepository
{
    protected $model;

    public function __construct(OrderMovie $model)
    {
        $this->model = $model;
    }
    public function checkCouponById(int $id) {
        return $this->model->where('coupon_id', $id)->count();
    }
}
