<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'2')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	$usu=$_SESSION['cod_user'];
	$fecha=date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Listado de Compras</title>
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

    <?php include_once "../../menu/m_bodega.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<div class="row-fluid">
                            <div class="span6">
                            	<h2><img src="../../img/logo.png" width="100" height="100"> Consultar Compras</h2>
                            </div>
                            <div class="span6" align="center">
                            	<div class="row-fluid"><br>
                                	<form name="form2" action="" method="post">
                                        <div class="span6" align="center">                                        
                                            <strong>Fecha Inicio</strong><br>
                                            <input type="date" value="<?php echo date('Y-m-d'); ?>" name="ini" autocomplete="off" required><br>
                                        </div>
                                        <div class="span6" align="center">
                                            <strong>Fecha Final</strong><br>
                                            <input type="date" value="<?php echo date('Y-m-d'); ?>" name="fin" autocomplete="off" required><br>
                                        </div>
                                        <button type="submit" class="btn"><i class="icon-list"></i> <strong>Consultar Compras</strong></button>
                                    </form>
                            	</div>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php
					if(!empty($_POST['nproducto'])){
						$nprod=limpiar($_POST['nproducto']);		$nid=limpiar($_POST['nid']);
						$ndeposito=limpiar($_POST['ndeposito']);	$nvalor=limpiar($_POST['nvalor']);
						$ncant=limpiar($_POST['ncant']);			$nprov=limpiar($_POST['nprov']);
						$mensaje='Compra de x'.$ncant.' '.$nprod.' Con el Proveedor '.$nprov;
						
						mysql_query("UPDATE compras SET estado='s' WHERE id='$nid'");
						$oResumen=new Proceso_Resumen($mensaje,'COMPRA',$nvalor,'SALIDA',$fecha,$usu);
						$oResumen->crear();
						echo mensajes('Se ha Registrado el Pago de la Compra de x'.$ncant.' '.$nprod.' Con el Proveedor '.$nprov.' Con Exito','verde');
						
							$_POST['ini']=$_SESSION['ini'];
							$_POST['fin']=$_SESSION['fin'];
						
					}
					if(!empty($_POST['nproducto1'])){
						$nprod=limpiar($_POST['nproducto1']);		$nid=limpiar($_POST['nid1']);
						$ndeposito=limpiar($_POST['ndeposito1']);	$nvalor=limpiar($_POST['nvalor1']);
						$ncant=limpiar($_POST['ncant1']);			$nprov=limpiar($_POST['nprov1']);
						$mensaje='Compra de x'.$ncant.' '.$nprod.' Con el Proveedor '.$nprov;
						
						mysql_query("UPDATE compras SET estado='n' WHERE id='$nid'");
						$oResumen=new Proceso_Resumen($mensaje,'COMPRA',$nvalor,'SALIDA',$fecha,$usu);
						$oResumen->crear();
						echo mensajes('Se ha Registrado el Pago de la Compra de x'.$ncant.' '.$nprod.' Con el Proveedor '.$nprov.' Con Exito','verde');
						
							$_POST['ini']=$_SESSION['ini'];
							$_POST['fin']=$_SESSION['fin'];
						
					}
				?>
                <table class="table table-bordered">
				<div class="span6">
				<table class="table table-bordered">
                	<tr class="well">
                        <td><strong>Producto</strong></td>
                        <td><strong>Deposito</strong></td>
                        <td><strong>Proveedor</strong></td>
                        <td><strong><center>Cant.</center></strong></td>
                        <td><div align="right"><strong>Valor de Venta</strong></div></td>
                        <td><strong><center>Estado</center></strong></td>
                    </tr>
                    <?php
						if(!empty($_POST['ini']) and !empty($_POST['fin'])){						
							$fin=limpiar($_POST['fin']);	$ini=limpiar($_POST['fin']);
							$_SESSION['fin']=$fin;			$_SESSION['ini']=$ini;
							$pa=mysql_query("SELECT * FROM compras WHERE fecha BETWEEN '$ini' AND '$fin'");	
							while($row=mysql_fetch_array($pa)){
								$oProducto=new Consultar_Producto($row['producto']);
								$oDeposito=new Consultar_Deposito($row['deposito']);
								$oProveedor=new Consultar_Proveedor($row['prov']);
								$oPersona=new Consultar_Persona($row['usu']);
								
								$persona=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
								
								if($row['estado']=='s'){
									$class='btn btn-success';
									$mensaje='Pagado';
								}else{
									$class='btn btn-danger';
									$mensaje='Sin Pagar';
								}
								
					?>
                    <tr>
                        <td><?php echo $oProducto->consultar('nombre'); ?></td>
                        <td><?php echo $oDeposito->consultar('nombre'); ?></td>
                        <td><?php echo $oProveedor->consultar('nombre'); ?></td>
                        <td><center><?php echo $row['cant']; ?></center></td>
                        <td><div align="right">$ <?php echo formato($row['valor']); ?></div></td>
                        <td>
                        	<center>
                                <a href="#m<?php echo $row['id']; ?>" role="button" class="<?php echo $class; ?> btn-mini" data-toggle="modal">
                                    <strong><?php echo $mensaje; ?></strong>
                                </a>
                            </center>
                            
                            <div id="m<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            	<form name="form22" action="" method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel" align="center">Informacion de la Compra</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" value="<?php echo $row['id']; ?>" name="nid">
                                    <input type="hidden" value="<?php echo $oProducto->consultar('nombre'); ?>" name="nproducto">
                                    <input type="hidden" value="<?php echo $oDeposito->consultar('nombre'); ?>" name="ndeposito">
                                    <input type="hidden" value="<?php echo $row['valor']; ?>" name="nvalor">
                                    <input type="hidden" value="<?php echo $row['cant']; ?>" name="ncant">
									<input type="hidden" value="<?php echo $oProveedor->consultar('nombre'); ?>" name="nprov">
                                	<?php
										if($mensaje=='Pagado'){ 
											echo '<strong>Factura Compra Pagado Con Exito</strong><br>';
										}else{
											echo '<strong>¿Seguro que Desea Pagar esta Compra?</strong><br>';	
										}
									?>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <?php 
									if($mensaje<>'Pagado'){ 
										echo '<button type="submit" class="btn btn-primary"><strong>Pagar Compra</strong></button>'; 
									}else{
										echo '<button type="submit" class="btn btn-primary"><strong>No pago</strong></button>'; 
									}
									?>
                                </div>
                                </form>
								<form name="form22" action="" method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel" align="center">Informacion de la Compra</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" value="<?php echo $row['id']; ?>" name="nid1">
                                    <input type="hidden" value="<?php echo $oProducto->consultar('nombre'); ?>" name="nproducto1">
                                    <input type="hidden" value="<?php echo $oDeposito->consultar('nombre'); ?>" name="ndeposito1">
                                    <input type="hidden" value="<?php echo $row['valor']; ?>" name="nvalor1">
                                    <input type="hidden" value="<?php echo $row['cant']; ?>" name="ncant1">
									<input type="hidden" value="<?php echo $oProveedor->consultar('nombre'); ?>" name="nprov1">
                                	<?php
										if($mensaje=='Sin Pago'){ 
											echo '<strong>Factura Compra Pagado Con Exito</strong><br>';
										}else{
											echo '<strong>¿Seguro que Desea Pagar esta Compra?</strong><br>';	
										}
									?>
                                    <strong>Proveedor: </strong><?php echo $oProveedor->consultar('nombre'); ?><br>
                                    <strong>La Cantidad de: </strong>x<?php echo $row['cant'].' '.$oProducto->consultar('nombre'); ?><br>
                                    <strong>Deposito: </strong><?php echo $oDeposito->consultar('nombre'); ?><br>
                                    <h2 class="text-success">$ <?php echo formato($row['valor']); ?></h2><br>
                                    <strong>Responsable: </strong><?php echo $persona; ?><br>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <?php 
									if($mensaje<>'Sin Pago'){ 
										echo '<button type="submit" class="btn btn-primary"><strong>Pagar Compra</strong></button>'; 
									}else{
										echo '<button type="submit" class="btn btn-primary"><strong>No pago</strong></button>'; 
									}
									?>
                                </div>
                                </form>
                            </div>
                            
                        </td>
                    </tr>
                    <?php }} ?>
					</table>
					</div>
					<div class="span6">
					<table class="table table-bordered">
                	<tr class="well">
                        <td><strong>Producto</strong></td>
                        <td><strong>Deposito</strong></td>
                        <td><strong>Proveedor</strong></td>
                        <td><strong><center>Cant.</center></strong></td>
                        <td><div align="right"><strong>Valor de Venta</strong></div></td>
                        <td><strong><center>Estado</center></strong></td>
                    </tr>
                    <?php
						if(!empty($_POST['ini']) and !empty($_POST['fin'])){						
							$fin=limpiar($_POST['fin']);	$ini=limpiar($_POST['fin']);
							$_SESSION['fin']=$fin;			$_SESSION['ini']=$ini;
							$pa=mysql_query("SELECT * FROM compras WHERE fecha BETWEEN '$ini' AND '$fin'");	
							while($row=mysql_fetch_array($pa)){
								$oProducto=new Consultar_Producto($row['producto']);
								$oDeposito=new Consultar_Deposito($row['deposito']);
								$oProveedor=new Consultar_Proveedor($row['prov']);
								$oPersona=new Consultar_Persona($row['usu']);
								
								$persona=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
								
								if($row['estado']=='s'){
									$class='btn btn-success';
									$mensaje='Pagado';
								}else{
									$class='btn btn-danger';
									$mensaje='Sin Pagar';
								}
								
					?>
                    <tr>
                        <td><?php echo $oProducto->consultar('nombre'); ?></td>
                        <td><?php echo $oDeposito->consultar('nombre'); ?></td>
                        <td><?php echo $oProveedor->consultar('nombre'); ?></td>
                        <td><center><?php echo $row['cant']; ?></center></td>
                        <td><div align="right">$ <?php echo formato($row['valor']); ?></div></td>
                        <td>
                        	<center>
                                <a href="#m<?php echo $row['id']; ?>" role="button" class="<?php echo $class; ?> btn-mini" data-toggle="modal">
                                    <strong><?php echo $mensaje; ?></strong>
                                </a>
                            </center>
                            
                            <div id="m<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            	<form name="form22" action="" method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel" align="center">Informacion de la Compra</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" value="<?php echo $row['id']; ?>" name="nid">
                                    <input type="hidden" value="<?php echo $oProducto->consultar('nombre'); ?>" name="nproducto">
                                    <input type="hidden" value="<?php echo $oDeposito->consultar('nombre'); ?>" name="ndeposito">
                                    <input type="hidden" value="<?php echo $row['valor']; ?>" name="nvalor">
                                    <input type="hidden" value="<?php echo $row['cant']; ?>" name="ncant">
									<input type="hidden" value="<?php echo $oProveedor->consultar('nombre'); ?>" name="nprov">
                                	<?php
										if($mensaje=='Pagado'){ 
											echo '<strong>Factura Compra Pagado Con Exito</strong><br>';
										}else{
											echo '<strong>¿Seguro que Desea Pagar esta Compra?</strong><br>';	
										}
									?>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <?php 
									if($mensaje<>'Pagado'){ 
										echo '<button type="submit" class="btn btn-primary"><strong>Pagar Compra</strong></button>'; 
									}else{
										echo '<button type="submit" class="btn btn-primary"><strong>No pago</strong></button>'; 
									}
									?>
                                </div>
                                </form>
								<form name="form22" action="" method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel" align="center">Informacion de la Compra</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" value="<?php echo $row['id']; ?>" name="nid1">
                                    <input type="hidden" value="<?php echo $oProducto->consultar('nombre'); ?>" name="nproducto1">
                                    <input type="hidden" value="<?php echo $oDeposito->consultar('nombre'); ?>" name="ndeposito1">
                                    <input type="hidden" value="<?php echo $row['valor']; ?>" name="nvalor1">
                                    <input type="hidden" value="<?php echo $row['cant']; ?>" name="ncant1">
									<input type="hidden" value="<?php echo $oProveedor->consultar('nombre'); ?>" name="nprov1">
                                	<?php
										if($mensaje=='Sin Pago'){ 
											echo '<strong>Factura Compra Pagado Con Exito</strong><br>';
										}else{
											echo '<strong>¿Seguro que Desea Pagar esta Compra?</strong><br>';	
										}
									?>
                                    <strong>Proveedor: </strong><?php echo $oProveedor->consultar('nombre'); ?><br>
                                    <strong>La Cantidad de: </strong>x<?php echo $row['cant'].' '.$oProducto->consultar('nombre'); ?><br>
                                    <strong>Deposito: </strong><?php echo $oDeposito->consultar('nombre'); ?><br>
                                    <h2 class="text-success">$ <?php echo formato($row['valor']); ?></h2><br>
                                    <strong>Responsable: </strong><?php echo $persona; ?><br>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <?php 
									if($mensaje<>'Sin Pago'){ 
										echo '<button type="submit" class="btn btn-primary"><strong>Pagar Compra</strong></button>'; 
									}else{
										echo '<button type="submit" class="btn btn-primary"><strong>No pago</strong></button>'; 
									}
									?>
                                </div>
                                </form>
                            </div>
                            
                        </td>
                    </tr>
                    <?php }} ?>
					</table>
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
