<?php

class Proceso_Deposito{
	var $id;	var $nombre;		var $dir;	
	var $tel;	var $encargado;		var $estado;	
	
	function __construct($id,$nombre,$dir,$tel,$encargado,$estado){
		$this->id=$id;		$this->nombre=$nombre;				$this->dir=$dir;
		$this->tel=$tel;	$this->encargado=$encargado;		$this->estado=$estado;
	}
	
	function crear(){
		$id=$this->id;		$nombre=$this->nombre;					$dir=$this->dir;
		$tel=$this->tel;	$encargado=$this->encargado;			$estado=$this->estado;
		
		mysql_query("INSERT INTO deposito (nombre, dir, tel, encargado, estado) VALUES ('$nombre','$dir','$tel','$encargado','$estado')");
	}
	
	function actualizar(){
		$id=$this->id;		$nombre=$this->nombre;					$dir=$this->dir;
		$tel=$this->tel;	$encargado=$this->encargado;			$estado=$this->estado;
		
		mysql_query("UPDATE deposito SET nombre='$nombre',
										 dir='$dir',
										 tel='$tel',
										 encargado='$encargado',
										 estado='$estado' 		 
								WHERE id='$id'");
	}
}

class Proceso_Sistemas{
	var $id;	var $nombre;	var $estado;	
	
	function __construct($id,$nombre,$estado){
		$this->id=$id;	$this->nombre=$nombre;	$this->estado=$estado;
	}
	
	function crear(){
		$id=$this->id;	$nombre=$this->nombre;	$estado=$this->estado;
		
		mysql_query("INSERT INTO unidad (nombre, estado) VALUES ('$nombre','$estado')");
	}
	
	function actualizar(){
		$id=$this->id;	$nombre=$this->nombre;	$estado=$this->estado;
		
		mysql_query("UPDATE unidad SET nombre='$nombre', estado='$estado' WHERE id='$id'");
	}
}

class Proceso_Departamento{
	var $id;	var $nombre;	var $estado;	
	
	function __construct($id,$nombre,$estado){
		$this->id=$id;	$this->nombre=$nombre;	$this->estado=$estado;
	}
	
	function crear(){
		$id=$this->id;	$nombre=$this->nombre;	$estado=$this->estado;
		
		mysql_query("INSERT INTO departamento (nombre, estado) VALUES ('$nombre','$estado')");
	}
	
	function actualizar(){
		$id=$this->id;	$nombre=$this->nombre;	$estado=$this->estado;
		
		mysql_query("UPDATE departamento SET nombre='$nombre', estado='$estado' WHERE id='$id'");
	}
}

class Proceso_IVA{
	var $id;	var $nombre;	var $valor;		var $estado;	
	
	function __construct($id,$nombre,$valor,$estado){
		$this->id=$id;		$this->nombre=$nombre;		$this->valor=$valor;	$this->estado=$estado;
	}
	
	function crear(){
		$id=$this->id;		$nombre=$this->nombre;		$valor=$this->valor;	$estado=$this->estado;
		
		mysql_query("INSERT INTO iva (nombre, valor, estado) VALUES ('$nombre','$valor','$estado')");
	}
	
	function actualizar(){
		$id=$this->id;		$nombre=$this->nombre;		$valor=$this->valor;	$estado=$this->estado;
		
		mysql_query("UPDATE iva SET nombre='$nombre', valor='$valor', estado='$estado' WHERE id='$id'");
	}
}
?>