<?php
	function encrypt($string, $key) {
		$result = ''; $key=$key.'2013';
	   	for($i=0; $i<strlen($string); $i++) {
			  $char = substr($string, $i, 1);
			  $keychar = substr($key, ($i % strlen($key))-1, 1);
			  $char = chr(ord($char)+ord($keychar));
			  $result.=$char;
	   	}
	   	return base64_encode($result);
	}
	#####CONTRASEÑA DE-ENCRIPTAR
	function decrypt($string, $key) {
	   	$result = ''; $key=$key.'2013';
	   	$string = base64_decode($string);
	   	for($i=0; $i<strlen($string); $i++) {
			  $char = substr($string, $i, 1);
			  $keychar = substr($key, ($i % strlen($key))-1, 1);
			  $char = chr(ord($char)-ord($keychar));
			  $result.=$char;
	   	}
	   	return $result;
	}
	
	function anti_decimales($valor){
		$numero=strlen($valor);
		$resultado='';
		for($i=0; $i<=$numero; $i++) {
			$letra=substr($valor, $i, 1);  // abcd
			if($letra=='.'){
				break;
			}else{
				$resultado=$resultado.$letra;
			}
		}
		return $resultado;
	}
	
	function permiso($usu,$id){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$consulta=$conexion->query("SELECT * FROM permisos WHERE usu='$usu' and permiso='$id' and estado='s'");
		if($v=$consulta->fetch_array()){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function cadenas(){
		return 'YABCDFGJAH';	
	}
	################################################################################################
	function total_abonado($cliente){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$consulta=$conexion->query("SELECT SUM(valor) as neto FROM abonos WHERE cliente='$cliente'");	
		if($valor=$consulta->fetch_array()){
			return $valor['neto'];
		}else{
			return 0;
		}
	}
	
	function total_ocupado($cliente){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$consulta=$conexion->query("SELECT SUM(valor) as neto FROM factura WHERE clase='CREDITO' and cliente='$cliente'");	
		if($valor=$consulta->fetch_array()){
			return $valor['neto'];
		}else{
			return 0;
		}
	}
	
	function total_abonado2($cliente,$factura){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$consulta=$conexion->query("SELECT SUM(valor) as neto FROM abonos WHERE factura='$factura' and cliente='$cliente'");	
		if($valor=$consulta->fetch_array()){
			return $valor['neto'];
		}else{
			return 0;
		}
	}
	
	function total_ocupado2($cliente,$factura){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		$consulta=$conexion->query("SELECT SUM(valor) as neto FROM factura WHERE factura='$factura' and  clase='CREDITO' and cliente='$cliente'");	
		if($valor=$consulta->fetch_array()){
			return $valor['neto'];
		}else{
			return 0;
		}
	}
	################################################################################################
	function fecha($fecha){
		$meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
		$a=substr($fecha, 0, 4); 	
		$m=substr($fecha, 5, 2); 
		$d=substr($fecha, 8);
		return $d." / ".$meses[$m-1]." / ".$a;
	}
	
	function estado($estado){
		if($estado=='s'){
			return '<span class="label label-success">Activo</span>';
		}else{
			return '<span class="label label-important">No Activo</span>';
		}
	}
	
	function tipo($tipo){
		if($tipo=='ENTRADA'){
			return '<span class="label label-success">ENTRADA</span>';
		}else{
			return '<span class="label label-important">SALIDA</span>';
		}
	}
	
	function estado2($estado){
		if($estado=='s'){
			return '<span class="label label-success">ACTIVO</span>';
		}else{
			return '<span class="label label-important">ANULADA</span>';
		}
	}
	
	function usuario($tipo){
		if($tipo=='a'){
			return 'ADMINISTRADOR';
		}elseif($tipo=='c'){
			return 'CAJERO';
		}
	}
	
	function mensajes($mensaje,$tipo){
		if($tipo=='verde'){
			$tipo='alert alert-success';
		}elseif($tipo=='rojo'){
			$tipo='alert alert-error';
		}elseif($tipo=='azul'){
			$tipo='alert alert-info';
		}
		return '<div class="'.$tipo.'" align="center">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>'.$mensaje.'</strong>
            </div>';
	}
	
	function formato($valor){
	    $conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
		return number_format($valor,0, '', '.');
	}
$conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");
	$pa=$conexion->query("SELECT * FROM empresa WHERE id=1");
	
    if($row=$pa->fetch_array()){
		$empresa_nombre=$row['empresa'];
		$empresa_nit=$row['nit'];
		$empresa_dir=$row['direccion'];
		$empresa_tel=$row['tel'].'-'.$row['fax'];
		$empresa_pais=$row['pais'].' - '.$row['ciudad'];
		$empresa_puntos=$row['puntos'];
	}
?>