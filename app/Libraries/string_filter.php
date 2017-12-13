<?php  
	namespace App\Libraries;

	/**
	 * Filtro para limpieza de textos
	 *
	 * @category    Extensions
	 * @package     Filters
	 */

	class StringFilter
	{

	    /**
	     * Ejecuta el filtro para los string en minúsculas
	     *
	     * @param string $s
	     * @param array $options
	     * @return string
	     */
	    public static function execute($s, $options) {        
	        $string = filter_var($s, FILTER_SANITIZE_STRING);
	        $string = strip_tags((string) $string);
	        $string = stripslashes((string) $string);
	        $string = trim($string);
	        return $string;
	   }

	}
	
?>