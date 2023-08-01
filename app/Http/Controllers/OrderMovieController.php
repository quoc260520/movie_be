<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderMovieRequest;
use App\Models\TimeMovie;
use App\Repositories\OrderMovieRepository;
use App\Repositories\TimeMovieRepository;
use Illuminate\Http\Request;

class OrderMovieController extends Controller
{
    protected $orderMovieRepository;
    protected $timeMovieRepository;
    public function __construct(OrderMovieRepository $orderMovieRepository, TimeMovieRepository $timeMovieRepository)
    {
        $this->orderMovieRepository = $orderMovieRepository;
        $this->timeMovieRepository = $timeMovieRepository;
    }
    public function index()
    {
    }

    public function getOrderByUser(Request $request)
    {
        return $this->responseData($this->orderMovieRepository->getOrderByUser());
    }
    public  function create(OrderMovieRequest $request)
    {
        $couponId = $request->get('coupon_id');
        $checkTime  = $this->timeMovieRepository->getById($request->get('time_id'), [], [['status', '=', TimeMovie::STATUS_OPEN]]);
        if (!$checkTime) {
            return $this->errorResponse([
                'code' => '402',
                'message' => 'Time movie not found',
            ]);
        }
        if ($couponId) {
            $isUser = $this->orderMovieRepository->checkCouponIsUsed($couponId);
            if ($isUser) {
                return $this->errorResponse([
                    'code' => '402',
                    'message' => 'Coupon not invalid',
                ]);
            }
        }
        if (!$this->orderMovieRepository->checkChairPass($request)) {
            return $this->errorResponse([
                'code' => '402',
                'message' => 'Seats are booked',
            ]);
        }
        return $this->responseData($this->orderMovieRepository->create($request));
    }
}
