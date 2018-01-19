<?php

namespace App\Models;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = TRUE;

    /**
     * Constante para definir un menú como activo
     */
    const ACTIVO = 1;

    /**
     * Constante para definir un menú como inactivo
     */
    const INACTIVO = 0;

    /**
     * Constante para definir un menú visible en el backend
     */
    const BACKEND = 1;

    /**
     * Constante para definir un menú visible en el frontend
     */
    const FRONTEND = 2;

    protected $fillable = ['menu_id', 'recurso_id', 'nome', 'url', 'posicion', 'icono', 'activo', 'visibilidad', 'custom'];

    public function parent()
    {
        return $this->belongsTo('App\Models\menu', 'menu_id');
    }
 
    public function children()
    {
        return $this->hasMany('App\Models\menu', 'menu_id');
    }

    public function recurso()
    {
    	return $this->belongsTo(Recurso::class);
    }

    /**
     * Método para obtener los menús padres, por entorno o perfil
     */
    public function getListadoMenuPadres($entorno='', $perfil='') {
        if($entorno == Menu::FRONTEND) {
            $query = Menu::whereNull('menu_id')
                            ->where('visibilidad', $entorno)
                            ->where('activo', self::ACTIVO)
                            ->groupBy('id')
                            ->orderBy('posicion', 'ASC')
                            ->get();
            //$query = DB::table('menu as pai')->select('pai.*')->where('pai.visibilidad', 1)->get();
            return $query;
        } else {    
            $query = Menu::from('menu')
                ->select('pai.*')
                ->Join('menu as pai', 'pai.id', '=', 'menu.menu_id');
            if ($entorno) {
                $query->leftJoin('recurso', 'recurso.id', '=', 'menu.recurso_id');
                $query->leftJoin('perfil_recurso', 'perfil_recurso.recurso_id', '=', 'recurso.id');
                $query->where('pai.visibilidad', $entorno);
                $query->where('pai.activo', self::ACTIVO);
            }
            $query->whereNull('pai.menu_id');
            if (!empty($perfil)) {
                $obj = new PerfilRecurso();
                if($obj::where('recurso_id', Recurso::COMODIN)->where('perfil_id', $perfil)->count()) {
                    $perfil = NULL; //Para que liste todos los menús
                }
                (empty($perfil) OR $perfil==Perfil::SUPER_USUARIO) ? '' : $query->where('perfil_recurso.perfil_id', $perfil);
            }
            $query->groupBy('pai.id')
                ->orderBy('pai.posicion', 'ASC');
            return $query->get();     
        }
    }

    /**
     * Método para obtener los submenús de cada menú según el perfil
     */
    public function getListadoSubmenu($entorno, $menu, $perfil='') {
        $query = Menu::from('menu')
                    ->leftJoin('recurso', 'recurso.id', '=', 'menu.recurso_id')
                    ->leftJoin('perfil_recurso', 'recurso.id', '=', 'perfil_recurso.recurso_id')
                    ->select('menu.*')
                    ->where('menu.menu_id', $menu)
                    ->where('menu.visibilidad', $entorno)
                    ->where('menu.activo', self::ACTIVO);
                    if ($perfil) {
                        $recurso = new PerfilRecurso();
                        if ($recurso::where('recurso_id', Recurso::COMODIN)->where('perfil_id', $perfil)->count()) {
                            $perfil = NULL; //Para que liste todos los submenús
                        }
                        (empty($perfil) OR $perfil==Perfil::SUPER_USUARIO) ? '' : $query->where('perfil_recurso.perfil_id', $perfil);
                    }
                    $query->groupBy('menu.id')
                    ->orderBy('menu.posicion', 'ASC');
        return $query->get();
    }

    /**
     * Método para obtener los menús padres
     */
    public function getMenusPorPadre($padre, $order='') {
        
        $query = Menu::from('menu')
                        ->select('menu.*', 'pai.nome AS padre', 'pai.posicion AS pai_posicion', 'recurso.recurso')
                        ->leftJoin('recurso', 'menu.recurso_id', '=', 'recurso.id')
                        ->leftJoin('menu as pai', 'pai.menu_id', '=', 'menu.id')
                        ->where('menu.menu_id', $padre)
                        ->groupBy('menu.id')
                        ->orderBy('pai.posicion', 'DESC')
                        ->get();
        return $query;
    }

    /**
    * Método para obtener los menús padres para edición
    */
    public function getListadoEdicion($entorno) {
        $query = Menu::whereNull('menu.menu_id')
                        ->where('menu.visibilidad', $entorno)
                        ->where('menu.activo', self::ACTIVO)
                        ->groupBy('menu.id')
                        ->get();
        return $query;
    }

    /**
     * Método para obtener el listado de los menús del sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoMenu($estado='todos', $order='', $page=0) {

        $query = Menu::from('menu')
                        ->select('menu.*', 'pai.nome AS padre', 'pai.posicion AS pai_posicion', 'recurso.recurso')
                        ->leftJoin('recurso', 'menu.recurso_id', '=', 'recurso.id')
                        ->leftJoin('menu as pai', 'pai.menu_id', '=', 'menu.id')
                        ->whereNotNull('menu.id')
                        ->groupBy('menu.id')
                        ->orderBy('pai.posicion', 'DESC')
                        ->get();
        return $query;
    }

    public function saveMenu($request)
    {  
        $this->menu_id = $request['menu_id'];
        $this->recurso_id = $request['recurso_id']; 
        $this->nome = $request['nome'];
        $this->url = $request['url'];
        $this->posicion = $request['posicion'];
        $this->icono = $request['icono']; 
        $this->activo = Menu::ACTIVO; 
        $this->visibilidad = $request['visibilidad'];
        $this->custom = (Auth::user()->perfil_id == Perfil::SUPER_USUARIO) ? 0 : 1;
        $this->save();
        return TRUE;
    }

    public function updateMenu($request , $id)
    {
        $menu = $this->find($id);
        $menu->menu_id = $request['menu_id'];
        $menu->recurso_id = $request['recurso_id']; 
        $menu->nome = $request['nome'];
        $menu->url = $request['url'];
        $menu->posicion = $request['posicion'];
        $menu->icono = $request['icono']; 
        $menu->activo = Menu::ACTIVO; 
        $menu->visibilidad = $request['visibilidad'];
        $menu->custom = (Auth::user()->perfil_id == Perfil::SUPER_USUARIO) ? 0 : 1;
        $menu->save();
        return 1;
    }

    
}
