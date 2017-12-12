<?php

namespace App\Http\Controllers\Painel;

use App\Models\Recurso;
use App\Models\Perfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use MyFunction;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($order='recurso.controlador', $page='10')
    {
        $recursos = new Recurso();
        $recursos = $recursos->getListadoRecursoPorModulo('todos', $order, $page);
        
        //$recurso = Recurso::getRecursosPorModulo($recursos[0]->modulo, $order);

        $model = MyFunction::className(Recurso::class);
        $count = $recursos->count();
        return view('painel.recursos.index', compact('recursos','recurso', 'model', 'count'));
    }

    public function lista($order='recurso.controlador', $page='10')
    {
        $recursos = new Recurso();
        $recursos = $recursos->getListadoRecursoPorModulo('todos', $order, $page);
        
        //$recurso = Recurso::getRecursosPorModulo($recursos[0]->modulo, $order);

        $model = MyFunction::className(Recurso::class);
        $count = $recursos->count();
        return view('painel.recursos.lista', compact('recursos','recurso', 'model', 'count'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('painel.recursos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'controlador' => 'required|max:45',
            'descripcion' => 'required|max:150',
        ]);

        $create = Recurso::create($request->all());

        if ($create) {
            return redirect()->route('recurso.index')->with('success', 'O recurso foi criado com sucesso!');
        } else {
            return redirect()->route('recurso.index')->with('status', 'Ocurrio algum erro, e o recurso nçao foi criado!!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function show(Recurso $recurso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function edit($key)
    {

        if(!$id = MyFunction::getKey($key, 'upd_recurso', 'int')) {
            return redirect()->route('recurso.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        }

        $recurso = Recurso::find($id);
        if (!$recurso) {
             return redirect()->route('recurso.index')->with('status', 'Lo sentimos, no se ha podido establecer la información del recurso');
        }

        return view('painel.recursos.edit', compact('recurso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recurso $recurso)
    {
        $update = Recurso::find($recurso->id)->update($request->all());

        if ($update) {
            return redirect()->route('recurso.index')->with('success', 'O recurso foi alterado com sucesso!');
        } else {
            return redirect()->route('recurso.index')->with('status', 'Ocurrio algum erro, e não foi alterado!!');
        }
    }

    /**
     * Método para inactivar/reactivar
     */
    public function estado(Request $request, $tipo, $key) {
        if(!$id = MyFunction::getKey($key, $tipo.'_recurso', 'int')) {
            return redirect()->route('recurso.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        } 

        
        $recurso = new Recurso();
        $recurso = $recurso->find($id);
        if(!$recurso->find($id)) {
            return redirect()->route('recurso.index')->with('success', 'Lo sentimos, no se ha podido establecer la información del recurso'); 
        } else {
            if(empty($recurso->custom) && Auth::user()->perfil_id != Perfil::SUPER_USUARIO) {
                return redirect()->route('recurso.index')->with('status', 'Lo sentimos, pero este recurso no se puede editar.');
            }
            if($tipo=='inactivar' && $recurso->activo == Recurso::INACTIVO) {
                //Flash::info('El recurso ya se encuentra inactivo');
                return redirect()->route('recurso.index')->with('status', 'El recurso ya se encuentra inactivo');
            } else if($tipo=='reactivar' && $recurso->activo == Recurso::ACTIVO) {
                //Flash::info('El recurso ya se encuentra activo');
                return redirect()->route('recurso.index')->with('status', 'El recurso ya se encuentra activo');
            } else {
                $estado = ($tipo=='inactivar') ? Recurso::INACTIVO : Recurso::ACTIVO;

                $dataUpdate = [
                    'activo' => $estado
                ];
                $update = $recurso->find($id)->update($dataUpdate);
                if ($update) {
                    // ($estado==Recurso::ACTIVO) ? redirect()->back()->with('success','El recurso se ha reactivado correctamente!') : redirect()->back()->with('success','El recurso se ha inactivado correctamente!');
                    ($estado==Recurso::ACTIVO) ? $request->session()->flash('success','El recurso se ha reactivado correctamente!') : $request->session()->flash('success','El recurso se ha inactivado correctamente!');
                } 
            }                
        }
        
        return redirect()->route('recurso.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Recurso $recurso, $key='')
    {

        if(!$id = MyFunction::getKey($key, 'eliminar_recurso', 'int')) {
            return redirect()->route('recurso.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        }

        //$recurso = new Recurso();
        if(!$recurso->find($id)) {
            return redirect()->route('recurso.index')->with('success', 'Lo sentimos, no se ha podido establecer la información del recurso'); 
        }      

        try {
            if($recurso->find($id)->delete()) {
                $request->session()->flash('success','El recurso se ha eliminado correctamente!');
            } else {
                $request->session()->flash('status', 'Lo sentimos, pero este recurso no se puede eliminar.');
            }
        } catch(KumbiaException $e) {
            $request->session()->flash('status','Este recurso no se puede eliminar porque se encuentra relacionado con otro registro.');
        }

        return redirect()->route('recurso.index');
    }
}
