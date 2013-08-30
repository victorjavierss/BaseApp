<?php
abstract class Controller_Facebook_CanvasAbstract extends Controller_FacebookAbstract{

	protected $_controller    = 'Facebook_CanvasAbstract';


	protected function _AuthAction(){
		ob_get_clean();
		$redirection = $this->_facebook->getLoginUrl( array( 'scope'=> $this->_additionalPerms ) );
		echo "<script type='text/javascript'>
			top.location.href = '{$redirection}';
			</script>";
		exit();
	}
}