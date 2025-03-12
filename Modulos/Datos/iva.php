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
	
	if(!empty($_GET['id'])){
		$id_iva=limpiar($_GET['id']);
		$id_iva=substr($id_iva,10);
		$id_iva=decrypt($id_iva,'URLIVACODIGO');
		
		$oIVA=new Consultar_IVA($id_iva);
		if($oIVA->consultar("nombre")==NULL){
			header('Location: iva.php');	
		}else{
			$titulo="Actualizar IVA";
			$boton="Actualizar Registro";
			$nombre_iva=$oIVA->consultar("nombre");
			$valor_iva=$oIVA->consultar("valor");
			$estado_iva=$oIVA->consultar("estado");
		}
	}else{
		
		$pame=mysql_query("SELECT MAX(id)as maximo FROM iva");			
		if($row=mysql_fetch_array($pame)){
			if($row['maximo']==NULL){
				$id_iva=1;
			}else{
				$id_iva=$row['maximo']+1;
			}
		}
		$titulo="Ingresar IVA Nuevo";	
		$boton="Guardar Registro";
		$nombre_iva='';
		$valor_iva='';
		$estado_iva='';
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Administrar IVA</title>
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
            	<a href="index.php"><strong><i class="icon-fast-backward"></i> Regresar</strong></a>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td><h1 align="center">Administrar IVA</h1></td>
                  </tr>
                </table>
                <?php 
					if(!empty($_POST['nombre'])){
						$id=limpiar($_POST['id']);
						$nombre=limpiar($_POST['nombre']);
						$valor=limpiar($_POST['valor']);
						$estado=limpiar($_POST['estado']);
						$overty=new Consultar_IVA($id);
						if($overty->consultar('nombre')==NULL){
							$oGuardar=new Proceso_IVA('',$nombre,$valor,$estado);
							$oGuardar->crear();
							echo mensajes('Nuevo IVA "'.$nombre.'" Registrado con Exito','verde');
						}else{
							$oActualizar=new Proceso_IVA($id,$nombre,$valor,$estado);
							$oActualizar->actualizar();
							echo mensajes('Nuevo IVA "'.$nombre.'" Actualizado con Exito','verde');
						}
					}
				?>
                <table class="table table-bordered">
                	<tr>
                    	<td>
                        	<div class="row-fluid">
                            	<div class="span6">
                                	<table class="table table-bordered table table-hover">
                                      <tr class="well">
                                        <td><strong><center>ID</center></strong></td>
                                        <td><strong>Descripcion</strong></td>
                                        <td><strong><center>Valor</center></strong></td>
                                        <td><strong><center>Estado</center></strong></td>
                                        <td><strong><center>Editar</center></strong></td>
                                      </tr>
                                      <?php
									  	$pame=mysql_query("SELECT * FROM iva ORDER BY nombre");			
										while($row=mysql_fetch_array($pame)){
											$url=cadenas().encrypt($row['id'],'URLIVACODIGO');
									  ?>
                                      <tr>
                                        <td><center><?php echo $row['id']; ?></center></td>
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><center><?php echo $row['valor'].' %'; ?></center></td>
                                        <td><center><?php echo estado($row['estado']); ?></center></td>
                                        <td>
                                        	<center>
                                                <a href="iva.php?id=<?php echo $url; ?>" class="btn btn-mini">
                                                    <i class="icon-edit"></i>
                                                </a>
                                            </center>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </table>
                                </div>
                            	<div class="span6">
                                	<table class="table table-bordered">
                                      <tr class="well">
                                      	<td><center><strong><?php echo $titulo; ?></strong></center></td>
                                      </tr>
                                      <tr>
                                      	<td>
                                        	<div align="center">
                                       	  	<form name="form1" method="post" action="">
                                            	<strong>Codigo</strong><br>
                                                <input type="text" name="id" value="<?php echo $id_iva; ?>" readonly autocomplete="off"><br>
                                                <strong>Descripcion</strong><br>
                                                <input type="text" name="nombre" value="<?php echo $nombre_iva; ?>" required autocomplete="off"><br>
                                                <strong>Valor del IVA</strong><br>
                                                <div class="input-append">
                                                    <input name="valor" value="0" type="number" min="0" max="100">
                                                    <span class="add-on"><strong>%</strong></span>
                                                </div><br>
                                                <strong>Estado</strong><br>
                                                <select name="estado">
                                                	<option value="s" <?php if($estado_iva=='s'){ echo 'selected'; } ?>>ACTIVO</option>
                                                    <option value="n" <?php if($estado_iva=='n'){ echo 'selected'; } ?>>NO ACTIVO</option>
                                                </select><br>
                                                <div class="form-actions">
                                                  <button type="submit" class="btn btn-primary"><strong><?php echo $boton; ?></strong></button>
                                                  <a href="iva.php" class="btn"><strong>Cancelar</strong></a>
                                                </div>
                                   	    	</form>
                                            </div>
                                        </td>
                                      </tr>
                                    </table>
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
