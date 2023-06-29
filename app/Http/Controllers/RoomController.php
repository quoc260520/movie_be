<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Repositories\RoomRepository;
use App\Http\Controllers\TraitsController;
use Illuminate\Http\Request;



class RoomController extends Controller
{
    use TraitsController;
    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository) {
        $this->roomRepository = $roomRepository;
    }

    public function index(Request $request)
    {
        return $this->responseData($this->roomRepository->getAllRoom());
    }
    public function create(RoomRequest $request)
    {
        return $this->responseData($this->roomRepository->create($request->only(['name', 'chair_number'])));
    }

    public function update($id, RoomRequest $request)
    {
        return $this->responseData($this->roomRepository->update($id,$request->only(['name', 'chair_number'])));
    }

    public function delete($id)
    {
        return $this->resultResponse($this->roomRepository->delete($id));
    }
}
