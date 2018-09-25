<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AuthSystem;
use Dao;
class AuthUserDefine
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        $user                    =   \GetUserInfo::getInfo('user_cd');
        $user_auth               =   \GetUserInfo::getInfo('auth_role_div');

        /*$routeController        =   $request->route()->getAction()['controller'];
        $explode                =   explode('@', $routeController);
        $explodeFunction        =   explode("\\", $explode[0]);
        $params['screen']       =   $explodeFunction[4];*/
        
        // check if user is not admin and screen is not dashboard then user accept 
        //if($user_auth != 1 && $user_auth != 3 && $params['screen'] != 'T007Controller' && $params['screen'] != 'T010LController'){
            if (!is_null($user)) {
                $permission     =   AuthSystem::hasPermission();
                $user_auth      =   \GetUserInfo::getInfo('auth_role_div');
                if ($permission) {
                    return $next($request);
                } else {
                    if ($request->ajax()) { 
                        return \Response::json(['status' => '405'], 405); // Unauthorized action.
                    } else {
                        return \Response::view('errors.not-found');
                        //return redirect('/login/success');
                    }
                }
            } else {
                //vulq added url last login 2017/04/19
                \Session::put('url_last_login', $request->url());
                
                return redirect('login');
            }
        //}
        if (!is_null($user)) {
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
