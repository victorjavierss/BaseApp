<?php
class StringGenerator{
	protected static $_metawords = array (
			"vic","bdp","mazatlan","mar","sndr","ald","hux","pot","pla","wis",
			"lol","rolf","fyi","asd","gof","fifa","lala","cof","uy","naH","cela",
			"mac","app","soft","tor","benj","halo"
	);

	protected static $_length 		 = 10;
	protected static $_digits_length = 4;

	/**
	 * Genera el password
	 * @return string
	 */
	public static function generate (){
		mt_srand ((double)microtime()*1000000);

		$pass = NULL;
			
		for($i = 0; $i < self::$_length - self::$_digits_length; $i++){
			$pass .= self::$_metawords[mt_rand(0, (count(self::$_metawords) - 1))];
		}

		for($i=1; $i < strlen($pass); $i++) {
			if(substr($pass, $i, 1) == substr($pass, $i-1, 1)) {
				$pass = substr($pass, 0, $i) . substr($pass, $i + 1);
			}
		}

		$pass = substr($pass, 0, self::$_length - self::$_digits_length);

		$pass .= sprintf("%0".self::$_digits_length."d", mt_rand (0, pow(10, self::$_digits_length) - 1));

		return $pass;
	}

	/**
	 * Longitud de la palabra generada
	 * @param $length
	 * @return void
	 */
	public static function setLength ($length){
		self::$_length = $length;
	}

	/**
	 * Número de dígitos
	 * @param $digits_length
	 * @return void
	 */
	public static function setDigits ($digits_length){
		self::$_digits_length = $digits_length;
	}
}