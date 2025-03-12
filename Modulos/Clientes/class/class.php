<?php

class Proceso_Cliente{
	var $doc;		var $nom;		var $ape;		var $fecha;		var $tel;		var $cel;
	var $sexo;		var $dir;		var $nota;		var $fechar;	var $estado;	var $correo;	
	var $con;		var $cupo;
	
	function __construct($doc,$nom,$ape,$fecha,$tel,$cel,$sexo,$dir,$nota,$fechar,$estado,$correo,$con,$cupo){
		$this->doc=$doc;	$this->nom=$nom;	$this->ape=$ape;	$this->fecha=$fecha; 	$this->tel=$tel;		$this->cel=$cel;
		$this->sexo=$sexo;	$this->dir=$dir;	$this->nota=$nota;	$this->fechar=$fechar;	$this->estado=$estado;	$this->correo=$correo;	
		$this->con=$con;	$this->cupo=$cupo;
	}
	
	function crear(){
		$doc=$this->doc;	$nom=$this->nom;	$ape=$this->ape;	$fecha=$this->fecha;	$tel=$this->tel;		$cel=$this->cel;
		$sexo=$this->sexo;	$dir=$this->dir;	$nota=$this->nota;	$fechar=$this->fechar;	$estado=$this->estado;	$correo=$this->correo;	
		$con=$this->con;	$cupo=$this->cupo;
		
		mysql_query("INSERT INTO persona (doc, nom, ape, fecha, tel, cel, sexo, dir, nota, fechar, estado) VALUES 
				('$doc','$nom','$ape','$fecha','$tel','$cel','$sexo','$dir','$nota','$fechar','$estado')");
		mysql_query("INSERT INTO username (usu, con, correo, fecha, tipo) VALUES ('$doc','$con','$correo','$fecha','cliente')");
		mysql_query("INSERT INTO cliente (doc, cupo, utilizado, puntos) value ('$doc','$cupo','0','0')");
	}
	
	function actualizar(){
		$doc=$this->doc;	$nom=$this->nom;	$ape=$this->ape;	$fecha=$this->fecha;	$tel=$this->tel;		$cel=$this->cel;
		$sexo=$this->sexo;	$dir=$this->dir;	$nota=$this->nota;	$fechar=$this->fechar;	$estado=$this->estado;	$correo=$this->correo;	
		$con=$this->con;	$cupo=$this->cupo;
		
		mysql_query("UPDATE persona SET nom='$nom', ape='$ape', fecha='$fecha', tel='$tel', cel='$cel', sexo='$sexo', dir='$dir',
										nota='$nota', estado='$estado' WHERE doc='$doc'");
		mysql_query("UPDATE username SET correo='$correo' WHERE usu='$doc'");
		mysql_query("UPDATE cliente SET cupo='$cupo' WHERE doc='$doc'");
	}
}

?>