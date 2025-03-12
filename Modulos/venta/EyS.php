<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'3')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	$usu=$_SESSION['cod_user'];
	$fecha=date('Y-m-d');
	//$hora=date("H:i:s");    
	######## NOS UBICAMOS EN QUE DEPOSITO O TIENDA SE HACE LA VENTA ##########
	$pa=$conexion->query("SELECT * FROM cajero WHERE usu='$usu'");				
	while($row=$pa->fetch_array()){
		$id_bodega=$row['deposito'];
		$oDeposito=new Consultar_Deposito($id_bodega);
		$nombre_deposito=$oDeposito->consultar('nombre');
	}
	if(!empty($_GET['del'])){
		$id=($_GET['del']);
		$conexion->query("DELETE FROM resumen WHERE id='$id'");
		header("Location: EyS.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Entrada y Salida</title>
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

    <?php include_once "../../menu/m_venta.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<h2 align="center">Registro de Entrada | Salida | Base de Dinero</h2>
                        
                        
                        <div class="row-fluid">
                        	<div class="span6" align="center">
                            	<div class="row-fluid">
                                	<form name="formee" action="" method="post" class="form-search">
		                        	<div class="span6" align="center">
        	                    		<strong>Fecha Inicio: </strong><br>
            	                	    <input type="date" name="ini" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required><br><br>
                                        <label class="radio"><input type="radio" name="buscar" value="ENTRADA" checked> <strong>ENTRADAS</strong></label>
                                        <label class="radio"><input type="radio" name="buscar" value="SALIDA" checked> <strong>SALIDA</strong></label>
										<label class="radio"><input type="radio" name="buscar" value="BASE" checked> <strong>BASE</strong></label>
                                        <label class="radio"><input type="radio" name="buscar" value="TODOS" checked> <strong>TODOS</strong></label>
                                    </div>
                                    <div class="span6" align="center">
                                    	<strong>Fecha Final:</strong><br>
                    		            <input type="date" name="fin" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required><br><br>
                                        <button class="btn" type="submit"><strong><i class="icon-list"></i> Consultar</strong></button>
                                    </div>
                                    </form>
                            	</div>
                                
                            </div>
                        	<div class="span6" align="center">      
                            	<strong>Deposito: <?php echo $nombre_deposito; ?></strong><br>   <br>                   	
                                <a href="#nuevo" role="button" class="btn" data-toggle="modal">
                                	<strong>Ingresar Nueva Entrada | Salida o Base de Dinero</strong>
                                </a>
                                
                                <div id="nuevo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                	<form name="formee" action="" method="post">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h3 id="myModalLabel" align="center">Registro de Entrada / Salida / Base Registro de Entrada / Salida / Base</h3>
                                    </div>
                                    <div class="modal-body">
                                    	<strong>Concepto</strong><br>
                                        <input type="text" name="concepto" autocomplete="off" required class="input-xlarge"><br><br>
                                        <strong>Valor</strong><br>
                                        <div class="input-prepend input-append">
											<span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                        <input type="number" name="valor" autocomplete="off" required class="input-large" min="1">
                                            <span class="add-on"><strong><strong>.00</strong></strong></span>
                                        </div><br><br>
                                        <strong>Entrada / Salida / Base</strong><br>
                                        <select name="tipo" class="input-xlarge">
                                        	<option value="ENTRADA">ENTRADA</option>
                                            <option value="SALIDA">SALIDA</option>
											<option value="BASE">BASE</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                        <button type="submit" class="btn btn-primary"><strong>Registrar</strong></button>
                                    </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                        
                    </td>
                  </tr>
                </table>
                <?php 
					if(!empty($_POST['concepto'])){
						$concepto=($_POST['concepto']);
						$tipo=($_POST['tipo']);		$valor=($_POST['valor']);
						
						$conexion->query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) 
						VALUE ('$concepto','OTROS','$valor','$tipo','$fecha','$usu','s','X','$id_bodega')");
						
						echo mensajes("Se ha Registrado con Exito la <strong>".$tipo."</strong> Por Valor de 
						<strong>$ ".formato($valor)."</strong>",'verde');
					}
				?>
                
                <?php
					if(!empty($_POST['fin']) and !empty($_POST['fin'])){
						$fin=($_POST['fin']);	$ini=($_POST['ini']);
						$buscar=($_POST['buscar']);
				?>
                <center>
                <table width="95%" rules="all" border="1">
                  <tr>
                    <td><strong>Concepto</strong></td>
                    <td><strong><center>Entrada/Salida/Base</center></strong></td>
                    <td><strong><div align="right">Valor</div></strong></td>
                    <td><strong><center>Estado</center></strong></td>
					<td></td>
                  </tr>
                  <?php
				  	$neto=0;$entrada=0;$salida=0;$base=0;
				  	if($buscar=='TODOS'){
						$consulta=$conexion->query("SELECT * FROM resumen 
						WHERE clase='OTROS' and deposito='$id_bodega' and fecha BETWEEN '$ini' and '$fin'");
					}else{
						$consulta=$conexion->query("SELECT * FROM resumen 
						WHERE clase='OTROS' and deposito='$id_bodega' and tipo='$buscar' and fecha BETWEEN '$ini' and '$fin'");	
					}
                    while($row=$consulta->fetch_array()){
						if($row['tipo']=='ENTRADA'){
							$entrada=$entrada+$row['valor'];
						}elseif($row['tipo']=='SALIDA'){
							$salida=$salida+$row['valor'];
						}elseif($row['tipo']=='BASE'){
							$base=$base+$row['valor'];
						}
						
						$neto=(($entrada+$base)-$salida);
				  ?>
                  <tr>
                    <td><strong><?php echo $row['concepto']; ?></strong></td>
                    <td><strong><center><?php echo tipo($row['tipo']); ?></center></strong></td>
                    <td><strong><div align="right">$ <?php echo formato($row['valor']); ?></div></strong></td>
                    <td><strong><center><?php echo estado2($row['estado']); ?></center></strong></td>
					 <td><center><a href="EyS.php?del=<?php echo $row['id']; ?>" class="btn btn-mini" title="Remover de la Lista de productos">
                                            <i class="icon-remove"></i>
                                        </center></a></td>
                  </tr>
				  <?php } ?>
                  <tr>
                    <td colspan="2"><BR><strong><div align="right">Entrada:</div></strong></td>
                    <td><BR><div align="right"><strong>$<?php echo formato($entrada); ?></strong></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><strong><div align="right">Salida:</div></strong></td>
                    <td><div align="right"><strong>$<?php echo formato($salida); ?></strong></div></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td colspan="2"><strong><div align="right">Base:</div></strong></td>
                    <td><div align="right"><strong>$<?php echo formato($base); ?></strong></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><strong><div align="right">NETO:</div></strong></td>
                    <td><div align="right"><strong>$<?php echo formato($neto); ?></strong></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  
                </table>
                </center>
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
