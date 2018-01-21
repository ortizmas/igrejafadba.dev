<?php

namespace App\Http\Controllers;
use Auth;
use App\Libraries\DwAcl;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct()
    {
    	
     	$this->middleware(function($request,$next)
 
       {
 
            // $perfil = Auth::user()->perfil_id;
            // if ($perfil == 1) {
           	// 	//return redirect('/painel');
            // } else {
            // 	return redirect('/home');
            // }
            if (Auth::check()) {
                $acl = new DwAcl();
                session()->forget('status');
                if (!$acl->check(Auth::user()->perfil_id)) {
                    //session()->put('status',   'O Senhor não posees privilegios para acceder a <b>' . \Request::url() . '</b>');
                    return redirect('/painel')->with('status',   "O Senhor não posees privilegios para acceder a <b>" . Request::url() . "</b>");
                } else {
                	//return redirect('/home');
                }
            } else {
                //return Redirect('/home');
            }
            
 
           return $next($request);
 
       });
    }

    public function init(Request $request, Closure $next)
    {
    	if ( Gate::denies('adm') ) 
             return redirect()->back();
    }
}
