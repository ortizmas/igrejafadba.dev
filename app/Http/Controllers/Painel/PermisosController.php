<?php

namespace App\Http\Controllers\Painel;

use App\Models\Perfil;
use App\Models\Recurso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermisosController extends Controller
{
    public function index()
    {
    	$recurso = new Recurso();
    	// $perfil = Perfil::where('id', 2)->get()->first();
    	// echo "Perfil: {$perfil->rol} => ";

    	// $recursos = $perfil->recursos;

    	// foreach ($recursos as $recurso) {
    	// 	echo "<b>{$recurso->recurso}</b> | ";
    	// }
    	//$recursos = Recurso::where('id', '>', '1')->groupBy('modulo')->get();
    	$recursos = $recurso->getListadoRecursoPorModulo($estado='todos', $order='controlador');

    	$perfil = new Perfil();
        $perfiles = $perfil->getListadoPerfil(Perfil::ACTIVO);
        $privilegios = $perfil->getPrivilegiosToArray();
    	
    	return view('painel.permisos.index', compact('recursos', 'perfiles', 'privilegios'));
    }

    public function store(Request  $request)
    {
    	$perfil = new Perfil();
    	foreach ($request->privilegios as $value) {
    		$data = explode('-', $value);
    		$recurso = Recurso::find($data[0]);
    		$recurso->perfiles()->sync($data[1]);
    	}

    	return redirect()->route('permiso.index')->with('success', 'As permissões foram cadastrados');
    }
}
