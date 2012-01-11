<?php
abstract class Controller_Abstract{
	protected $_view_vars = array();
	/* @var $_db DbAdapter */
	protected $_db = null;
	protected $_controller = 'abstract';
	
	public function __construct(){
		$this->_db = DbAdapter::instance();
	}
	
	public function dispatch( $action ){
		$arguments = func_get_args();
		array_shift ($arguments);
		call_user_func_array(array($this, $action.'Action'), $arguments);
		$path_file = APP_HOME . DIRECTORY_SEPARATOR . 'mods' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . "{$this->_controller}-{$action}.phtml";

		if (false === file_exists($path_file) ){
						
		}else{
			$vars = $this->_view_vars;
			if(is_array($vars)){
				foreach ($vars as $key => $value){
					$$key = $value;
				}
			}
			//Finalmente, incluimos la plantilla.
			include_once($path_file);
		}
	}
}