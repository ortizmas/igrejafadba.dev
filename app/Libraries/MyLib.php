<?php  
	namespace App\Libraries;
	use App\Models\Recurso;
	use App\Models\Perfil;
	/**
	 * MyLib
	 */
	class MyLib
	{
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

	    public function currentUser()
		{
		    return auth()->user();
		}
	}
	
?>