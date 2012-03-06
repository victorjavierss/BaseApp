<?php
abstract class Controller_FacebookAbstract extends Controller_Abstract{
	protected $_controller    = 'FacebookAbstract';
	protected $_facebook      = null;
	protected $_facebook_user = null;
	
	public function __construct(){
		parent::__construct();
		
		$facebook_config = APP_HOME . DIRECTORY_SEPARATOR . 'config'.DIRECTORY_SEPARATOR.'config.ini';
		$facebook_config = parse_ini_file($facebook_config, TRUE);
		$facebook_config = $facebook_config['facebook'];

		$this->_facebook = new Facebook(array(
		      'appId' => $facebook_config['app_id']
		     ,'secret' => $facebook_config['secret']
		));

		try{
			$user = $this->_facebook->getUser();
		}catch(FacebookApiException $e){
			$user = null;
		}

		if ($user) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$this->_facebook_user= $this->_facebook->api('/me');
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}else{
		// Login or logout url will be needed depending on current user state.
			$redirection = $this->_facebook->getLoginUrl() . '&scope=email,publish_stream';
			header("Location: $redirection") ;
		}
	}
}