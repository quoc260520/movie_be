<?php

namespace App\Repositories;

use App\Models\OrderMovie;
use App\Models\TimeMovie;
use Carbon\Carbon;

class TimeMovieRepository
{
    protected $model;

    public function __construct(TimeMovie $model)
    {
        $this->model = $model;
    }
    public function getById($id, $relation = [], $condition = [])
    {
        return $this->model->with($relation)
            ->when(count($condition), function ($q) use ($condition) {
                return $q->where($condition);
            })
            ->find($id);
    }

    public function getTimeMovieByMonth($request)
    {
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();
        $roomId = $request->get('room_id');
        return $this->model
            ->when($roomId, function ($q) use ($roomId) {
                return  $q->whereIn('room_id', $roomId);
            })
            ->whereYear('time_start', $date)
            ->whereMonth('time_start', $date)
            ->with('movie:id,name,time')
            ->get();
    }

    public  function getTimeByRoom($roomIds, $idTime = null)
    {
        if ($idTime) {
            $roomIds = array($roomIds);
        }
        return $this->model->whereIn('room_id', $roomIds)
            ->when($idTime, function ($q) use ($idTime) {
                return $q->where('id', '<>', $idTime);
            })->where('status', TimeMovie::STATUS_OPEN)
            ->get();
    }

    public function create(array $data)
    {
        $dataInsert = [];
        $now = Carbon::now();
        foreach ($data['room_id'] as $room_id) {
            array_push($dataInsert, [
                'room_id' => $room_id,
                'movie_id' => $data['movie_id'],
                'time_start' => $data['time_start'],
                'time_end' => $data['time_end'],
                'status' => array_key_exists('status', $data) ? $data['status'] : TimeMovie::STATUS_CLOSE,
                'price' => $data['price'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        return $this->model->insert($dataInsert);
    }

    public function update($id, array $data)
    {
        $timeMovie = $this->model->findOrFail($id);
        $timeMovie->update($data);
        return $timeMovie;
    }

    public function delete($id)
    {
        $timeMovie = $this->model->findOrFail($id);
        $timeMovie->delete();
    }
    public function checkTimeBeforeInsert($timeRoom, $data, $movie)
    {
        if (!is_array($data['room_id'])) {
            $data['room_id'] = array($data['room_id']);
        }
        foreach ($data['room_id'] as $roomId) {
            $time = $timeRoom->where('room_id', $roomId)
                ->where('time_start', '<', Carbon::parse($data['time_start'])->addMinutes($movie->time))
                ->where('time_start', '>', Carbon::parse($data['time_start'])->subMinutes($movie->time));
            if (count($time)) {
                return false;
            }
        }
        return true;
    }

    public function getTimeByDay($request)
    {
        $date = $request->get('date');
        $roomId = $request->get('room_id');
        return $this->model
            ->when($roomId, function ($q) use ($roomId) {
                return  $q->whereIn('room_id', $roomId);
            })
            ->when($date, function ($q) use ($date) {
                return  $q->whereDate('time_start', $date);
            })
            ->with('movie:id,name,time')
            ->get();
    }
    public function getAllOrderByTimeId($id)
    {
        return $this->model
            ->join('rooms', 'rooms.id', '=', 'time_movies.room_id')
            ->where('time_movies.id', $id)
            ->select('time_movies.*', 'rooms.chair_number')
            ->with('orders', function($q) {
                return $q->whereIn('status', [OrderMovie::STATUS_UNUSED, OrderMovie::STATUS_USED]);
            })
            ->with('orders.orderDetails')
            ->first();
    }
}
