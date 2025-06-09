<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Si no está autenticado, redirigir a login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar si el rol del usuario está en los permitidos
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }

        return $next($request);
    }
}