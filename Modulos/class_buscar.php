<?php

######################REGISTRAR RESUMEN#########################

class Proceso_Resumen{
   
	var $concepto;	var $clase;		var $valor;  var $conexion;
	var $tipo; 		var $fecha;		var $usu;
	
	
	
	function __construct($concepto,$clase,$valor,$tipo,$fecha,$usu){
		$this->concepto=$concepto;	$this->clase=$clase;	$this->valor=$valor;	
		$this->tipo=$tipo;			$this->fecha=$fecha;	$this->usu=$usu;
		$this->tipo=$conexion;
	}
	
	function crear(){
		$concepto=$this->concepto;	$clase=$this->clase;	$valor=$this->valor;
		$tipo=$this->tipo;			$fecha=$this->fecha;	$usu=$this->usu;
		$conexion=$this->conexion;
		
		$conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		
		$conexion->query("INSERT INTO resumen (concepto, clase, valor, tipo, fecha, usu, estado) 
		VALUES ('$concepto','$clase','$valor','$tipo','$fecha','$usu','s')");
	}
}

################################################################

class Consultar_Cajero{
	private $consulta;
	private $fetch;

	function __construct($codigo){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$this->consulta = $conexion->query("SELECT * FROM username, persona, cajero WHERE cajero.usu='$codigo' and username.usu=persona.doc and username.usu='$codigo'");
		$this->fetch = mysqli_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}


class Consultar_Cliente{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM username, persona, cliente WHERE cliente.doc='$codigo' and username.usu=persona.doc and username.usu='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Cliente1{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM persona, cliente WHERE cliente.doc='$codigo' and username.usu=persona.doc and username.usu='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Departamento{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM departamento WHERE id='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Usuario{
   
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
	   $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$this->consulta = $conexion->query("SELECT * FROM username, persona WHERE username.usu=persona.doc and username.usu='$codigo'");
		$this->fetch = mysqli_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Persona{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM persona WHERE doc='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Proveedor{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM proveedor WHERE id='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Deposito{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$this->consulta = $conexion->query("SELECT * FROM deposito WHERE id='$codigo'");
		$this->fetch = mysqli_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Producto{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$this->consulta = $conexion->query("SELECT * FROM producto WHERE codigo='$codigo' or nombre='$codigo'");
		$this->fetch = mysqli_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Producto1{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM producto1 WHERE codigo='$codigo' or nombre='$codigo'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_IVA{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$this->consulta = $conexion->query("SELECT * FROM iva WHERE id=$codigo");
		$this->fetch = mysqli_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Sistema{
	private $consulta;
	private $fetch;
	
	function __construct($codigo){
		$this->consulta = mysql_query("SELECT * FROM unidad WHERE id=$codigo");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}

class Consultar_Contenido{
	private $consulta;
	private $fetch;
	
	function __construct($codigo,$bodega){
		$this->consulta = mysql_query("SELECT * FROM contenido WHERE codigo='$codigo' and bodega='$bodega'");
		$this->fetch = mysql_fetch_array($this->consulta);
	}
	
	function consultar($campo){
		return $this->fetch[$campo];
	}
}
?>