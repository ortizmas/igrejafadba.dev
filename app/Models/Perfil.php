<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
	protected $table = 'perfil';

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
