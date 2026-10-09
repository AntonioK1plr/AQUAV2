<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        abort_unless($user && $user->is_active && in_array(mb_strtolower($user->role), array_map('mb_strtolower', $roles), true), 403);

        return $next($request);
    }
}
