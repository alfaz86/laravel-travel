<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Periksa apakah token ada di header
            if (!$token = $request->bearerToken()) {
                // Jika token tidak ditemukan, kembalikan response JSON
                return $this->errorResponse('Authorization Token not found', 401);
            }

            // Coba autentikasi token
            JWTAuth::setToken($token)->authenticate();
        } catch (TokenExpiredException $e) {
            // Jika token kadaluarsa, kembalikan response JSON
            return $this->errorResponse('Token is Expired', 401);
        } catch (TokenInvalidException $e) {
            // Jika token tidak valid, kembalikan response JSON
            return $this->errorResponse('Authorization Token not valid', 401);
        } catch (\Exception $e) {
            // Tangani error lainnya
            return $this->errorResponse('An error occurred while processing the token', 500, $e->getMessage());
        }

        return $next($request); // Melanjutkan permintaan jika token valid
    }
}
