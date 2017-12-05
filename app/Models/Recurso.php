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
    const INACTIVO = 2;
    
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
        //$conditions = 'recurso.id IS NOT NULL AND recurso.id > 1'; 
        //$whereData = array(array('recurso.id', 'IS NOT NULL') , array('recurso.id' ,'>','1'));   
        $whereData = [
		    ['recurso.id' ,'>','1']
		];          
        if($estado!='todos') {

            //$conditions.= ($estado==self::ACTIVO) ? " AND activo=".self::ACTIVO : " AND activo=".self::INACTIVO;
            $whereData.= ($estado==self::ACTIVO) ? " AND activo=".self::ACTIVO : " AND activo=".self::INACTIVO;
        }  

        //return $this->find("conditions: $conditions", "group: recurso.modulo", "order: recurso.modulo ASC");
        return DB::table('recurso')
        	->whereNotNull('recurso.id')
        	->where($whereData)
        	->orderBy('recurso.modulo', 'asc')
        	->get();
    }
}
