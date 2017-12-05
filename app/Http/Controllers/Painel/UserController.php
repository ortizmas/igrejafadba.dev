<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

use Gate;
use App\Models\User;
use App\Models\Perfil;
//use App\Models\Permissao;
//use Auth;


class UserController extends Controller
{
    private $responseContainer = ['status' => 'ko', 'message' => '', 'error' => '', 'data' => ''];
    private $user;
    protected $model = 'User';

    public function __construct(User $user)
    {
        $this->user = $user;
        
        if( Gate::denies("user") ){
            return redirect()->back();
        } 
            
    }

    public function index()
    {
        //$users = User::paginate();
        //$perfil = $users->perfil;
        $users = $this->user->paginate();
        $model = $this->model;
        $count = $users->count();
        //$perfils = $users->perfil;
        //dd($perfils);

        return view('painel.users.index', compact('users', 'model', 'count'));
    }

    public function roles($id)
    {
        //Recupera o usuario
        $user = $this->user->find($id);

        //Recuperar os roles do usuario
        $roles = $user->roles()->get();
        return view('painel.user.roles', compact('user', 'roles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perfils = new Perfil;
        $perfils = $perfils->getAllPerfiles();
        return view('painel.users.create', compact('perfils'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $save = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'perfil_id' => $request['perfil_id'],
            'activo' => $request['activo'],
        ]);

        //event(new Registered($user = $this->create($request->all())));
        
        if ($save) {
            return redirect('painel/users')->with('success', 'Usuario cadastrado com sucesso');
        } else {
            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $usr = User::find($user);
        //dd($users->name);
        //return view('user', ['user' => $usr, 'post' => $post]); //para pasar varias variables
        return view('user', compact('usr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::find($id);

        $perfils = new Perfil;
        $perfils = $perfils->getAllPerfiles();
        return view('painel.users.edit', compact('user', 'perfils'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //$this->validator($request->all())->validate();

        $dataUser = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'perfil_id' => $request['perfil_id'],
            'activo' => $request['activo'],
        ];

        $update = User::find($id)->update($dataUser);

        //event(new Registered($user = $this->create($request->all())));
        
        if ($update) {
            return redirect('painel/users')->with('success', 'Usuario atualizado com sucesso');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function estado($model='', $id = '', Request $request)
    {
        //dd($model);
        $this->request = $request;
        switch ($model) {
            case "User":
                if ($this->request->input('field')) {
                    $field = $this->request->input('field');
                    $value = $this->request->input('value');
                    $object = User::whereId($id)->firstOrFail();
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
        $modelClass = new User();
        $object = $modelClass::whereId($id)->first();
        if (is_object($object)) {
            $object->delete();
            $this->responseContainer['status'] = 'ok';
            $this->responseContainer['message'] = 'Data has been deleted';
            return redirect()->action('Painel\UserController@index')->with('status', 'The user ' . $object->name . ' Foi deletado!');
        } else {
            $this->responseContainer['error'] = 'Data not found';
        }
        return $this->responseHandler();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function responseHandler()
    {
        return response()->json($this->responseContainer);
    }
}
