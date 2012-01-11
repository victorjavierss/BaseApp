<?php
class Controller_Exception extends Controller_Abstract {
	public function showErrorAction(Exception $ex){
		echo $ex->getMessage();
	}
}