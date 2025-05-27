<?php

namespace App\Http\Middleware\Admin;

use App\Traits\Route\RouteTrait;
use Closure;
use App\Enums\AdminType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\Response\AuthResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermission
{
    use AuthResponseTrait ,RouteTrait;
    public function handle(Request $request, Closure $next): Response
    {
        $role = auth('admin')->user()?->role;
        $permissionsList = $role?->permissions->pluck('permission')->filter(fn($p) => Str::startsWith($p, 'admin.'))->toArray();
        $currentRouteName = $request->route()->getName();
        return match (auth('admin')->user()->type) {
            AdminType::SUPER_ADMIN  =>  $next($request),
            AdminType::ADMIN        =>  self::checkThatIsAuthorized($currentRouteName , $permissionsList , $next , $request),
            default                 =>  $next($request) ,
        };
    }

    public static function abortUnauthorized(): Response
    {
        abort(403, 'Unauthorized');
    }

    public function checkThatIsAuthorized($currentRouteName , $permissionsList , Closure $next , Request $request)
    {
       if (!in_array($currentRouteName, $permissionsList) && !in_array($currentRouteName, self::exceptedRoutesFromRoles())) {
        if($request->ajax()){
            return $this->respondUnAuthorized(__('response.unauthorized'));
        }
            return self::abortUnauthorized();
        }
        return $next($request) ;
    }
}
