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
	
	$usu=$_SESSION['cod_user'];$very=FALSE;
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');
	
	if(!empty($_GET['cliente']) and !empty($_GET['neto'])){
		$neto=limpiar($_GET['neto']);		$nneto=limpiar($_GET['neto']);
		$fecha=date('Y-m-d');				$cliente=($_GET['cliente']);
		
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
		$pa=mysql_query("SELECT * FROM cajero WHERE usu='$usu'");				
		while($row=mysql_fetch_array($pa)){
			$id_bodega=$row['deposito'];
			$oDeposito=new Consultar_Deposito($id_bodega);
			$nombre_deposito=$oDeposito->consultar('nombre');
		}
	}
	
		################# CLIENTE PARA PUNTOS ###################

		$pa=mysql_query("SELECT * FROM persona, cliente WHERE persona.doc='$cliente' and cliente.doc='$cliente' GROUP BY persona.doc");				
		if($row=mysql_fetch_array($pa)){
			if(!empty($row['utilizado'])){
				$very_cupo=$row['utilizado']+$neto;	
			}else{
				$very_cupo=$neto;	
			}
			
			if($row['cupo']>=$very_cupo){
				$nombre_cliente='<strong>Cliente: </strong> ('.$cliente.') '.$row['nom'].' '.$row['ape'].'<br>';
				$very=TRUE;
			}else{
				$very=FALSE;
				header('Location: index.php?mensaje=1');
			}
		}else{
			$very=FALSE;
			header('Location: index.php?mensaje=2');
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
	<div align="center"><strong><?php echo $s.' '.formato($neto); ?></strong>
    	<table width="90%">
          <tr>
            <td>
            	<strong><a href="index.php">Regresar</a></strong>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td><h2 align="center">Compras a Credito</h2></td>
                  </tr>
                </table>
                               
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
                                                    </center>
                                                </td>
                                                <td><br>
                                                    <strong>Factura: </strong><?php echo $factura; ?> | <strong>Tipo de Compra: </strong>Credito<br>
                                                    <?php echo $nombre_cliente; ?>
                                                    <strong>Fecha: </strong><?php echo fecha($fecha); ?> | 
                                                    <strong>Feha y Hora </strong><?php $time = time();echo date("d-m-Y (H:i:s)", $time);?><br>
                                                    <strong>Cajero/a: </strong><?php echo $cajero_nombre; ?>
                                                </td>
                                            </tr>
                                        </table>
										<br>
                                        <table width="95%" rules="all" border="1">
                                        	<tr>
                                            	<td width="8%"><strong>Cant</strong></td>
                                                <td width="27%"><strong>Descripcion</strong></td>
                                                <td width="18%"><div align="right"><strong>Precio Unitario</strong></div></td>
                                                <td width="16%"><div align="right"><strong>Exentas</strong></div></td>
                                                <td width="15%"><div align="right"><strong>IVA 5%</strong></div></td>
                                                <td width="16%"><div align="right"><strong>IVA 10%</strong></div></td>
                                            </tr>
                                            <?php 
												$item=0;
												$A_iva1=0;$A_iva2=0;$A_iva3=0;
												$A_val1=0;$A_val2=0;$A_val3=0;
												
												$pa=mysql_query("SELECT * FROM caja_tmp, producto 
												WHERE caja_tmp.usu='$usu' and caja_tmp.producto=producto.codigo");				
										        while($row=mysql_fetch_array($pa)){												
												
													$iva1=0;$iva2=0;$iva3=0;
													$val1=0;$val2=0;$val3=0;
												
													$item=$item+$row['cant'];	$cantidad=$row['cant'];
													$codigo=$row['producto'];
													$p_nombre=$row['nombre'];
													##### CONSULTAR IVA ###################
													$oIVA=new Consultar_Sistema($row['ivaventa']);
													$iva=$oIVA->consultar('valor_iva');											
													##### Calcular el valor e importe ###### *(($iva/100)+1)
													$defecto=strtolower($row['defecto']);
													$valor=($row[$defecto.'_venta']-($row[$defecto.'_venta']*($iva/100)));
													$costo=$row[$defecto.'_costo'];
													
													/*if($row['ivaventa']=='1'){
														$iva1=($row[$defecto.'_venta']*($iva/100))*$cantidad;					
														$val1=$valor*$cantidad;	
														
														$A_iva1=$A_iva1+$iva1;
														$A_val1=$A_val1+$val1;
														
													}elseif($row['ivaventa']=='2'){
														$iva2=($row[$defecto.'_venta']*($iva/100))*$cantidad;									
														$val2=$valor*$cantidad;	
														
														$A_iva2=$A_iva2+$iva2;
														$A_val2=$A_val2+$val2;
														
													}elseif($row['ivaventa']=='3'){
														$iva3=($row[$defecto.'_venta']*($iva/100))*$cantidad;
														$val3=$valor*$cantidad;	
														
														$A_iva3=$A_iva3+$iva3;
														$A_val3=$A_val3+$val3;					
													}*/
													
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
                                            	<td><?php echo $cantidad; ?></td>
                                                <td><?php echo $p_nombre; ?></td>
                                                <td><div align="right"><?php echo $s.' '.formato($valor); ?></div></td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($val1==0){
																echo '';
															}else{
																echo $s.' '.formato($val1);	
															}
														?>
                                                    </div>
                                                </td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($val2==0){
																echo '';
															}else{
																echo $s.' '.formato($val2);	
															}
														?>
                                                    </div>
                                                </td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($val3==0){
																echo '';
															}else{
																echo $s.' '.formato($val3);	
															}
														?>
                                                    </div>
                                                </td>
                                            </tr>
											<?php } ?>
                                            <tr>
                                            	<td colspan="2"><strong>Sub Totales:</strong></td>
                                            	<td>&nbsp;</td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($A_val1==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val1);
															}
														?>
                                                    </div>
                                                </td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($A_val2==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val2);	
															}
														?>
                                                    </div>
                                                </td>
                                                <td>
                                                	<div align="right">
                                                    	<?php 
															if($A_val3==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val3);	
															}
														?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Total</strong></div></td>
                                              <td><div align="right"><?php echo $s.' '.formato($_GET['neto']); ?></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Descuento</strong></div></td>
                                              <td><div align="right"><?php echo $s; ?> 0</div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="5"><div align="right"><strong>Total a Pagar</strong></div></td>
                                              <td><div align="right"><?php echo $s.' '.formato($_GET['neto']); ?></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="5">
                                              <?php
											  	$total_iva=anti_decimales($A_iva2)+anti_decimales($A_iva3);
											  ?>
                                            <strong>Liquidacion del IVA: 5% </strong><?php echo $s.' '.formato(anti_decimales($A_iva2)); ?>
                                            <strong>| 10% </strong><?php echo $s.' '.formato(anti_decimales($A_iva3)); ?>
                                            <strong>| Total IVA </strong><?php echo $s.' '.formato($total_iva); ?>
                                              </td>
                                              <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <br>
                                        <center>
                                        	<?php echo $nombre_empresa; ?><br>
                                            <?php echo $tel_empresa; ?><br>
                                            <?php echo $pais_empresa; ?><br>
                                            <?php echo $dir_empresa; ?><br>
                                        </center>
                              </div>
                            </center>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
        </table>
    </div>
    
    <?php 
		######## GUARDAMOS LA INFORMACION DE LA FACTURA
		mysql_query("INSERT INTO factura (factura,valor,fecha,estado,usu,clase,cliente) 
					VALUE ('$factura','$neto','$fecha','s','$usu','SEPARADO','$cliente')");
		mysql_query("UPDATE cliente SET utilizado='$very_cupo' WHERE doc='$cliente'");
		$mensaje='Venta a SEPARADO Factura: '.$factura.' por Valor de $ '.formato($neto);
		
		mysql_query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) VALUE ('$mensaje','VENTA','$neto','SEPARADO','$fecha','$usu','s','$factura','$id_bodega')");
		
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
