<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidasiAksesMenu
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $menu = $request->route()->getName();


        if (!Auth::user()->can($menu)) {
            return redirect()->route('dashboard')->withErrors([
                'message' => 'User tidak memiliki akses ke menu ini.',
            ]);
        }

        return $next($request);
    }
}
