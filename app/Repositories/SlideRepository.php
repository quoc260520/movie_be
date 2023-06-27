<?php

namespace App\Repositories;

use App\Models\Slide;

class SlideRepository
{
    protected $model;

    public function __construct(Slide $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAllSlide()
    {
        return $this->model->paginate(env('PAGE', 20));
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $slide = $this->model->findOrFail($id);
        $slide->update($data);
        return $slide;
    }

    public function delete($id)
    {
        $slide = $this->model->findOrFail($id);
        $slide->delete();
    }
}
