<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\TimeMovie;

class MovieRepository
{
    protected $model;

    public function __construct(Movie $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->find($id);
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

    public function getMovieWithTime($id) {
        $movie = $this->model->where('id', $id)
        ->with('timeMovies', function($q) {
            $q->where('status', TimeMovie::STATUS_OPEN);
        })
        ->get();
        return  $movie;
    }
}
