<?php 
    include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
    $sessionTime = 365 * 24 * 60 * 60; // 1 año de duración
    session_set_cookie_params($sessionTime);
    session_start();
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'3')==FALSE){
			header('Location: ../../error.php');
		}
	
	}
	$usu=$_SESSION['cod_user'];	
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
	
	$pa=$conexion->query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=$pa->fetch_array()){
		$id_bodega=$row['deposito'];
		$oDeposito=new Consultar_Deposito($id_bodega);
		$nombre_deposito=$oDeposito->consultar('nombre');
	}
	
	if(!empty($_GET['del'])){
		$id=($_GET['del']);
		$conexion->query("DELETE FROM caja_tmp WHERE id='$id'");
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Ventas</title>
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
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                   	    <div class="row-fluid">
	                        <div class="span6">
                            	<form name="form2" action="" method="post">
                                    <strong>Codigo o Nombre del Producto</strong><br>
                                    <input type="text" autofocus list="browsers" name="buscar" autocomplete="off" class="input-xxlarge" required>
                                    <datalist id="browsers">
                                        <?php
                                            $pa=$conexion->query("SELECT producto.nombre FROM contenido, producto 
                                            WHERE producto.codigo=contenido.producto and contenido.deposito='1'");				
                                            while($row=$pa->fetch_array()){
                                                echo '<option value="'.$row['nombre'].'">';
                                            }
                                        ?> 
                                    </datalist>
                                </form>
                            </div>
    	                    <div class="span6">
                            	<div class="row-fluid">
			                        <div class="span6">
                                    	<i class="icon-ok"></i> <strong>Cajero: </strong> <?php echo $cajero_nombre; ?><br>
                                        <i class="icon-ok"></i> <strong>Deposito: </strong> <?php echo $nombre_deposito; ?><br>
                                        <i class="icon-ok"></i> <strong>Fecha: </strong> <?php echo fecha(date('Y-m-d')); ?>
                                    </div>
                                    <div class="span6" align="right">
                                    	<?php
											if (file_exists("../../usuarios/".@$_SESSION['cod_user'].".jpg")){
												echo '<img src="../../usuarios/'.@$_SESSION['cod_user'].'.jpg" width="50" height="50" class="img-polaroid img-polaroid">';
											}else{
												echo '<img src="../../usuarios/defecto.png" width="80" height="80">';
											}
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php
					if(!empty($_POST['new_cant'])){
						$new_cant=($_POST['new_cant']);
						$ncodigo=($_POST['ncodigo']);
						$conexion->query("UPDATE caja_tmp SET cant='$new_cant' WHERE producto='$ncodigo' and usu='$usu'");
					}
					
					if(!empty($_POST['ncodigo_ref'])){
						$referencia=($_POST['referencia']);
						$ref_ant=($_POST['ref_ant']);
						$ncodigo=($_POST['ncodigo_ref']);
						
						if($referencia==''){
							$conexion->query("UPDATE caja_tmp SET ref='' WHERE producto='$ncodigo' and usu='$usu' and ref='$ref_ant'");
						}else{
							$pa=$conexion->query("SELECT * FROM caja_tmp, detalle WHERE caja_tmp.ref='$referencia' or detalle.referencia='$referencia'");				
							if($row=$pa->fetch_array()){
								echo mensajes('El Numero de Referencia "'.$referencia.'" Esta siendo usada','rojo');
							}else{
								$conexion->query("UPDATE caja_tmp SET ref='$referencia' WHERE producto='$ncodigo' and usu='$usu' and ref='$ref_ant'");
							}
						}
						
					}	
				
                	if(!empty($_POST['buscar'])){
						$buscar=($_POST['buscar']);
						$poa=$conexion->query("SELECT producto.codigo FROM producto, contenido 
						WHERE producto.codigo=contenido.producto and contenido.deposito='1' and (producto.codigo='$buscar' or producto.nombre='$buscar') GROUP BY producto.nombre");	
						if($roow=$poa->fetch_array()){
							$codigo=$roow['codigo'];
							$pa=$conexion->query("SELECT * FROM caja_tmp WHERE producto='$codigo' and usu='$usu' and ref=''");	
							if($row=$pa->fetch_array()){
								$cant=$row['cant']+1;
								$conexion->query("UPDATE caja_tmp SET cant='$cant' WHERE producto='$codigo' and usu='$usu'");
							}else{
								$conexion->query("INSERT INTO caja_tmp (producto,ref, cant, usu) VALUES ('$codigo','','1','$usu')");	
							}
						}else{
							echo mensajes('El Producto que Busca no se encuentra Registrado en la Base de Datos','rojo');	
						}
					}
					
					if(!empty($_GET['mensaje'])){
						if($_GET['mensaje']=='2'){
							echo mensajes("El Cliente no se Encuentra Registrado en la Base de Datos","rojo");
						}elseif($_GET['mensaje']=='1'){
							echo mensajes("El Cliente No Tiene Cupo Suficiente para hacer esta compra","rojo");
						}
					}
                ?>
                <div class="row-fluid">
	                <div class="span8">
                    	<div style="width:100%; height:300px; overflow: auto;">
                        <table class="table table-bordered">
                            <tr class="well">
                            	<td><strong>Codigo</strong></td>
                                <td><strong>Referencia</strong></td>
                                <td><strong>Descripcion del Producto</strong></td>
                                <td><strong><center>Cant.</center></strong></td>
                                <td><strong><div align="right">Valor</div></strong></td>
                                <td><strong><div align="right">Total</div></strong></td>
                                <td></td>
                            </tr>
                            <?php 
								$neto=0;$item=0;
                                $pa=$conexion->query("SELECT * FROM caja_tmp, producto WHERE caja_tmp.usu='$usu' and caja_tmp.producto=producto.codigo");				
                                while($row=$pa->fetch_array()){
									$item=$item+$row['cant'];
									##### CONSULTAR IVA ###################
									
									
									
									
									$oIVA=new Consultar_IVA($row['ivaventa']);
									$iva=$oIVA->consultar('valor');
                                    ##### Calcular el valor e importe ######
                                    $defecto=strtolower($row['defecto']);
                                     $valor=$row['a_venta']*(($iva/100)+1);
                                    $importe=$row['cant']*$valor;
									$neto=$neto+$importe;
                                    ########################################
									if($row['ref']==NULL){
										$referencia='Sin Referencia';
									}else{
										$referencia=$row['ref'];
									}
                            ?>
                            <tr>
                            	<td><?php echo $row['codigo']; ?></td>
                                <td>
                                	<a href="#r<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
										<strong><?php echo $referencia; ?></strong>
                                    </a>
                                </td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td>
                                	<center>
                                    	<a href="#m<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
											<strong><?php echo $row['cant']; ?></strong>
                                        </a>
                                    </center>
                                </td>
                                <td><div align="right"><?php echo $s.' '.formato($valor); ?></div></td>
                                <td><div align="right"><?php echo $s.' '.formato($importe); ?></div></td>
                                <td>
                                    <center>
                                        <a href="index.php?del=<?php echo $row['id']; ?>" class="btn btn-mini" title="Remover de la Lista de Compra">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                            
                             <div id="r<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <form name="forme" action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Referencia del Producto<br>[<?php echo $row['nombre']; ?>]</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" name="ncodigo_ref" value="<?php echo $row['codigo']; ?>">
                               		<strong>Referencia del Producto</strong><br>
                                    <input type="text" name="referencia" value="<?php echo $row['ref']; ?>" class="input-xlarge" autocomplete="off">
                                    <input type="hidden" name="ref_ant" value="<?php echo $row['ref']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <button type="submit" class="btn btn-primary"><strong>Registrar Referencia</strong></button>
                                </div>
                                </form>
                            </div>
                            
                            
                            <div id="m<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <form name="forme" action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Actualizar Cantidad<br>[<?php echo $row['nombre']; ?>]</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" name="ncodigo" value="<?php echo $row['codigo']; ?>">
                               		<strong>Nueva Cantidad</strong><br>
                                    <input type="number" name="new_cant" min="1" value="<?php echo $row['cant'] ?>" autocomplete="off" required>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <button type="submit" class="btn btn-primary"><strong>Actualizar Cantidad</strong></button>
                                </div>
                                </form>
                            </div>
                            
                            <?php } ?>
                        </table>
                        </div>
                    </div>
    	            <div class="span4">
                    	<table class="table table-bordered">
                            <tr>
                                <td>
                                	<center><strong>Neto a Pagar</strong>
                                	<pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($neto); ?></h2></pre>
                                    <strong>Numero de Items: <br><span class="badge badge-success"><?php echo $item; ?></span></strong></center>
                                </td>
                            </tr>
                    	</table>
                        <?php if($neto<>0){ ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                	<div align="center">
                                        <a href="#contado" role="button" class="btn" data-toggle="modal">
                                            <i class="icon-shopping-cart"></i> <strong>Efectivo</strong>
                                        </a>
										<a href="#tcredito " role="button" class="btn" data-toggle="modal">
                                            <i class="icon-shopping-cart"></i> <strong>Tarjeta</strong>
                                        </a>
                                        <a href="#credito" role="button" class="btn" data-toggle="modal">
                                            <i class="icon-shopping-cart"></i> <strong>Separado</strong>
                                        </a><br><br>
                                	</div>
                                </td>
                            </tr>
                    	</table>
                        <?php } ?>
                    </div>
                </div>
                
            </td>
          </tr>
        </table>
    </div>
    
    <div id="credito" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<form name="contado" action="pro_credito.php" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel" align="center">Separado</h3>
        </div>
        <div class="modal-body" align="center">
        	<strong>Hola! <?php echo $cajero_nombre; ?></strong><br><br>
            <strong>Documento del Cliente</strong><br>
            <input type="number" name="cliente" autocomplete="off" class="input-xlarge">
            <input type="hidden" value="<?php echo $neto; ?>" name="neto">
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
            <button type="submit" class="btn btn-primary"><strong>Registrar Venta</strong></button>
        </div>
        </form>
    </div>
    
	<div id="tcredito" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<form name="tcredito" action="pro_tcredito.php" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel" align="center">Venta Tarjeta</h3>
        </div>
        <div class="modal-body" align="center">
        	<strong>Hola! <?php echo $cajero_nombre; ?></strong><br>
			<strong>Neto a Pagar</strong>
           	<pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($neto); ?></h2></pre>
            <strong>Dinero Recibido</strong><br>
            <div class="input-prepend input-append">
				<span class="add-on"><strong><?php echo $s; ?></strong></span>
            	<input type="number" name="valor_recibido" min="<?php echo $neto; ?>" autocomplete="off" required>
        	</div><br>
            <strong>Aplicar Descuento</strong><br>
            <input type="number" min="0" max="999999999" value="0" name="descuento" autocomplete="off"><br>
            <strong>Documento del Cliente</strong><br>
            <input type="number" name="puntos" autocomplete="off">
            <input type="hidden" value="<?php echo $neto; ?>" name="neto">
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
            <button type="submit" class="btn btn-primary"><strong>Registrar Venta</strong></button>
        </div>
        </form>
    </div>
	
    <div id="contado" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<form name="contado" action="pro_contado.php" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel" align="center">Venta Efectivo</h3>
        </div>
        <div class="modal-body" align="center">
        	<strong>Hola! <?php echo $cajero_nombre; ?></strong><br>
			<strong>Neto a Pagar</strong>
           	<pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($neto); ?></h2></pre>
            <strong>Dinero Recibido</strong><br>
            <div class="input-prepend input-append">
				<span class="add-on"><strong><?php echo $s; ?></strong></span>
            	<input type="float" name="valor_recibido" min="<?php echo $neto; ?>" autocomplete="off">
        	</div><br>
            <strong>Aplicar Descuento</strong><br>
            <input type="number" min="0" max="99999999999" value="0" name="descuento" autocomplete="off"><br>
            <strong>Documento del Cliente</strong><br>
            <input type="number" name="puntos" autocomplete="off">
            <input type="hidden" value="<?php echo $neto; ?>" name="neto">
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
            <button type="submit" class="btn btn-primary"><strong>Registrar Venta</strong></button>
        </div>
        </form>
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
