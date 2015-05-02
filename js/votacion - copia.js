var personajes = new Array();
var inversabatalla = new Array();
var inversapersonaje = new Array();
var idbatalla = new Array();
var activado = new Array();
var ip;
var idevento;
var iduser;
var idbatalla;
var cantidadmatch;

function llenardatos(arrayidpersonaje,inmaximo,inip,inidevento,iniduser,arrayidbatalla,incantidadmatch)
{
	personajes = arrayidpersonaje;
	idbatalla = arrayidbatalla;
	cantidadmatch = incantidadmatch;
	maximo = inmaximo;
	
	ip = inip;
	idevento = inidevento;
	iduser = iniduser;
	
	for(var i=0;i<cantidadmatch;i++)
	{
		activado[i] = new Array();
		for(var j=0;j<personajes[i].length;j++)	
		{
			inversabatalla[personajes[i][j]] = i;	
			inversapersonaje[personajes[i][j]] = j;	
			activado[i][j] = 0;
		}
	}
}

function votoactivar(id) 
{
	var batallapersonaje = inversabatalla[id];
	if(contaractivos(batallapersonaje)<maximo-1)
	{
		$("#idpersonaje"+id).addClass("active");
		$("#idpersonaje"+id).html("Listo")
		activado[batallapersonaje][inversapersonaje[id]]=1;
		$("#idpersonaje"+id).attr("onclick", "votodesactivar("+id+")");
	}
	else if(contaractivos(batallapersonaje)==maximo-1)
	{
		$("#idpersonaje"+id).addClass("active");
		$("#idpersonaje"+id).html("Listo")
		activado[batallapersonaje][inversapersonaje[id]]=1;
		$("#idpersonaje"+id).attr("onclick", "votodesactivar("+id+")");
		for(var i=0;i<activado[batallapersonaje].length;i++)
		{
			if(activado[batallapersonaje][i]==0)
			{
				$("#idpersonaje"+personajes[batallapersonaje][i]).attr("disabled", "disabled");
			}
		}
	}
	datospost();
}
		
function votodesactivar(id)
{
	var batallapersonaje = inversabatalla[id];
	if(contaractivos(batallapersonaje)==maximo)
		for(var i=0;i<activado[batallapersonaje].length;i++)
			if(activado[batallapersonaje][i]==0)
				 $("#idpersonaje"+personajes[batallapersonaje][i]).removeAttr("disabled");
	$("#idpersonaje"+id).removeClass("active");
	$("#idpersonaje"+id).html("Votar")
	activado[batallapersonaje][inversapersonaje[id]]=0;
	$("#idpersonaje"+id).attr("onclick", "votoactivar("+id+")");
	datospost();
}

function contaractivos(batallacontar)
{
	var cantidadactivos = 0;
	for(var i=0;i<activado[batallacontar].length;i++)
	{
		if(activado[batallacontar][i]==1)
			cantidadactivos++;
	}	
	return cantidadactivos;
}
		
function datospost()
{
	var eventocadena = iduser+"-"+idevento+"-"+ip+";";
	for(var j=0;j<cantidadmatch;j++)
	{
		eventocadena += ";";
		eventocadena += idbatalla[j]+"-"+contaractivos(j)+"-"+maximo;
		for(var i=0;i<activado[j].length;i++)
			if(activado[j][i]==1)
				eventocadena += "-"+personajes[j][i];
	}
	$("#evento"+idevento).attr("value",eventocadena );
}