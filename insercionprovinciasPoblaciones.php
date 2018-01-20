<?php
	//poner la ruta que proceda 
	require_once("conexion/conexion.php");
	$conexion = crearConexion();	
	set_time_limit(1800); //para evitar el max 30 segundos por defecto 
	// he activado extension=php_soap.dll en php.ini para que funcione ;		
	
	
	$cliente = new SoapClient('http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejeroCodigos.asmx?WSDL',array('trace'=> TRUE));			
		$provincias = $cliente->ObtenerProvincias();				
		$xmlObj=simplexml_load_string($provincias->any) or die("Error: Cannot create object");		
		$provincias = $xmlObj->provinciero;	
		$provinciaslistado = "";
		$codigo="";
		$error ="Listado de errores <br>";
		for($i=0;$i<48;$i++){ //cambiar longitud sizeOf
				$prov1[$i] = $provincias->prov[$i]->np;		 //nombre provincia 				
				$codigopostal = "" .$provincias->prov[$i]->cpine; //código postal  tipo texto 				
				//insertar provincias
				$consulta = "INSERT INTO `provincias`(`provincia`, `codigopostal`) VALUES ('$prov1[$i]','$codigopostal')";		
				if(!$conexion->query($consulta)){						
						$error .= "insercion provincias $prov1[$i] no realizada <br>";
				}		
				//obtengo los municipios de ese codigo postal 
				$municipios = $cliente -> ObtenerMunicipiosCodigos($codigopostal);
				$xmlObj = simplexml_load_string($municipios->any) or die("Error: Cannot create object");
				$municipio = $xmlObj->municipiero->muni;				
				foreach($municipio as $valor) {			//inserto los municipios		
					$consulta = "INSERT INTO `poblaciones`(`poblacion`, `codigopostal`) VALUES ('$valor->nm','$codigopostal')";
					if(!$conexion->query($consulta)){						
						$error .= "insercion  poblaciones  $valor->nm no realizada <br>"; //pasar a log 
					}					
				}				
		}
		echo "<p>Inserción finalizada </p>";
		echo $error;
?>