<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if ($request->header('Authorization')) {
                $decode = parseJwt($request->header('Authorization'));

                if ($decode->user) {
                    return $next($request);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthorize',
                'code'    => 401,
            ]);
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unauthorize',
                'code'    => 401,
            ]);
        }
    }
}
