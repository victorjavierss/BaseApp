<?php
class DbAdapter{

	protected $_driver = 'mysqli';
	protected  $_link = NULL;
	protected $_resultset;
	protected $_table_class = 'DbRecord';
	protected static $_db = NULL;

	const FETCH_ASSOC  = 'assoc';
	const FETCH_ARRAY  = 'array';
	const FETCH_OBJECT = 'object';

	private function __construct($params){
		$native_connect = $this->_driver . "_connect";
		if($this->_link == NULL){
			$this->_link = $native_connect($params['host'], $params['user'], $params['password']);
			if(!$this->_link){
				throw new Exception("No se pudo conectar a la BD");	
			}
		}
		$native_select_db = $this->_driver . "_select_db";
		$native_select_db($this->_link, $params['name']);
	}
	
	public function query($sql){
		$native_query = $this->_driver . "_query";
		$this->_resultset = $native_query($this->_link, $sql);
		return $this->_resultset;
	}
	/**
	 * @return DbRecord
	 */
	public function fetch($type = self::FETCH_OBJECT){
		$native_fetch = $this->_driver . '_fetch_' . $type;
		return $this->_resultset!=false ? $native_fetch($this->_resultset, $this->_table_class) : FALSE;
	}

	public function fetchAll($type = self::FETCH_OBJECT){
		$results = array();
		while($result = $this->fetch($type)){
			$results [] = $result;
		}
		return $results;
	}

	public function __call($func, $params){
		$native_func = $this->_driver . "_" . $func;
		!count($params) && $params = array_merge(array($this->_link),$params);
		return call_user_func_array($native_func, $params);
	}
	/**
	 * Enter description here ...
	 * @param String $ini
	 * @return DbAdapter
	 */
	public static function instance($ini = 'db.ini'){
		if( ! isset( self::$_db[$ini]) ){
			$ini_file = APP_HOME . DIRECTORY_SEPARATOR . 'config' 
								 . DIRECTORY_SEPARATOR . $ini;
								
			$db_config = parse_ini_file($ini_file);
			self::$_db[$ini]= new DbAdapter($db_config);
		}else{
			#Ya existe una instancia de $ini
		}
		return self::$_db[$ini];
	}
}