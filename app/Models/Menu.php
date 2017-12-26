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
            if($entorno){
                $query = Menu::from('menu as pai')
                                ->select('pai.*')
                                ->leftJoin('recurso', 'pai.recurso_id', '=', 'recurso.id')
                                ->leftJoin('recurso_perfil', 'recurso_perfil.recurso_id', '=', 'recurso.id')
                                ->where('pai.id', '=', 'pai.menu_id')
                                ->whereNull('pai.menu_id')
                                ->where('pai.visibilidad', '=', $entorno)
                                ->where('pai.activo', self::ACTIVO)
                                ->groupBy('pai.id')
                                ->orderBy('pai.posicion', 'ASC');
            } else {
                $query = Menu::from('menu as pai')
                                ->select('pai.*')
                                ->where('pai.id', '=', 'pai.menu_id')
                                ->whereNull('pai.menu_id')
                                ->groupBy('pai.id')
                                ->orderBy('pai.posicion', 'ASC');
            }

            if(!empty($perfil)) {
                //Verifico si el perfil tiene el comodín
                $recurso = new RecursoPerfil();
                if($recurso->count("recurso_id = ".Recurso::COMODIN." AND perfil_id= $perfil")) {
                    $perfil = NULL; //Para que liste todos los menús
                }
                $conditions.= (empty($perfil) OR $perfil==Perfil::SUPER_USUARIO) ? '' : " AND recurso_perfil.perfil_id = $perfil";
            }
                            
            return $query->get();
        }
    }

    /**
     * Método para obtener los submenús de cada menú según el perfil
     */
    public function getListadoSubmenu($entorno, $menu, $perfil='') {
        $columns = 'menu.*';
        $join = 'LEFT JOIN recurso ON recurso.id = menu.recurso_id ';
        $join.= 'LEFT JOIN recurso_perfil ON recurso.id = recurso_perfil.recurso_id ';
        $conditions = "menu.menu_id = $menu AND menu.visibilidad = $entorno AND menu.activo = ".self::ACTIVO;
        if($perfil) {
            //Verifico si el perfil tiene el comodín
            $recurso = new RecursoPerfil();
            if($recurso->count("recurso_id = ".Recurso::COMODIN." AND perfil_id= $perfil")) {
                $perfil = NULL; //Para que liste todos los submenús
            }
            $conditions.= (empty($perfil) OR $perfil==Perfil::SUPER_USUARIO) ? '' :  " AND recurso_perfil.perfil_id = $perfil";
        }
        $group = 'menu.id';
        $order = 'menu.posicion ASC';
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order");
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
                        ->orderBy('menu.posicion', 'ASC')
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
        // $columns = 'menu.*, (padre.menu) AS padre, (padre.posicion) AS padre_posicion, recurso.recurso';
        // $join = 'LEFT JOIN recurso ON recurso.id = menu.recurso_id ';
        // $join.= 'LEFT JOIN menu AS padre ON padre.id = menu.menu_id ';
        // $conditions = 'menu.id IS NOT NULL';
        // if($estado!='todos') {
        //     $conditions.= ($estado==self::ACTIVO) ? " AND menu.activo=".self::ACTIVO : " AND menu.activo=".self::INACTIVO;
        // }

        // $order = $this->get_order($order, 'padre_posicion', array(
        //     'posicion' => array(
        //         'ASC'  => 'padre_posicion ASC, menu.posicion ASC',
        //         'DESC' => 'padre_posicion DESC, menu.posicion DESC'
        //     ),
        //     'padre' => array(
        //         'ASC'  => 'padre ASC, padre_posicion ASC, menu.posicion ASC',
        //         'DESC' => 'padre DESC, padre_posicion DESC, menu.posicion DESC'
        //     ),
        //     'menu' => array(
        //         'ASC'  => 'padre ASC, menu ASC, padre_posicion ASC, menu.posicion ASC',
        //         'DESC' => 'padre DESC, menu DESC, padre_posicion DESC, menu.posicion DESC'
        //     ),
        //     'visibilidad' => array(
        //         'ASC'  => 'padre.visibilidad ASC, menu.visibilidad ASC, menu ASC, padre_posicion ASC, menu.posicion ASC',
        //         'DESC' => 'padre.visibilidad DESC, menu.visibilidad DESC, padre DESC, menu DESC, padre_posicion DESC, menu.posicion DESC'
        //     ),
        //     'activo' => array(
        //         'ASC'  => 'menu.activo ASC, padre_posicion ASC, menu.posicion ASC',
        //         'DESC' => 'menu.activo DESC, menu.visibilidad DESC, padre DESC, menu DESC, padre_posicion DESC, menu.posicion DESC'
        //     )
        // ));

        // if($page) {
        //     return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        // }
        // return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");

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
