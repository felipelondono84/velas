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
    <title>Compras</title>
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
                        		<h2><img src="../../img/logo.png" width="100" height="100"> Realizar Compras</h2>    
                            </div>
                            <div class="span6" align="center">
                            
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
                            	<?php }else{ ?><br>
                                    <a href="#m" role="button" class="btn" data-toggle="modal">
                                        <strong>Ingresar Nueva Compra</strong>
                                    </a>
                                    <a href="compra.php" class="btn">
                                    	<strong>Cancelar</strong>
                                    </a>
                                <?php } ?>
                                <a href="consultar_compra.php" class="btn">
                                	<strong><i class="icon-list"></i> Listado de Compras</strong>
                                </a>
                                
                            </div>
                    	</div>
                        
                        <div id="m" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        	<form name="form2" action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Informacion de la Compra</h3>
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
                                </datalist>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                <button type="submit" class="btn btn-primary"><strong>Registrar</strong></button>
                            </div>
                            </form>
                        </div>
                        
                    </td>
                  </tr>
                </table>
                <h3 align="center"><?php echo $nombre_bodega; ?></h3>
                <?php 
					if(!empty($_POST['articulo'])){ 
						$producto=limpiar($_POST['articulo']);
						$pa=mysql_query("SELECT * FROM producto WHERE codigo='$producto' or nombre='$producto'");	
						if($row=mysql_fetch_array($pa)){
							$oIVAc=new Consultar_IVA($row['ivacompra']);
							$IVAc=($oIVAc->consultar('valor')/100)+1;
							$producto=$row['codigo'];	
							$total=$row['costo_prov']+$row['ocosto_prov'];
							$total_iva=$total*$IVAc;
							
							$oDepartamento=new Consultar_Departamento($row['depart']);
							$oSistema=new Consultar_Sistema($row['unidad']);
							$depasis=$oDepartamento->consultar('nombre').' / '.$oSistema->consultar('nombre');
						
				?>
                <table class="table table-bordered">
                  <tr>
                    <td>
                        <div class="row-fluid">
                            <div class="span6">
                                <i class="icon-ok"></i> <strong>Codigo del Producto:</strong> <?php echo $row['codigo']; ?><br>
                                <i class="icon-ok"></i> <strong>Nombre del Producto:</strong> <?php echo $row['nombre']; ?><br>
                                <i class="icon-ok"></i> <strong>Departamente / Sistema de Medida: </strong><?php echo $depasis; ?><br>
                                <i class="icon-ok"></i> <strong>Costo Compra:</strong> $ <?php echo formato($row['costo_prov']); ?><br>
                                <i class="icon-ok"></i> <strong>Otros Costos:</strong> $ <?php echo formato($row['ocosto_prov']); ?><br>
                                <i class="icon-ok"></i> <strong>Total Costo:</strong> $ <?php echo formato($total); ?><br>
                                <span class="text-success">
                                <i class="icon-ok"></i> <strong>Total Costo + IVA: $ <?php echo formato($total_iva); ?></strong><br>
                                </span>
                            </div>
                            <div class="span6">
                                <form name="form22" action="" method="post">
                                	<input type="hidden" name="producto" value="<?php echo $row['codigo']; ?>">
                                    <input type="hidden" name="valor" value="<?php echo formato($row['costo_prov']); ?>">
                                    <input type="hidden" name="NP" value="<?php echo $row['nombre']; ?>">
                                    <strong>Proveedor</strong><br>
                                    <select name="prov">
                                        <?php
                                            $paa=mysql_query("SELECT * FROM pro_prov WHERE producto='$producto'");	
                                            while($roww=mysql_fetch_array($paa)){
                                                $oProveedor=new Consultar_Proveedor($roww['proveedor']);
                                                echo '<option value="'.$roww['proveedor'].'">'.$oProveedor->consultar('nombre').'</option>';
                                            }
                                        ?>
                                    </select><br>
                                    <strong>Cantidad de Compra</strong><br>
                                    <input type="number" name="cant" min="1" value="1" autocomplete="off" required><br>
                                    <button type="submit" class="btn"><strong>Procesar Compra y Actualizar Inventario</strong></button>
                                </form>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php }else{ echo mensajes("El Codigo o Nombre del Articulo no Existe","rojo");  }} ?>
                <?php 
					if(!empty($_POST['prov'])){
						$prov=limpiar($_POST['prov']);		$producto=limpiar($_POST['producto']);		$NP=limpiar($_POST['NP']);
						$cant=limpiar($_POST['cant']);		$valor=limpiar($_POST['valor'])*$cant;		$fecha=date('Y-m-d');
						
						$oCompra=new Proceso_Compra('',$producto,$prov,$id_bodega,$cant,$valor,'',$usu,$fecha);
						$oCompra->crear();
						
						$paa=mysql_query("SELECT * FROM contenido WHERE producto='$producto' and deposito='$id_bodega'");	
                        if($roww=mysql_fetch_array($paa)){
							$new_cant=$roww['cant']+$cant;
							mysql_query("UPDATE contenido SET cant='$new_cant' WHERE producto='$producto' and deposito='$id_bodega'");
						}
						
						echo mensajes('La Compra de x'.$cant.' '.$NP.' Se ha Registrado con Exito en el Deposito '.$nombre_bodega.'<br>
						<a href="consultar_compra.php">Consultar Compras</a>','verde');
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
