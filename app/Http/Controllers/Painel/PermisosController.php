<?php

namespace App\Http\Controllers\Painel;

use App\Models\Perfil;
use App\Models\Recurso;
use App\Models\PerfilRecurso;

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

    public function store(Request  $request, PerfilRecurso $obj)
    {
        if($request['privilegios'] OR $request['old_privilegios']) {
            if($obj->setPerfilRecurso($request)) {
                return redirect()->route('permiso.index')->with('success', 'As permissões foram cadastrados');              
                //Input::delete('privilegios');//Para que no queden persistentes
                //Input::delete('old_privilegios');
            } else {
                return redirect()->route('permiso.index')->with('status', 'As permissões não foram cadastrados');
            }
        } else {
            return redirect()->route('permiso.index')->with('status', 'Têm que selecionar pelomenos um recurso');
        }
    	
    }

    // public function store(Request  $request)
    // {
    //     $perfils = Perfil::find($request->input('perfil_id', []));
    //     foreach ($perfils as $value) {
    //         $perfil  = Perfil::find($value->id);
    //         $perfil->recursos()->sync($request->input('privilegios', []));
    //     }

    //     return redirect()->route('permiso.index')->with('success', 'As permissões foram cadastrados');
    // }
}
