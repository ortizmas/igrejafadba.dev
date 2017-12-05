<?php

namespace App\Http\Controllers\Painel;

use App\Models\Perfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerfilController extends Controller
{
    private $responseContainer = ['status' => 'ko', 'message' => '', 'error' => '', 'data' => ''];
    protected $model = 'Perfil';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data['perfiles'] = Perfil::paginate();
        $data['pageConfig'] = $this->model;
        $data['perfiles_total'] = Perfil::count();

        return view('painel.perfiles.index', $data);
        //return view('painel.perfiles.index', compact('perfiles', 'pageConfig', 'perfiles_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('painel.perfiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rol' => 'required|unique:perfil|max:45',
            'plantilla' => 'required',
        ]);

        $save = Perfil::create($request->all());

        // $perfil = [
        //     'rol' => $request->rol,
        //     'plantilla' => $request->plantilla,
        //     'activo' => $request->activo,
        // ];

        // $save = Perfil::insert($perfil);

        if ($save) {
            return redirect('painel/perfil')->with('success', 'Perfil cadastrado com sucesso');
        } else {
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        //return view('perfil.show',compact('perfil'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['perfil'] = Perfil::find($id);

        return view('painel.perfiles.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {
        // $dataPerfil = [
        //     'rol' => $request->rol,
        //     'plantilla' => $request->plantilla,
        //     'activo' => $request->activo,
        // ];

        // $update = Perfil::find($perfil->id)->update($dataPerfil); //Modelo 1

        //$update = Perfil::find($perfil->id)->update($request->all());
        
        $request->validate([
            'rol' => 'required|max:45',
            'plantilla' => 'required',
        ]);

        $update = $perfil->update($request->all());
        

        if ($update) {
            return redirect('painel/perfil')->with('success', 'Perfil atualizado com sucesso');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function estado($model='', $id = '', Request $request)
    {
        $this->request = $request;
        switch ($model) {
            case "Perfil":
                if ($this->request->input('field')) {
                    $field = $this->request->input('field');
                    $value = $this->request->input('value');
                    $object = Perfil::whereId($id)->firstOrFail();
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

        $modelClass = new Perfil();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy($model, $id)
    {
        //$this->init($model);
        // $this->id = $id;
        // $model = new  Perfil();
        // $perfil = $model::whereId($this->id)->firstOrFail();
        // $perfil->delete();
        // //flash()->error('The items ' . $perfil->name . ' has been deleted!')->important();
        // return redirect()->action('Painel\PerfilController@index')->with('status', 'The items ' . $perfil->rol . ' Foi deletado!');
        // //return redirect('painel/perfil')->with('status', 'The items ' . $perfil->rol . ' Foi deletado!');
    }

    public function responseHandler()
    {
        return response()->json($this->responseContainer);
    }
}
