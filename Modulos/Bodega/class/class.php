<?php
class Proceso_Compra{
	var $producto;	var $prov;		var $deposito;	var $cant;
	var $valor; 	var $estado;	var $usu;		var $fecha;		var $id;
	
	function __construct($id,$producto,$prov,$deposito,$cant,$valor,$estado,$usu,$fecha){
		$this->producto=$producto;	$this->prov=$prov;		$this->deposito=$deposito;	$this->cant=$cant;
		$this->valor=$valor;		$this->estado=$estado;	$this->usu=$usu;			$this->fecha=$fecha;	$this->id=$id;
	}
	
	function crear(){
		$producto=$this->producto;	$prov=$this->prov;		$deposito=$this->deposito;	$cant=$this->cant;
		$valor=$this->valor;		$estado=$this->estado;	$usu=$this->usu;			$fecha=$this->fecha;	$id=$this->id;
		
		mysql_query("INSERT INTO compras (producto, prov, deposito, cant, valor, estado, usu, fecha) 
		VALUES ('$producto','$prov','$deposito','$cant','$valor','n','$usu','$fecha')");
	}
	
	function actualizar(){
		$producto=$this->producto;	$prov=$this->prov;		$deposito=$this->deposito;	$cant=$this->cant;
		$valor=$this->valor;		$estado=$this->estado;	$usu=$this->usu;			$fecha=$this->fecha;	$id=$this->id;
		
		mysql_query("UPDATE compras SET estado='s' WHERE id='$id'");
	}
}
?>