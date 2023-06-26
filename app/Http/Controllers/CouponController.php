<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Repositories\CouponRepository;
use App\Repositories\OrderMovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    use TraitsController;
    protected $couponRepository;
    protected $orderMovieRepository;

    public function __construct(CouponRepository $couponRepository, OrderMovieRepository $orderMovieRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->orderMovieRepository = $orderMovieRepository;
    }

    public function index(Request $request)
    {
        return $this->couponRepository->getAllCoupon();
    }
    public function create(CouponRequest $request)
    {
        $data = $request->only(['discount', 'max_discount', 'salary', 'time_start', 'time_end']);
        $data['code'] = Str::upper($request->code);
        return $this->couponRepository->create($data);
    }

    public function update($id, CouponRequest $request)
    {
        $data = $request->only(['discount', 'max_discount', 'salary', 'time_start', 'time_end']);
        $data['code'] = Str::upper($request->code);
        $check = $this->orderMovieRepository->checkCouponById($id);
        if ($check) {
            DB::beginTransaction();
            $coupon = $this->couponRepository->update($id, $data);
            if( $coupon->wasChanged('discount') || $coupon->wasChanged('discount')) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Used discount codes cannot be updated',
                ], JsonResponse::HTTP_BAD_REQUEST);
            }
            DB::commit();
            return $coupon;
        }
        return $this->couponRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->couponRepository->delete($id);
    }
}
