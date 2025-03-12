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
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Estado de Inventario</title>
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
                            <div class="span6">
                        		<h2 align="center"><img src="../../img/logo.png" width="100" height="100"> Estado de Inventario</h2>    
                            </div>
                            <div class="span6">
                            	<form name="form3" method="post" action="" class="form-search">
                                    <div align="center">
                                      <strong>Seleccione Deposito</strong><br>
                                      <select name="bodega" class="input-xlarge">
                                            <?php
                                                $pa=mysql_query("SELECT * FROM deposito");				
                                                while($row=mysql_fetch_array($pa)){
                                                    echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
                                                }
                                            ?>
                                        </select><br><br>
                                        <label class="radio">
                                        	<input type="radio" name="radio" value="baja" checked> <strong>Baja Existencia</strong>
                                        </label> | 
                                        <label class="radio">
                                        	<input type="radio" name="radio" value="todo"> <strong>Todos</strong>
                                        </label><br><br>
                                        <button type="submit" class="btn" name="buton"><i class="icon-search"></i> <strong>Consultar</strong></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                  	</td>
                  </tr>
                </table>
                <?php
					if(!empty($_POST['bodega'])){
						$bodega=limpiar($_POST['bodega']);
						$radio=limpiar($_POST['radio']);
						
						$oBodega=new Consultar_Deposito($bodega);
						$nombre_bodega=$oBodega->consultar('nombre');
						
						if($radio=='baja'){
							$opcion='Mostrar Baja Existencia';
						}elseif($radio=='todo'){
							$opcion='Mostrar Todos los Resultados';
						}
						
						echo '<center><strong>Deposito: '.$nombre_bodega.' | '.$opcion.'</strong></center>';
				?>
                <table class="table table-bordered">
                  <tr class="well">
				  
					
                  	<td><strong>Producto</strong></td>
                    <td><center><strong>Existencia</strong></center></td>
                    <td><div align="right"><strong>Prec. Unit Venta</strong></div></td>
                    <td><div align="right"><strong>Prec. Unit Costo</strong></div></td>
                    <td><div align="right"><strong>Total Venta</strong></div></td>
                    <td><div align="right"><strong>Total Costo</strong></div></td>
                    <td><div align="right"><strong>Ganancias</strong></div></td>
                  </tr>
                  <?php
			  	 	$pa=mysql_query("SELECT * FROM contenido WHERE deposito='1'");									
                    while($row=mysql_fetch_array($pa)){
						 $oProducto=new Consultar_Producto($row['producto']);
						 //$defecto=strtolower($oProducto->consultar('defecto'));
						 //$valorV=$oProducto->consultar($defecto.'_venta');
						 //$valorC=$oProducto->consultar($defecto.'_costo');
						 //$t_valorV=$row['cant']*$valorV;
						 //$t_valorC=$row['cant']*$valorC;
						 //$ganancia=$t_valorV-$t_valorC;
						 $id=$row['id'];
						if($row['cant']<=$row['minima']){
							$class='label label-important';
						}else{
							$class='label label-success';
						}
						 
						if($radio=='baja' and $row['cant']<=$row['minima']){
				  ?>
                  <tr>
					<td><?php echo $oProducto->consultar('nombre'); ?></td>
                  
                    <td><center><?php echo '<span class="'.$class.'">'.$row['cant'].'</span>'; ?></center></td>
                    <!--<td><div align="right">$ <?php echo formato($valorV); ?></div></td>
                    <td><div align="right">$ <?php echo formato($valorC); ?></div></td>
                    <td><div align="right">$ <?php echo formato($t_valorV); ?></div></td>
                    <td><div align="right">$ <?php echo formato($t_valorC); ?></div></td>
                    <td><div align="right">$ <?php echo formato($ganancia); ?></div></td>-->
					 
                  </tr>
                  <?php }elseif($radio=='todo'){ ?>
                   <tr>
                  	<td><?php echo $oProducto->consultar('nombre'); ?></td>
                    <td><center><?php echo '<span class="'.$class.'">'.$row['cant'].'</span>'; ?></center></td>
                    <td><div align="right">$ <?php echo formato($valorV); ?></div></td>
                    <td><div align="right">$ <?php echo formato($valorC); ?></div></td>
                    <td><div align="right">$ <?php echo formato($t_valorV); ?></div></td>
                    <td><div align="right">$ <?php echo formato($t_valorC); ?></div></td>
                    <td><div align="right">$ <?php echo formato($ganancia); ?></div></td>
                  </tr>
                  <?php }}?>
            	</table>
                <?php 
					}else{ 
						echo '<div class="alert alert-info" align="center"><strong>Seleccione Un deposito y una Opcion para Consultar</strong></div>'; 
					}
				?>
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
