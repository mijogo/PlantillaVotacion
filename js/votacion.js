function votajs(arrayidpersonaje,inmaximo,inip,inidevento,iniduser,arrayidbatalla,incantidadmatch) 
{
	this.personajes = arrayidpersonaje;
	this.idbatalla = arrayidbatalla;
	this.cantidadmatch = incantidadmatch;
	this.maximo = inmaximo;
	
	this.ip = inip;
	this.idevento = inidevento;
	this.iduser = iniduser;
	
	this.inversabatalla = new Array();
	this.inversapersonaje = new Array();
	this.activado = new Array();
	
	this.init = function() 
	{
		for(var i=0;i<this.cantidadmatch;i++)
		{
			this.activado[i] = new Array();
			for(var j=0;j<this.personajes[i].length;j++)	
			{
				this.inversabatalla[this.personajes[i][j]] = i;	
				this.inversapersonaje[this.personajes[i][j]] = j;	
				this.activado[i][j] = 0;
			}
		}
	}
	
	this.votoactivar = function(id) 
	{
		var batallapersonaje = this.inversabatalla[id];
		if(this.contartotal()==0)
			$("#botonvoto").removeAttr("disabled");
		
		if(this.contaractivos(batallapersonaje)<this.maximo-1)
		{
			$("#idpersonaje"+id).addClass("active");
			$("#idpersonaje"+id).html("Listo")
			this.activado[batallapersonaje][this.inversapersonaje[id]]=1;
			$("#idpersonaje"+id).attr("onclick", "votacion.votodesactivar("+id+")");
		}
		else if(this.contaractivos(batallapersonaje)==this.maximo-1)
		{
			$("#idpersonaje"+id).addClass("active");
			$("#idpersonaje"+id).html("Listo")
			this.activado[batallapersonaje][this.inversapersonaje[id]]=1;
			$("#idpersonaje"+id).attr("onclick", "votacion.votodesactivar("+id+")");
			for(var i=0;i<this.activado[batallapersonaje].length;i++)
			{
				if(this.activado[batallapersonaje][i]==0)
				{
					$("#idpersonaje"+this.personajes[batallapersonaje][i]).attr("disabled", "disabled");
				}
			}
		}
		this.datospost();
	}
	
	this.votodesactivar = function(id)
	{
		if(this.contartotal()==1)
			$("#botonvoto").attr("disabled","disabled");
		var batallapersonaje = this.inversabatalla[id];
		if(this.contaractivos(batallapersonaje)==this.maximo)
			for(var i=0;i<this.activado[batallapersonaje].length;i++)
				if(this.activado[batallapersonaje][i]==0)
					 $("#idpersonaje"+this.personajes[batallapersonaje][i]).removeAttr("disabled");
		$("#idpersonaje"+id).removeClass("active");
		$("#idpersonaje"+id).html("Votar")
		this.activado[batallapersonaje][this.inversapersonaje[id]]=0;
		$("#idpersonaje"+id).attr("onclick", "votacion.votoactivar("+id+")");
		this.datospost();
	}
	
	this.contaractivos = function(batallacontar)
	{
		var cantidadactivos = 0;
		for(var i=0;i<this.activado[batallacontar].length;i++)
		{
			if(this.activado[batallacontar][i]==1)
				cantidadactivos++;
		}	
		return cantidadactivos;
	}
	
	this.contartotal = function()
	{
		var cantidadactivos = 0;
		for(var i=0;i<this.activado.length;i++)
		{
			for(var j=0;j<this.activado[i].length;j++)
				if(this.activado[i][j]==1)
					cantidadactivos++;
		}
		return cantidadactivos;	
	}
	
	this.datospost = function()
	{
		var eventocadena = this.iduser+"-"+this.idevento+"-"+this.ip+"-"+this.cantidadmatch;
		for(var j=0;j<this.cantidadmatch;j++)
		{
			eventocadena += ";";
			eventocadena += this.idbatalla[j]+"-"+this.contaractivos(j)+"-"+this.maximo;
			for(var i=0;i<this.activado[j].length;i++)
				if(this.activado[j][i]==1)
					eventocadena += "-"+this.personajes[j][i];
		}
		$("#evento"+this.idevento).attr("value",eventocadena );
	}
}