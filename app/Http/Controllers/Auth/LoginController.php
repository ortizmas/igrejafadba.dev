<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

use Auth;
use App\Models\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/painel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Muestra el formulario para login.
     */
    public function showLoginForm()
    {
        // Verificamos que el usuario no esta autenticado
        if (Auth::check())
        {
            // Si esta autenticado lo mandamos a la rai­z donde estara el mensaje de bienvenida.
            return Redirect::to('painel/');
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return view('auth.login');
        //return view('auth.acesso');
    }


    /**
     * Valida los datos del usuario.
     */
    public function login(Request $request, User $user)
    {
        $this->validateLogin($request);

        // Se a classe estiver usando o traço ThrottlesLogins, podemos acelerar automaticamente 
        // as tentativas de login para este aplicativo. Vamos chamar isso pelo nome de usuário e 
        // o endereço IP do cliente que faz esses pedidos neste aplicativo.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        
        // Guardamos en un arreglo los datos del usuario.
        $userdata = array(
            'email' => $request->input('email'),
            'password'=> $request->input('password')
        );
        // Validamos los datos y ademas mandamos como un segundo parametro la opcion de recordar el usuario.
        if(Auth::attempt($userdata, $request->input('remember-me', 0)))
        {    
            //Redirecciona para el modulo App/Controllers/Dashboard/index_controller.php
            return redirect('painel/')->with('success','Ingreso correctamente al sistema');

        }

        // En caso de que la autenticacion haya fallado manda un mensaje al formulario de login y tambien regresamos los valores enviados con withInput().
        //return Redirect::to('login')->with('mensaje_error', 'Datos de acceso erroneos, Intentelo nuevamente')->withInput();
        
        // Se a tentativa de login não teve êxito, incrementaremos o número de tentativas 
        // para efetuar o login e redirecionar o usuário de volta ao formulário de login. Claro, quando isso 
        // o usuário ultrapassa o número máximo de tentativas que serão bloqueadas.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
        
    /**
     * Muestra el formulario de login mostrando un mensaje de que cerro sesion.
     */    
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

}
