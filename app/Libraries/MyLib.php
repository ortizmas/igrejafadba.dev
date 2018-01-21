<?php  
	namespace App\Libraries;

	use App\Models\Recurso;
	use App\Models\Perfil;
	use App\Models\Menu;
	use Illuminate\Support\Str;
	use Illuminate\Http\Request;
	/**
	 * MyLib
	 */
	class MyLib
	{
		const BACKEND = 1;
	    /**
	     * construct
	     */
	    public function __construct()
	    {
	        $this->recursos = new Recurso;
	    }

	    public static function hasRecurso($modulo, $order)
	    {
	    	$recursos = new Recurso;
	    	return $recursos->hasRecurso($modulo, $order);
	    }

	    public static function getActivo()
	    {
	    	return Recurso::ACTIVO;
	    }

	    public static function getSuperUser()
	    {
	    	return Perfil::SUPER_USUARIO;	
	    }

	    public static function currentUser()
		{
		    return auth()->user();
		}

		public static function get($s, $filter, $options = array()) {
			 if (is_string($options)) {
	            $filters = func_get_args();
	            unset($filters[0]);

	            $options = array();
	            foreach ($filters as $f) {
	                $filter_class = Str::ucfirst($f).'Filter';
	                if (!class_exists($filter_class, false)) {
	                    self::_load_filter($f);
	                }

	                $s = call_user_func(array(__NAMESPACE__ .'\\'. $filter_class, 'execute'), $s, $options);
	            }
	        } else {
	            $filter_class = studly_case($filter).'Filter'; //StringFilter Str::ucfirst tambe funciona so que no elimina espacios
	            //!class_exists($filter_class, false) isto da true
	            if (!class_exists($filter_class, false)) {
	                self::_load_filter($filter); //include app_path()."/Libraries/{$filter}_filter.php";
	            }
	            //dd(__NAMESPACE__);
	            //não usar namespace App\Libraries; pq ocaciona error
	            
	            $s = call_user_func(array(__NAMESPACE__ .'\\'. $filter_class, 'execute'), $s, $options); //OBTEMOS  StringFilter::execute($s, $options);
	            //$s = call_user_func_array(array(__NAMESPACE__ .'\\'.$filter_class, 'execute'), array($s, $options)); //Com array
	        }

	        return $s;
	    }

	    /**
	     * Carga un Filtro
	     *
	     * @param string $filter filtro
	     * @throw KumbiaException
	     * base_path() = C:\xampp\htdocs\igrejafadba.dev
	     * app_path() = C:\xampp\htdocs\igrejafadba.dev\app
	     * dirname(__FILE__) = C:\xampp\htdocs\igrejafadba.dev\app\Libraries
	     */
	    public static function _load_filter($filter) {
	    	//$file = app_path() . "/Libraries/{$filter}_filter.php";
	        $file = app_path() . "/Libraries/{$filter}_filter.php";
	        if (!is_file($file)) {
	            $file = app_path() ."/Helpers/{$filter}_filter.php";
	            if (!is_file($file)) {
	            	abort(403, "Filtro {$filter} no encontrado");
	            }
	        }
	        require_once $file; //inclue arquivo 
	    }

	}
	
?>