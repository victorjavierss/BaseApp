<?php

$iniFile = APP_HOME . DIRECTORY_SEPARATOR . 'config' 
					. DIRECTORY_SEPARATOR . 'config.ini';

$config = new Zend_Config_Ini($iniFile,'general');

$js_include = array();
$js_capture = FALSE;
$title      = $config->title;
$head      = array();

function is_ajax(){	
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')?true:false; 
}

function head_capture(){
	ob_start();
}

function head_end_capture(){
	global $head;
	$head[] = str_replace(array('\n','\t'), null, ob_get_clean()) ;
}

function head_print(){
	global $head;
	echo implode(NULL, $head);;
}

function js_include($js_script_file, $prepend = FALSE){
	global $js_include;
	if(!in_array($js_script_file, $js_include)){
		if($prepend){
			$js_include = array_merge(array($js_script_file),$js_include);
		}else{
			$js_include [] = $js_script_file;
		}
	}
}

function js_capture(){
	ob_start();
}

function js_end_capture(){
	global $js_capture;
	$js_capture[] = str_replace(array('\n','\t'), null, ob_get_clean()) ;
}

function js_print(){
	global $js_include;
	global $js_capture;
	
	foreach($js_include as $script){
		echo "<script type='text/javascript' src='{$script}'></script>";
	}
	if($js_capture){
		echo "<script type='text/javascript'>\n
				//<![CDATA[\n";
		echo implode("\n", $js_capture);
		echo "//]]>\n
				</script>";
	}
}
function getFiles($dir){
	$files=array();
	if(is_dir($dir)){
		$open = opendir($dir);
		while($file = readdir($open)){
			$path=$dir."/".$file;
			if( is_file($path) ){
				$files[] = $file;
			}
		}
	}
	natsort($files);
	return $files;
}

function title($titulo = FALSE){
	GLOBAL $title;
	if($titulo){
		$title = $titulo;
	}
	return $title;
}

function preg_grep_keys( $pattern, $input, $flags = 0 ){
	$keys = preg_grep( $pattern, array_keys( $input ), $flags );
	$vals = array();
	foreach ( $keys as $key ){
		$vals[$key] = $input[$key];
	}
	return $vals;
}