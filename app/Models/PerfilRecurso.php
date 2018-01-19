<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilRecurso extends Model
{
    protected $table = 'perfil_recurso';

    protected $fillable = ['perfil_id', 'recurso_id'];

    public function setPerfilRecurso($request)
    {
    	$obj = new PerfilRecurso();
        \DB::beginTransaction();
        //Elimino los antiguos privilegios
        $privilegios = $request['privilegios'];
        $old_privilegios = $request['old_privilegios'];
        if(!empty($old_privilegios)) {
            $items = explode(',', $old_privilegios);
            foreach($items as $value) {
                $data = explode('-', $value); //el formato es 1-4 = recurso-rol
                if($data[1] != Recurso::DASHBOARD && $data[1] != Recurso::MI_CUENTA) { //Para que no elimine el principal y mi cuenta
                    $object = $obj::where('perfil_id', $data[0])->where('recurso_id', $data[1])->first()->delete();
                    if (!$object) {
                        \DB::rollBack();
                        return FALSE;
                    }                
                }
            }                        
        }

        if(!empty($privilegios)) {

            foreach($privilegios as $value) {                 
                $data = explode('-', $value); //el formato es 1-4 = recurso_id-perfil_id
                $perfil_id = $data[0];
                $recurso_id = $data[1];
                $dataPerfilRecurso = [
                    'perfil_id' => $perfil_id,
                    'recurso_id' => $recurso_id
                ];
                
                //$existDados = $obj->exists("perfil_id=$perfil_id AND recurso_id=$recurso_id ");
                $existDados = $obj::where('perfil_id', $perfil_id)->where('recurso_id', $recurso_id)->exists();
                if($existDados){
                    continue;
                }

                if(!$obj->insert($dataPerfilRecurso)) {            
                    \DB::rollBack();
                    return FALSE;
                }
            }
        }
        \DB::commit();
        return TRUE;
    }
}
