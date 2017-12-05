<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Helper;

use App\Models\User;
// use App\Role;
// use App\Permission;
// use App\Post;

class PainelController extends Controller
{
	public function __construct()
    {
        //Helper::shout('now i\'m using my helper class in a controller!!');
    }

    public function index()
    {
    	//$totalUsers = User::count();
    	//$totalRoles = Role::count();
    	//$totalPermissions = Permission::count();
    	//$totalPosts = Post::count();
    	//$IpReal =  getIp(); //\Request::ip();
    	//return view('painel.home.index', compact('totalUsers', 'totalRoles', 'totalPermissions', 'totalPosts', 'IpReal'));
        return view('painel.home.index');
    }
}
