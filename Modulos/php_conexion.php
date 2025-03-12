<?php
error_reporting(E_ALL ^ E_DEPRECATED);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

$conexion = mysqli_connect($host, $user, $password, $database);
	
	#$conexion = mysql_connect("localhost","root","");
	#mysql_select_db("prueba",$conexion);

//$conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");


	date_default_timezone_set("America/Bogota");
    $conexion->query("SET NAMES utf8");
	$conexion->query("SET CHARACTER_SET utf");
	$s='$';
	
	function limpiar($tags){
		$tags = strip_tags($tags);
		$tags = stripslashes($tags);
		$tags = htmlentities($tags);
		return $tags;
	}
	

	
?>