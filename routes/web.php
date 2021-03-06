<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
| Uma rota não é uma URL|URI 
| Rotas do tipo GET é para retornar algo, como por exemplo uma listagem de conteúdo e etc.
| Rotas do tipo POST normalmente são utilizados para cadastrar algo no sistema.
| Rotas do tipo PUT ou PATH são para editar algum registro.
| Rotas do tipo de DELETE são para deletar algo.
| E por último, rotas do tipo OPTIONS
|
*
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// 

Route::group(['prefix' => 'painel', 'middleware' => 'auth'], function(){
	//Controller para ajax
	//Route::get('update/{method}/{model?}/{id?}', 'Painel\AjaxController@update')->middleware('auth');
	//Route::get('delete/{model?}/{id?}',        'Painel\AjaxController@delete')->middleware('auth');

	//PerfilController
	Route::resource('perfil', 'Painel\PerfilController');
	Route::get('perfil/{model?}/{id?}', 'Painel\PerfilController@estado');
	Route::get('Perfil/{section}/{id?}', 'Painel\PerfilController@delete');

	//RecursoController
	
	Route::get('recurso/estado/{tipo?}/{id?}', 'Painel\RecursoController@estado');
	Route::get('recurso/{id}/delete', ['as' => 'recurso.delete', 'uses' => 'Painel\RecursoController@destroy']);
	$this->get('recurso/lista', 'Painel\RecursoController@lista');
	$this->get('recurso/listaEloquent', 'Painel\RecursoController@listaEloquent');
	Route::resource('recurso', 'Painel\RecursoController');
	// Route::get('recurso/destroy/{id?}', 'Painel\RecursoController@destroy');
	//Route::get('recursos/{model?}/{id?}', 'Painel\RecursoController@estado');
	//Route::get('Recurso/{section}/{id?}', 'Painel\RecursoController@delete');
	
	//TaskController
	//Route::resource('task', 'Painel\TaskController');
	//Route::get('/delete/{section}/{id?}', 'TaskController@destroy');

	//PostController
	//Route::resource('post', 'Painel\PostController');

	//UserController
	Route::resource('users', 'Painel\UserController');
	Route::get('users/{model?}/{id?}', 'Painel\UserController@estado'); //update estado
	Route::get('User/{section}/{id?}', 'Painel\UserController@delete'); //delete
	Route::get('users/{id}/roles', 'Painel\UserController@roles');

	//RoleController
	//Route::get('roles', 'Painel\RoleController@index');
	//Route::get('role/{id}/permissions', 'Painel\RoleController@permissions');

	//PermissionController
	//Route::get('permissions', 'Painel\PermissionController@index');
	//Route::get('permission/{id}/roles', 'Painel\PermissionController@roles');
	
	Route::group(['as' => 'menu.', 'prefix' => 'menus'], function()
	{
		Route::get('', ['as' => 'index', 'uses' => 'Painel\MenuController@index']);
		Route::get('lista', ['as' => 'lista', 'uses' => 'Painel\MenuController@lista']);
		Route::get('criar', ['as' => 'create', 'uses' => 'Painel\MenuController@create']);
		Route::post('salvar', ['as' => 'store', 'uses' => 'Painel\MenuController@store']);
		Route::get('{id}/editar', ['as' => 'edit', 'uses' => 'Painel\MenuController@edit']);
		Route::patch('{id}/atualizar', ['as' => 'update', 'uses' => 'Painel\MenuController@update']);
		Route::get('{id}/remover', ['as' => 'destroy', 'uses' => 'Painel\MenuController@destroy']);
		Route::get('estado/{tipo?}/{id?}', ['as' => 'destroy', 'uses' => 'Painel\RecursoController@estado']);
	});

	Route::group(['as' => 'permiso.', 'prefix' => 'permisos'], function()
	{
		Route::get('', ['as' => 'index', 'uses' => 'Painel\PermisosController@index']);
		Route::get('lista', ['as' => 'lista', 'uses' => 'Painel\PermisosController@lista']);
		Route::get('criar', ['as' => 'create', 'uses' => 'Painel\PermisosController@create']);
		Route::post('salvar', ['as' => 'store', 'uses' => 'Painel\PermisosController@store']);
	});

	//PainelController
	Route::get('/', 'Painel\PainelController@index');
	
});




//Auth::routes();

// Authentication Routes...
$this->get('admin', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('admin', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');



Route::get('/', 'Portal\SiteController@index');
//Route::get('/roles-permissions',        'Portal\SiteController@rolesPermission');

Route::get('recursiveMenu',function(){
	$categories = App\Models\Menu::with('children')->where('menu_id','=', NULL)->get();
	/*$categories = App\Models\Menu::with('children')->from('menu')
                    ->select('menu.*', 'pai.nome AS padre', 'pai.posicion AS pai_posicion', 'recurso.recurso')
                    ->leftJoin('recurso', 'menu.recurso_id', '=', 'recurso.id')
                    ->leftJoin('menu as pai', 'pai.menu_id', '=', 'menu.id')
                    ->where('menu.menu_id','=', NULL)
                    ->groupBy('menu.id')
                    ->orderBy('pai.posicion', 'DESC')
                    ->get();*/
	//dd($categories);
	return view('welcome',['categories'=>$categories]);
});

Route::get('/func', function () {
    return MyFunction::full_name("John","Doe");
});

Route::get('usuarios/{id}', function($id){
	return "Meu parametro id: {$id}";
})->where('id', '[0-9]+');

Route::get('usuarios/nuevo', function(){
	return "Criar novo usuario";
});

Route::get('rotas', function(){
	$root = get_class(File::getFacadeRoot());
	var_dump($root);
});

