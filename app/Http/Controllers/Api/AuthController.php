<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends ApiController
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Membuat user baru
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            // Membuat token untuk user
            $token = JWTAuth::fromUser($user);

            // Mengembalikan response sukses dengan data user dan token
            return $this->successResponse(compact('user', 'token'), 'User registered successfully', 201);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi pengecualian
            return $this->errorResponse('Registration failed', 500, $e->getMessage());
        }
    }

    /**
     * Login a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            // Cek kredensial dan buat token
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->unauthorizedResponse('Invalid credentials');
            }

            // Mengembalikan response sukses dengan token
            return $this->successResponse(compact('token'), 'Login successful');
        } catch (JWTException $e) {
            // Menangani error JWTException dan mengembalikan error response
            return $this->errorResponse('Could not create token', 500, $e->getMessage());
        } catch (\Exception $e) {
            // Menangani error lainnya dan mengembalikan error response
            return $this->errorResponse('Login failed', 500, $e->getMessage());
        }
    }

    /**
     * Logout the user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Menginvalidate token saat logout
            JWTAuth::invalidate(JWTAuth::getToken());

            // Mengembalikan response sukses
            return $this->successResponse([], 'Successfully logged out');
        } catch (JWTException $e) {
            // Menangani error JWTException dan mengembalikan error response
            return $this->errorResponse('Failed to logout', 500, $e->getMessage());
        } catch (\Exception $e) {
            // Menangani error lainnya dan mengembalikan error response
            return $this->errorResponse('Logout failed', 500, $e->getMessage());
        }
    }

    /**
     * Get the authenticated user
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            // Mengembalikan data user yang terautentikasi
            return $this->successResponse(auth()->user(), 'User data retrieved');
        } catch (\Exception $e) {
            // Mengembalikan error response jika terjadi error
            return $this->errorResponse('Failed to retrieve user data', 500, $e->getMessage());
        }
    }
}
