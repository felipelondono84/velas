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
		$abono=0;
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
    <script LANGUAGE="JavaScript">
		var cuenta=0;
		function enviado() { 
			if (cuenta == 0){
				cuenta++;
				return true;
			}else{
				alert("Formulario ya enviado");
				return false;
			}
		}
	</script>
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
					if(!empty($_GET['m'])){
						echo mensajes('El Abono ha sido Registrado con Exito, Por Valor de '.$s.' '.formato(limpiar($_GET['m'])),'verde');
					}
					if(!empty($_POST['abono'])){
						$abono=limpiar($_POST['abono']);	$factura=limpiar($_POST['factura']);
						mysql_query("INSERT INTO abonos (cliente,valor,fecha,usu,factura) VALUES ('$id_doc','$abono','$fecha','$usu','$factura')");
						
						$cons=mysql_query("SELECT MAX(id) as maximo FROM abonos WHERE cliente='$id_doc'");
						if($ma=mysql_fetch_array($cons)){
							$id_max=$ma['maximo'];
						}
						
						$concepto='Abono del Cliente ('.$id_doc.') '.$nombre_cliente.' Por Valor de '.$s.' '.formato($abono).' Correspondiente a la Factura '.$factura;
						mysql_query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) 
						VALUE ('$concepto','ABONO','$abono','ENTRADA','$fecha','$usu','s','$id_max','$id_bodega')");
						
						$new_puntos=$puntos+((int)$abono/$empresa_puntos);
						mysql_query("UPDATE cliente SET puntos='$new_puntos' WHERE doc='$id_doc'");
						
						echo '<meta http-equiv="refresh" content="0;url=abonos.php?id='.$id_url.'&factura='.$factura.'&m='.$abono.'">';
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
									}
								?>
                            </div>
                        	<div class="span4" align="center">
                            	<strong>Cliente</strong><br>
                                <h2><?php echo $nombre_cliente; ?></h2>
                                <strong> * * * Estado de Cuenta * * *</strong>
                            </div>
                            <div class="span4" align="center">
                            	<strong>Puntos Promocionales</strong><br>
                                <h2><?php echo formato($puntos); ?></h2>
                                <strong>Codigo: (<?php echo $id_doc; ?>)</strong>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                
                <table class="table table-bordered">
                	<tr>
                    	<td>
                       	<div class="row-fluid">
                            <div class="span6" align="center">
                            	<strong>Saldo Actual</strong>
								<pre style="width:80%; font-size:24px"><strong><?php echo $s.' '.formato($saldo); ?></strong></pre>
							</div>
                            <div class="span6" align="center">
                            	<strong>Limite de Credito</strong>
								<pre style="width:80%; font-size:24px"><strong><?php echo $s.' '.formato($cupo); ?></strong></pre>
                            </div>
                        </div>
                        <br>
                       	<center><strong>Cupo Utilizado: <?php echo number_format($por_cupo, 2, ',', ' '); ?> % 
                        | Cupo Disponible: <?php echo $s.' '.formato($dif); ?></strong></center>
                        <div class="progress progress-striped active">
	                        <div class="bar" style="width: <?php echo $por_cupo; ?>%;"></div>
                        </div>
                       	
                        
                        </td>
                    </tr>
                </table>
                
                
                <div class="row-fluid">
	                <div class="span4">
                    	<table class="table table-bordered">
                        	<tr class="well">
                            	<td><strong><center>Mis Facturas</center></strong></td>
                            </tr>
                            <tr>
                                <td>
                                	<center>
                                	<?php 
										$pa=mysql_query("SELECT * FROM factura WHERE cliente='$id_doc' ORDER BY id DESC");				
										while($row=mysql_fetch_array($pa)){
											
											echo '<a href="abonos.php?id='.$id_url.'&factura='.$row['factura'].'">
												<strong><i class="icon-file"></i> '.fecha($row['fecha']).' '.$row['factura'].'</strong>
												</a>
												<br>';
										}
									?>
                                    </center>
                                </td>
                        	</tr>
                    	</table>
                    </div>
    	            <div class="span8">
                    	<?php 
							$neto=0;
							if(!empty($_GET['factura'])){ 
								$id_factura=limpiar($_GET['factura']);
								echo '<center><strong>Factura No. '.$_GET['factura'].'</strong></center>'; 
						?>  
                    	<center>
                            <div class="btn-group">
                                <a href="#reg" role="button" class="btn" data-toggle="modal"><strong>Registrar Abonos</strong></a>
                                <a href="#con" role="button" class="btn" data-toggle="modal"><strong>Consultar mis Abonos</strong></a>
                            </div><br><br>
                            <?php 
								$total2=total_ocupado2($id_doc,$id_factura);
								$total_abono2=total_abonado2($id_doc,$id_factura);
								$dif2=$total2-$total_abono2;
								if($total2<>0){
									$por_cupo2=$total_abono2*100/$total2;
								}else{
									$por_cupo2=0;	
								}
							?>
                            <center><strong>
                            Total Pagado: <?php echo $s.' '.formato($total_abono2); ?> ( <?php echo number_format($por_cupo2, 2, ',', ' '); ?> % )
                            | Saldo Adeudado: <?php echo $s.' '.formato($dif2); ?>
                            </strong></center>
                            <div class="progress progress-striped active">
	                        	<div class="bar" style="width: <?php echo $por_cupo2; ?>%;"></div>
                        	</div>
                        </center>
                                         
                        <div id="con" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Consultar Abonos de Factura<br><?php echo $id_factura; ?></h3>
                            </div>
                            <div class="modal-body">
                            	<table class="table table-bordered">
                					<tr class="well">
                                    	<td><strong>Valor del Abono</strong></td>
                                        <td><strong><center>Fecha</center></strong></td>
                                    </tr>
                                    <?php 
										$pa=mysql_query("SELECT * FROM abonos WHERE factura='$id_factura' and cliente='$id_doc' ORDER BY id DESC LIMIT 0 , 20");				
										while($row=mysql_fetch_array($pa)){
									?>
                                    <tr>
                                    	<td><strong><?php echo $s.' '.formato($row['valor']); ?></strong></td>
                                        <td><strong><center><?php echo $s.' '.fecha($row['fecha']); ?></center></strong></td>
                                    </tr>
                                    <?php } ?>
                            	</table>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                            </div>
                        </div>
                        
                        <div id="reg" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        	<form name="reg" action="" method="post">
                            <div class="modal-header">
                            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            	<h3 id="myModalLabel" align="center">Registrar Abonos</h3>
                            </div>
                            <div class="modal-body" align="center">
                            	<strong>Hola! <?php echo $cajero_nombre; ?>,<br>
                                Vas a registrar un abono al Credito del Cliente<br><?php echo '('.$id_doc.') '.$nombre_cliente; ?><br>
                                Correspondiente a la Factura <?php echo $id_factura; ?></strong>
                                <br><br>
                                <strong>Valor</strong><br>
                                <div class="input-prepend input-append">
                                	<input type="hidden" name="factura" value="<?php echo $id_factura; ?>">
                                	<span class="add-on"><strong><?php echo $s; ?></strong></span>
                                	<input type="number" name="abono" class="input-large" min="1" max="<?php echo $dif2; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
	                            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
    	                        <button type="submit" class="btn btn-primary"><strong>Registrar Abono</strong></button>
                            </div>
                            </form>
                        </div>                    
                    	
                    	<table class="table table-bordered">
                        	<tr class="well">
                            	<td><strong>Producto</strong></td>
                                <td><strong><center>Codigo</center></strong></td>
                                <td><strong><center>Referencia</center></strong></td>
                                <td><strong><div align="right">Valor U.</div></strong></td>
                                <td><strong><center>Cant.</center></strong></td>
                                <td><strong><div align="right">Importe</div></strong></td>
                            </tr>
                            <?php 
								if(!empty($_GET['factura'])){
									$id_factura=limpiar($_GET['factura']);
									$pa=mysql_query("SELECT * FROM detalle WHERE factura='$id_factura'");				
									while($row=mysql_fetch_array($pa)){
										$importe=$row['valor']*$row['cant'];
										$neto=$neto+$importe;
							?>
                            <tr>
                            	<td><?php echo $row['producto']; ?></td>
                                <td><center><?php echo $row['codigo']; ?></center></td>
                                <td><center><?php echo $row['referencia']; ?></center></td>
                                <td><div align="right"><?php echo $s.' '.formato($row['valor']); ?></div></td>
                                <td><center><?php echo $row['cant']; ?></center></td>
                                <td><div align="right"><?php echo $s.' '.formato($importe); ?></div></td>
                            </tr>
                            <?php }} ?>
                        </table>
                        <pre style="font-size:24px"><div align="right"><strong>Total Valor Factura <?php echo $s.' '.formato($neto); ?></strong></div></pre><?php } ?>
                    </div>
                </div>
            </td>
          </tr>
        </table>
    </div>
    <!-- Le javascript ../../js/jquery.js
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../js/jquery.js"></script>
    <script src="../../js/bootstrap-transition.js"></script>
    <script src="../../js/bootstrap-alert.js"></script>
    <script src="../../js/bootstrap-modal.js"></script>
    <script src="../../js/bootstrap-dropdown.js"></script>
    <script src="../../js/bootstrap-scrollspy.js"></script>
    <script src="../../js/bootstrap-tab.js"></script>
    <script src="../../js/bootstrap-tooltip.js"></script>
    <script src="../../js/bootstrap-popover.js"></script>
    <script src="../../js/bootstrap-button.js"></script>
    <script src="../../js/bootstrap-collapse.js"></script>
    <script src="../../js/bootstrap-carousel.js"></script>
    <script src="../../js/bootstrap-typeahead.js"></script>

  </body>
</html>
