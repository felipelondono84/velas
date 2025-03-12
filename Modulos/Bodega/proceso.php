<?php
	session_start();
	
	include_once "../php_conexion.php";
	$usu=$_SESSION['cod_user'];
	if(!empty($_GET['bodega'])){
		$id_bodega=limpiar($_GET['bodega']);
		$n=0;
		$pa=mysql_query("SELECT * FROM act_tmp WHERE usu='$usu' and deposito='$id_bodega'");
		while($row=mysql_fetch_array($pa)){
			$producto=$row['producto'];
			$cantidad=$row['cantidad'];
			$n++;
			mysql_query("UPDATE contenido SET cant='$cantidad' WHERE producto='$producto' and deposito='$id_bodega'");
		}	
		mysql_query("DELETE FROM act_tmp WHERE usu='$usu' and deposito='$id_bodega'");
	}
	header('Location: actualizar.php?b='.$id_bodega.'&c='.$n);
?>