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
        return $this->timeMovieRepository->getTimeMovieByMonth($request);
    }
    public function create(TimeMovieRequest $request)
    {
        $movie = $this->movieRepository->getById($request->get('movie_id'));
        $timeStart = Carbon::parse($request->get('time_start'));
        $timeEnd = Carbon::parse($request->get('time_end'));
        $diffInMinutes = $timeStart->diffInMinutes($timeEnd);
        $checkTime = $diffInMinutes > $movie->time;
        if(!$checkTime) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => [
                    'time_end' => 'Movie show time is not enough',
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $timeRoom = $this->timeMovieRepository->getTimeByRoom($request->get('room_id'));
        if(count($timeRoom)) {
            $flagCheckTime = $this->timeMovieRepository->checkTimeBeforeInsert($timeRoom, $request->all(), $movie);
            if(!$flagCheckTime) {
                return response()->json([
                    'message' => 'The same time must not overlap',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            } 
        }
        return $this->timeMovieRepository->create($request->all());
    }

    public function update($id, TimeMovieUpdateRequest $request)
    {
        $movie = $this->movieRepository->getById($request->get('movie_id'));
        $timeStart = Carbon::parse($request->get('time_start'));
        $timeEnd = Carbon::parse($request->get('time_end'));
        $diffInMinutes = $timeStart->diffInMinutes($timeEnd);
        $checkTime = $diffInMinutes > $movie->time;
        if (!$checkTime) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => [
                    'time_end' => 'Movie show time is not enough',
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $timeRoom = $this->timeMovieRepository->getTimeByRoom($request->get('room_id'), $id);
        if (count($timeRoom)) {
            $flagCheckTime = $this->timeMovieRepository->checkTimeBeforeInsert($timeRoom, $request->all(), $movie);
            if (!$flagCheckTime) {
                return response()->json([
                    'message' => 'The same time must not overlap',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $this->timeMovieRepository->update($id, $request->only(['name']));
    }

    public function delete($id)
    {
        return $this->timeMovieRepository->delete($id);
    }

    public function getTimeByDay(Request $request) {
        return $this->timeMovieRepository->getTimeByDay($request);
    }
}
