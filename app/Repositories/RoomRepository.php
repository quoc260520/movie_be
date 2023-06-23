<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    protected $model;

    public function __construct(Room $model)
    {
        $this->model = $model;
    }
    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAllRoom()
    {
        return $this->model->paginate(env('PAGE', 20));
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $room = $this->model->findOrFail($id);
        $room->update($data);
        return $room;
    }

    public function delete($id)
    {
        $room = $this->model->findOrFail($id);
        $room->delete();
    }
}