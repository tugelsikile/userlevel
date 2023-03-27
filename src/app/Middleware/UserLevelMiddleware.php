<?php

namespace Tugelsikile\UserLevel\app\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use Tugelsikile\UserLevel\app\Models\Menu;
use Tugelsikile\UserLevel\app\Models\UserPrivilege;

class UserLevelMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = null;
        if (strlen($request->method()) != 'get') {
            $user = auth()->guard('api')->user();
        } else {
            $user = auth()->user();
        }
        if ($user != null) {
            if ($request->isJson()) {
                return response()->json(['message' => 'Forbidden'],401);
            } else {
                throw new \Exception("Forbidden",401);
            }
        } else {
            $currentRoute = Route::getCurrentRoute()->getName();
            $menu = Menu::where('route', $currentRoute)->first();
            if ($menu != null) {
                $priv = UserPrivilege::where('level', $user->level)->where('route', $currentRoute)->first();
                if ($priv != null) {
                    switch (strtolower($request->method())) {
                        default :
                        case 'get' :
                        case 'post' :
                            if (!$priv->r) $this->responseFormat($request,"Forbidden");
                            break;
                        case 'put' :
                            if (!$priv->c) $this->responseFormat($request,"Forbidden");
                            break;
                        case 'patch' :
                            if (!$priv->u) $this->responseFormat($request,"Forbidden");
                            break;
                        case 'delete' :
                            if (!$priv->d) $this->responseFormat($request,"Forbidden");
                            break;
                    }
                }
            }
        }
    }
    private function responseFormat($request, $message, $status = 401) {
        if (strlen($request->method()) != 'get') {
            throw new \Exception($message, $status);
        } else {
            return response()->json(['message' => $message], $status);
        }
    }
}

