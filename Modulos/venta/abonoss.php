<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'3')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	$usu=$_SESSION['cod_user'];
	$fecha=date('Y-m-d');
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
	
	if(!empty($_GET['id'])){
		$id_url=$_GET['id'];
		$id_doc=limpiar($_GET['id']);
		$id_doc=substr($id_doc,10);
		$id_doc=decrypt($id_doc,'URLCODIGO');
		
		######## NOS UBICAMOS EN QUE DEPOSITO O TIENDA SE HACE LA VENTA ##########
		$pa=mysql_query("SELECT * FROM Cajero WHERE usu='$usu'");				
		if($row=mysql_fetch_array($pa)){
			$id_bodega=$row['deposito'];
		}
		
		$pa=mysql_query("SELECT * FROM persona, username, cliente WHERE username.usu='$id_doc' and cliente.doc='$id_doc' and persona.doc='$id_doc'");				
		if($row=mysql_fetch_array($pa)){
			$nombre_cliente=$row['nom'].' '.$row['ape'];
			$puntos=$row['puntos'];
		}else{
			header('Location: ../Clientes/lista_cliente.php');	
		}
	}else{
		header('Location: ../Clientes/lista_cliente.php');	
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Abonos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="../../css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../../ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="../../ico/favicon.png">
  </head>
  <!-- FACEBOOK COMENTARIOS -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <!-- FIN CODIGO FACEBOOK -->
  <body>

    <?php include_once "../../menu/m_venta.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<?php
					########################### REGISTRAMOS EL ABONO ###############################################
					if(!empty($_POST['abono'])){
						$abono=limpiar($_POST['abono']);
						mysql_query("INSERT INTO abonos (cliente,valor,fecha,usu) VALUES ('$id_doc','$abono','$fecha','$usu')");
						
						$cons=mysql_query("SELECT MAX(id) as maximo FROM abonos WHERE cliente='$id_doc'");
						if($ma=mysql_fetch_array($cons)){
							$id_max=$ma['maximo'];
						}
						
						$concepto='Abono del Cliente ('.$id_doc.') '.$nombre_cliente.' Por Valor de $ '.formato($abono);
						mysql_query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) 
						VALUE ('$concepto','ABONO','$abono','ENTRADA','$fecha','$usu','s','$id_max','$id_bodega')");
						
						$new_puntos=$puntos+((int)$abono/$empresa_puntos);
						mysql_query("UPDATE cliente SET puntos='$new_puntos' WHERE doc='$id_doc'");
						
						echo mensajes('El Abono ha sido Registrado con Exito, Por Valor de $ '.formato($abono),'verde');
					}
					####################################################################################################
									
					$pa=mysql_query("SELECT * FROM persona, username, cliente 
					WHERE username.usu='$id_doc' and cliente.doc='$id_doc' and persona.doc='$id_doc'");				
					if($row=mysql_fetch_array($pa)){
						$puntos=$row['puntos'];
						
						$saldo=total_ocupado($id_doc)-total_abonado($id_doc);		
						$cupo=$row['cupo'];		$dif=$cupo-$saldo;
						
						$por_cupo=$saldo*100/$cupo;
					}
				?>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	
                        <div class="row-fluid">
                            <div class="span4" align="center">
                            	<?php
									if (file_exists("../../usuarios/".$id_doc.".jpg")){
										echo '<img src="../../usuarios/'.$id_doc.'.jpg" width="100" height="100" class="img-circle img-polaroid">';
									}else{
										echo '<img src="../../usuarios/defecto.png" width="100" height="100">';
									}l>
