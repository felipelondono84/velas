<?php
 	session_start();
	include_once "Modulos/php_conexion.php";
	include_once "Modulos/class_buscar.php";
	include_once "Modulos/funciones.php";
	$documento=limpiar($_SESSION['cod_user']);
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cambiar Clave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/google-code-prettify/prettify.css" rel="stylesheet">
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
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
    <script src="js/bootstrap-affix.js"></script>
    <script src="js/holder/holder.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src="js/application.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

</head>
<body>
<?php include_once "menu/m_principal.php"; ?><br>
<div align="center">
<table width="50%" border="0">
<tr>
  <td>
<table border="0" class="table table-bordered">
  <tr class="info">
    <td><center><strong>Cambiar Contraseña</strong></center></td>
  </tr>
  <tr>
    <td>
      <div align="center">
        <form name="form1" method="post" action="">
          <label><strong>Contraseña Antigua: </strong></label><input type="password" class="input-xlarge" name="contra" id="contra">
          <label><strong>Nueva Contraseña: </strong></label><input type="password" class="input-xlarge" name="c1" id="c1" required>
          <label><strong>Repita Nueva Contraseña: </strong></label><input type="password" class="input-xlarge" name="c2" id="c2" required><br><br>
          <input type="submit" name="button" id="button" class="btn btn-primary" value="Cambiar Contraseña">
          </form>
        <?php 
	if(!empty($_POST['c1']) and !empty($_POST['c2']) and !empty($_POST['contra'])){
		if($_POST['c1']==$_POST['c2']){
			$usuario=limpiar($_SESSION['cod_user']);
			$contra=encrypt($_POST['contra'],$usuario);
			$can=mysql_query("SELECT * FROM username WHERE usu='".$usuario."' and con='".$contra."'");
			if($dato=mysql_fetch_array($can)){
				$cnueva=encrypt($_POST['c2'],$usuario);
				$sql="Update username Set con='$cnueva' Where usu='$usuario'";
				mysql_query($sql);
				echo mensajes("Contraseña Actualizada con Exito","verde");
			}else{
				echo mensajes("La Contraseña Digitada no corresponde a la antigua","rojo");
			}
		}else{
			echo mensajes("Las dos Contraseña! Digitadas no soy iguales","verde");
		}
	}
	?>
        </div>
      </td>
    </tr>
</table>
</td></tr>
</table>
</div>
</body>
</html>