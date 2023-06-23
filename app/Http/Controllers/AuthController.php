<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\Client as OauthClient;
use Throwable;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(AuthRequest $request) {
        $user = User::where('email', $request->get('email'))->get();
        if (!$user || !Auth::attempt($request->only(['email','password']))) {
            return response()->json([
                'message' => 'Email or password not allowed',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        return $this->getTokenAndRefreshToken($request);
    }
    public function register(AuthRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'unique:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user = User::create([
                'name' => Str::random(6),
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->assignRole(config('permission.roles.user'));

            return $this->getTokenAndRefreshToken($request);
        } catch (Throwable $e) {
            Log::channel('daily')->error($e->getMessage());
            return response()->json([
                'message' => 'Đã có lỗi xảy ra',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function getTokenAndRefreshToken(Request $request)
    {
        $oauthClient = OauthClient::where('password_client', 1)->first();
        $http = new Client();
        $response = $http->request('POST', 'http://127.0.0.1:8000/oauth/token', [
        // $response = $http->request('POST', route('passport.token'), [
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
        return response()->json([
            'response_code' => 200,
            'response_message' => 'Token was refreshed successful!',
            'data' => [
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => 'Bearer',
                'expires_at' => $result['expires_in'],
            ],
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
