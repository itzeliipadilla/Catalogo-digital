<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Verifica que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Verifica si el rol del usuario coincide con el requerido
        if ($request->user()->role !== $role) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return $next($request);
    }
}
