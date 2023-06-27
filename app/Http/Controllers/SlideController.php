<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideRequest;
use App\Repositories\SlideRepository;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    use TraitsController;
    protected $slideRepository;

    public function __construct(SlideRepository $slideRepository)
    {
        $this->slideRepository = $slideRepository;
    }

    public function index(Request $request)
    {
        return $this->slideRepository->getAllSlide();
    }
    public function create(SlideRequest $request)
    {
        return $this->slideRepository->create($request->only(['name']));
    }

    public function update($id, SlideRequest $request)
    {
        return $this->slideRepository->update($id, $request->only(['name']));
    }

    public function delete($id)
    {
        return $this->slideRepository->delete($id);
    }
}
