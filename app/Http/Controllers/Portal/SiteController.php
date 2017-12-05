<?php

namespace App\Http\Controllers\Portal;
//use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = Post::all();
        // return view('home', compact('posts'));
        return view('portal.home.index');
    }

    public function rolesPermission(){
        //echo auth()->user()->name;
        $nameUser = auth()->user()->name;
        var_dump("<p>$nameUser</p>");
        echo "<hr>";

        foreach ( auth()->user()->roles as $role) {
            echo $role->name . " => ";

            $permissions = $role->permissions;
            foreach ($permissions as $key => $permission) {
                echo "$permission->name | ";
            }

            echo "<hr>";
        }
    }
}
