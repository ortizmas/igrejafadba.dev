<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;


class Recurso extends Model
{
    protected $table = 'recurso';

    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = FALSE;

    /**
     * Constante para definir un recurso como activo
     */
    const ACTIVO = 1;
    
    /**
     * Constante para definir un recurso como inactivo
     */
    const INACTIVO = 0;
    
    /**
     * Constante para identificar el comodín *
     */
    const COMODIN = 1;
    
    /**
     * Constante para definir el recurso principal
     */
    const DASHBOARD = 2;
    
    /**
     * Constante para definir el recurso "Mi Cuenta"
     */
    const MI_CUENTA = 3;

    protected $fillable = ['modulo', 'controlador', 'accion', 'recurso', 'descripcion', 'activo', 'custom'];

    public function menu()
    {
    	return $this->hasMany(Menu::class);
    }

    /**
     * Método para obtener el listado de los recursos por módulos del sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoRecursoPorModulo($estado='todos', $order='') {                
        $whereData = [
		    ['recurso.id' ,'>','1']
		];          
        if( $estado != 'todos' ) {
            if ( $estado == self::ACTIVO ) {
                $whereData = [
                    ['recurso.id' ,'>','1'],
                    ['recurso.activo' ,'>', self::ACTIVO]
                ]; 
            } else {
                $whereData = [
                    ['recurso.id' ,'>','1'],
                    ['recurso.activo', '=', self::INACTIVO]
                ];
            }
        }

        //Eloquent
        $recurso = Recurso::whereNotNull('recurso.id')
            ->where($whereData)
            ->groupBy('recurso.modulo')
            ->orderBy($order, 'asc')
            ->get();
        return $recurso;

        //Query Builder
        // return DB::table('recurso')
        // 	->whereNotNull('recurso.id')
        // 	->where($whereData)
        //     ->groupBy('recurso.modulo')
        // 	->orderBy($order, 'asc')
        // 	->get();
    }

    public function getRecursos($modulo, $order='recurso.controlador') {
        return DB::table('recurso')
                    ->where('recurso.modulo', $modulo)
                    ->orderBy($order, 'asc')
                    ->get();
    }

    /**
     * Método para listar los recursos por módulos
     * @param type $modulo
     * @param type $order
     * @return type
     */
    public function getRecursosPorModulo($modulo, $order='recurso.controlador') {
        return DB::table('recurso')
                    ->where('recurso.modulo', $modulo)
                    ->orderBy($order, 'asc')
                    ->get();
    }

    public function hasRecurso($modulo, $order='recurso.controlador') {
        return DB::table('recurso')
                    ->where('recurso.modulo', $modulo)
                    ->orderBy($order, 'asc')
                    ->get();
    }

    /**
     * Método para obtener el listado de los recursos del sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoRecurso($estado='todos', $order='', $page=0) { 
        //$recursos = Recurso::whereNotNull('accion')->pluck('recurso', 'id')->all();
        //$recursos = Recurso::where($conditions)->pluck('recurso', 'id')->toArray(); 
        
        /*$recursos = \DB::table('recurso')
                    ->where('id', '!=', NULL)
                    ->where(function($query) use ($estado){
                        if ($estado != 'todos') {
                            if ($estado==Recurso::ACTIVO) {
                                $query->where('activo','=',Recurso::ACTIVO);
                            } else {
                                $query->where('activo','=',Recurso::INACTIVO);
                            }
                            //($estado==Recurso::ACTIVO) ? $query->where('activo','=',Recurso::ACTIVO) : $query->where('activo','=',Recurso::INACTIVO);
                        }
                    })
                    ->orderBy('recurso', 'ASC')
                    ->pluck('recurso', 'id')->toArray();*/  
                           
        $query = Recurso::whereNotNull('id');
                if($estado !='todos')
                    ($estado==Recurso::ACTIVO) ? $query->where('activo','=',Recurso::ACTIVO) : $query->where('activo','=',Recurso::INACTIVO);
                $query->orderBy('recurso', 'ASC');
        $recursos = $query->pluck('recurso', 'id')->toArray();
        return $recursos;
    }

    /**
    * com concatenação de variable
    */
    public function getListaRecursos($estado='todos', $order='', $page=0)
    {
        $conditions = 'recurso.id IS NOT NULL';                
        if($estado!='todos') {
            $conditions.= ($estado==Recurso::ACTIVO) ? " AND activo=".Recurso::ACTIVO : " AND activo=".Recurso::INACTIVO;
        }
        $sql = "select * from recurso where {$conditions}";

        $recursos = \DB::select($sql); 

        return $recursos;
    }
}
