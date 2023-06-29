<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Movie;

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

    public function getAll()
    {
        return $this->model->with('category:id,name')->paginate(env('PAGE', 20));
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
}
