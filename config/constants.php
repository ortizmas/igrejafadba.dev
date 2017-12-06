<?php  
	/**
	 * Define el PUBLIC_PATH
	 *
	 * PUBLIC_PATH:
	 * - Path para genera la Url en los links a acciones y controladores
	 * - Esta ruta la utiliza Kumbia como base para generar las Urls para acceder de lado de
	 *   cliente (con el navegador web) y es relativa al DOCUMENT_ROOT del servidor web
	 *
	 *  EN PRODUCCION ESTA CONSTANTE DEBERÍA SER ESTABLECIDA MANUALMENTE
	 * llamar de qualquer lugar como echo config('constants.your-returned-const'); ou echo PUBLIC_PATH;
	 */
	$number = isset($_SERVER['PATH_INFO']) ? strlen(urldecode($_SERVER['PATH_INFO'])) - 1 : 0;
	$number += empty($_SERVER['QUERY_STRING']) ? 0 : strlen(urldecode($_SERVER['QUERY_STRING'])) + 1;
	define('PUBLIC_PATH', substr(urldecode($_SERVER['REQUEST_URI']), 0, -$number));

	return [
		'your-returned-const' => 'Your returned constant value!'
	];
?>