<?php

namespace App\Models;

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
}
