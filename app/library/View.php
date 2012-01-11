<?php
class View{
	
	private static $_header_css_js = "";
	private static $_content       = NULL;
	private static $_raw_output    = FALSE;
	 
	private function __construct(){}
 
	public static function header($module){
		  if ( FALSE !== $module ){
		  	Wisdom_Head::css($module);
		  	self::$_header_css_js.=Wisdom_Head::js($module);
		  }
	}

	public static function render(){
		$content = ob_get_clean();

		if(!Wisdom_Request::isAjax() && !self::$_raw_output){
			/**
			 *	@depreated
			 */
			$parse['header'] = Wisdom_Head::metas() .
			                   Wisdom_Head::css()->render(). 
			                   Wisdom_Head::js()    . 
			                   self::$_header_css_js.
							   Wisdom_Head::renderMessageQueue();
			/*$parse [ 'metas' ]    = Wisdom_Head::metas();
			$parse ['style']    = Wisdom_Head::css()->render();
			$parse ['javascript'] = Wisdom_Head::js();
	*/
			$parse['content'] = $content;

			$common_path = APP_HOME."/common/views";
			
			$template = Wisdom_Mobile::is() && is_file($common_path."/mobile.phtml") ? "mobile" : "template";
			
			Wisdom_View::element($template, $common_path, $parse);
		}else{
			#Solo se muestra el contenido
			echo $content;
		}
	}
	
	public static function page($vars = array(),$view=NULL,$path = NULL){
	    $backtrace        = debug_backtrace();
	    $module           = NULL;
	    $objeto_anterior = $backtrace[1]["object"];
	    if( !$view ){
	        $view = $backtrace[1]["function"];
	    }
	    if( !$path ){
	        $path   = $objeto_anterior->getPath() . '/views';
	    }
	    $module = $objeto_anterior->getModule();
	    self::header($module);
	    self::element($view, $path, $vars);
	}
	
	public static function element($name, $path, $vars = array(), $ext="phtml"){
	    //Armamos la ruta de la vista
		$path_file = $path."/{$name}.{$ext}";

		if (file_exists($path_file) == false){
		    throw new Exception("No view for {$name} was found in {$path_file}",3);
		}else{

		   $req = Wisdom_Utils::factory("Wisdom_Request");

		   $lang = $req->lang;

	       $vars['helper']     = Wisdom_Utils::factory()->get('Wisdom_Helper');
	       $vars['widget']     = Wisdom_Utils::factory()->get('Wisdom_Widget');
	       $vars['translator'] = Wisdom_Utils::factory()->get('Wisdom_Services')->Translator($lang);
	       $vars['js']         = Wisdom_Utils::factory("Wisdom_Head_Javascript");
	       $vars['css']        = Wisdom_Utils::factory("Wisdom_Head_Style");
		   $vars['request']    = Wisdom_Utils::factory()->get("Wisdom_Request");

	       if(is_array($vars)){
              foreach ($vars as $key => $value){
                  	$$key = $value;
              }
           }

		   //Finalmente, incluimos la plantilla.
		   include_once($path_file);
		}
	}

	public static function notify($message){
		$_SESSION['message_queue']['messages'][]= $message;
	}
}
?>