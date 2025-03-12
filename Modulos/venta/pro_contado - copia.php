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
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
	
	if(!empty($_GET['valor_recibido']) and !empty($_GET['neto'])){
		$valor_recibido=limpiar($_GET['valor_recibido']);
		$descuento=limpiar($_GET['descuento']);
		$neto=limpiar($_GET['neto']);		$nneto=limpiar($_GET['neto']);
		
		if($descuento<>0){
			$sdescuento=($neto*($descuento/100));
			$sneto=$neto-$sdescuento;
		}else{
			$sneto=limpiar($_GET['neto']);
			$sdescuento=0;
		}
			$fecha=date('Y-m-d');
		
		$pa=mysql_query("SELECT * FROM caja_tmp	WHERE usu='$usu'");				
		if(!$row=mysql_fetch_array($pa)){	
			header('Location: index.php');
		}
		
		######### TRAEMOS LOS DATOS DE LA EMPRESA #############
		$pa=mysql_query("SELECT * FROM empresa WHERE id=1");				
        if($row=mysql_fetch_array($pa)){
			$nombre_empresa=$row['empresa'];
			$nit_empresa=$row['nit'];
			$dir_empresa=$row['direccion'];
			$tel_empresa=$row['tel'].'-'.$row['fax'];
			$pais_empresa=$row['pais'].' - '.$row['ciudad'];
			$id_puntos=$row['puntos'];
		}
		
		######### SACAMOS EL VALOR MAXIMO DE LA FACTURA Y LE SUMAMOS UNO ##########
		$pa=mysql_query("SELECT MAX(factura)as maximo FROM factura");				
        if($row=mysql_fetch_array($pa)){
			if($row['maximo']==NULL){
				$factura='100000001';
			}else{
				$factura=$row['maximo']+1;
			}
		}
		
		######## NOS UBICAMOS EN QUE DEPOSITO O TIENDA SE HACE LA VENTA ##########
		$pa=mysql_query("SELECT * FROM Cajero WHERE usu='$usu'");				
		while($row=mysql_fetch_array($pa)){
			$id_bodega=$row['deposito'];
			$oDeposito=new Consultar_Deposito($id_bodega);
			$nombre_deposito=$oDeposito->consultar('nombre');
		}
	}
	
	################# CLIENTE PARA PUNTOS ###################
		if(!empty($_GET['puntos'])){
			$puntos=limpiar($_GET['puntos']);
			$pa=mysql_query("SELECT * FROM persona, cliente WHERE persona.doc='$puntos' and cliente.doc='$puntos'");				
			if($row=mysql_fetch_array($pa)){
				$nombre_cliente='<strong>Cliente: </strong> ('.$puntos.') '.$row['nom'].' '.$row['ape'].'<br>';
				$new_puntos=$row['puntos']+((int)$neto/$id_puntos);
				mysql_query("UPDATE cliente SET puntos='$new_puntos' WHERE doc='$puntos'");
				$very=TRUE;
			}else{
				$very=FALSE;
				header('Location: index.php?mensaje=2');
			}
		}else{
			$puntos='Sin Especificar';
			$very=TRUE;
			$nombre_cliente='';
		}
		
		if($very==TRUE){
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Compras al Contado</title>
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
            	<strong><a href="index.php">Regresar</a></strong>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td><h2 align="center">Compras al Contado</h2></td>
                  </tr>
                </table>
                
                <div class="row-fluid">
                	<div class="span4">
                    	<table class="table table-bordered">
                          <tr>
                            <td>
                            	<div class="row-fluid">
				                	<div class="span4">
                                    	<strong>Valor Recibido:</strong><br>
                                        <strong>Total Factura: </strong><br>
                                        <strong>Vuelto: </strong>
                                    </div>
                                    <div class="span8" align="right">
                                    	 <?php echo $s.' '.formato($valor_recibido); ?><br>
										 <?php echo $s.' '.formato($neto); ?><br>
                                         <?php echo $s.' '.formato($valor_recibido-$neto); ?>
                                    </div>
                                </div>
                            </td>
                          </tr>
                        </table>
                    </div>
                	<div class="span8">
                    	<table class="table table-bordered">
                          	<tr>
                            	<td>
                                	<center>
                                   	<button onclick="imprimir();" class="btn"><i class="icon-print"></i> <strong>IMPRIMIR</strong></button><BR><br>
                                	<div id="imprimeme">
                                    	<center><strong>Gracias por su Compra</strong></center>
                                    	<table width="95%">
                                        	<tr>
                                                <td>
                                                    <center>
                                                    	<strong><?php echo $nombre_deposito; ?></strong><br>
                                                        <img src="../../img/logo.png" width="80" height="80"><br>
                                                        <strong><?php echo $nombre_empresa; ?></strong><br>
														<strong><?php echo $nit_empresa; ?></strong><br>
                                                    </center>
                                                </td>
                                                <td><br>
                                                    <strong>Factura: </strong><?php echo $factura; ?> | <strong>Tipo de Compra: </strong>Contado<br>
                                                    <?php echo $nombre_cliente; ?>
                                                    <strong>Fecha: </strong><?php echo fecha($fecha); ?> | 
                                                    <strong>Hora: </strong><?php echo date('H:m:s'); ?><br>
                                                    <strong>Cajero/a: </strong><?php echo $cajero_nombre; ?>
                                                </td>
                                            </tr>
                                        </table>
										<br>
                                        <table width="95%" rules="all" border="1">
                                        	<tr>
                                            	<td><strong>Cant</strong></td>
                                                <td><strong>Cod. Articulo</strong></td>
                                                <td><strong>Referencia</strong></td>
                                                <td><strong>Descripcion</strong></td>
                                                <td><div align="right"><strong>Valor Unitario</strong></div></td>
                                                <td><div align="right"><strong>Importe</strong></div></td>
                                            </tr>
                                            <?php 
												$item=0;
												$pa=mysql_query("SELECT * FROM caja_tmp, producto 
												WHERE caja_tmp.usu='$usu' and caja_tmp.producto=producto.codigo");				
										        while($row=mysql_fetch_array($pa)){												
													$item=$item+$row['cant'];	$cantidad=$row['cant'];
													$codigo=$row['producto'];
													$p_nombre=$row['nombre'];
													##### CONSULTAR IVA ###################
													$oIVA=new Consultar_IVA($row['ivaventa']);
													$iva=$oIVA->consultar('valor');
													##### Calcular el valor e importe ######
													$defecto=strtolower($row['defecto']);
													$valor=$row[$defecto.'_venta']*(($iva/100)+1);
													$costo=$row[$defecto.'_costo'];
													$importe=$row['cant']*$valor;
													########################################
													if($row['ref']==NULL){
														$referencia='Sin Referencia';
													}else{
														$referencia=$row['ref'];
													}
													
													#######REGISTRAMOS LOS DETALLES##############
													mysql_query("INSERT INTO detalle (factura,codigo,referencia,producto,cant,valor,costo) VALUES 
													('$factura','$codigo','$referencia','$p_nombre','$cantidad','$valor','$costo')");
													#########DESCONTAR INVENTARIO################################################################
													$pwa=mysql_query("SELECT cant FROM contenido WHERE producto='$codigo' and deposito='$id_bodega'");				
										       		if($roww=mysql_fetch_array($pwa)){	
														$new_cant=$roww['cant']-$cantidad;
														mysql_query("UPDATE contenido SET cant='$new_cant' 
														WHERE producto='$codigo' and deposito='$id_bodega'");
													}
													#############################################################################################
											?>
                                            <tr>
                                            	<td>x<?php echo $cantidad; ?></td>
                                                <td><?php echo $codigo; ?></td>
                                                <td><?php echo $referencia; ?></td>
                                                <td><?php echo $p_nombre; ?></td>
                                                <td><div align="right"><?php echo $s.' '.formato($valor); ?></div></td>
                                                <td><div align="right"><?php echo $s.' '.formato($importe); ?></div></td>
                                            </tr>
											<?php } ?>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Total </strong></div>
                                              </td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($neto); ?></strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Descuento</strong></div></td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($sdescuento); ?></strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Total a Pagar</strong></div></td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($sneto); ?></strong></div></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <center>
                                        	Gracias por preferirnos
                                            <?php echo $pais_empresa; ?><br>
                                            <?php echo $dir_empresa; ?><br>
                                        </center>
                                    </div>
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
    
    <?php 
		######## GUARDAMOS LA INFORMACION DE LA FACTURA
		mysql_query("INSERT INTO factura (factura,valor,fecha,estado,usu,clase,cliente) 
					VALUE ('$factura','$neto','$fecha','s','$usu','CONTADO','$puntos')");
		if($descuento<>0){
			$mensaje='Venta al Contado Factura: '.$factura.' por Valor de '.$s.' '.formato($neto).' Descuento Incluido de '.$descuento.' %';		
		}else{
			$mensaje='Venta al Contado Factura: '.$factura.' por Valor de '.$s.' '.formato($neto);
		}
		
		mysql_query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) VALUE ('$mensaje','VENTA','$neto','ENTRADA','$fecha','$usu','s','$factura','$id_bodega')");
		
		mysql_query("DELETE FROM caja_tmp WHERE usu='$usu'");
	?>
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
<?php } ?>
