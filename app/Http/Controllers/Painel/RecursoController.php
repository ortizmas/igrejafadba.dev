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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            return redirect()->route('recurso.index');
        }

        $recurso = Recurso::findOrFail($id);
        return view('painel.recursos.edit')->withRecurso($recurso);;
        
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
        //
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
