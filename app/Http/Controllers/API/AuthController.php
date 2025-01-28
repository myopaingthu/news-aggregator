<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Authenticate the user and issue an API token.
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return Response::error('Invalid login details', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token,
        ];
        return Response::success(Response::HTTP_OK, $data);
    }

    /**
     * Revoke the user's current access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response::success(Response::HTTP_OK, [], 'Logged out successfully');
    }
}
