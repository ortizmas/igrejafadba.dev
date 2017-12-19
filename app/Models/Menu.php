<?php

namespace App\Models;

use DB;
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
    const INACTIVO = 2;

    /**
     * Constante para definir un menú visible en el backend
     */
    const BACKEND = 1;

    /**
     * Constante para definir un menú visible en el frontend
     */
    const FRONTEND = 2;


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
        
        $query = Menu::from('menu as pai')
                        ->select('pai.*')
                        ->leftJoin('recurso', 'pai.recurso_id', '=', 'recurso.id')
                        ->leftJoin('menu', 'menu.menu_id', '=', 'pai.id')
                        ->where('menu.id', $padre)
                        ->groupBy('pai.id')
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
}
