<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Application|ResponseFactory|Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('admin')->check()) {
            return response(
                ['message' => __('exception.auth.unauthorized')],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }
        return $next($request);
    }
}
