<?php
class Proceso_Proveedor{
	var $id;	var $nombre;	var $dir;	var $tel;	
	var $fax;	var $nota;		var $contacto;
	
	function __construct($id,$nombre,$dir,$tel,$fax,$nota,$contacto){
		$this->id=$id;		$this->nombre=$nombre;		$this->dir=$dir;	$this->tel=$tel;
		$this->fax=$fax;	$this->nota=$nota;			$this->contacto=$contacto;	
	}
	
	function crear(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$id=$this->id;		$nombre=$this->nombre;		$dir=$this->dir;	$tel=$this->tel;
		$fax=$this->fax;	$nota=$this->nota;			$contacto=$this->contacto;	
		
		$conexion->query("INSERT INTO proveedor (nombre, dir, tel, fax, nota, contacto) VALUES ('$nombre','$dir','$tel','$fax','$nota','$contacto')");
	}
	
	function actualizar(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$id=$this->id;		$nombre=$this->nombre;		$dir=$this->dir;	$tel=$this->tel;
		$fax=$this->fax;	$nota=$this->nota;			$contacto=$this->contacto;	
		
		$conexion->query("UPDATE proveedor SET nombre='$nombre', 
										dir='$dir',
										tel='$tel',
										fax='$fax',
										nota='$nota',
										contacto='$contacto'
									WHERE id='$id'");
	}
}

class Proceso_Contenido{
	var $id;			var $deposito;		var $cant;			var $minima;		var $producto;
	
	function __construct($id, $deposito, $cant, $minima, $producto){
		$this->id=$id;		$this->deposito=$deposito;		$this->cant=$cant;		$this->minima=$minima;		$this->producto=$producto;
	}
	
	function crear(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$id=$this->id;		$deposito=$this->deposito;		$cant=$this->cant;		$minima=$this->minima;		$producto=$this->producto;	
		$conexion->query("INSERT INTO contenido (deposito, producto, cant, minima) VALUES ('$deposito','$producto','$cant','$minima')");
	}
	
	function actualizar(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$id=$this->id;		$deposito=$this->deposito;		$cant=$this->cant;		$minima=$this->minima;		$producto=$this->producto;	
		$conexion->query("UPDATE contenido SET cant='$cant', minima='$minima' WHERE id='$id'");
	}
}
class Proceso_Producto{
	var $codigo;       	var $nombre;      	var $depart;   		var $unidad;		var $defecto;  
	var $ivacompra;		var $ivaventa;		var $costo_prov;	var $ocosto_prov;
	var $a_venta;		var $b_venta;		var $c_venta;		var $d_venta;
	var $a_costo;		var $b_costo;		var $c_costo;		var $d_costo;
	
	function __construct($codigo, $nombre, $depart, $unidad, $defecto, $ivacompra, $ivaventa, $costo_prov, $ocosto_prov, $a_venta, $b_venta, $c_venta, $d_venta, $a_costo, $b_costo, $c_costo, $d_costo){
		
		$this->codigo=$codigo;			$this->nombre=$nombre;		$this->depart=$depart;			$this->unidad=$unidad;	$this->defecto=$defecto;
		$this->ivacompra=$ivacompra;	$this->ivaventa=$ivaventa;	$this->costo_prov=$costo_prov;	$this->ocosto_prov=$ocosto_prov;		
		$this->a_venta=$a_venta;		$this->b_venta=$b_venta;	$this->c_venta=$c_venta;		$this->d_venta=$d_venta;
		$this->a_costo=$a_costo;		$this->b_costo=$b_costo;	$this->c_costo=$c_costo;		$this->d_costo=$d_costo;		      
	}
	
	function crear(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$codigo=$this->codigo;			$nombre=$this->nombre;		$depart=$this->depart;			$unidad=$this->unidad;	$defecto=$this->defecto;
		$ivacompra=$this->ivacompra;	$ivaventa=$this->ivaventa;	$costo_prov=$this->costo_prov;	$ocosto_prov=$this->ocosto_prov;
		$a_venta=$this->a_venta;		$b_venta=$this->b_venta;	$c_venta=$this->c_venta;		$d_venta=$this->d_venta;
		$a_costo=$this->a_costo;		$b_costo=$this->b_costo;	$c_costo=$this->c_costo;		$d_costo=$this->d_costo;
		
		$conexion->query("INSERT INTO producto 
		(codigo, nombre, depart, unidad, defecto, ivacompra, ivaventa, costo_prov, ocosto_prov, a_venta, b_venta, c_venta, d_venta, a_costo, b_costo, c_costo, d_costo) VALUES ('$codigo','$nombre','$depart','$unidad','$defecto','$ivacompra','$ivaventa','$costo_prov','$ocosto_prov','$a_venta','$b_venta','$c_venta','$d_venta','$a_costo','$b_costo','$c_costo','$d_costo')");
	}
	function actualizar(){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$codigo=$this->codigo;			$nombre=$this->nombre;		$depart=$this->depart;			$unidad=$this->unidad;	$defecto=$this->defecto;
		$ivacompra=$this->ivacompra;	$ivaventa=$this->ivaventa;	$costo_prov=$this->costo_prov;	$ocosto_prov=$this->ocosto_prov;
		$a_venta=$this->a_venta;		$b_venta=$this->b_venta;	$c_venta=$this->c_venta;		$d_venta=$this->d_venta;
		$a_costo=$this->a_costo;		$b_costo=$this->b_costo;	$c_costo=$this->c_costo;		$d_costo=$this->d_costo;
		$conexion->query("UPDATE producto SET nombre='$nombre', 
										depart='$depart',
										unidad='$unidad',
										defecto='$defecto',
										ivacompra='$ivacompra',
										ivaventa='$ivaventa',
										costo_prov='$costo_prov',
										ocosto_prov='$ocosto_prov',
										a_venta='$a_venta',
										b_venta='$b_venta',
										c_venta='$c_venta',
										d_venta='$d_venta',
										a_costo='$a_costo',
										b_costo='$b_costo',
										c_costo='$c_costo',
										d_costo='$d_costo'
								WHERE codigo='$codigo'");		
	}
}

?>