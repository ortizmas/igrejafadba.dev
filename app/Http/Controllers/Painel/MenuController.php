<?php

namespace App\Http\Controllers\Painel;

use App\Models\Menu;
use App\Models\Recurso;
use App\Models\Perfil;
use MyFunction;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    
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
    public function create(Menu $menu)
    {

        //Listar Menus
        //$menus = Menu::pluck('nome', 'id')->all();
        $menus = $menu->getListadoMenu($estado=Recurso::ACTIVO, $order='', $page=0);

        //Listar Recursos
        $recurso = new Recurso;
        // $recursos = $recurso->getListaRecursos($estado='todos', $order='', $page=0); 
        $recursos = $recurso->getListadoRecurso($estado=Recurso::ACTIVO, $order='', $page=0);

        return view('painel.menus.create', compact('menu', 'menus', 'recursos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Menu $menu)
    {
        //$menu = new Menu();

        $request->validate([
            'nome' => 'required',
            'url' => 'required|unique:menu'
        ]);

        // $dataForm = [
        //     'menu_id' => $request['menu_id'],
        //     'recurso_id' => $request['recurso_id'], 
        //     'nome'  => $request['nome'], 
        //     'url' => $request['url'], 
        //     'posicion' => $request['posicion'], 
        //     'icono' => $request['icono'], 
        //     'activo' => Recurso::ACTIVO, 
        //     'visibilidad' => $request['visibilidad'], 
        //     'custom' => (Auth::user()->perfil_id == Perfil::SUPER_USUARIO) ? 0 : 1
        // ];

        // $save = Menu::create($dataForm);
        $save = $menu->saveMenu($request->all()); //chamamos a função do modelo

        if ($save) {
            return redirect()->back()->with('success', 'Menu cadastrado com success!!');
        } else {
            return redirect()->back()->with('status', 'Ocurrio algum erro, e o monu não foi criado!!');
        }
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
        $menu = new Menu();

        $request->validate([
            'nome' => 'required',
            'url' => 'required'
        ]);

        // $dataForm = [
        //     'menu_id' => $request['menu_id'],
        //     'recurso_id' => $request['recurso_id'], 
        //     'nome'  => $request['nome'], 
        //     'url' => $request['url'], 
        //     'posicion' => $request['posicion'], 
        //     'icono' => $request['icono'], 
        //     'activo' => Recurso::ACTIVO, 
        //     'visibilidad' => $request['visibilidad'], 
        //     'custom' => (Auth::user()->perfil_id == Perfil::SUPER_USUARIO) ? 0 : 1
        // ];

        // $updateMenu = Menu::find($id)->update($dataForm);
        $updateMenu = $menu->updateMenu($request, $id);

        if($updateMenu){
            return redirect()->route('menu.index')->with('success', 'O menu foi atualizado com sucesso!!');
        } else {
            return redirect()->route('menu.index')->with('status', 'O menu não foi atualizado tente nova mente!!');
        }
    }

    /**
     * Método para inactivar/reactivar
     */
    public function estado(Request $request, $tipo, $key) {

        if(!$id = MyFunction::getKey($key, $tipo.'_menu', 'int')) {
            return redirect()->route('menu.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        } 

        
        $menu = new Menu();
        $menu = $menu->find($id);

        if($menu->id == '1'){
            return redirect()->route('menu.index')->with('status', 'Lo sentimos, este menu não é posivel alterar!!');
        }

        if(!$menu->find($id)) {
            return redirect()->route('menu.index')->with('success', 'Lo sentimos, no se ha podido establecer la información del menu'); 
        } else {
            if(empty($menu->custom) && Auth::user()->perfil_id != Perfil::SUPER_USUARIO) {
                return redirect()->route('menu.index')->with('status', 'Lo sentimos, pero este menu no se puede editar.');
            }
            if($tipo=='inactivar' && $menu->activo == Menu::INACTIVO) {
                //Flash::info('El menu ya se encuentra inactivo');
                return redirect()->route('menu.index')->with('status', 'El menu ya se encuentra inactivo');
            } else if($tipo=='reactivar' && $menu->activo == Menu::ACTIVO) {
                //Flash::info('El menu ya se encuentra activo');
                return redirect()->route('menu.index')->with('status', 'El menu ya se encuentra activo');
            } else {
                $estado = ($tipo=='inactivar') ? Menu::INACTIVO : Menu::ACTIVO;

                $dataUpdate = [
                    'activo' => $estado
                ];
                $update = $menu->find($id)->update($dataUpdate);
                if ($update) {
                    // ($estado==menu::ACTIVO) ? redirect()->back()->with('success','El menu se ha reactivado correctamente!') : redirect()->back()->with('success','El menu se ha inactivado correctamente!');
                    ($estado==menu::ACTIVO) ? $request->session()->flash('success','El menu se ha reactivado correctamente!') : $request->session()->flash('success','El menu se ha inactivado correctamente!');
                } 
            }                
        }
        
        return redirect()->route('menu.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $key)
    {
        if(!$id = MyFunction::getKey($key, 'eliminar_menu', 'int')) {
            return redirect()->route('menu.index')->with('status', 'Acceso denegado. La llave de seguridad es incorrecta.');
        }

        $menu = Menu::find($id);
        if($menu->id == '1'){
            return redirect()->route('menu.index')->with('status', 'Lo sentimos, este menu não é posivel excluir!!');
        }

        if (!$menu) {
            return redirect()->route('menu.index')->with('status', 'Lo sentimos, no se ha podido establecer la información del menu');
        }

        try {
            if(Menu::find($id)->delete()) {
                $request->session()->flash('success','El menu se ha eliminado correctamente!');
            } else {
                $request->session()->flash('status', 'Lo sentimos, pero este menu no se puede excluir.');
            }
        } catch(Exception $e) {
            $request->session()->flash('status','Este recurso no se puede eliminar porque se encuentra relacionado con otro registro.');
        }

        return redirect()->route('menu.index');
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
