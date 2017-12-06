<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

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
    public static function getListadoRecursoPorModulo($estado='todos', $order='') {                
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
        return DB::table('recurso')
        	->whereNotNull('recurso.id')
        	->where($whereData)
            ->groupBy('recurso.modulo')
        	->orderBy($order, 'asc')
        	->get();
    }

    /**
     * Método para listar los recursos por módulos
     * @param type $modulo
     * @param type $order
     * @return type
     */
    public static function getRecursosPorModulo($modulo, $order='recurso.controlador') {
        // $conditions = "recurso.modulo = '$modulo'";
        // $order = $this->get_order($order, 'id', array(            
        //     'controlador' => array(
        //         'ASC' => 'controlador ASC, accion ASC',
        //         'DESC' => 'controlador DESC, accion DESC'
        //     ),
        //     'accion' => array(
        //         'ASC' => 'accion ASC, controlador ASC',
        //         'DESC' => 'accion DESC, controlador DESC'
        //     )
        // ));        
        //return $this->find("conditions: $conditions", "order: $order");
        return DB::table('recurso')
                    ->where('recurso.modulo', $modulo)
                    ->orderBy($order, 'asc')
                    ->get();
    }

    public static function hasRecurso($modulo, $order='recurso.controlador') {
        return DB::table('recurso')
                    ->where('recurso.modulo', $modulo)
                    ->orderBy($order, 'asc')
                    ->get();
    }
}
