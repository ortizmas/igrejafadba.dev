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
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// 

Route::group(['prefix' => 'painel'], function(){
	//Controller para ajax
	//Route::get('update/{method}/{model?}/{id?}', 'Painel\AjaxController@update')->middleware('auth');
	//Route::get('delete/{model?}/{id?}',        'Painel\AjaxController@delete')->middleware('auth');

	//PerfilController
	Route::resource('perfil', 'Painel\PerfilController')->middleware('auth');
	Route::get('perfil/{model?}/{id?}', 'Painel\PerfilController@estado')->middleware('auth');
	Route::get('Perfil/{section}/{id?}', 'Painel\PerfilController@delete')->middleware('auth');
	
	//TaskController
	//Route::resource('task', 'Painel\TaskController')->middleware('auth');
	//Route::get('/delete/{section}/{id?}', 'TaskController@destroy')->middleware('auth');

	//PostController
	//Route::resource('post', 'Painel\PostController')->middleware('auth');

	//UserController
	Route::resource('users', 'Painel\UserController')->middleware('auth');
	Route::get('users/{model?}/{id?}', 'Painel\UserController@estado')->middleware('auth'); //update estado
	Route::get('User/{section}/{id?}', 'Painel\UserController@delete')->middleware('auth'); //delete
	Route::get('users/{id}/roles', 'Painel\UserController@roles')->middleware('auth');

	//RoleController
	//Route::get('roles', 'Painel\RoleController@index')->middleware('auth');
	//Route::get('role/{id}/permissions', 'Painel\RoleController@permissions')->middleware('auth');

	//PermissionController
	//Route::get('permissions', 'Painel\PermissionController@index')->middleware('auth');
	//Route::get('permission/{id}/roles', 'Painel\PermissionController@roles')->middleware('auth');

	//PainelController
	Route::get('/', 'Painel\PainelController@index')->middleware('auth');
	
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

Route::get('/func', function () {
    return MyFunction::full_name("John","Doe");
});

Route::get('usuarios/{id}', function($id){
	return "Meu parametro id: {$id}";
})->where('id', '[0-9]+');

Route::get('usuarios/nuevo', function(){
	return "Criar novo usuario";
});

