<?php
class logicc
{
	function logicc()
	{
		$this->torneoActual = new torneo();
		$this->torneoActual->setactivo(1);
		$this->torneoActual = $torneoActual->read(false,1,array("activo"));
	}
	function trabaja($id_pagina,$tipo_pagina)
	{
	}
	
	function inscribir_personaje($nombre,$serie,$idtorneo,$estado)
	{
		$personaje_inscribir = new personajepar("",$nombre,$serie,-1,-1,"","",$this->torneoActual->getid(),"INS","N","N","",-1);
		$personaje_inscribir->save();
	}
}