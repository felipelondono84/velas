<?php

class Consultar_Tarifas{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM tarifas WHERE descrip LIKE '%$codigo%'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Usuarios{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM usuario WHERE usu='$codigo' or nombre LIKE '%$codigo%'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}


class Proceso_Tarifas{
    var $id;   	var $nombre;	var $usu;		var $con;	var $tipo;	var $estado;
    
    function __construct($id,$nombre,$usu,$con,$tipo,$estado){
        $this->id=$id;	$this->nombre=$nombre;	$this->usu=$usu;	$this->con=$con;	$this->tipo=$tipo;	$this->estado=$estado;
    }
    
    function guardar(){
        $id=$this->id;	$nombre=$this->nombre;	$usu=$this->usu;	$con=$this->con;	$tipo=$this->tipo;	$estado=$this->estado;
        mysql_query("INSERT INTO usuario (nombre, usu, con, tipo, estado) VALUE ('$nombre','$usu','$con','$tipo','$estado')");
    }
	
	function actualizar(){
		$id=$this->id;	$nombre=$this->nombre;	$usu=$this->usu;	$con=$this->con;	$tipo=$this->tipo;	$estado=$this->estado;
		mysql_query("UPDATE usuario SET nombre='$nombre', usu='$usu', con='$con', tipo='$tipo', estado='$estado' WHERE id='$id'");
    }
}



?>