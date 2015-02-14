<?php
class logicv
{
	function logicv()
	{
	}
	
	function logicaView($id_pagina,$tipo_pagina)
	{
		$paginas[0]=1;//home presentacion
		
		if($id_pagina == $paginas[0])
		{
			$texto[0]="";
			$texto[1]="Aca va la presentacion";
			return $texto;
		}
	}
	
}