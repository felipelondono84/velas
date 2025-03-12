<?php 
	//session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if(@$_SESSION['tipo_user']=='a' or @$_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'6')==FALSE){
			header('Location: ../../error.php');
		}
	}
	
	if(!empty($_POST['ini']) and !empty($_POST['fin'])){
		$inicio=($_POST['ini']);
		$final=($_POST['fin']);
		$id_deposito=($_POST['deposito']);
	}else{
		$id_deposito='';
		$inicio=date('Y-m-d');
		$final=date('Y-m-d');
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Cierre de Caja - Cajero</title>
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
  	<script>
		function imprimir(){
		  var objeto=document.getElementById('imprimeme');  //obtenemos el objeto a imprimir
		  var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
		  ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
		  ventana.document.close();  //cerramos el documento
		  ventana.print();  //imprimimos la ventana
		  ventana.close();  //cerramos la ventana
		}
	</script>
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

    <?php include_once "../../menu/m_reportes.php"; ?>
	<div align="center">
    	<table width="100%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                    	<center>
                    		<h2>Cierre de Caja Por Deposito</h2>
                            <div class="btn-group">
                                <a href="cierre_cajero.php" class="btn"><strong>Cierre de Caja por Cajeros/a</strong></a>
                                <a href="cierre_bodega.php" class="btn btn-primary"><strong>Cierre de Caja por Deposito</strong></a>
								 <a href="cierre_general.php" class="btn btn-primary"><strong>Cierre de Caja General</strong></a>
                            </div>
                        </center>
                    </td>
                  </tr>
                </table>
                
                <table class="table table-bordered">
                  <tr>
                    <td>
                    	<form name="form1" action="" method="post">
                            <div class="row-fluid">
                                <div class="span4" align="center">
                                    <strong>Fecha Inicio</strong><br>
                                    <input type="date" name="ini" value="<?php echo $inicio; ?>" autocomplete="off" required>
                                </div>
                                <div class="span4" align="center">
                                    <strong>Fecha Fin</strong><br>
                                    <input type="date" name="fin" value="<?php echo $final; ?>" autocomplete="off" required>
                                </div>
                                <div class="span4" align="center">
                                    <strong>Deposito</strong><br>
                                    <select name="deposito">
                                        <?php
                                            $consulta=$conexion->query("SELECT * FROM deposito");
                                            while($row=$consulta->fetch_array()){
												if($id_deposito==$row['id']){
													echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';
												}else{
													echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
												}
                                            }
                                        ?>
                                    </select>
                                </div>
                                <center><button type="submit" class="btn"><i class="icon-search"></i> <strong>Consultar</strong></button></center>
                            </div>
                            </form>
                            
                            
                            <button class="btn" onclick="imprimir();"><i class="icon-print"></i> <strong>Imprimir Reporte</strong></button>
                            
                            <center>
                            <div id="imprimeme">
                            <?php 
						if(!empty($_POST['deposito']) and !empty($_POST['ini']) and !empty($_POST['fin'])){
							$deposito=($_POST['deposito']);
							$oDeposito=new Consultar_Deposito($deposito);
							$nombre_deposito=$oDeposito->consultar('nombre');
							$ini=($_POST['ini']);
							$fin=($_POST['fin']);
							
							$tcredito=0;$entrada=0;$salida=0;$numero=0;$venta=0;$compra=0;$base=0;
							$consultar=$conexion->query("SELECT * FROM resumen WHERE deposito='$deposito' AND fecha BETWEEN '$ini' and '$fin' and estado='s'");
							while($row=$consultar->fetch_array()){
								$numero=$numero+1;	$factura=$row['very'];
								
								if($row['tipo']=='ENTRADA'){
									$entrada=$entrada+$row['valor'];
								}elseif($row['tipo']=='SALIDA'){
									$salida=$salida+$row['valor'];
								}elseif($row['tipo']=='T.CREDITO'){
									$tcredito=$tcredito+$row['valor'];
								}elseif($row['tipo']=='BASE'){
									$base=$row['valor'];
								}
								$cons=$conexion->query("SELECT * FROM detalle WHERE factura='$factura'");
								while($date=$cons->fetch_array()){
									$venta=$venta+($date['valor']*$date['cant']);
									$compra=$compra+($date['costo']*$date['cant']);
								}
							}
							?>
                            <strong><?php echo $empresa_nombre; ?></strong><br>
                            <strong><?php echo $empresa_nit; ?></strong><br><br>
                            <strong><center>Cierre de Caja del Deposito: 
							<?php echo $nombre_deposito.' | '.fecha($ini).' AL '.fecha($fin); ?></center></strong><br>
                            <table width="100%" rules="all" border="2">
                              <tr>
                                <td><strong><center>Total Entrada</center></strong></td>
                                <td><strong><center>Total Salida</center></strong></td>
								<td><strong><center>Total T.Credito</center></strong></td>
								<td><strong><center>Base</center></strong></td>
                                <td><strong><center>Total en Caja</center></strong></td>
                                <td><strong><center>Total Ganancias</center></strong></td>
                                <td><strong><center>No de Transacciones</center></strong></td>
                              </tr>
                              <tr>
                                <td><center>$ <?php echo formato($entrada+$tcredito); ?></center></td>
                                <td><center>$ <?php echo formato($salida); ?></center></td>
								<td><center>$ <?php echo formato($tcredito); ?></center></td>
								<td><center>$ <?php echo formato($base); ?></center></td>
                                <td><center>$ <?php echo formato(($entrada+$base)-$salida); ?></center></td>
                                <td><center>$ <?php echo formato($venta-$compra); ?></center></td>
                                <td><center><?php echo $numero; ?></center></td>
                              </tr>
                            </table><br><br>
                                              
                            <table width="90%" rules="all" border="2">
                                <tr>
                                	<td width="5%"><strong>Clase</strong></td>
                                	<td width="40%"><strong>Concepto</strong></td>
                                    <td width="10%"><strong><center>Entrada/Salida/T.C/Sepa.</center></strong></td>
                                    <td width="25%"><strong><div align="right">Valor</div></strong></td>
                                </tr>
                                <?php 
						$signo='';
						$consultar=$conexion->query("SELECT * FROM resumen WHERE deposito='$id_deposito' AND fecha BETWEEN '$ini' and '$fin' and estado='s'");
						while($row=$consultar->fetch_array()){									
							$oCajero=new Consultar_Cajero($row['usu']);
							$nombre_cajero=$oCajero->consultar('nom').' '.$oCajero->consultar('ape');
							if($row['tipo']=='ENTRADA'){
								$signo='+';
							}elseif($row['tipo']=='SALIDA'){
								$signo='-';
							}
								?>
                                <tr>
                                	<td width="5%"><?php echo $row['clase']; ?></td>
                                    <td width="60%">
										<?php echo $row['concepto'].'<br> <strong>Fecha: </strong>'.
										fecha($row['fecha']).'<br><strong>Cajero: </strong>'.$nombre_cajero; ?>
                                    </td>
                                    <td><center><?php echo $row['tipo']; ?></center></td>
                                    <td><div align="right"><?php echo' $ '.formato($row['valor']); ?></div></td>
                                </tr>
                                <?php } ?>
                            </table> 
                            <?php } ?>
                            </div>
                            </center>
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
