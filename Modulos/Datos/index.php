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
    <title>Administrar Informacion</title>
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
        <table width="90%">
          <tr>
            <td>
                <div class="row-fluid">
                    <div class="span4" align="center">
                    	<table class="table-bordered" width="100%">
                        	<tr>
                            	<td>
                                	<center>
                                        <h2> Administrar<br>IVA </h2>
                                        <img src="../../img/logo.png"><br><br>
                                        <a class="btn btn-large" href="iva.php"><strong>Entrar</strong></a><br><br>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="span4" align="center">
                        <table class="table-bordered" width="100%">
                        	<tr>
                            	<td>
                                	<center>
                                        <h2> Administrar Datos Empresa </h2>
                                        <img src="../../img/logo.png"><br><br>
                                        <a class="btn btn-large" href="empresa.php"><strong>Entrar</strong></a><br><br>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="span4" align="center">
                    	<table class="table-bordered" width="100%">
                        	<tr>
                            	<td>
                                	<center>
                                        <h2> Administrar Departamentos </h2>
                                        <img src="../../img/logo.png"><br><br>
                                        <a class="btn btn-large" href="departamento.php"><strong>Entrar</strong></a><br><br>
                                    </center>
                                </td>
                            </tr>
                        </table>
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
