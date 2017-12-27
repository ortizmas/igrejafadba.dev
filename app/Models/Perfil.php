<?php

namespace App\Models;

use DB;
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

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'perfil_recurso');
    }

    public function getAllPerfiles(){
    	return Perfil::all();
    }

    public function getListadoPerfilAll($estado)
    {
        $perfil = Perfil::where('activo', $estado)->get();
        return $perfil;
    }

    /**
     * Método para obtener el listado de los perfiles del sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoPerfil($estado='todos', $order='', $page=0) {  

        $perfil = Perfil::from('perfil')
                    ->select('perfil.*', DB::raw(' count(users.id) as usuarios '))
                    ->leftJoin('users', 'perfil.id', '=', 'users.perfil_id' )
                    ->whereNotNull('perfil.id')
                    ->where( function($query) use ($estado) {
                        if ( $estado == 'acl' ) {
                            $query->where('perfil.activo', self::ACTIVO);
                        } else if ( $estado == 'mi_cuenta') {
                            $query->where('perfil.activo', self::ACTIVO);
                            ($estado==self::ACTIVO) ? $query->where('perfil.activo', self::ACTIVO) : $query->where('perfil.activo', self::INACTIVO); 
                        } else {
                            $query->where('perfil.id', '>', '1');
                            if( $estado != 'todos' ) {
                                ($estado==self::ACTIVO) ? $query->where('perfil.activo', self::ACTIVO) : $query->where('perfil.activo', self::INACTIVO);                
                            }
                        }
                    })
                    ->get();
        return $perfil;
    }

    public function getPerfilRecursos( $perfilId, $recursoId ) {
        return DB::table( 'perfil_recurso' )
                ->where( 'perfil_id', '=', $perfilId)
                ->where( 'recurso_id', '=', $recursoId )
                ->get();
        /* TODO:
          Replace above code with the below, when Laravel supports it.
          Below code currently detaches all records in 'team_user' table, where
          'user_id' = $adminId.
              return $this->admins()->detach($adminId);
         */
    }

    /**
     * Método para listar los privilegios y compararlos con los recursos y perfiles
     * @return array
     */
    public function getPrivilegiosToArray() {
        $data = array();
        $privilegios = DB::table( 'perfil_recurso' )->get();
        foreach($privilegios as $privilegio) {
            $data[] = $privilegio->perfil_id.'-'.$privilegio->recurso_id;
        }        
        return $data;
    }

}
