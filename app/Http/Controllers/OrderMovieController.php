<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderMovieRequest;
use App\Repositories\OrderMovieRepository;
use Illuminate\Http\Request;

class OrderMovieController extends Controller
{
    protected $orderMovieRepository;
    public function __construct(OrderMovieRepository $orderMovieRepository) {
        $this->orderMovieRepository = $orderMovieRepository;
    }
    public function index() {
        
    }

    public function getOrderByUser(Request $request) {
        return $this->responseData($this->orderMovieRepository->getOrderByUser());
    }
    public  function create(OrderMovieRequest $request) {
        $couponId = $request->get('coupon_id');
        if($couponId) {
            $isUser = $this->orderMovieRepository->checkCouponIsUsed($couponId);
            if(!$isUser) {
                return $this->errorResponse([
                    'code' => '402',
                    'message' => 'Coupon not invalid',
                ]);
            }
        }
        if(!$this->orderMovieRepository->checkChairPass($request)) {
            return $this->errorResponse([
                'code' => '402',
                'message' => 'Seats are booked',
            ]);
        }
        return $this->responseData($this->orderMovieRepository->create($request));
    }
}
