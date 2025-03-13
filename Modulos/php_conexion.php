<?php

$DB_HOST = getenv("MYSQLHOST") ?: "localhost";
$DB_USER = getenv("MYSQLUSER") ?: "root";
$DB_PASSWORD = getenv("MYSQLPASSWORD") ?: "";
$DB_NAME = getenv("MYSQLDATABASE") ?: "database";
$DB_PORT = getenv("MYSQLPORT") ?: 3306; // Convertir en número

$conexion = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, intval($DB_PORT));

// Verificar si la conexión falló
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa a la base de datos.";
}



// $host = getenv('DB_HOST');
// $usuario = getenv('DB_USER');
// $password = getenv('DB_PASSWORD');
// $base_datos = getenv('DB_NAME');
// $puerto = getenv('DB_PORT') ?: 3306; // Usa el puerto por defecto si no está definido

// $conexion = mysqli_connect($host, $usuario, $password, $base_datos, $puerto);

// // Verifica si la conexión falló
// if (!$conexion) {
//     die("Error de conexión: " . mysqli_connect_error());
// }




// $host = getenv('DB_HOST');
// $user = getenv('DB_USER');
// $password = getenv('DB_PASSWORD');
// $database = getenv('DB_NAME');

// $conexion = mysqli_connect($host, $user, $password, $database);
	
	#$conexion = mysql_connect("localhost","root","");
	#mysql_select_db("prueba",$conexion);

//$conexion = mysqli_connect("localhost","lflsoftw_uservel","h9H[~*AlZOv-","lflsoftw_velas");


	// date_default_timezone_set("America/Bogota");
    // $conexion->query("SET NAMES utf8");
	// $conexion->query("SET CHARACTER_SET utf");
	// $s='$';
	
	function limpiar($tags){
		$tags = strip_tags($tags);
		$tags = stripslashes($tags);
		$tags = htmlentities($tags);
		return $tags;
	}
	

	
?>