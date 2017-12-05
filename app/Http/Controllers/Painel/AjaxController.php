<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use App\Models\Perfil;
use App\Models\User;

class AjaxController extends Controller
{
    private $responseContainer = ['status' => 'ko', 'message' => '', 'error' => '', 'data' => ''];
    protected $request;

    public function update($action, $model, $id = '', Request $request)
    {
        
        $this->request = $request;
        switch ($action) {
            case "updateItemField":

                if ($this->request->input('field')) {
                    $field = $this->request->input('field');
                    $value = $this->request->input('value');
                    $modelClass = new User;
                    $object = $modelClass::whereId($id)->firstOrFail();
                    $object->$field = $value;
                    $object->save();
                    $this->responseContainer['status'] = 'ok';
                    $this->responseContainer['message'] = $model . 'Data has been updated';
                   	$this->responseContainer['data'] = $object;
                 }
                break;
        }
        return $this->responseHandler();
    }

    public function delete($model, $id = '')
    {

        $modelClass = new $model;
        $object = $modelClass::whereId($id)->first();
        if (is_object($object)) {
            $object->delete();
            $this->responseContainer['status'] = 'ok';
            $this->responseContainer['message'] = 'Data has been deleted';
            return redirect()->action('Painel\PerfilController@index')->with('status', 'The items ' . $object->rol . ' Foi deletado!');
        } else {
            $this->responseContainer['error'] = 'Data not found';
        }
        return $this->responseHandler();
    }

    public function responseHandler()
    {
        return response()->json($this->responseContainer);
    }
}
