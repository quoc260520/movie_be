<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\TimeMovie;
use Illuminate\Support\Carbon;

class MovieRepository
{
    protected $model;

    public function __construct(Movie $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->with('category:id,name')->find($id);
    }

    public function getAll($request)
    {
        $nameMovie = $request->get('nameMovie');
        $category = $this->model->with('category:id,name')
        ->when($nameMovie, function ($q) use ($nameMovie) {
            return $q->where('name', 'LIKE', ["%$nameMovie%"]);
        })
        ->paginate(env('PAGE', 20));
        return $category;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $movie = $this->model->findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function delete($id)
    {
        $movie = $this->model->findOrFail($id);
        $movie->delete();
    }

    public function getMovieWithTime($request, $id) {
        $now = Carbon::now();
        $date = $request->get('date');
        $movie = $this->model->where('id', $id)
        ->with('timeMovies', function($q) use ($now, $date) {
            $q->where('status', TimeMovie::STATUS_OPEN)
            ->whereDate('time_start', '>=', $now)
            ->when($date, function ($q) use ($date) {
                $q->whereDate('time_start', $date);
            })
            ->orderBy('time_start','asc')
            ->with('room:id,name');
        })
        ->get();
        return  $this->formatData($movie);
    }

    public function formatData($data) {
        $arrayData = [];
        foreach($data[0]->timeMovies as $item) {
            if(!array_key_exists($item->room->name, $arrayData)) {
                $arrayData[$item->room->name] = [];
            }
            array_push($arrayData[$item->room->name], $item);
        }
        return $arrayData;
    }
}
