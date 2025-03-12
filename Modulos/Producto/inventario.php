<?php 
	session_save_path();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if(@$_SESSION['tipo_user']=='a' or @$_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'8')==FALSE){
			header('Location: ../../error.php');
		}
	}
	
	if(!empty($_GET['codigo'])){
		$id_codigo=($_GET['codigo']);
		$id_codigo=substr($id_codigo,10);
		$id_codigo=decrypt($id_codigo,'URLCODIGO');
		
		$pa=$conexion->query("SELECT * FROM producto WHERE codigo='$id_codigo'");				
		if($row=$pa->fetch_array()){
			$existe=TRUE;
			$nombre_producto=$row['nombre'];
		}else{
			$existe=FALSE;	
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Inventario</title>
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

    <?php include_once "../../menu/m_producto.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<h2 align="center">Inventario [<?php echo $nombre_producto; ?>]</h2>
                        <?php 
							if($existe==TRUE){ 
							$url1=cadenas().encrypt($id_codigo,'URLCODIGO');
						?>
                        	<center>
                                <div class="btn-group">
                                    <a href="crear_producto.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Producto ] </strong></a>
                                    <a href="inventario.php?codigo=<?php echo $url1; ?>" class="btn btn-primary"><strong> [ Inventario ] </strong></a>
                                    <a href="crear_proveedor.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Proveedor ] </strong></a>
                                </div>
                            </center>
						<?php }	?>
                    </td>
                  </tr>
                </table>
                <div align="right">
                	<a href="#nuevo" role="button" class="btn" data-toggle="modal"><strong>Ingresar Producto en Deposito</strong></a>
                </div>
                
                <div id="nuevo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<form name="form1" method="post" action="">
                    <div class="modal-header">
    	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                    <h3 id="myModalLabel">Ingresar</h3>
                    </div>
                    <div class="modal-body">
						<strong>Producto: </strong><?php echo $nombre_producto; ?><br><br>
                        <strong>Deposito</strong><br>
                      <select name="deposito" class="input-xlarge" >
                        	<?php
								$pa=$conexion->query("SELECT * FROM deposito WHERE estado='s'");				
								while($row=$pa->fetch_array()){
									echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
								}
							?>
                        </select><br>
                        <strong>Cantidad Actual</strong><br>
                      	<input type="number" name="cant" class="input-xlarge" value="1" min="1" autocomplete="off" required><br>
                        <strong>Cantidad Minima</strong><br>
                      	<input type="number" class="input-xlarge" name="minima" value="1" min="1" autocomplete="off" required><br>
                  </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <strong>Cerrar</strong></button>
                        <button class="btn btn-primary"><i class="icon-ok"></i> <strong>Ingresar a Deposito</strong></button>
                    </div>
                    </form>
                </div>
                <br>
                <table class="table table-bordered">
                  <tr>
                    <td>
                    	<center><pre><strong>Listado de Existencia por Deposito</strong></pre></center>
                        <?php 
							if(!empty($_POST['cant'])){
								$cant=($_POST['cant']);
								$minima=($_POST['minima']);	
								$deposito=($_POST['deposito']);
								$oND=new Consultar_Deposito($deposito);
								$nom_depo=$oND->consultar('nombre');
								if(empty($_POST['id'])){
									$pa=$conexion->query("SELECT * FROM contenido WHERE deposito='$deposito' and producto='$id_codigo'");				
									if($row=$pa->fetch_array()){
										echo mensajes('El Producto "'.$nombre_producto.'" Ya se Encuentra en el Deposito "'.$nom_depo.'"','rojo');
									}else{
										$oDeposito=new Proceso_Contenido('',$deposito,$cant,$minima,$id_codigo);
										$oDeposito->crear();
										echo mensajes('El Producto "'.$nombre_producto.'" 
										Ha sido Ingresado con Exito en el Deposito "'.$nom_depo.'"','verde');
									}
								}else{
									$id=($_POST['id']);
									$oDeposito=new Proceso_Contenido($id,$deposito,$cant,$minima,$id_codigo);
									$oDeposito->actualizar();
									echo mensajes('El Producto "'.$nombre_producto.'" en el Deposito "'.$nom_depo.'" Actualizado con Exito','verde');
								}
								
							}
						?>
                        <table class="table table-bordered table table-hover">
                          <tr class="well">
                            <td><center><strong>ID</strong></center></td>
                            <td><strong>Deposito</strong></td>
                            <td><center><strong>Cant. Actual</strong></center></td>
                            <td><center><strong>Cant. Minima</strong></center></td>
                            <td><div align="right"><strong>Valorado (Sin IVA)</strong></div></td>
                            <td></td>
                          </tr>
                          <?php
						  	$pa=$conexion->query("SELECT * FROM contenido WHERE producto='$id_codigo'");				
							while($row=$pa->fetch_array()){
								$oDepor=new Consultar_Deposito($row['deposito']);
								$oProducto=new Consultar_Producto($row['producto']);
								$valorado=$oProducto->consultar('costo_prov')*$row['cant'];
						  ?>
                          <tr>
                            <td><center><?php echo $row['id']; ?></center></td>
                            <td><?php echo $oDepor->consultar('nombre'); ?></td>
                            <td><center><?php echo $row['cant']; ?></center></td>
                            <td><center><?php echo $row['minima']; ?></center></td>
                            <td><div align="right"><?php echo $s.' '.formato($valorado); ?></div></td>
                            <td>
                            	<center>
                                    <a href="#act<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
                                        <i class="icon-refresh"></i>
                                    </a>
                                </center>
                            </td>
                          </tr>
                          
                        <div id="act<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        	<form name="form2" method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="deposito" value="<?php echo $row['deposito']; ?>">
                            <div class="modal-header">
    	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                            <h3 id="myModalLabel">Actualizar Existencia</h3>
                            </div>
                            <div class="modal-body">
								<strong>Producto: </strong><?php echo $nombre_producto; ?><br><br>
                        		<strong>Deposito: </strong><?php echo $oDepor->consultar('nombre'); ?><br><br>
                                <strong>Cantidad Actual</strong><br>
                                <input type="number" name="cant" class="input-xlarge" value="<?php echo $row['cant']; ?>" min="1" autocomplete="off" required><br>
                                <strong>Cantidad Minima</strong><br>
                                <input type="number" class="input-xlarge" name="minima" value="<?php echo $row['minima']; ?>" min="1" autocomplete="off" required><br>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                <button type="submit" class="btn btn-primary"><strong>Actualizar Existencia</strong></button>
                            </div>
                            </form>
                        </div>
                          
                          <?php } ?>
                        </table>

                        
                    </td>
                  </tr>
                </table>
                
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
