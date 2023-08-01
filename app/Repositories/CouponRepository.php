<?php

namespace App\Repositories;

use App\Models\Coupon;
use Carbon\Carbon;

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

    public function checkCoupon($id)
    {
        $now = Carbon::now();
        return $this->model->where('id', $id)
            ->where('time_start', '>=', $now)
            ->where('time_end', '<', $now)->count();
    }
    public function apply($request)
    {
        $code = $request->get('code');
        $coupon = $this->model->query()->where('code', $code)->first();
        return $coupon;
    }
}
