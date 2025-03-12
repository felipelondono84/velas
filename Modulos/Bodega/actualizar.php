<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'2')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	$usu=$_SESSION['cod_user'];
	if(!empty($_GET['bodega'])){
		if(!empty($_GET['idremove'])){
			$id=limpiar($_GET['idremove']);
			mysql_query("DELETE FROM act_tmp WHERE id='$id' and usu='$usu'");
			header('Location: actualizar.php?bodega='.$_GET['bodega']);
		}
		$id_bodega=limpiar($_GET['bodega']);
		$id_bodega=substr($id_bodega,10);
		$id_bodega=decrypt($id_bodega,'URLCODIGO');
		$oBodega=new Consultar_Deposito($id_bodega);
		$nombre_bodega=$oBodega->consultar('nombre');
	}else{
		$id_bodega='';	
		$nombre_bodega='';
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Act. Inventario</title>
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

    <?php include_once "../../menu/m_bodega.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<div class="row-fluid">
                            <div class="span6" align="center">
                        		<h2><img src="../../img/logo.png" width="100" height="100"> Actualizar Inventario</h2>    
                            </div>
                            <div class="span6">
                            	<?php if(empty($_GET['bodega'])){ ?>
                            	<form name="form3" method="get" action="" class="form-search">
                                    <div align="center"><br>
                                      <strong>Seleccione Deposito</strong><br>
                                      <select name="bodega" class="input-xlarge">
                                            <?php
                                                $pa=mysql_query("SELECT * FROM deposito");				
                                                while($row=mysql_fetch_array($pa)){
													$url=cadenas().encrypt($row['id'],'URLCODIGO');
													if($row['id']==$id_bodega){
	                                                    echo '<option value="'.$url.'" selected>'.$row['nombre'].'</option>';	
													}else{
														echo '<option value="'.$url.'">'.$row['nombre'].'</option>';		
													}
                                                }
                                            ?>
                                        </select> 
                                        <button type="submit" value="CFO395jjdnFFTYHDfkkdj4jj456ADTHHFffgY" 
                                        class="btn" name="CFO395jjdnFFTYHDfkkdj4jj456ADTHHFffgY">
                                        	<i class="icon-search"></i> <strong>Abrir Deposito</strong>
                                        </button>
                                    </div>
                                </form>
                                <?php }else{ ?>
                                <center>
                                    <a href="actualizar.php" class="btn"><strong>Cerrar Bodega | Nueva Busqueda</strong></a><br><br>
                                    <a href="#m" role="button" class="btn" data-toggle="modal"><strong>Ingresar Articulo</strong></a>
                                    
                                    <div id="m" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    	<form name="form1" method="post" action="" class="form-search">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel" align="center">Ingresar Articulo</h3>
                                        </div>
                                        <div class="modal-body" align="center">
                                        	<strong>Nombre o Codigo del Articulo</strong><br>
                                            <input type="text" name="articulo" autofocus list="browsers" class="input-xlarge" autocomplete="off">
                                            <datalist id="browsers">
                                                <?php
                                                    $pa=mysql_query("SELECT producto.nombre FROM contenido, producto 
                                                    WHERE producto.codigo=contenido.producto and contenido.deposito='$id_bodega'");				
                                                    while($row=mysql_fetch_array($pa)){
                                                        echo '<option value="'.$row['nombre'].'">';
                                                    }
                                                ?> 
                                            </datalist><br><br>
                                            <strong>Cantidad En Bodega</strong><br>
                                            <input type="number" name="cant" value="1" min="1" autocomplete="off" class="input-xlarge" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                            <button type="submit" class="btn btn-primary"><strong>Ingresar</strong></button>
                                        </div>
                                        </form>
                                    </div>
                                </center>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php 
					if(!empty($_POST['articulo'])){
						$articulo=limpiar($_POST['articulo']);
						$cantidad=limpiar($_POST['cant']);
						$pa=mysql_query("SELECT codigo FROM producto WHERE codigo='$articulo' or nombre='$articulo'");	
						if($row=mysql_fetch_array($pa)){
							$articulo=$row['codigo'];
							
							$pwa=mysql_query("SELECT * FROM act_tmp WHERE producto='$articulo' and usu='$usu'");	
							if($roww=mysql_fetch_array($pwa)){
								mysql_query("UPDATE act_tmp SET cantidad='$cantidad' WHERE producto='$articulo' and usu='$usu'");
							}else{
								mysql_query("INSERT INTO act_tmp (producto, cantidad, deposito, usu) 
								VALUES ('$articulo','$cantidad','$id_bodega','$usu')");
							}
						}else{
							echo mensajes('El Codigo o Nombre del Articulo "'.$articulo.'" No Existe en Este Deposito','rojo');	
						}
					}
				?>
                <h2 align="center"><?php echo $nombre_bodega; ?></h2>
                <?php 
					if(!empty($_GET['b']) and !empty($_GET['c'])){ 
						$b=limpiar($_GET['b']);
						$oB=new Consultar_Deposito($b);
						$NB=$oB->consultar("nombre");
						echo mensajes('Se ha Actualizado la Cantidad de x'.$_GET['c'].' Articulos en el Deposito "'.$NB.'"','verde');
					}
				?>
                <table class="table table-bordered">
                	<tr class="well">
                    	<td><strong>Articulo</strong></td>
                        <td><strong><center>Cant. en Sistema</center></strong></td>
                        <td><strong><center>Cant. en Bodega</center></strong></td>
                        <td><strong><center>Diferencia</center></strong></td>
                        <td></td>
                    </tr>
                    <?php
						$pa=mysql_query("SELECT act_tmp.id, producto.nombre, act_tmp.cantidad, contenido.cant FROM act_tmp, contenido, producto 
						  WHERE act_tmp.usu='$usu' and 
								act_tmp.deposito='$id_bodega' and 
								act_tmp.producto=contenido.producto and 
								act_tmp.producto=producto.codigo GROUP BY producto.nombre");				
                        while($row=mysql_fetch_array($pa)){
							$url_bodega=cadenas().encrypt($id_bodega,'URLCODIGO');
							$url2='?bodega='.$url_bodega.'&idremove='.$row['id'];
							
							$dif=$row['cantidad']-$row['cant'];
							if($dif>=0){
								$class='badge badge-success';
							}else{
								$class='badge badge-important';
							}
					?>
                    <tr>
                    	<td><?php echo $row['nombre']; ?></td>
                        <td><center><?php echo $row['cant']; ?></center></td>
                        <td><center><?php echo $row['cantidad']; ?></center></td>
                        <td><center><?php echo '<span class="'.$class.'">'.$dif.'</span>'; ?></center></td>
                        <td>
                        	<center>
                                <a href="actualizar.php<?php echo $url2; ?>" class="btn btn-mini" title="Descartar de la Lista">
                                    <i class="icon-remove"></i>
                                </a>
							</center>
                        </td>
                    </tr>
                    <?php } ?>
            	</table>
                <?php 
					$pa=mysql_query("SELECT * FROM act_tmp WHERE usu='$usu'");
					if($row=mysql_fetch_array($pa)){
				?>
                <div align="right">
                	<a href="proceso.php?bodega=<?php echo $id_bodega; ?>" class="btn"><i class="icon-ok"></i> <strong>Actualizar Inventario</strong></a>
                </div>                
                <?php } ?>
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
