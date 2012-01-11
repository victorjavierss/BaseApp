<?php
class Ensamblador_Area_Fondo extends Ensamblador_Area_Abstract{
	protected $_name = 'fondo';

	protected function init(){
		$this->_propiedades['fondo'] = new Ensamblador_Area_Propiedad_Fondo();
	}
	
	public function ensamblar($info = array()){
		$ruta = APP_HOME . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $this->_info['folder'] . DIRECTORY_SEPARATOR;
		$imagen = $this->getFondo();
		
		var_dump($this->_info);
		
		$size = getimagesize($ruta.$imagen);
		return array(
				'orden' =>  $this->_info['orden']
				,'cmd'   => "-size {$size[0]}x{$size[1]} xc:white {$ruta}{$imagen} -geometry +0+0  -composite "
			);
	}
	
	public function getFondo(){
		return $this->_info['originales']->Fondo;
	}
}