<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        Log::info($role);
        if(array_key_exists('x-auth-token', getallheaders() )){
            $token = getallheaders()['x-auth-token'];
        }else{
            return \Response::json(['status'=>'Failed','msg'=>'Wrong Url, Undefined token key index'],405);
        }
        $row = \App\Models\User::where('access_token',$token)->first();
        if($row){
            $userrole = \DB::table('users_roles')
            ->join('roles','users_roles.role_id','=','roles.id')
            ->where('users_roles.user_id',$row->id)->first();
            if($userrole->name==$role){

            }else{
                return \Response::json(['status'=>'Failed','msg'=>'Forbidden'],403);
            }

        }else{
            return \Response::json(['status'=>'Failed','msg'=>'Invalid'],405);
   
        }
                // if(!$request->user()->hasRole($role)) {
        //      abort(404);
        // }

        // if($permission !== null && !$request->user()->can($permission)) {
        //       abort(404);
        // }

        return $next($request);
    }
}
