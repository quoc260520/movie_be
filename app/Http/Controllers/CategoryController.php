<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }
    public function index(Request $request)
    {
        return $this->categoryRepository->getAllCategory();
    }
    public function create(Request $request)
    {
        $nameCategory = $request->only(['name']);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('categories')->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->categoryRepository->create($nameCategory);
    }

    public function update(Request $request, $id)
    {
        $nameCategory = $request->only(['name']);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('categories')->ignore($id)->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->categoryRepository->update($id,$nameCategory);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
