<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideRequest;
use App\Repositories\FileSystem\FileSystemRepository;
use App\Repositories\SlideRepository;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    use TraitsController;
    protected $slideRepository;
    protected $fileSystemRepository;


    public function __construct(SlideRepository $slideRepository, FileSystemRepository $fileSystemRepository)
    {
        $this->slideRepository = $slideRepository;
        $this->fileSystemRepository = $fileSystemRepository;
    }

    public function index(Request $request)
    {
        $this->fileSystemRepository->uploadFile('123213');
        return $this->responseData($this->slideRepository->getAllSlide());
    }
    public function create(SlideRequest $request)
    {
        return $this->responseData($this->slideRepository->create($request->only(['name'])));
    }

    public function update($id, SlideRequest $request)
    {
        return $this->responseData($this->slideRepository->update($id, $request->only(['name'])));
    }

    public function delete($id)
    {
        return $this->resultResponse($this->slideRepository->delete($id));
    }
}
