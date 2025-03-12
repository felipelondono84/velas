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
	if(!empty($_GET['del'])){
		$codigo=($_GET['del']);
		$conexion->query("DELETE FROM producto WHERE codigo='$codigo'");
		header("Location: listado_producto.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Listo de Producto</title>
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
                    	<h1 align="center">Listado de Productos</h1>
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
                	<a class="btn" href="crear_producto.php" title="Ingresar Nuevo Producto"><i class="icon-plus"></i> <strong>Crear Producto</strong></a>
                </div>
                <br>
                <table class="table table-bordered">
                  <tr class="well">
                    <td><strong>Codigo</strong></td>
                    <td><strong>Nombre del Producto</strong></td>
                    <td></td>
                  </tr>
                  <?php
				  	if(!empty($_POST['buscar'])){
						$buscar=($_POST['buscar']);
						$pame=$conexion->query("SELECT * FROM producto WHERE nombre LIKE '%$buscar%' or codigo='$buscar' ORDER BY codigo  ASC");	
					}else{
						$pame=$conexion->query("SELECT * FROM producto ORDER BY codigo  ASC");		
					}		
					while($row=$pame->fetch_array()){
						$url=cadenas().encrypt($row['codigo'],'URLCODIGO');
						$cod=$row['codigo'];
				  ?>
                  <tr>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td>
                    	<center>
                            <a class="btn btn-mini" href="crear_producto1.php?codigo=<?php echo $url; ?>" title="Editar">
                                <i class="icon-edit"></i>
                            </a>
                            <a href="#m<?php echo $cod; ?>" title="Consultar Bodegas" role="button" class="btn btn-mini" data-toggle="modal">
                            	<i class="icon-list"></i>
                            </a>
							
                                        <a href="listado_producto.php?del=<?php echo $row['codigo']; ?>" class="btn btn-mini" title="Remover de la Lista de productos">
                                            <i class="icon-remove"></i>
                                        </a>
                                   
                        </center>
                        
                        <div id="m<?php echo $cod; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">
                                	<div class="row-fluid">
                                    	<div class="span4">
										<?php 
                                            if (file_exists("../../Producto/".$cod.".jpg")){
                                                echo '<img src="../../Producto/'.$cod.'.jpg" width="70" height="70" class="img-circle img-polaroid">';
                                            }else{
                                                echo '<img src="../../Producto/defecto.jpg" width="70" height="70">';
                                            }
                                        ?>
                                        </div>
                                    	<div class="span8">Estado en Depositos<br><?php echo $row['nombre']; ?></div>
                                    </div>
                                	
                                </h3>
                            </div>
                            <div class="modal-body">
                            	<table class="table table-bordered">
                                  <tr class="well">
                                    <td><strong>Deposito</strong></td>
                                    <td><strong>Informacion</strong></td>
                                    <td><center><strong>Cantidad</strong></center></td>
                                  </tr>
                                  <?php 
								  	$consulta=$conexion->query("SELECT * FROM contenido, producto 
									WHERE contenido.producto='$cod' and producto.codigo='$cod'");
									while($dato=$consulta->fetch_array()){
										$Odep=new Consultar_Deposito($dato['deposito']);
										$info=$Odep->consultar('tel').' '.$Odep->consultar('encargado');
								  ?>
                                  <tr>
                                    <td><?php echo $Odep->consultar('nombre'); ?></td>
                                    <td><?php echo $info; ?></td>
                                    <td><center><?php echo $dato['cant']; ?></center></td>
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
