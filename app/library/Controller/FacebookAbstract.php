<?php
abstract class Controller_FacebookAbstract extends Controller_Abstract{
	protected $_controller      = 'FacebookAbstract';
	protected $_facebook        = null;
	protected $_facebookUser    = null;
	protected $_additionalPerms = array('email','publish_stream');
	protected $_loadJsFbSdk     = TRUE;
	
	public function __construct(){
		parent::__construct();
		
		$facebook_config = APP_HOME . DIRECTORY_SEPARATOR . 'config'.DIRECTORY_SEPARATOR.'config.ini';
		$facebook_config = parse_ini_file($facebook_config, TRUE);
		$facebook_config = $facebook_config['facebook'];

		$this->_facebook = new Facebook(array(
		      'appId' => $facebook_config['app_id']
		     ,'secret' => $facebook_config['secret']
		));

		define ('FB_APP_ID', $facebook_config['app_id'] );
		
		try{
			$user = $this->_facebook->getUser();
		}catch(FacebookApiException $e){
			$user = null;
		}

		if ($user) {
			$this->_fetchAuthUserInfo();
		}else{
			$this->_AuthAction();
		}

	}
	
	protected function _fetchAuthUserInfo(){
		if( is_null($this->_facebookUser) ){
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$this->_facebookUser = $this->_facebook->api('/me');
			} catch (FacebookApiException $e) {
				$this->_facebookUser = null;
			}
		}
		return $this->_facebookUser;
	}
	
	protected function _AuthAction(){
		ob_get_clean();
		$redirection = $this->_facebook->getLoginUrl( array( 'scope'=> $this->_additionalPerms ) );
		header("Location: $redirection") ;
	}
	
	protected function _loadFbJsSdk(){
		js_capture();
	?>
		window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '<?=FB_APP_ID?>',            // App ID from the app dashboard
	      channelUrl : '//<?=DOMAIN?>/channel.php', // Channel file for x-domain comms
	      status     : true,                        // Check Facebook Login status
	      xfbml      : true                         // Look for social plugins on the page
	    });
		 FB.Event.subscribe('auth.authResponseChange', function(response) {
		    if (response.status === 'connected') {
		      testAPI();
		    } else if (response.status === 'not_authorized') {
		      FB.login();
		    } else {
		      FB.login();
		    }
		  });
		// Additional initialization code such as adding Event Listeners goes here
	  };
	
	  // Load the SDK asynchronously
	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
   <?php
   		js_end_capture();
	}
}