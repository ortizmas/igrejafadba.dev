<?php 
	namespace App\Helpers;
	use Illuminate\Support\Str;

	class MyFunction
	{
		/**
	     * Constante de llave de seguridad
	     */
	    const TEXT_KEY = 'XTaiE$4Y2M~DBc{)wK-|LI+cPwr=x_Dpf';

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

		public static function ucfirst(string $string)
		{
			return Str::ucfirst($string);
		}

		public static function full_name($nome, $apellido)
		{
			echo "{$nome} apellidos: {$apellido}";
		}

		/**
	     * Método para generar las llaves de seguridad
	     * 
	     * @param int,string $id Identificador o valor de la llave primaria
	     * @param string $action Texto o acción para la llave
	     * @return string
	     */
	    public static function setKey($id, $action='') {        
	        $key = (defined('TEXT_KEY')) ? TEXT_KEY : self::TEXT_KEY.date("Y-m-d");        
	        $key = md5($id.$key.$action);
	        $tam = strlen($key);
	        return $id.'.'.substr($key,0,6).substr($key,$tam-6, $tam);
	    }  
	    
	    /**
	     * Método para verificar si la llave es válida
	     * 
	     * @param string $id
	     * @param string $action
	     * @param string $filter Filtro a aplicar al id devuelto
	     * @return boolean
	     */
	    public static function getKey($valueKey, $action='', $filter='', $popup=FALSE) {
	        $key        = explode('.', $valueKey); 
	        $id         = empty($key[0]) ? NULL : $key[0];
	        $validKey   = self::setKey($id, $action);               
	        $valid      = ($validKey === $valueKey) ? TRUE : FALSE; 
	        if(!$valid) {
	            Flash::error('Acceso denegado. La llave de seguridad es incorrecta.');
	            if($popup) {
	                View::error();
	            } 
	            return FALSE;
	        }                
	        return ($filter) ? Str::camel($id, $filter) : $id;
	    } 

	    /**
	     * Convierte los argumentos de un metodo de parametros por nombre a un string con los atributos
	     *
	     * @param string|array $params argumentos a convertir
	     * @return string
	     */
	    public static function getAttrs($params)
	    {
	        if(!is_array($params)) return (string)$params;
	        $data = '';
	        foreach ($params as $k => $v) {
	            $data .= " $k=\"$v\"";
	        }
	        return $data;
	    }

	    /**
	     * Método para generar un link tipo botón
	     * @param string $action
	     * @param string $text
	     * @param array $attrs
	     * @param string $icon
	     * @param boolean $loadAjax
	     * @return type
	     */
	    public static function button($action, $text = NULL, $attrs = array(), $icon='', $loadAjax = 'APP_AJAX') {
	        if (is_array($attrs) OR empty($attrs)) {
	            if(empty($attrs)) {
	                $attrs['class'] = 'btn-info';
	            }
	            if($loadAjax) {
	                if(empty($attrs['class'])) {
	                    $attrs['class'] = 'js-link js-spinner js-url';
	                } else {
	                    if(!preg_match("/\bbtn-disabled\b/i", $attrs['class']) && !preg_match("/\bload-content\b/i", $attrs['class'])) {
	                        if(!preg_match("/\bno-ajax\b/i", $attrs['class'])) {
	                            $attrs['class'] = 'js-link '.$attrs['class'];
	                        }
	                        if(!preg_match("/\bno-spinner\b/i", $attrs['class'])) {
	                            $attrs['class'] = 'js-spinner '.$attrs['class'];
	                        }
	                        if(!preg_match("/\bno-url\b/i", $attrs['class'])) {
	                            $attrs['class'] = 'js-url '.$attrs['class'];
	                        }
	                    }
	                }
	            }
	            $attrs['class'] = 'btn '.$attrs['class'];
	            if(!preg_match("/\btext-bold\b/i", $attrs['class'])) {
	                $attrs['class'] = $attrs['class'].' text-bold';
	            }
	            if(!empty($attrs)) {
	                $attrs = Self::getAttrs($attrs);
	            }
	        }

	        if(!empty($action)) {
	            $action = trim($action, '/').'/';
	        }
	        $text = (!empty($text) && $icon) ? '<span class="hidden-xs">'.Str::upper($text, 'upper').'</span>' : Str::upper($text, 'upper');
	        if($icon) {
	            $text = '<i class="btn-icon-only fa '.$icon.'"></i> '.$text;
	        }
	        if(empty($action) OR preg_match("/\bbtn-disabled\b/i", $attrs) OR preg_match("/\bload-content\b/i", $attrs)) {
	            return "<button $attrs >$text</button>";
	        }
	        if(!preg_match('/^(http|ftp|https)\:\/\/+[a-z0-9\.\_-]+$/i', $action)) {
	            //return '<a href="' . base_path() . "$action\" $attrs >$text</a>";
	            return '<a href="' . PUBLIC_PATH . "$action\" $attrs >$text</a>";
	        }
	        return "<a href=\"$action\" $attrs >$text</a>";
	    }

	    /**
	     * Método para crear un ícono para las acciones del datagrid
	     * @param string $action
	     * @param array $attrs
	     * @param string $type
	     * @param strin $icon
	     * @param boolean $loadAjax
	     * @return string
	     */
	    public static function buttonTable($title, $action, $attrs = NULL, $type='info', $icon='fa-search', $loadAjax = 'APP_AJAX') {
	        if(empty($attrs)) {
	            $attrs = array();
	            $attrs['class'] = "btn-small btn-$type";
	        } else {
	            $attrs['class'] = empty($attrs['class']) ? "btn-small btn-$type" : "btn-small btn-$type ".$attrs['class'];
	        }
	        $attrs['title'] = $title;
	        $attrs['rel'] = 'tooltip';
	        return self::button($action, '', $attrs, $icon, $loadAjax);
	    }
	}
?>