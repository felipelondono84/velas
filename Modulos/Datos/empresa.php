<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'5')==FALSE){
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
    <title>Informacion Empresa</title>
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

    <?php include_once "../../menu/m_datos.php"; ?>
    <div align="center">
    <table width="60%">
      <tr>
        <td>
        	<?php 	
			
				if(!empty($_POST['empresa']) and !empty($_POST['nit'])){
					$empresa=limpiar($_POST['empresa']);		$nit=limpiar($_POST['nit']);
					$pais=limpiar($_POST['pais']);				$tel=limpiar($_POST['tel']);
					$ciudad=limpiar($_POST['ciudad']);			$fax=limpiar($_POST['fax']);
					$direccion=limpiar($_POST['direccion']);	$web=limpiar($_POST['web']);
					$correo=limpiar($_POST['correo']);			$fecha=date('Y-m-d');
					$moneda=limpiar($_POST['moneda']);			$puntos=limpiar($_POST['puntos']);
					mysql_query("UPDATE empresa SET empresa='$empresa',
													pais='$pais',
													ciudad='$ciudad',
													direccion='$direccion',
													correo='$correo',
													moneda='$moneda',
													nit='$nit',
													tel='$tel',
													fax='$fax',
													web='$web',
													fecha='$fecha',
													puntos='$puntos'												
												WHERE id=1");
					
					//subir la imagen del articulo
					$nameimagen = $_FILES['imagen']['name'];
					$tmpimagen = $_FILES['imagen']['tmp_name'];
					$extimagen = pathinfo($nameimagen);
					$ext = array("png","jpg");
					$urlnueva = "../../img/logo.jpg";			
					if(is_uploaded_file($tmpimagen)){
						if(array_search($extimagen['extension'],$ext)){
							copy($tmpimagen,$urlnueva);	
						}else{
							echo mensajes("ERROR AL SUBIR EL LOGO, jpg o png","rojo");		
						}
					}else{
						echo mensajes("ERROR AL SUBIR EL LOGO, jpg o png","rojo");	
					}
					
					echo mensajes('Dato de la Empresa Actualizado con Exito, Ctrl+F5 para Actualizar la imagen','verde');	
				}
				
				
				$pa=mysql_query("SELECT * FROM empresa WHERE id=1");									
				$row=mysql_fetch_array($pa);
			?>
        	<table class="table table-bordered">
              <tr class="well">
                <td>
               	    <div class="row-fluid">
	                    <div class="span4" align="center">
                        	<img src="../../img/logo.png" width="100" height="100">
                        </div>
    	                <div class="span8">
                    		<h1 align="center">Informacion Empresa</h1>
                        </div>
                    </div>
                	
                </td>
              </tr>
            </table>
     
        </td>
      </tr>
    </table>
    <table width="60%">
      <tr>
        <td>
       	    <div class="row-fluid">
	            <div class="span4">
                	<a href="index.php" class="text-info"><i class="icon-fast-backward"></i> <strong>Regresar</strong></a>
                </div>
    	        <div class="span8" align="right">
                	<a href="#myModal" role="button" class="btn" data-toggle="modal"><strong>Actualizar Informacion</strong></a>
                </div>
            </div><br>
            
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <form name="form2" enctype="multipart/form-data" method="post" action="">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Actualizar Informacion Empresa</h3>
                </div>
                <div class="modal-body"> 
					<div class="row-fluid">
                    	<div class="span6">
                        	<strong>Nombre Empresa</strong><br>
                            <input type="text" name="empresa" autocomplete="off" required value="<?php echo $row['empresa']; ?>"><br>
                            <strong>Pais</strong><br>
                            <input type="text" name="pais" autocomplete="off" required value="<?php echo $row['pais']; ?>"><br>
                            <strong>Ciudad</strong><br>
                            <input type="text" name="ciudad" autocomplete="off" required value="<?php echo $row['ciudad']; ?>"><br>
                            <strong>Direccion</strong><br>
                            <input type="text" name="direccion" autocomplete="off" required value="<?php echo $row['direccion']; ?>"><br>
                            <strong>Correo</strong><br>
                            <input type="email" name="correo" autocomplete="off" required value="<?php echo $row['correo']; ?>"><br>
                            <strong>Subir Logo</strong><br>
                            <input type="file" name="imagen" id="imagen">
                        </div>
                    	<div class="span6">
                        	<strong>Nit</strong><br>
                            <input type="text" name="nit" autocomplete="off" required value="<?php echo $row['nit']; ?>"><br>
                            <strong>Telefonos</strong><br>
                            <input type="text" name="tel" autocomplete="off" required value="<?php echo $row['tel']; ?>"><br>
                            <strong>Fax</strong><br>
                            <input type="text" name="fax" autocomplete="off" required value="<?php echo $row['fax']; ?>"><br>
                            <strong>Pagina WEB</strong><br>
                            <input type="text" name="web" autocomplete="off" required value="<?php echo $row['web']; ?>"><br>
                            <strong>Un Punto por Cada Valor de</strong><br>
                            <input type="number" name="puntos" autocomplete="off" required value="<?php echo $row['puntos']; ?>"><br>
                            <strong>Moneda</strong><br>
                            <input type="text" name="moneda" autocomplete="off" required value="<?php echo $row['moneda']; ?>"><br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <strong>Cerrar</strong></button>
                    <button class="btn btn-primary"><i class="icon-ok"></i> <strong>Actualizar</strong></button>
                </div>
                </form>
            </div>
        	<table class="table table-bordered">
              <tr>
                <td>
                    <div class="row-fluid">
                    	<div class="span6">
                        	<i class="icon-ok"></i> <strong>Nombre: </strong><?php echo $row['empresa']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Pais: </strong><?php echo $row['pais']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Ciudad: </strong><?php echo $row['ciudad']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Direccion: </strong><?php echo $row['direccion']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Correo: </strong><?php echo $row['correo']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Moneda: </strong><?php echo $row['moneda']; ?>
                        </div>
                    	<div class="span6">
                        	<i class="icon-ok"></i> <strong>Nit: </strong><?php echo $row['nit']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Telefono: </strong><?php echo $row['tel']; ?><br><br>
                            <i class="icon-ok"></i> <strong>FAX: </strong><?php echo $row['fax']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Pagina Web: </strong><?php echo $row['web']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Un Punto cada: </strong><?php echo $row['puntos']; ?><br><br>
                            <i class="icon-ok"></i> <strong>Ultima Actualizacion: </strong><?php echo fecha($row['fecha']); ?><br><br>
                    	</div>
                    </div>
                   
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
