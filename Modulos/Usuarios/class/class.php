<?php
function permisos($usu,$tipo){
	if($tipo=='a'){
		$consultar=mysql_query("SELECT * FROM permisos_tmp");
		while($date=mysql_fetch_array($consultar)){
			$permiso=$date['id'];
			mysql_query("INSERT INTO permisos (permiso,usu,estado) VALUES ('$permiso','$usu','s')");
		}
	}elseif($tipo=='c'){
		$consultar=mysql_query("SELECT * FROM permisos_tmp");
		while($date=mysql_fetch_array($consultar)){
			$permiso=$date['id'];
			
			if($permiso=='3'){
				mysql_query("INSERT INTO permisos (permiso,usu,estado) VALUES ('$permiso','$usu','s')");
			}else{
				mysql_query("INSERT INTO permisos (permiso,usu,estado) VALUES ('$permiso','$usu','n')");
			}
		}
	}
}

class Proceso_Usuario{
	var $doc;		var $nom;		var $ape;		var $fecha;		var $tel;		var $cel;
	var $sexo;		var $dir;		var $nota;		var $fechar;	var $estado;	var $correo;	
	var $con;		var $tipo;		var $deposito;	var $por;
	
	function __construct($doc,$nom,$ape,$fecha,$tel,$cel,$sexo,$dir,$nota,$fechar,$estado,$correo,$con,$tipo,$deposito,$por){
		$this->doc=$doc;	$this->nom=$nom;	$this->ape=$ape;	$this->fecha=$fecha; 	$this->tel=$tel;		$this->cel=$cel;
		$this->sexo=$sexo;	$this->dir=$dir;	$this->nota=$nota;	$this->fechar=$fechar;	$this->estado=$estado;	$this->correo=$correo;	
		$this->con=$con;	$this->tipo=$tipo;	$this->deposito=$deposito;					$this->por=$por;
	}
	
	function crear(){
		$doc=$this->doc;	$nom=$this->nom;	$ape=$this->ape;	$fecha=$this->fecha;	$tel=$this->tel;		$cel=$this->cel;
		$sexo=$this->sexo;	$dir=$this->dir;	$nota=$this->nota;	$fechar=$this->fechar;	$estado=$this->estado;	$correo=$this->correo;	
		$con=$this->con;	$tipo=$this->tipo;	$deposito=$this->deposito;					$por=$this->por;
		
		mysql_query("INSERT INTO persona (doc, nom, ape, fecha, tel, cel, sexo, dir, nota, fechar, estado) VALUES 
				('$doc','$nom','$ape','$fecha','$tel','$cel','$sexo','$dir','$nota','$fechar','$estado')");
		mysql_query("INSERT INTO username (usu, con, correo, fecha, tipo) VALUES ('$doc','$con','$correo','$fecha','$tipo')");
		mysql_query("INSERT INTO cajero (usu, deposito,por) VALUES ('$doc','$deposito','$por')");
	}
	
	function actualizar(){
		$doc=$this->doc;	$nom=$this->nom;	$ape=$this->ape;	$fecha=$this->fecha;	$tel=$this->tel;		$cel=$this->cel;
		$sexo=$this->sexo;	$dir=$this->dir;	$nota=$this->nota;	$fechar=$this->fechar;	$estado=$this->estado;	$correo=$this->correo;	
		$con=$this->con;	$tipo=$this->tipo;	$deposito=$this->deposito;					$por=$this->por;
		
		mysql_query("UPDATE persona SET nom='$nom', ape='$ape', fecha='$fecha', tel='$tel', cel='$cel', sexo='$sexo', dir='$dir',
										nota='$nota', estado='$estado' WHERE doc='$doc'");
		mysql_query("UPDATE username SET correo='$correo', tipo='$tipo' WHERE usu='$doc'");
		mysql_query("UPDATE cajero SET deposito='$deposito', por='$por' WHERE usu='$doc'");
	}
}

?>