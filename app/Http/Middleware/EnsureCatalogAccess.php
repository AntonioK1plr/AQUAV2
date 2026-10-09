<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCatalogAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless(
            !$user || ($user->is_active && in_array(mb_strtolower($user->role), ['cliente', 'cajero'], true)),
            403,
            'El catálogo está disponible para clientes y cajeros.'
        );

        return $next($request);
    }
}
