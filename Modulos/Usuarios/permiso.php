<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'7')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	if(!empty($_GET['id'])){
		$url_doc=limpiar($_GET['id']);
		$id_doc=limpiar($_GET['id']);
		$id_doc=substr($id_doc,10);
		$id_doc=decrypt($id_doc,'URLCODIGO');
		$pa=mysql_query("SELECT * FROM persona, username, cajero WHERE username.usu='$id_doc' and persona.doc='$id_doc'");				
		if($row=mysql_fetch_array($pa)){
			$usuario=$row['nom'].' '.$row['ape'];
			
			if($row['tipo']=='c'){
				$tipo_usu='CAJERO';
			}else{
				$tipo_usu='ADMINISTRADOR';	
			}
			$direccion=$row['dir'];
			$telefonos=$row['tel'].' - '.$row['cel'];
			
			$oDeposito=new Consultar_Deposito($row['deposito']);
			$nombre_deposito=$oDeposito->consultar('nombre');
		}
		if(!empty($_GET['pe'])){
			$id_pe=limpiar($_GET['pe']);
			$id_pe=substr($id_pe,10);
			$id_pe=decrypt($id_pe,'URLCODIGO');
			
			$pa=mysql_query("SELECT * FROM permisos WHERE id='$id_pe' and estado='s'");				
			if($row=mysql_fetch_array($pa)){
				mysql_query("UPDATE permisos SET estado='n' WHERE id='$id_pe'");
			}else{
				mysql_query("UPDATE permisos SET estado='s' WHERE id='$id_pe'");
			}
			header('Location: permiso.php?id='.$url_doc);
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Administrar Permisos</title>
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
                    <td><h2 align="center">Administrar Permisos <br>[<?php echo $usuario; ?>]</h2></td>
                  </tr>
                </table>
                <div class="row-fluid">
                	<div class="span6">
                        <table class="table table-bordered table table-hover">
                            <tr class="well">
                                <td><strong>Descripcion del Permiso</strong></td>
                                <td><strong><center>Estado</center></strong></td>
                            </tr>
                            <?php 
                                $consulta=mysql_query("SELECT * FROM permisos_tmp, permisos 
                                WHERE permisos.permiso=permisos_tmp.id and permisos.usu='$id_doc' ORDER BY nombre");
                                while($row=mysql_fetch_array($consulta)){
									$url='?id='.$url_doc.'&pe='.$row['id'];
									
									$url='?id='.$url_doc.'&pe='.cadenas().encrypt($row['id'],'URLCODIGO');
									
									if($row['estado']=='s'){
										$estado='<span class="label label-success">PERMITIDO</span>';
									}elseif($row['estado']=='n'){
										$estado=' <span class="label label-important">NO PERMITIDO</span> ';
									}
                            ?>
                            <tr>
                                <td><?php echo $row['nombre']; ?></td>
                                <td>
                                	<center>
                                    	<a href="permiso.php<?php echo $url; ?>" title="Cambiar Estado">
											<?php echo $estado; ?>
                                        </a>
                                    </center>
                            	</td>
                            </tr>
                            <?php } ?>
                        </table>
                	</div>
                	<div class="span6">
                    	<table class="table table-bordered">
                        	<tr>
                            	<td>
                                	<center>
                                	<?php
										if (file_exists("../../usuarios/".$id_doc.".jpg")){
											echo '<img src="../../usuarios/'.$id_doc.'.jpg" width="100" height="100" class="img-circle img-polaroid">';
										}else{
											echo '<img src="../../usuarios/defecto.png" width="100" height="100">';
										}
									?><br><br>
                                    <i class="icon-ok"></i> <strong>Tipo de Usuario: </strong><?php echo $tipo_usu; ?><br><br>
                                    <i class="icon-ok"></i> <strong>Pertenece al Deposito: </strong><?php echo $nombre_deposito; ?><br><br>
                                    <i class="icon-ok"></i> <strong>Direccion del Cajero: </strong><?php echo $direccion; ?><br><br>
                                    <i class="icon-ok"></i> <strong>Telefono / Celular: </strong><?php echo $telefonos; ?>
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
