<?php

namespace App\Models;

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
}
