<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

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
 *
 * * @OA\Post(
 *     path="/api/auth/login",
 *     description="Login",
 *     @OA\Response(response="default", description="Login page")
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseData($data) {
        $code = JsonResponse::HTTP_OK;
        if(isset($data['code'])) {
            $code = $data['code'];
            unset($data['code']);
        }
        return response()->json($data,$code);
    }

    public function successResponse($data = ['message' => 'OK'])
    {
        return response()->json($data);
    }

    public function errorResponse($errorData = null)
    {
        $errorData = $errorData ? $errorData : ['errors' => 'An error has occurred'];
        return response()->json($errorData, $errorData['code'] ? $errorData['code'] : JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function resultResponse($data) {
        return !$data['errors'] ? $this->successResponse() : $this->errorResponse();
    }
}
