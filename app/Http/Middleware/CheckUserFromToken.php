<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class CheckUserFromToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $this->auth->setRequest($request)->getToken();
        if (!$token) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'message' => trans('auth.token_not_provided'),
                'errors' => []
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->refreshToken($token);
        } catch (JWTException $e) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'message' => trans('auth.invalid_token'),
                'errors' => []
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user) {
            return \response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'message' => trans('auth.user_not_found'),
                'errors' => []
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->status === User::INACTIVE) {
            return \response()->json([
                'status_code' => Response::HTTP_PRECONDITION_FAILED,
                'message' => trans('auth.need_active'),
                'errors' => []
            ], Response::HTTP_PRECONDITION_FAILED);
        }
        return $next($request);
    }

    /**
     * Refresh token
     *
     * @param mixed $token Token
     *
     * @return mixed
     */
    private function refreshToken($token)
    {
        try {
            $jwtManager = $this->auth->manager();
            $newToken = $jwtManager->refresh($token);
            return response()->json([
                trans('auth.login.recreate_token'),
                ['token' => $newToken->get()]],
                440
            );
        } catch (TokenExpiredException $e) {
            return response()->json([
                trans('auth.login.token_expired'),
                []],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
