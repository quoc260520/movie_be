<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    protected $model;

    public function __construct(Coupon $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getAllCoupon()
    {
        return $this->model->paginate(env('PAGE', 20));
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $coupon = $this->model->findOrFail($id);
        $coupon->update($data);
        return $coupon;
    }

    public function delete($id)
    {
        $coupon = $this->model->findOrFail($id);
        $coupon->delete();
    }
}
