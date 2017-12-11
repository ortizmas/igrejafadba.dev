<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Btn extends Controller
{
    public static function delete()
    {
    	return view('painel.btn.delete')->render();
    }
}
