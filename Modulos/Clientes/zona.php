<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	$fecha=date('Y-m-d');
		
	if($_SESSION['tipo_user']=='cliente'){
		$id_doc=$_SESSION['cod_user'];
	
		$pa=mysql_query("SELECT * FROM persona, username, cliente WHERE username.usu='$id_doc' and cliente.doc='$id_doc' and persona.doc='$id_doc'");				
		if($row=mysql_fetch_array($pa)){
			$nombre_cliente=$row['nom'].' '.$row['ape'];
			$puntos=$row['puntos'];
		}else{
			header('Location: ../../error.php');	
		}
	}else{
		header('Location: ../../error.php');	
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?php echo $nombre_cliente; ?></title>
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

    <?php include_once "../../menu/m_zona.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<?php				
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
								<pre style="width:80%; font-size:24px"><strong>$ <?php echo formato($saldo); ?></strong></pre>
							</div>
                            <div class="span6" align="center">
                            	<strong>Limite de Credito</strong>
								<pre style="width:80%; font-size:24px"><strong>$ <?php echo formato($cupo); ?></strong></pre>
                            </div>
                        </div>
                        <br>
                       	<center><strong>Cupo Utilizado: <?php echo $por_cupo; ?> % | Cupo Disponible: $ <?php echo formato($dif); ?></strong></center>
                        <div class="progress progress-striped active">
	                        <div class="bar" style="width: <?php echo $por_cupo; ?>%;"></div>
                        </div>
                       	<center>
                            <div class="btn-group">
                                <a href="#con" role="button" class="btn" data-toggle="modal"><strong>Consultar mis Abonos</strong></a>
                            </div>
                        </center>
                        
                        
                        <div id="con" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">Modal header</h3>
                            </div>
                            <div class="modal-body">
                            	<table class="table table-bordered">
                					<tr class="well">
                                    	<td><strong>Valor del Abono</strong></td>
                                        <td><strong><center>Fecha</center></strong></td>
                                    </tr>
                                    <?php 
										$pa=mysql_query("SELECT * FROM abonos WHERE cliente='$id_doc' ORDER BY id DESC LIMIT 0 , 20");				
										while($row=mysql_fetch_array($pa)){
									?>
                                    <tr>
                                    	<td><strong>$ <?php echo formato($row['valor']); ?></strong></td>
                                        <td><strong><center><?php echo fecha($row['fecha']); ?></center></strong></td>
                                    </tr>
                                    <?php } ?>
                            	</table>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                            </div>
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
											
											echo '<a href="zona.php?factura='.$row['factura'].'">
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
                    	<?php $neto=0;if(!empty($_GET['factura'])){ echo '<center><strong>Factura No. '.$_GET['factura'].'</strong></center>'; }?>
                    	
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
                                <td><div align="right">$ <?php echo formato($row['valor']); ?></div></td>
                                <td><center><?php echo $row['cant']; ?></center></td>
                                <td><div align="right">$ <?php echo formato($importe); ?></div></td>
                            </tr>
                            <?php }} ?>
                        </table>
                        <pre style="font-size:24px"><div align="right"><strong>Total Valor Factura $ <?php echo formato($neto); ?></strong></div></pre>
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
