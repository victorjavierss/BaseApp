<?php
class Controller_Index extends Controller_Abstract{

	protected $_controller = 'index';
	
	public function indexAction(){
		Zend_Db_Table::setDefaultAdapter($this->_db);
		$tablaAlgo = new Zend_Db_Table('encuesta');
		var_dump($tablaAlgo->info(Zend_Db_Table::COLS));
	}
}