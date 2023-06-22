<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\Client as OauthClient;
use Throwable;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(AuthRequest $request) {
    }
    public function register(AuthRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'unique:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Email đã tồn tại',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user = User::create([
                'name' => Str::random(6),
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $this->refreshToken($request);
            return response()->json([
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $token->token->expires_at
                )->toDateTimeString()
            ]);
        } catch (Throwable $e) {
            Log::channel('daily')->error($e->getMessage());
            return response()->json([
                'message' => 'Đã có lỗi xảy ra',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function refreshToken(Request $request)
    {
        $oauthClient = OauthClient::where('password_client', 1)->first();
        $http = new Client();
        $response = $http->request('POST', 'http://127.0.0.1:8088/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oauthClient->id,
                'client_secret' => $oauthClient->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return $result;
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
