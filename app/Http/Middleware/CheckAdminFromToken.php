<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class CheckAdminFromToken extends BaseMiddleware
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
        $user = currentUserLogin();
        if ($user && $user->is_admin != User::IS_ADMIN) {
            return response()->json([
                'status_code' => Response::HTTP_FORBIDDEN,
                'message' => trans('auth.forbidden'),
                'errors' => []
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
