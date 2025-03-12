<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'8')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	$nombre='';			$depart='';			$unidad='';			$d_costo='';
	$defecto='';		$ivacompra='';		$ivaventa='';		$costo_prov='0';
	$ocosto_prov='0';	$a_venta='0';		$b_venta='0';		$c_venta='0';
	$d_venta='0';		$a_costo='0';		$b_costo='0';		$c_costo='0';
	$codigo='';			$boton='Guardar Informacion';			$existe=FALSE;		$ivav=0;
	$titulo='Crear Turno'; $placa='';
	$m_destino='';
	$r_destino='';
	$origen='';
	
	if(!empty($_GET['id'])){
		$id_codigo=limpiar($_GET['id']);
		$id_codigo=substr($id_codigo,10);
		$id_codigo=decrypt($id_codigo,'URLCODIGO');
		
		
		$pa=mysql_query("SELECT * FROM factura WHERE id='$id_codigo'");				
		if($row=mysql_fetch_array($pa)){
			$existe=TRUE;
			$oP=new Consultar_Factura($id_codigo);		$codigo=$id_codigo;
			$id=$oP->consultar('id');			$boton='Actualizar Informacion';
			$valor=$oP->consultar('valor');			
			$factura=$oP->consultar('factura');			
				
			//$placa=$oP->consultar('placa');				
			
			//$oIVA=new Consultar_IVA($ivaventa);	
			//$ivav=($oIVA->consultar('valor')/100)+1;
			
			
		}
		
		
	}
	$cons=mysql_query("SELECT * FROM detalle WHERE id='$id'");
		while($info=mysql_fetch_array($cons)){
			$importe=$info['valor']*$info['cant'];
			
			$cant=$info['cant'];
			$codigo=$info['codigo'];
			$referencia=$info['referencia'];
			$producto=$info['producto'];
			
		}
		$pa=mysql_query("SELECT * FROM empresa WHERE id=1");				
        if($row=mysql_fetch_array($pa)){
			$nombre_empresa=$row['empresa'];
			$nit_empresa=$row['nit'];
			$dir_empresa=$row['direccion'];
			$tel_empresa=$row['tel'].'-'.$row['fax'];
			$pais_empresa=$row['pais'].' - '.$row['ciudad'];
			$id_puntos=$row['puntos'];
		}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
   <title>INFORMACION ORDEN DE VUELO</title>
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

    <?php 
		include_once "../../menu/m_producto.php"; 
		
		
	?>
    <div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                   	    <div class="row-fluid">
						
                           <button onclick="imprimir();" class="btn"><i class="icon-print"></i> <strong>IMPRIMIR</strong></button><BR><br>
	                        <div id="imprimeme">
							<!--<div class="span4" align="center"><?php echo $imagen; ?></div>-->
							
							<table width="100%">
    	                    <div class="span8" align="center">
							<td>
							<img src="../../img/logo.png" width="80" height="80"><br>
							<!--<strong> <?php echo $nombre_empresa; ?> </strong><br><br>-->
							<strong> <?php echo $dir_empresa; ?> </strong><br><br>
							<strong> <?php echo $tel_empresa; ?> </strong><br><br>
							
							<strong>Factura:</strong><?php echo $factura; ?><br><br>
                            	<strong>Producto:</strong><?php echo $producto; ?><br>
								<strong>Cantidad:</strong><?php echo $cant; ?><br>
								<strong>Valor:</strong><?php echo $s.' '.formato($importe); ?><br>
							</td>	
							</table>	
								
                                <?php 
									if($existe==TRUE){ 
									$url1=cadenas().encrypt($id_codigo,'URLCODIGO');
								?>
									<!--<center>
										<div class="btn-group">
											<a href="crear_producto.php?codigo=<?php echo $url1; ?>" class="btn btn-primary"><strong> [ Producto ] </strong></a>
											<a href="inventario.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Inventario ] </strong></a>
											<a href="crear_proveedor.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Proveedor ] </strong></a>
										</div>
									</center>-->
								<?php }	?>
                            </div>
							</div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php			
					if(!empty($_POST['nombre']) and !empty($_POST['codigo'])){
						$codigo=limpiar($_POST['codigo']);				$nombre=limpiar($_POST['nombre']);
						$depart=limpiar($_POST['depart']);				
						$unidad=limpiar($_POST['unidad']);
						$defecto=limpiar($_POST['defecto']);			//$ivacompra=limpiar($_POST['ivacompra']);
									//$costo_prov=limpiar($_POST['costo_prov']);
						$a_venta=limpiar($_POST['a_venta']);			//$ivaventa=limpiar($_POST['ivaventa']);
						//$c_venta=limpiar($_POST['c_venta']);			//$b_venta=limpiar($_POST['b_venta']);
						//$d_venta=limpiar($_POST['d_venta']);			
						//$ocosto_prov=limpiar($_POST['ocosto_prov']);
						//$a_costo=limpiar($_POST['a_costo']);			$d_costo=limpiar($_POST['d_costo']);
						//$b_costo=limpiar($_POST['b_costo']);			
						//$c_costo=limpiar($_POST['c_costo']);
						$r_destino=limpiar($_POST['r_destino']);
						$m_destino=limpiar($_POST['m_destino']);
									$url=cadenas().encrypt($codigo,'URLCODIGO');
						
						if(empty($_GET['codigo'])){
							$pa=mysql_query("SELECT * FROM producto WHERE codigo='$codigo'");				
							if($row=mysql_fetch_array($pa)){
								echo mensajes('El Codigo "'.$codigo.'" Ya se Encuentra Registrado en la Base de Datos','rojo');
							}
						}
						
						$oGuardar=new Proceso_Producto($codigo, $nombre, $depart, $unidad, $defecto, 1, 1, $costo_prov, $ocosto_prov, $a_venta, $b_venta, $c_venta, $d_venta, $a_costo, $b_costo, $c_costo, $d_costo,$r_destino,$m_destino);
						
						if($existe==FALSE){
							$oGuardar->crear();
							echo mensajes('El Servicio "'.$nombre.'" Identificado con el Codigo "'.$codigo.'" Ha sido Registrado con Exito<BR>
							<a href="crear_producto.php?codigo='.$url.'"><strong>Seguir Editando</strong></a>','verde');	
						}else{
							$oGuardar->actualizar();
							echo mensajes('El Servicio "'.$nombre.'" Identificado con el Codigo "'.$codigo.'" Ha sido Actualizado con Exito','verde');		
						}
						
						//subir la imagen del articulo
						$nameimagen = $_FILES['imagen']['name'];
						$tmpimagen = $_FILES['imagen']['tmp_name'];
						$extimagen = pathinfo($nameimagen);
						$ext = array("png","jpg");
						$urlnueva = "../../Producto/".$codigo.".jpg";			
						if(is_uploaded_file($tmpimagen)){
							if(array_search($extimagen['extension'],$ext)){
								copy($tmpimagen,$urlnueva);	
							}else{
								echo mensajes("Error al Cargar la Imagen","rojo");	
							}
						}else{
							echo mensajes("Error al Cargar la Imagen","rojo");	
						}
						
					}
				?>
                <!--<table class="table table-bordered">
                  <tr>
                    <td>
                   	  <form name="form1" enctype="multipart/form-data" method="post" action="">
                       <div align="center"><pre><strong>Informacion Basica</strong></pre></div>
                        <div class="row-fluid">
                          <div class="span6" align="center">
                          	<strong>Número de Placa</strong><br>
                            <input type="text" name="codigo" <?php if($existe==TRUE){ echo 'readonly';}else{ echo 'required'; } ?>  class="input-xlarge" autocomplete="off" value="<?php echo $codigo; ?>"><br>
                          </div>
                          <div class="span6" align="center">
                          	<strong>Nombre del Cliente</strong><br>
                            <input type="text" name="nombre" class="input-xxlarge" autocomplete="off" required value="<?php echo $nombre; ?>"><br>
                          </div>
						  
                        <div class="span6" align="center">
                          	<strong>Nombre Servicio</strong><br>
                            
                            <input type="text" name="depart" min="0" value="<?php echo $depart; ?>" autocomplete="off" required>
                          </div>
                        
                   		<div class="row-fluid">
                         <div class="span6" align="center">
                            	<strong>Precio Servicio</strong><br>
                                <select name="a_venta" class="input-xlarge">
                                	<?php
										$pa=mysql_query("SELECT * FROM iva WHERE estado='s'");				
                    					while($row=mysql_fetch_array($pa)){
											if($row['valor']==$a_venta){
												echo '<option value="'.$row['valor'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['valor'].'">'.$row['nombre'].'</option>';	
											}	
										}
									?>
                              </select>
                            </div>
							<div class="span6" align="center">
                            	<strong>Numero de Celular</strong><br>
                                
                                    <input type="number" name="unidad"  value="<?php echo $unidad; ?>" autocomplete="off">
                                
                            </div>
							</div>
							</div>
                            <div class="span6" align="center">
                            	<strong>Unidad</strong><br>
                                <select name="unidad" class="input-xlarge">
                                	<?php
										$pa=mysql_query("SELECT * FROM unidad WHERE estado='s'");				
                    					while($row=mysql_fetch_array($pa)){
											if($row['id']==$unidad){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
											}
										}
									?>
                              </select>
                            </div>
					    </div>
                        
                        <div align="center"><pre><strong>Configuracion del IVA</strong></pre></div>
                        <div class="row-fluid">
                        	<div class="span4">
                            	<strong>Precio por Defecto</strong><br>
                              <select name="defecto" class="input-xlarge">
                                	<option value="A" <?php if($defecto=='A'){ echo 'selected'; } ?>>Precio A</option>
                                    <option value="B" <?php if($defecto=='B'){ echo 'selected'; } ?>>Promo fin de Año</option>
                                    <option value="C" <?php if($defecto=='C'){ echo 'selected'; } ?>>Precio C</option>
                                    <option value="D" <?php if($defecto=='D'){ echo 'selected'; } ?>>Precio D</option>
                                </select>
                            </div>
                          <div class="span4">
                            	<strong>IVA Compra</strong><br>
                            <select name="ivacompra" class="input-xlarge">
                                	<?php
										$pa=mysql_query("SELECT * FROM iva WHERE estado='s'");				
                    					while($row=mysql_fetch_array($pa)){
											if($row['id']==$ivacompra){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
											}
										}
									?>
                              </select>
                          </div>
						  <div class="span4">
                            	<strong>IVA Venta</strong><br>
                            <select name="ivaventa" class="input-xlarge">
                                	<?php
										$pa=mysql_query("SELECT * FROM iva WHERE estado='s'");				
                    					while($row=mysql_fetch_array($pa)){
											if($row['id']==$ivaventa){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
											}
										}
									?>
                              </select>
                          </div>
                          
                        </div>
                        
                        <div align="center"><pre><strong>Costos del Proveedor</strong></pre></div>
                        <div class="row-fluid">
                            <div class="span4">
                            	<strong>Costo Proveedor</strong><br>
                                <div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
                                    <input type="number" name="costo_prov" min="0" value="<?php echo $costo_prov; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="span4">
                            	<strong>Otros Costos</strong><br>
                                <div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="ocosto_prov" min="0" value="<?php echo $ocosto_prov; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="span4">
                            	<?php 
									if($existe==TRUE){
										echo '<strong>Total con IVA</strong><br>';
										$iva=new Consultar_IVA($ivacompra);
										$tiva=($iva->consultar('valor')/100)+1;
										$total_prov=($costo_prov+$ocosto_prov)*$tiva;
										echo $s.' '.formato($total_prov);
									}
								?>
                          </div>
                        </div>
                        
                        <div align="center"><pre><strong>Costos del Producto</strong></pre></div>
                        <div align="center">
                       	<table width="80%">
                          <tr>
                            <td><center><strong>PRECIOS</strong></center></td>
                            <td><center><strong>VENTA</strong></center></td>
                            <td><center><strong>COSTO</strong></center></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td><center><strong>A</strong></center></td>
                            <td> 
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="a_venta" min="0" value="<?php echo $a_venta; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="a_costo" min="0" value="<?php echo $a_costo; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td><?php echo $s.' '.formato($a_venta*$ivav); ?></td>
                          </tr>
                          <tr>
                            <td><center><strong>Promo fin de Año</strong></center></td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="b_venta" min="0" value="<?php echo $b_venta; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td>
                            	<div class="input-prepend input-append">
                                	<span class="add-on"><strong><?php echo $s; ?></strong></span>
                       				<input type="number" name="b_costo" min="0" value="<?php echo $b_costo; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td><?php echo $s.' '.formato($b_venta*$ivav); ?></td>
                          </tr>
                          <tr>
                            <td><center><strong>C</strong></center></td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="c_venta" min="0" value="<?php echo $c_venta; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="c_costo" min="0" value="<?php echo $c_costo; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td><?php echo $s.' '.formato($c_venta*$ivav); ?></td>
                          </tr>
                          <tr>
                            <td><center><strong>D</strong></center></td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="d_venta" min="0" value="<?php echo $d_venta; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td>
                            	<div class="input-prepend input-append">
                                    <span class="add-on"><strong><?php echo $s; ?></strong></span>
	                                <input type="number" name="d_costo" min="0" value="<?php echo $d_costo; ?>" autocomplete="off" required>
                                </div>
                            </td>
                            <td><?php echo $s.' '.formato($d_venta*$ivav); ?></td>
                          </tr>
                          <tr>
                            <td colspan="4">
                            	<div align="center">
                                	<strong>Fotografia</strong><br>
	                                <input type="file" name="imagen"><br>
                                </div>
                            </td>
                          </tr>
                        </table>
                        <br>
                        <div class="form-actions" align="center">
                          <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <strong><?php echo $boton; ?></strong></button>
                          <button type="reset" class="btn"><i class="icon-remove"></i> <strong>Cancelar</strong></button>
                        </div>
                        </form>
                    </td>
                  </tr>
                </table>-->
            </td>
          </tr>
        </table>

    </div>
<?php 
		######## GUARDAMOS LA INFORMACION DE LA FACTURA
		
		mysql_query("INSERT INTO contenido (deposito,producto,cant,minima) 
		VALUE ('1','$codigo','99999999999','8888')");
		
	
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
