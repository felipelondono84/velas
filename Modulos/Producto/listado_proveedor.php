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
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Listado de Proveedores</title>
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
                   	 	<h1 align="center">listado de Proveedores</h1>
                        <center>
                      	<form name="form3" method="post" action="" class="form-search">
                        	<div class="input-prepend input-append">
								<span class="add-on"><i class="icon-search"></i></span>
                        		<input type="text" name="buscar" autocomplete="off" class="input-xxlarge search-query" 
                                autofocus placeholder="Buscar Proveedor por Nombre">
                            </div>
                            <button type="submit" class="btn" name="buton"><strong>Buscar</strong></button>
                    	</form>
                        </center>
                    </td>
                  </tr>
                </table>
                <div align="right">
	                <a href="#nuevo" role="button" class="btn" data-toggle="modal"><strong>Crear Nuevo Proveedor</strong></a>
                </div>
                
                <div id="nuevo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<form name="form1" method="post" action="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Crear Proveedor</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                            <div class="span6">
                                <strong>Nombre del Proveedor</strong><br>
                                <input type="text" name="nombre" autocomplete="off" required value=""><br>
                                <strong>Direccion</strong><br>
                                <input type="text" name="dir" autocomplete="off" required value=""><br>
                                <strong>Telefonos</strong><br>
                                <input type="text" name="tel" autocomplete="off" required value=""><br>
                            </div>
                            <div class="span6">
                                <strong>Numero FAX</strong><br>
                                <input type="text" name="fax" autocomplete="off" required value=""><br>
                                <strong>Nota</strong><br>
                                <input type="text" name="nota" autocomplete="off" required value=""><br>
                                <strong>Contacto</strong><br>
                                <input type="text" name="contacto" autocomplete="off" required value=""><br>
                            </div>
                    	</div>
                    </div>
                    <div class="modal-footer">
    	                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
        	            <button type="submit" class="btn btn-primary"><strong>Registrar Proveedor</strong></button>
                    </div>
                    </form>
                </div>
                
                <br>
                <?php 
					if(!empty($_POST['nombre'])){ 
						$nombre=limpiar($_POST['nombre']);		$dir=limpiar($_POST['dir']);
						$tel=limpiar($_POST['tel']);			$fax=limpiar($_POST['fax']);
						$nota=limpiar($_POST['nota']);			$contacto=limpiar($_POST['contacto']);
						
						if(empty($_POST['id'])){
							$oProveedor=new Proceso_Proveedor('',$nombre,$dir,$tel,$fax,$nota,$contacto);
							$oProveedor->crear();
							echo mensajes('Proveedor "'.$nombre.'" Creado con Exito','verde');
						}else{
							$id=limpiar($_POST['id']);
							$oProveedor=new Proceso_Proveedor($id,$nombre,$dir,$tel,$fax,$nota,$contacto);
							$oProveedor->actualizar();
							echo mensajes('Proveedor "'.$nombre.'" Actualizado con Exito','verde');
						}
					}
				?>
                <table class="table table-bordered">
                  <tr class="well">
                    <td><strong>Nombre Proveedor</strong></td>
                    <td><strong>Direccion</strong></td>
                    <td><strong>Telefono</strong></td>
                    <td><strong>FAX</strong></td>
                    <td><strong>Contacto</strong></td>
                    <td></td>
                  </tr>
				  <?php 
				  	if(!empty($_POST['buscar'])){
						$buscar=limpiar($_POST['buscar']);
						$pame=mysql_query("SELECT * FROM proveedor WHERE nombre LIKE '%$buscar%' ORDER BY nombre");	
					}else{
						$pame=mysql_query("SELECT * FROM proveedor ORDER BY nombre");		
					}		
					while($row=mysql_fetch_array($pame)){
				  ?>
                  <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['dir']; ?></td>
                    <td><?php echo $row['tel']; ?></td>
                    <td><?php echo $row['fax']; ?></td>
                    <td><?php echo $row['contacto'] ?></td>
                    <td>
                    	<center>
                            <a href="#act<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
                                <i class="icon-edit"></i>
                            </a>
                        </center>
                    </td>
                  </tr>
                  <div id="act<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<form name="form2" method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Crear Proveedor</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                            <div class="span6">
                                <strong>Nombre del Proveedor</strong><br>
                                <input type="text" name="nombre" autocomplete="off" required value="<?php echo $row['nombre']; ?>"><br>
                                <strong>Direccion</strong><br>
                                <input type="text" name="dir" autocomplete="off" required value="<?php echo $row['dir']; ?>"><br>
                                <strong>Telefonos</strong><br>
                                <input type="text" name="tel" autocomplete="off" required value="<?php echo $row['tel']; ?>"><br>
                            </div>
                            <div class="span6">
                                <strong>Numero FAX</strong><br>
                                <input type="text" name="fax" autocomplete="off" required value="<?php echo $row['fax']; ?>"><br>
                                <strong>Nota</strong><br>
                                <input type="text" name="nota" autocomplete="off" required value="<?php echo $row['nota']; ?>"><br>
                                <strong>Contacto</strong><br>
                                <input type="text" name="contacto" autocomplete="off" required value="<?php echo $row['contacto']; ?>"><br>
                            </div>
                    	</div>
                    </div>
                    <div class="modal-footer">
    	                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
        	            <button type="submit" class="btn btn-primary"><strong>Actualizar Proveedor</strong></button>
                    </div>
                    </form>
                </div>
                  <?php } ?>
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
