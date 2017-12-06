<?php

namespace App\Http\Controllers\Painel;

use App\Models\Recurso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        //$recursos = Recurso::paginate();
        $recursos = Recurso::getListadoRecursoPorModulo('todos', $order, $page);
        $recurso = Recurso::getRecursosPorModulo($recursos[0]->modulo, $order);
        $model = MyFunction::className(Recurso::class);
        $count = $recursos->count();
        return view('painel.recursos.index', compact('recursos','recurso', 'model', 'count'));
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recurso $recurso)
    {
        //
    }
}
