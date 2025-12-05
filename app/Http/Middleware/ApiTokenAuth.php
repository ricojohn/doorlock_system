<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiToken = config('app.api_token');
        $providedToken = $request->header('X-API-Token') ?? $request->input('api_token');

        if (! $apiToken || $providedToken !== $apiToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API token.',
            ], 401);
        }

        return $next($request);
    }
}
