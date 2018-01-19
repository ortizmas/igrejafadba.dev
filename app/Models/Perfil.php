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
                    ->join('users', 'perfil.id', '=', 'users.perfil_id' )
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
                    ->groupBy('perfil.id')
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
    /**
     * Método para obtener los ecursos de un perfil
     * @param type $perfil
     * @return type
     */
    public function getRecursos($perfil){   
        $query = Recurso::from('perfil')
                ->join('perfil_recurso', 'perfil.id', '=', 'perfil_recurso.perfil_id')
                ->join('recurso', 'recurso.id', '=', 'perfil_recurso.recurso_id')
                ->where('perfil.id', $perfil)
                ->select('recurso.*')
                ->get();
        return $query;


        // $columnas = "recurso.*";
        // $join = "INNER JOIN recurso_perfil ON perfil.id = recurso_perfil.perfil_id ";
        // $join.= "INNER JOIN recurso ON recurso.id = recurso_perfil.recurso_id ";
        // $conditions = "perfil.id = '$perfil'";
        // return $this->find("columns: $columnas" , "join: $join", "conditions: $conditions");
    }

}
