<?php  
/**
 * Route:get() -> Listar, Read
 * Route:post() -> Create
 * Route:put(), Route:patch() -> Update
 * Route:delete() -> Deletar
 * Documentação de rotas e exemplos
 * Categorias
 */

	Route::get('categorias/listar');
	Route::get('categorias/criar');
	Route::post('categorias/salvar');
	Route::get('categorias/{$id}/editar');
	Route::post('categorias/{$id}/atualizar');
	Route::get('categorias/{$id}/remover');

	/* VEMOS QUE CATEGORIA ESTA REPITENDO ENTÃO AGRUPAMOS AS ROTAS */

	Route::group(['prefix' => 'categorias'], function()
	{
		Route::get('', ['uses' => 'CategoryController@index']);
		Route::get('criar', ['uses' => 'CategoryController@create']);
		Route::post('salvar', ['uses' => 'CategoryController@store']);
		Route::get('{$id}/editar', ['uses' => 'CategoryController@edit']);
		Route::post('{$id}/atualizar', ['uses' => 'CategoryController@update']);
		Route::get('{$id}/remover', ['uses' => 'CategoryController@destroy']);
	});

	/**
	 * Nomes nas rotas
	 */
	
	Route::group(['as' => 'cats.', 'prefix' => 'categorias'], function()
	{
		Route::get('', ['as' => 'index', 'uses' => 'CategoryController@index']);
		Route::get('criar', ['as' => 'create', 'uses' => 'CategoryController@create']);
		Route::post('salvar', ['as' => 'store', 'uses' => 'CategoryController@store']);
		Route::get('{$id}/editar', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
		Route::post('{$id}/atualizar', ['as' => 'update', 'uses' => 'CategoryController@update']);
		Route::get('{$id}/remover', ['as' => 'destroy', 'uses' => 'CategoryController@destroy']);
	});

	/**
	 * Route Controller
	 * Quando se usa esta forma temos que renomear todas as funciones do controller getIndex(), getCreate(), etc.
	 * se pode renomar a função a portuges getCriar(), getEditar(), getAtualizar(); etc, em este caso o parametro $id vai depos categoria/editar/100 -> id
	 */
	
	//Para mostrar com route:list se tem que configurar de esta forma, o nome das rotas pode ser o que quiser ´~ao existe convension
	
	$config = [
		'getIndex' => 'cats.index',
		'getCriar' => 'cats.create',
		'getEditar' => 'cats.edit',
		'getSalvar' => 'cats.store',
		'getAtualizar' => 'cats.update',
		'getRemover' => 'cats.destroy',
	];

	Route::controller('categorias', 'CategoryController', $config);

	/**
	 * Middleware => criar no constructor do controller $this->middleware('auth');
	 */

	// public function __construct(){
	// 	$this->middleware('auth'); //esto no controller das rotas
	// }
	
	/**
	 * Resource
	 */
	
	Route::resource('categorias', 'CategoryController');

?>