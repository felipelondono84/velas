<?php 
	session_save_path();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if(@$_SESSION['tipo_user']=='a' or @$_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'8')==FALSE){
			header('Location: ../../error.php');
		}
	}
	
	$nombre='';			$depart='';			$unidad='';			$d_costo='0';
	$defecto='';		$ivacompra='';		$ivaventa='';		$costo_prov='0';
	$ocosto_prov='0';	$a_venta='0';		$b_venta='0';		$c_venta='0';
	$d_venta='0';		$a_costo='0';		$b_costo='0';		$c_costo='0';
	$codigo='';			$boton='Guardar Informacion';			$existe=FALSE;		$ivav=0;
	$titulo='Crear Producto';
	
	if(!empty($_GET['codigo'])){
		$id_codigo=($_GET['codigo']);
		$id_codigo=substr($id_codigo,10);
		$id_codigo=decrypt($id_codigo,'URLCODIGO');
		
		
		$pa=$conexion->query("SELECT * FROM producto WHERE codigo='$id_codigo'");				
		if($row=$pa->fetch_array()){
			$existe=TRUE;
			$oP=new Consultar_Producto($id_codigo);		$codigo=$id_codigo;
			$nombre=$oP->consultar('nombre');			$boton='Actualizar Informacion';
			$depart=$oP->consultar('depart');			$unidad=$oP->consultar('unidad');
			$defecto=$oP->consultar('defecto');			$ivacompra=$oP->consultar('ivacompra');
			$ivaventa=$oP->consultar('ivaventa');		$costo_prov=$oP->consultar('costo_prov');
			$ocosto_prov=$oP->consultar('ocosto_prov');	$a_venta=$oP->consultar('a_venta');
			$b_venta=$oP->consultar('b_venta');			$c_venta=$oP->consultar('c_venta');
			$d_venta=$oP->consultar('d_venta');			$a_costo=$oP->consultar('a_costo');
			$b_costo=$oP->consultar('b_costo');			$c_costo=$oP->consultar('c_costo');
			$d_costo=$oP->consultar('d_costo');			$titulo=$oP->consultar('nombre');		
			
			$oIVA=new Consultar_IVA($ivaventa);	
			$ivav=($oIVA->consultar('valor')/100)+1;
		}else{
			$existe=FALSE;	
		}
	}

