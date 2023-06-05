<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $app_permissions = config('app_permissions');
        $permission_key = 'controller:' . $request->route()->getName();

        if (isset($app_permissions['keys'][ $permission_key ])) {
            $error_message = "No \"{$app_permissions['keys'][ $permission_key ]}\" permission";

            if (! $user = auth()->user()) {
                throw new \Exception($error_message);
            }

            if (!$user->isRoot() AND $group = $user->appUserGroup()->first()) {
                if (!in_array($permission_key, $group->permissions)) {
                    throw new \Exception($error_message);
                }
            }
        }

        return $next($request);
    }
}
