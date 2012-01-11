<?php
class DbAdapter{
	protected static $_db = NULL;

	public function __call($func, $params){
		$native_func = $this->_driver . "_" . $func;
		!count($params) && $params = array_merge(array($this->_link),$params);
		return call_user_func_array($native_func, $params);
	}
	/**
	 * Gets an instance for a DbAdapter
	 * @param String $section
	 * @return DbAdapter
	 */
	public static function instance($section  = 'default'){
		if( ! isset( self::$_db[$section]) ){
			$iniFile = APP_HOME . DIRECTORY_SEPARATOR . 'config' 
								 . DIRECTORY_SEPARATOR . 'db.ini';
			$config = new Zend_Config_Ini($iniFile,$section);
			self::$_db[$section] = Zend_Db::factory($config->database);
		}else{
			#Ya existe una instancia de $section
		}
		return self::$_db[$section];
	}
}