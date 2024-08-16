<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id_role = Auth::user()->id_role;
        $level = Auth::user()->level;

        if ($level >= 1) {
            $access = DB::table('user_menu as um')
                ->join('menu as m', 'um.id_menu', '=', 'm.id_menu')
                ->select('um.id_role', 'um.id_menu', 'm.prefix')
                ->where('um.id_role', $id_role)
                ->where('m.prefix', $request->route()->getPrefix())
                ->first();

            if (!$access) {
                throw new ErrorResponse(type: 'Unauthorized', message: 'Anda tidak memiliki akses.', statusCode: 403);
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
