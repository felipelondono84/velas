<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'4')==FALSE){
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
    <title>Listado de Usuarios</title>
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

    <?php include_once "../../menu/m_usuario.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<h1 align="center">
                    		<img src="../../img/logo.png" width="100" height="100">
                    		Consultar Usuario Registrados
                    	</h1>
                        <center>
                      	<form name="form3" method="post" action="" class="form-search">
                        	<div class="input-prepend input-append">
								<span class="add-on"><i class="icon-search"></i></span>
                        		<input type="text" name="buscar" autocomplete="on" class="input-xxlarge search-query" 
                                autofocus placeholder="Buscar Usuario por Documento o Nombres">
                            </div>
                            <button type="submit" class="btn" name="buton"><strong>Buscar</strong></button>
                    	</form>
                        </center>
                  	</td>
                  </tr>
                </table>
                
            	<table class="table table-bordered">
                  <tr class="well">
                    <td width="18%"><strong>Documento</strong></td>
                    <td width="52%"><strong>Nombre y Apellidos</strong></td>
                    <td width="20%"><strong><center>Tipo de Usuario</center></strong></td>
                    <td width="5%"><strong><center>Permisos</center></strong></td>
                  </tr>
                  	<?php 
				  	if(!empty($_POST['buscar'])){
						$buscar=limpiar($_POST['buscar']);
						$pame=$conexion->query("SELECT * FROM username WHERE nom LIKE '%$buscar%' ORDER BY usu");	
					}else{
						$pame=$conexion->query("SELECT * FROM username ORDER BY usu");		
					}		
					while($row=$pame->fetch_array()){
						$url=cadenas().encrypt($row['usu'],'URLCODIGO');
				  ?>
                   <tr>
                    <td><?php echo $row['usu']; ?></td>
                    <td>
                    	<a href="crear_usuario.php?doc=<?php echo $url; ?>" title="Editar Usuario">
							<?php echo $row['nom']; ?>
                        </a>
                    </td>
                    <td>
                    	<center><?php echo usuario($row['tipo']); ?></center>
                    </td>
                    <td>
                    	<center>
                            <a href="permiso.php?id=<?php echo $url; ?>" title="Editar Permisos" class="btn btn-mini">
                                <i class="icon-certificate"></i>
                            </a>
                        </center>
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