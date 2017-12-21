<?php

namespace App\Http\Controllers\Painel;

use App\Models\Menu;
use App\Models\Recurso;
use MyFunction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = new Menu;
        $data['menus'] = $menu->getListadoEdicion(Menu::BACKEND);
        $data['front'] = $menu->getListadoEdicion(Menu::FRONTEND);
        $data['categories'] = Menu::with('children')->where('menu_id','=', NULL)->get();
        return view('painel.menus.index', $data);
    }

    public function lista()
    {
        $menu = new Menu;
        $data['menus'] = $menu->getListadoEdicion(Menu::BACKEND);
        $data['front'] = $menu->getListadoEdicion(Menu::FRONTEND);
        return view('painel.menus.lista', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Hello create menu";
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
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($key)
    {
        if(!$id = MyFunction::getKey($key, 'upd_menu', 'int')) {
            return redirect()->route('menu.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        }

        $menu = Menu::find($id);
        if($menu->id == '1'){
            return redirect()->route('menu.index')->with('status', 'Lo sentimos, este menu não é posivel alterar!!');
        }

        if (!$menu) {
            return redirect()->route('menu.index')->with('status', 'Lo sentimos, no se ha podido establecer la información del menu');
        }

        //Listar Menus
        //$menus = Menu::pluck('nome', 'id')->all();
        $menus = $menu->getListadoMenu($estado=Recurso::ACTIVO, $order='', $page=0);

        //Listar Recursos
        $recurso = new Recurso;
        // $recursos = $recurso->getListaRecursos($estado='todos', $order='', $page=0); 
        $recursos = $recurso->getListadoRecurso($estado=Recurso::ACTIVO, $order='', $page=0);

        return view('painel.menus.edit', compact('menu', 'menus', 'recursos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateMenu = Menu::find($id)->update($request->all());

        if($updateMenu){
            return redirect()->route('menu.index')->with('success', 'O menu foi atualizado com sucesso!!');
        } else {
            return redirect()->route('menu.index')->with('status', 'O menu não foi atualizado tente nova mente!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }

    public function plusOne($x=0)
    {
        if($x<10)
        {
            echo ++$x, "<br />";
            self::plusOne($x);
        }
        else
        {
            echo 'Finished! <br />';
        }
    }
}
