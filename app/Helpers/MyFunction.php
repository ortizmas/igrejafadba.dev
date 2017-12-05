<?php 
	namespace App\Helpers;
	use Illuminate\Support\Str;

	class MyFunction
	{
		public static function ma_get_admin_delete_url($model=NULL)
		{
			
		    $modelName = (!is_object($model)) ? strtolower($model) : strtolower(str_plural(class_basename($model)));
		    $path = '/painel/'. class_basename($model);
		    return url($path . '/' . str_plural($modelName) . '/' . $model->id);
		}

		/**
		 * Obter o nome da class
		 */
		public static function className($nameClass)
		{
			$path = explode('\\', $nameClass);
    		return array_pop($path); //Obten o ultimo array
		}

		public static function full_name($nome, $apellido)
		{
			echo "{$nome} apellidos: {$apellido}";
		}
	}
?>