$pa=$conexion->query("SELECT MAX(codigo)as maximo FROM producto");				
        if($row=$pa->fetch_array()){
			if($row['maximo']==NULL){
				$codigo='1001';
			}else{
				$codigo=$row['maximo']+1;
			}
		}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
   <title>Crear Producto</title>
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

    <?php 
		include_once "../../menu/m_producto.php"; 
		
		if (file_exists("../../Producto/".$codigo.".png")){
			$imagen='<img src="../../Producto/'.$codigo.'.jpg" width="100" height="100" class="img-circle img-polaroid">';
		}else{
		$imagen='<img src="../../Producto/defecto.jpg" width="100" height="100">';
		}
	?>
    <div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                   	    <div class="row-fluid">
	                        <div class="span4" align="center"><?php echo $imagen; ?></div>
    	                    <div class="span8" align="center">
                            	<h1><?php echo $titulo; ?></h1>
                                <?php 
									if($existe==TRUE){ 
									$url1=cadenas().encrypt($id_codigo,'URLCODIGO');
								?>
									<center>
										<div class="btn-group">
											<a href="crear_producto.php?codigo=<?php echo $url1; ?>" class="btn btn-primary"><strong> [ Producto ] </strong></a>
											<a href="inventario.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Inventario ] </strong></a>
											<a href="crear_proveedor.php?codigo=<?php echo $url1; ?>" class="btn"><strong> [ Proveedor ] </strong></a>
										</div>
									</center>
								<?php }	?>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
               <?php			
					if(!empty($_POST['nombre']) and !empty($_POST['codigo'])){
						$codigo=($_POST['codigo']);				$nombre=($_POST['nombre']);
						$depart=($_POST['depart']);				$unidad=($_POST['unidad']);
						$defecto=($_POST['defecto']);			$ivacompra=($_POST['ivacompra']);
						$ivaventa=($_POST['ivaventa']);			$costo_prov=($_POST['costo_prov']);
						$ocosto_prov=($_POST['ocosto_prov']);	$a_venta=($_POST['a_venta']);
						$b_venta=($_POST['b_venta']);			$c_venta=($_POST['c_venta']);
						$d_venta=($_POST['d_venta']);			$a_costo=($_POST['a_costo']);
						$b_costo=($_POST['b_costo']);			$c_costo=($_POST['c_costo']);
						$d_costo=($_POST['d_costo']);			$url=cadenas().encrypt($codigo,'URLCODIGO');
						
						if(empty($_GET['codigo'])){
							$pa=$conexion->query("SELECT * FROM producto WHERE codigo='$codigo'");				
							if($row=$pa->fetch_array()){
								echo mensajes('El Codigo "'.$codigo.'" Ya se Encuentra Registrado en la Base de Datos','rojo');
							}
						}
						$oConsultar=new Consultar_Producto($codigo);
						$oGuardar=new Proceso_Producto($codigo, $nombre, $depart, $unidad, $defecto, $ivacompra, $ivaventa, $costo_prov, $ocosto_prov, $a_venta, $b_venta, $c_venta, $d_venta, $a_costo, $b_costo, $c_costo, $d_costo);
						
						if(!empty($_GET['codigo'])){
							$oGuardar->actualizar();
							echo mensajes('El Cliente  Ha sido Actualizado/a con Exito','verde');
							
							}else{
								
								if($oConsultar->consultar('codigo')==NULL){
									$oGuardar->crear();
									
							echo mensajes('El Producto "'.$nombre.'" Identificado con el Codigo "'.$codigo.'" Ha sido Registrado con Exito<BR>
							<a href="crear_producto.php?codigo='.$url.'"><strong>Seguir Editando</strong></a>','verde');	
						
						
						
						
						}
						else{
									echo mensajes('El Producto "'.$nombre.' '.$codigo.'" Ya se Encuentra Registrado"','rojo');
								}
							}
					}
				?>
                <table class="table table-bordered">
                  <tr>
                    <td>
                   	  <form name="form1" enctype="multipart/form-data" method="post" action="">
                        <div align="center"><pre><strong>Informacion Basica</strong></pre></div>
                        <div class="row-fluid">
                          <div class="span6" align="center">
                          	<strong>Codigo de Registro</strong><br>
                            <input type="text" name="codigo" <?php if($existe==TRUE){ echo 'readonly';}else{ echo 'required'; } ?>  class="input-xlarge" autocomplete="off" value="<?php echo $codigo; ?>"><br>
                          </div>
                          <div class="span6" align="center">
                          	<strong>Nombre del Producto</strong><br>
                            <input type="text" name="nombre" class="input-xxlarge" autocomplete="off" required value="<?php echo $nombre; ?>"><br>
                          </div>
                        </div>
                        
                   		<div class="row-fluid">
                          <div class="span6" align="center">
                            <strong>Departamento</strong><br>
							<select name="depart" class="input-xlarge">
                                	<?php
										$pa=$conexion->query("SELECT * FROM departamento WHERE estado='s'");				
                    					while($row=$pa->fetch_array()){
											if($row['id']==$depart){
												echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].'</option>';	
											}else{
												echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';	
											}
										}
									?>
                              </select>
                            </div>
                            <div class="span6" align="center">
                            	<strong>Unidad</strong><br>
                                <select name="unidad" class="input-xlarge">
                                	<?php
										$pa=$conexion->query("SELECT * FROM unidad WHERE estado='s'");				
                    					while($row=$pa->fetch_array()){
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
										$pa=$conexion->query("SELECT * FROM iva WHERE estado='s'");				
                    					while($row=$pa->fetch_array()){
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
										$pa=$conexion->query("SELECT * FROM iva WHERE estado='s'");				
                    					while($row=$pa->fetch_array()){
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
                        </div>-->
                        
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
                            <td><center><strong>Al menudeo</strong></center></td>
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
                            <td><center><strong>Por Mayor</strong></center></td>
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
                            <td><center><strong>Especial</strong></center></td>
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
                            <td><center><strong>Otros</strong></center></td>
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
                          <!--<tr>
                            <td colspan="4">
                            	<div align="center">
                                	<strong>Fotografia</strong><br>
	                                <input type="file" name="imagen"><br>
                                </div>
                            </td>
                          </tr>-->
                        </table>
                        <br>
                        <div class="form-actions" align="center">
                          <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <strong><?php echo $boton; ?></strong></button>
                          <button type="reset" class="btn"><i class="icon-remove"></i> <strong>Cancelar</strong></button>
                        </div>
                        </form>
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
