<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Repositories\RoomRepository;
use App\Http\Controllers\TraitsController;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="darius@matulionis.lt"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/",
 *     description="Home page",
 *     @OA\Response(response="default", description="Welcome page")
 * )
 */

class RoomController extends Controller
{
    use TraitsController;
    protected $roomRepository;
    
    public function __construct(RoomRepository $roomRepository) {
        $this->roomRepository = $roomRepository;
    }

    public function index(Request $request)
    {
        return $this->roomRepository->getAllRoom();
    }
    public function create(RoomRequest $request)
    {
        return $this->roomRepository->create($request->only(['name', 'chair_number']));
    }

    public function update($id, RoomRequest $request)
    {
        return $this->roomRepository->update($id,$request->only(['name', 'chair_number']));
    }

    public function delete($id)
    {
        return $this->roomRepository->delete($id);
    }   
}
