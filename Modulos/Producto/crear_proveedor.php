<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'8')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	if(!empty($_GET['codigo'])){
		$id_codigo=limpiar($_GET['codigo']);
		$id_codigo=substr($id_codigo,10);
		$id_codigo=decrypt($id_codigo,'URLCODIGO');
		
		$pa=mysql_query("SELECT * FROM producto WHERE codigo='$id_codigo'");				
		if($row=mysql_fetch_array($pa)){
			$existe=TRUE;
			$nombre_producto=$row['nombre'];
		}else{
			$existe=FALSE;	
		}
	}else{
		$existe=FALSE;	
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Proveedores</title>
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
                    	<h2 align="center">Proveedores</h2>
                        <?php 
							if($existe==TRUE){ 
								$url1=cadenas().encrypt($id_codigo,'URLCODIGO');
						?>
                        	<h2 align="center">[<?php echo $nombre_producto; ?>]</h2>
                        	<center>
                                <div class="btn-group">
                                    <a href="crear_producto.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Producto ] </strong></a>
                                    <a href="inventario.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Inventario ] </strong></a>
                                    <a href="crear_proveedor.php?codigo=<?php echo $url1; ?>" class="btn btn-primary"><strong> [ Proveedor ] </strong></a>
                                </div>
                            </center>
						<?php }	?>
                    </td>
                  </tr>
                </table>
                <div align="right">
                    <a href="#existe" role="button" class="btn" data-toggle="modal"><strong>Proveedor Existente</strong></a>
                    <a href="#nuevo" role="button" class="btn" data-toggle="modal"><strong>Nuevo Proveedor</strong></a>
                </div>
                
                <div id="nuevo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<form name="form2" method="post" action="">
                    <div class="modal-header">
    	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                    <h3 id="myModalLabel">Crear Proveedor</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                            <div class="span6">
                            	<strong>Nombre del Proveedor</strong><br>
                                <input type="text" name="nombre" autocomplete="off" required><br>
                                <strong>Direccion</strong><br>
                                <input type="text" name="dir" autocomplete="off" required><br>
                                <strong>Telefonos</strong><br>
                                <input type="text" name="tel" autocomplete="off" required><br>
                            </div>
                            <div class="span6">
                            	<strong>Numero FAX</strong><br>
                                <input type="text" name="fax" autocomplete="off" required><br>
                                <strong>Nota</strong><br>
                                <input type="text" name="nota" autocomplete="off" required><br>
                                <strong>Contacto</strong><br>
                                <input type="text" name="contacto" autocomplete="off" required><br>
                            </div>
       					</div>
                    </div>
                	<div class="modal-footer">
            		    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
    		            <button class="btn btn-primary"><strong>Registrar Proveedor</strong></button>
	                </div>
                    </form>
                </div>
                
                <div id="existe" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<form name="form1" method="post" action="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Asignar Proveedor Existente</h3>
                    </div>
                    <div class="modal-body" align="center">
                    	<?php 
							$pame=mysql_query("SELECT * FROM proveedor");				
							if(!$name=mysql_fetch_array($pame)){
								echo mensajes('No hay Proveedores Registrados','rojo');									
							}else{
						?>
                    	<strong>PROVEEDOR</strong><BR>
                        <select name="proveedor_e">
							<?php							
                                $pa=mysql_query("SELECT * FROM proveedor");				
                                while($row=mysql_fetch_array($pa)){
                                    echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
                                }			
                            ?>
                        </select>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                        <button type="submit" class="btn btn-primary"><strong>Asignar Proveedor</strong></button>
                    </div>
                    </form>
                </div>
                
                <br>
                
                <?php 
					if(!empty($_POST['proveedor_e'])){
						$proveedor_e=limpiar($_POST['proveedor_e']);
						$oPro=new Consultar_Proveedor($proveedor_e);
						$nombre_prov=$oPro->consultar('nombre');
						
						$pa=mysql_query("SELECT * FROM pro_prov WHERE producto='$id_codigo' and proveedor='$proveedor_e'");				
                        if($row=mysql_fetch_array($pa)){
							echo mensajes('Este Proveedor "'.$nombre_prov.'" ya se Encuentra Relacionado con el Producto "'.$nombre_producto.'"','rojo');
						}else{
							mysql_query("INSERT INTO pro_prov (producto, proveedor) VALUES ('$id_codigo','$proveedor_e')");
							echo mensajes('El Producto "'.$nombre_producto.'" se le ha Asignado el Proveedor "'.$nombre_prov.'" con Exito','verde');
						}
					}
					
					if(!empty($_POST['nombre']) and !empty($_POST['dir'])){
						$nombre=limpiar($_POST['nombre']);		$dir=limpiar($_POST['dir']);
						$tel=limpiar($_POST['tel']);			$fax=limpiar($_POST['fax']);
						$nota=limpiar($_POST['nota']);			$contacto=limpiar($_POST['contacto']);
						
						
						mysql_query("INSERT INTO proveedor (nombre,dir,tel,fax,contacto,nota) 
						VALUES ('$nombre','$dir','$tel','$fax','$contacto','$nota')");
						
						$pa=mysql_query("SELECT MAX(id)as maxid FROM proveedor");				
                        if($row=mysql_fetch_array($pa)){
							$max_prov=$row['maxid'];
						}
						
						mysql_query("INSERT INTO pro_prov (producto, proveedor) VALUES ('$id_codigo','$max_prov')");
						
						echo mensajes('El Nuevo Proveedor "'.$nombre.'" Ha sido Registrado y Asociado con el Producto "'.$nombre_producto.'"','verde');
					}
				?>
                
                <table class="table table-bordered table table-hover">
                  <tr class="well">
                    <td><strong>Nombre Proveedor</strong></td>
                    <td><strong>Direccion</strong></td>
                    <td><strong>Telefonos</strong></td>
                    <td><strong>FAX</strong></td>
                    <td><strong>Contacto</strong></td>
                  </tr>
                  <?php
					if($existe==TRUE){				  				  	
						$pa=mysql_query("SELECT * FROM pro_prov WHERE producto='$id_codigo'");				
						while($row=mysql_fetch_array($pa)){
							$oProveedor=new Consultar_Proveedor($row['proveedor']);
				  ?>
                  <tr>
                  	<td><?php echo $oProveedor->consultar('nombre'); ?></td>
                    <td><?php echo $oProveedor->consultar('dir'); ?></td>
                    <td><?php echo $oProveedor->consultar('tel'); ?></td>
                    <td><?php echo $oProveedor->consultar('fax'); ?></td>
                    <td><?php echo $oProveedor->consultar('contacto'); ?></td>
                  </tr>
                  <?php }}else{ 
					  $pa=mysql_query("SELECT * FROM proveedor");				
						while($row=mysql_fetch_array($pa)){
				  ?>
                  <tr>
                  	<td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['dir']; ?></td>
                    <td><?php echo $row['tel']; ?></td>
                    <td><?php echo $row['fax']; ?></td>
                    <td><?php echo $row['contacto']; ?></td>
                  </tr>
                  <?php }} ?>
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
