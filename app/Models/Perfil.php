<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
	protected $table = 'perfil';

    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = FALSE;
    
    /**
     * Constante para definir el perfil de Super Usuario
     */
    const SUPER_USUARIO = 1;
    
    /**
     * Constante para definir un perfil como activo
     */
    const ACTIVO = 1;
    
    /**
     * Constante para definir un perfil como inactivo
     */
    const INACTIVO = 0;

    protected $fillable = [
    	'rol', 'plantilla', 'activo'
    ];

    //Relação de uno a muchos, um usuario para muchas tarefas
    public function user()
    {
    	//retorna seleccionamiento de um para um
        return $this->hasOne(User::class);
    }

    public function getAllPerfiles(){
    	return Perfil::all();
    }

}
