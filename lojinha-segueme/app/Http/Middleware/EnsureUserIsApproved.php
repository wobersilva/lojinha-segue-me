<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if ($request->user()) {
            // Se não está aprovado, faz logout e redireciona
            if (!$request->user()->isApproved()) {
                auth()->logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Sua conta ainda não foi aprovada. Aguarde a aprovação de um administrador.');
            }
        }

        return $next($request);
    }
}
