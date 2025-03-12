<?php 
	session_start();
	include_once "Modulos/php_conexion.php";
	include_once "Modulos/class_buscar.php";
	include_once "Modulos/funciones.php";
	$documento=limpiar($_SESSION['cod_user']);
	$paaw=mysql_query("SELECT * FROM persona WHERE doc='$documento'");				
	if($rwow=mysql_fetch_array($paaw)){
		$nombre=$rwow['nom'].' '.$rwow['ape'];
		$tel=$rwow['tel'];		$cel=$rwow['cel'];	$dir=$rwow['dir'];
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="ico/favicon.png">
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
	<?php include_once "menu/m_principal.php"; ?><br>
    <div align="center">
    <table width="80%">
      <tr>
        <td>
        	<div class="row-fluid">
                <div class="span4" align="center">
					<h3 align="center"><?php echo $nombre; ?></h3><br>
                	<div class="well">
						<?php
                            if (file_exists("usuarios/".$_SESSION['cod_user'].".jpg")){
                                echo '<img src="usuarios/'.$_SESSION['cod_user'].'.jpg" width="200" height="200" class="img-polaroid img-polaroid">';
                            }else{
                                echo '<img src="usuarios/defecto.png" width="200" height="200">';
                            }
                        ?>
                        <br><br>
                        <div class="btn-group btn-group-vertical">
	                        <a href="Principal.php" class="btn"><strong><i class="icon-home"></i> Regresar al Menu Principal</strong></a>
                            <a href="cambiar_contra.php" class="btn"><strong><i class="icon-refresh"></i> Cambiar Contraseña</strong></a>
                            <a href="php_cerrar.php" class="btn"><strong><i class="icon-off"></i> Salir</strong></a>
                        </div>
                    
                    </div>
                </div>
                <div class="span8">
                	<h3 align="center">INFORMACION PRINCIPAL</h3><br>
                	<strong><i class="icon-ok"></i> Documento: </strong> <?php echo $documento; ?><br><br>
                    <strong><i class="icon-ok"></i> Telefono: </strong> <?php echo $tel; ?><br><br>
					<strong><i class="icon-ok"></i> Celular: </strong> <?php echo $cel; ?><br><br>
                    <strong><i class="icon-ok"></i> Direccion: </strong> <?php echo $dir; ?><br><br>
                    
                </div>
            </div>
        </td>
      </tr>
    </table>
    </div>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

  </body>
</html>
