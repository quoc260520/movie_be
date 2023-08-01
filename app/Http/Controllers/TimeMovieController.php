<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeMovieRequest;
use App\Http\Requests\TimeMovieUpdateRequest;
use App\Repositories\MovieRepository;
use App\Repositories\TimeMovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimeMovieController extends Controller
{
    use TraitsController;
    protected $timeMovieRepository;
    protected $movieRepository;

    public function __construct(TimeMovieRepository $timeMovieRepository, MovieRepository $movieRepository)
    {
        $this->timeMovieRepository = $timeMovieRepository;
        $this->movieRepository = $movieRepository;
    }

    public function index(Request $request)
    {
        return $this->responseData($this->timeMovieRepository->getTimeMovieByMonth($request));
    }
    public function create(TimeMovieRequest $request)
    {
        $movie = $this->movieRepository->getById($request->get('movie_id'));
        $timeStart = Carbon::parse($request->get('time_start'));
        $timeEnd = Carbon::parse($request->get('time_end'));
        $diffInMinutes = $timeStart->diffInMinutes($timeEnd);
        $checkTime = $diffInMinutes > $movie->time;
        if (!$checkTime) {
            return $this->errorResponse([
                'message' => 'Validation error',
                'errors' => [
                    'time_end' => 'Movie show time is not enough',
                ],
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }
        $timeRoom = $this->timeMovieRepository->getTimeByRoom($request->get('room_id'));
        if (count($timeRoom)) {
            $flagCheckTime = $this->timeMovieRepository->checkTimeBeforeInsert($timeRoom, $request->all(), $movie);
            if (!$flagCheckTime) {
                return $this->errorResponse([
                    'message' => 'The same time must not overlap',
                    'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                ]);
            }
        }
        return $this->responseData($this->timeMovieRepository->create($request->all()));
    }

    public function update($id, TimeMovieUpdateRequest $request)
    {
        $movie = $this->movieRepository->getById($request->get('movie_id'));
        $timeStart = Carbon::parse($request->get('time_start'));
        $timeEnd = Carbon::parse($request->get('time_end'));
        $diffInMinutes = $timeStart->diffInMinutes($timeEnd);
        $checkTime = $diffInMinutes > $movie->time;
        if (!$checkTime) {
            return $this->errorResponse([
                'message' => 'Validation error',
                'errors' => [
                    'time_end' => 'Movie show time is not enough',
                ],
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }
        $timeRoom = $this->timeMovieRepository->getTimeByRoom($request->get('room_id'), $id);
        if (count($timeRoom)) {
            $flagCheckTime = $this->timeMovieRepository->checkTimeBeforeInsert($timeRoom, $request->all(), $movie);
            if (!$flagCheckTime) {
                return $this->errorResponse([
                    'message' => 'The same time must not overlap',
                    'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                ]);
            }
        }
        return $this->responseData($this->timeMovieRepository->update($id, $request->only(['name'])));
    }

    public function delete($id)
    {
        return $this->resultResponse($this->timeMovieRepository->delete($id));
    }

    public function getTimeByDay(Request $request)
    {
        return $this->responseData($this->timeMovieRepository->getTimeByDay($request));
    }
    public function getAllOrderByTimeId(Request $request, $id)
    {
        $dataOrders = $this->timeMovieRepository->getAllOrderByTimeId($id);
        $arrayChairOrder = [];
        foreach ($dataOrders->orders as $order) {
            foreach ($order->orderDetails as $detail) {
                array_push($arrayChairOrder, $detail->no_chair);
            }
        }
        $dataOrders['array_chair_order'] = $arrayChairOrder;
        return $this->responseData($dataOrders);
    }
}
