<?php

namespace App\Repositories;

use App\Models\OrderMovie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderMovieRepository
{
    protected $model;
    protected $couponRepository;
    protected $timeMovieRepository;

    public function __construct(OrderMovie $model, CouponRepository $couponRepository, TimeMovieRepository $timeMovieRepository)
    {
        $this->model = $model;
        $this->couponRepository = $couponRepository;
        $this->timeMovieRepository = $timeMovieRepository;
    }
    public function checkCouponById(int $id) {
    }
    public function create($request) {
        try {
            DB::beginTransaction();
            $timeMovie = $this->timeMovieRepository->getById($request->get('time_id'));
            $order = $this->model->create($request->all());
            $data = array();
            foreach($request->get('no_chair') as $seats) {
                $data[] = [
                    'price' => $timeMovie->price,
                    'no_chair' => $seats
                ];
            }
            $order->orderDetails()->createMany($data);
            DB::commit();
            return $order->toArray();
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('daily')->error($e->getMessage());
        }
    }

    public function getOrderByUser() {
        $user = auth()->id();
        return $this->model->where('user_id', $user)
        ->with('timeMovie', 'orderDetails')
        ->with(['coupon' => function ($q) {
            $q->withTrashed();
        }])
        ->get();
    }
    public function checkCouponIsUsed($id) {
        $user = auth()->id();
        $isUsed = $this->model->where('user_id', $user)
        ->where('coupon_id', $id)->count();
        $isDue = $this->couponRepository->checkCoupon($id);
        return $isUsed && $isDue;
    }
    public function checkChairPass($request) {
        $orderDetailWithTime = $this->model->where('time_id', $request->get('time_id'))
        ->with('orderDetails', 'timeMovie:id,room_id', 'timeMovie.room:id,chair_number')->get();
        foreach($orderDetailWithTime as $orderDetail) {
            if(count($orderDetail->orderDetails)) {
                $check = $orderDetail->orderDetails->whereIn('no_chair', $request->get('no_chair'))->count();
                $numberChair = $orderDetail->timeMovie->room->chair_number;
                $numberInvalid = array_filter($request->get('no_chair'), function($val) use ($numberChair) {
                    return $val > $numberChair;
                });
                if($check || count($numberInvalid)){
                    return false;
                }
            }
        }
        return true;
    }
}
