<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Repositories\FileSystem\FileSystemRepository;
use App\Repositories\MovieRepository;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    use TraitsController;
    protected $movieRepository;
    protected $fileSystemRepository;

    public function __construct(MovieRepository $movieRepository, FileSystemRepository $fileSystemRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->fileSystemRepository = $fileSystemRepository;
    }

    public function index(Request $request)
    {
        return $this->responseData($this->movieRepository->getAll($request));
    }

    public function getMovieById($id) {
        return $this->responseData($this->movieRepository->getById($id));
    }
    public function create(MovieRequest $request)
    {
        $data = $request->only([
            'category_id',
            'name',
            'description',
            'author',
            'time',
        ]);
        $data['images'] = json_encode([$this->fileSystemRepository->uploadFile('image')]);
        return $this->responseData($this->movieRepository->create($data));
    }

    public function update($id, MovieRequest $request)
    {
        $data = $request->only([
            'category_id',
            'name',
            'description',
            'author',
            'time',
        ]);
        $data['images'] = json_encode([$this->fileSystemRepository->uploadFile('image')]);
        return $this->responseData($this->movieRepository->update($id, $data));
    }

    public function delete($id)
    {
        return $this->resultResponse($this->movieRepository->delete($id));
    }

    public function getMovieWithTime(Request $request, $id) {
        return $this->responseData($this->movieRepository->getMovieWithTime($request, $id));
    }
}
