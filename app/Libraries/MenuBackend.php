<?php 
	namespace App\Libraries;

	use App\Models\Menu;
	use App\Helpers\MyFunction;
	use App\Helpers\MyUtils;

	use Illuminate\Support\Str;
	use Illuminate\Http\Request;

	class MenuBackend
	{
		/**
	     * Constante para definir un menú visible en el backend
	     */
	    const BACKEND = 1;

	    /**
	     * Constante para definir un menú visible en el frontend
	     */
	    const FRONTEND = 2;

	    /**
	     * Variable que contiene los menús 
	     */
	    protected static $_main = null;
	    
	    /**
	     * Variable que contien los items del menú
	     */        
	    protected static $_items = null;
	    
	    /**
	     * Variabla para indicar el entorno
	     */
	    protected static $_entorno;
	    
	    /**
	     * Variable para indicar el perfil
	     */
	    protected static $_perfil;

		public static function load($entorno, $perfil=null)
		{
			self::$_entorno = $entorno; 
	        self::$_perfil = $perfil;
	        $menu = new Menu();
	        if(self::$_main==NULL) {                        
	            self::$_main = $menu->getListadoMenuPadres($entorno, $perfil);
	        }        
	        if(self::$_items==NULL && self::$_main) {
	            foreach(self::$_main as $menu) {                            
	                self::$_items[$menu->menu] = $menu->getListadoSubmenu($entorno, $menu->id, $perfil);
	            }
	        }
		}

		/**
	     * Método para renderizar el menú de escritorio
	     */
	    // public static function getMenus() {
	    //     $route = request()->path();
	    //     $html = '';
	    //     if(self::$_main) {
	    //         $html.= '<ul class="nav navbar-nav">'.PHP_EOL;
		   //          foreach(self::$_main as $main) {         
		   //              $active = ($main->url==$route) ? 'active' : null;
		   //              $submenu = $main->getListadoSubmenu(self::$_entorno, $main->id, self::$_perfil);
		   //              if ($submenu) {
		   //                      $text = $main->menu.'<b class="caret"></b>';
		   //                      $html.= '<li class="dropdown">';                        
		   //                      $html.= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $main->nome .' <i class="'.$main->icono.'"></i></a>'.PHP_EOL;
		   //                      $html.= '<ul class="dropdown-menu">';
		   //                      foreach($submenu as $item) {                        
		   //                          $active = ($item->url==$route) ? 'active' : null;
		   //                          $html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $item->nome .'</a></li>';
		   //                      }                        
		   //                      $html.= '</ul>';
		   //                      $html.= '</li>';
		                    
		   //              } else {
		   //              	$html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $main->nome .'</a></li>'.PHP_EOL;
		   //              }
		   //          }
	    //         $html.= '</ul>'.PHP_EOL;
	    //     }        
	    //     return $html;
	    // }
	    

	    public static function getMenus() {
	        $route = request()->path();
	        $html = '';
	        if(self::$_main) {
	            $html.= '<ul class="nav navbar-nav">'.PHP_EOL;
		            foreach(self::$_main as $main) {         
		                $active = ($main->url==$route) ? 'active' : null;
		                $submenu = $main->getListadoSubmenu(self::$_entorno, $main->id, self::$_perfil);
		                if ($submenu) {
		                        $text = $main->menu.'<b class="caret"></b>';
		                        $html.= '<li class="dropdown">';                        
		                        $html.= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $main->nome .' <i class="'.$main->icono.'"></i></a>'.PHP_EOL;
		                        $html.= '<ul class="dropdown-menu">';
		                        foreach($submenu as $item) {                        
		                            $active = ($item->url==$route) ? 'active' : null;
		                            $html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $item->nome .'</a></li>';
		                        }                        
		                        $html.= '</ul>';
		                        $html.= '</li>';
		                    
		                } else {
		                	$html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $main->nome .'</a></li>'.PHP_EOL;
		                }
		            }
	            $html.= '</ul>'.PHP_EOL;
	        }        
	        return $html;
	    }
	    

	    /**
	     * Método para listar los items en el backend
	     */
	    public static function getItems() {
	        $route = trim(request()->path());
	        $html = '';        
	        foreach(self::$_items as $menu => $items) {
	            if(array_key_exists($menu, self::$_items)) {
	                foreach(self::$_items[$menu] as $item) {                    
	                    $active = ($item->url==$route or $item->url=='principal') ? 'active' : null;
	                    $submenu = $item->getListadoSubmenu(self::$_entorno, $item->id, self::$_perfil);
	                    if($submenu) {
	                        $html.= '<li class="'.$active.'dropdown">';
	                        $html.= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $item->nome .' <i class="'.$item->icono.'"></i></a>';                     
	                        $html.= '<ul class="dropdown-menu" role="menu">';
	                        foreach($submenu as $tmp) {
	                            $html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $item->nome .'</a></li>'.PHP_EOL;
	                        }
	                        $html.= '</ul>';
	                        $html.= '</li>';
	                    } else {
	                        $html.='<li><a class="'.$active.'" href="#"><i class="fa fa-users text-aqua"></i> '. $item->nome .'</a></li>'.PHP_EOL;                        
	                    }
	                }
	            }
	        }
	        return $html;  
	    }
	}
?>