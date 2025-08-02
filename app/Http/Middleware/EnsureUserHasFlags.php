<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasFlags
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$flags): Response
    {
        $user = Auth::user();

        foreach ($flags as $flag) {
            if (!$user->flags->contains('name', $flag)) {
                return response()->json([
                    'message' => 'Você não tem permissão para acessar este recurso',
                    'missing' => $flag
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
