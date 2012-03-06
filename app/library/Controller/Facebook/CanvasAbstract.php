<?php
class Controller_Facebook_CanvasAbstract extends Controller_Abstract{

	protected $_controller    = 'Facebook_CanvasAbstract';
	protected $_facebookApi   = NULL;

	public function __construct(){
		parent::__construct();

		$configFile = APP_HOME . DIRECTORY_SEPARATOR . 'config'.DIRECTORY_SEPARATOR.'config.ini';
		$fbConfig = new Zend_Config_Ini($configFile, 'facebook');

		var_dump($fbConfig);

		/*$this->$_facebookApi = new Facebook(array(
				      'appId' => $facebook_config['app_id'],
				      'secret' => $facebook_config['secret']));
		*/
	}
}