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
	
	if(!empty($_GET['valor_recibido']) or !empty($_GET['neto'])){
		$valor_recibido=($_GET['valor_recibido']);
		$descuento=($_GET['descuento']);
		$neto=($_GET['neto']);		$nneto=($_GET['neto']);
		
		if($descuento<>0){
			$sdescuento=($neto*($descuento/100));
			$sneto=$neto-$descuento;
		}else{
			$sneto=($_GET['neto']);
			$sdescuento=0;
		}
			$fecha=date('Y-m-d');
		
		$pa=$conexion->query("SELECT * FROM caja_tmp	WHERE usu='$usu'");				
		if(!$row=$pa->fetch_array()){	
			header('Location: index.php');
		}
		
		######### TRAEMOS LOS DATOS DE LA EMPRESA #############
		$pa=$conexion->query("SELECT * FROM empresa WHERE id=1");				
        if($row=$pa->fetch_array()){
			$nombre_empresa=$row['empresa'];
			$nit_empresa=$row['nit'];
			$dir_empresa=$row['direccion'];
			$tel_empresa=$row['tel'].'-'.$row['fax'];
			$pais_empresa=$row['pais'].' - '.$row['ciudad'];
			$id_puntos=$row['puntos'];
		}
		
		######### SACAMOS EL VALOR MAXIMO DE LA FACTURA Y LE SUMAMOS UNO ##########
		$pa=$conexion->query("SELECT MAX(factura)as maximo FROM factura");				
        if($row=$pa->fetch_array()){
			if($row['maximo']==NULL){
				$factura='100000001';
			}else{
				$factura=$row['maximo']+1;
			}
		}
		
		######## NOS UBICAMOS EN QUE DEPOSITO O TIENDA SE HACE LA VENTA ##########
		$pa=$conexion->query("SELECT * FROM cajero WHERE deposito='1'");				
		while($row=$pa->fetch_array()){
			$id_bodega=$row['deposito'];
			$oDeposito=new Consultar_Deposito($id_bodega);
			$nombre_deposito=$oDeposito->consultar('nombre');
		}
	}
	
	################# CLIENTE PARA PUNTOS ###################
		if(!empty($_GET['puntos'])){
			$puntos=($_GET['puntos']);
			$pa=$conexion->query("SELECT * FROM persona WHERE persona.doc='$puntos'");				
			if($row=$pa->fetch_array()){
				$nombre_cliente='<strong>Cliente: </strong> ('.$puntos.') '.$row['nom'].' '.$row['ape'].'<br>';
				//$new_puntos=$row['puntos']+((int)$neto/$id_puntos);
				$conexion->query("UPDATE cliente SET puntos='$new_puntos' WHERE doc='$puntos'");
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
    .row-fluid .span4 .table.table-bordered tr td .row-fluid .span4 strong {
	font-size: 14px;
}
  .row-fluid .span4 .table.table-bordered tr td .row-fluid .span4 strong {
	font-size: 16px;
}
  .row-fluid .span4 .table.table-bordered tr td .row-fluid .span4 strong {
	font-size: 18px;
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
    	<table width="98%">
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
				                	<div class="span4"><br>
									
                                   	  <strong>Valor Recibido: </strong><br><br><br><br>
                                      <strong>Total Factura: </strong><br><br><br><br>
                                      <strong>Vuelto: </strong><br>
                                  </div>
                                    <div class="span8" align="right">
                                    	 <pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($valor_recibido); ?><br></h2></pre>
										 <pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($neto); ?><br></h2></pre>
                                         <pre><h2 class="text-success" align="center"><?php echo $s.' '.formato($valor_recibido-$neto); ?></h2></pre>
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
                                    	
                                        <!--ANCHO DE LA IMPRESION-->
                                    	<table width="100%">
                                        	<tr>
                                                <td>
                                                    <center>
                                                    	<strong><?php echo $nombre_deposito; ?></strong><br>
                                                        <!--<img src="../../img/logo.png" width="80" height="80"><br>-->
                                                        <strong><?php echo $nombre_empresa; ?></strong><br>
														<strong><?php echo $nit_empresa; ?></strong><br>
														<strong>Iva Regimen Simplificado</strong>
                                                    </center>
                                                </td>
                                                <td><br>
                                                    <strong>Factura: </strong><?php echo $factura; ?> | <strong>Tipo de Compra: </strong>Contado<br>
                                                    <?php echo $nombre_cliente; ?>
                                                   
                                                    <strong>Feha y Hora </strong><?php $time = time();echo date("d-m-Y (H:i:s)", $time);?><br>
                                                  
                                                    <strong>Cajero/a: </strong><?php echo $cajero_nombre; ?>
                                                </td>
                                            </tr>
                                        </table>
										<br>
										<!--ANCHO DE LA IMPRESION-->
                                        <table width="100%" rules="all" border="1">
                                        	<tr>
                                            	<td width="8%"><strong>Cant</strong></td>
                                                <td width="27%"><strong>Descripcion</strong></td>
                                                <td width="18%"><div align="right"><strong>Precio Unitario</strong></div></td>
                                                <!--<td width="16%"><div align="right"><strong>Exentas</strong></div></td>
                                                <td width="15%"><div align="right"><strong>IVA 5%</strong></div></td>
                                                <td width="16%"><div align="right"><strong>IVA 10%</strong></div></td>-->
                                            </tr>
                                            <?php 
												$item=0;
												$A_iva1=0;$A_iva2=0;$A_iva3=0;
												$A_val1=0;$A_val2=0;$A_val3=0;
												
												$pa=$conexion->query("SELECT * FROM caja_tmp, producto 
												WHERE caja_tmp.usu='$usu' and caja_tmp.producto=producto.codigo");				
										        while($row=$pa->fetch_array()){												
												
													$iva1=0;$iva2=0;$iva3=0;
													$val1=0;$val2=0;$val3=0;
												
													$item=$item+$row['cant'];	$cantidad=$row['cant'];
													$codigo=$row['producto'];
													$p_nombre=$row['nombre'];
													##### CONSULTAR IVA ###################
													$oIVA=new Consultar_IVA($row['ivaventa']);
													$iva=$oIVA->consultar('valor');											
													##### Calcular el valor e importe ###### *(($iva/100)+1)
													$defecto=strtolower($row['defecto']);
													$valor=round($row['c_venta']*(($iva/100)+1));
													
													
													$costo=$row[$defecto.'_costo'];
													
													if($row['ivaventa']=='1'){
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
													}
													
													########################################
													if($row['ref']==NULL){
														$referencia='Sin Referencia';
													}else{
														$referencia=$row['ref'];
													}
													
													#######REGISTRAMOS LOS DETALLES##############
													$conexion->query("INSERT INTO detalle (factura,codigo,referencia,producto,cant,valor,costo) VALUES 
													('$factura','$codigo','$referencia','$p_nombre','$cantidad','$valor','$costo')");
													#########DESCONTAR INVENTARIO################################################################
													$pwa=$conexion->query("SELECT cant FROM contenido WHERE producto='$codigo' and deposito='$id_bodega'");				
										       		if($roww=$pwa->fetch_array()){	
														$new_cant=$roww['cant']-$cantidad;
														$conexion->query("UPDATE contenido SET cant='$new_cant' 
														WHERE producto='$codigo' and deposito='$id_bodega'");
													}
													#############################################################################################
											?>
                                            <tr>
                                            	<td><?php echo $cantidad; ?></td>
                                                <td><?php echo $p_nombre; ?></td>
                                                <td><div align="right"><?php echo $s.' '.formato($valor); ?></div></td>
                                                
                                            </tr>
											<?php } ?>
                                            <tr>
                                            	<td colspan="2"><strong>Sub Totales:</strong></td>
                                                
                                                <td><?php 
															if($A_val1==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val1);
															}
														?>
                                                <?php 
															if($A_val2==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val2);	
															}
														?>
                                                <?php 
															if($A_val3==0){
																echo '';
															}else{
																echo $s.' '.formato($A_val3);	
															}
														?></td>
                                                <!--<td>
                                                	<div align="right"></div>
                                                </td>
                                                <td>
                                                	<div align="right"></div>
                                                </td>
                                                <td>
                                                	<div align="right"></div>
                                                </td>-->
                                            </tr>
                                            <tr>
                                              <td colspan="2"><div align="right"><strong>Total </strong></div>
                                              </td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($neto); ?></strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><div align="right"><strong>Descuento</strong></div></td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($descuento); ?></strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><div align="right"><strong>Total a Pagar</strong></div></td>
                                              <td><div align="right"><strong><?php echo $s.' '.formato($sneto); ?></strong></div></td>
                                            </tr>
                                            
                                        </table>
                                        <br>
                                        <center>
                                        	
                                            <strong>GRACIAS POR PREFERIRNOS!</strong> <BR>
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
		$conexion->query("INSERT INTO factura (factura,valor,fecha,estado,usu,clase,cliente) 
					VALUE ('$factura','$sneto','$fecha','s','$usu','CONTADO','$puntos')");
		if($descuento<>0){
			$mensaje='Venta al Contado Factura: '.$factura.' por Valor de '.$s.' '.formato($neto).' Descuento Incluido de '.$descuento.' %';		
		}else{
			$mensaje='Venta al Contado Factura: '.$factura.' por Valor de '.$s.' '.formato($neto);
		}
		
		$conexion->query("INSERT INTO resumen (concepto,clase,valor,tipo,fecha,usu,estado,very,deposito) 
		VALUE ('$mensaje','VENTA','$sneto','ENTRADA','$fecha','$usu','s','$factura','$id_bodega')");
		
		$conexion->query("DELETE FROM caja_tmp WHERE usu='$usu'");
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
