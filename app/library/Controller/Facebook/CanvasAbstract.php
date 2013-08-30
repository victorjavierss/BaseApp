<?php
abstract class Controller_Facebook_CanvasAbstract extends Controller_FacebookAbstract{

	protected $_controller    = 'Facebook_CanvasAbstract';


	protected function _AuthAction(){
		ob_get_clean();
		$this->_loadFbJsSdk();
		js_print();
		exit();
	}
}