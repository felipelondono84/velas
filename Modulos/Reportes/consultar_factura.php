<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
		if(permiso($_SESSION['cod_user'],'6')==FALSE){
			header('Location: ../../error.php');
		}
	}else{
		header('Location: ../../error.php');
	}
	
	if(!empty($_GET['fin']) and !empty($_GET['ini'])){
		$final=limpiar($_GET['fin']);
		$inicio=limpiar($_GET['ini']);
	}else{
		$final=date('Y-m-d');
		$inicio=date('Y-m-d');
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Consultar Facturas</title>
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

    <?php include_once "../../menu/m_reportes.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                	<tr class="well">
                        <td>
                            <h1 align="center">Consultar Facturas</h1>
                            <div class="row-fluid">
	                            <div class="span6">
                                	<form name="form2" action="" method="get">
                               	    <div class="row-fluid">
	                                    <div class="span6" align="center">
                                        	<strong>Fecha Inicio</strong><br>
                                            <input type="date" name="ini" value="<?php echo $inicio; ?>" autocomplete="off" required>
                                        </div>
    	                                <div class="span6" align="center">
                                        	<strong>Fecha Final</strong><br>
                                            <input type="date" name="fin" value="<?php echo $final; ?>" autocomplete="off" required>
                                        </div>
                                        <center>
                                        	<button type="submit" class="btn">
                                            	<i class="icon-calendar"></i><strong> Consultar por Rango de Fechas</strong>
                                        	</button>
                                        </center>
                                    </div>
                                    </form>
                                </div>
                                <form name="form33" action="" method="get" class="form-search">
    	                        <div class="span6" align="center">
                                	<strong>Consultar Factura</strong><br>
                                    <input type="text" name="factura" autocomplete="off" required 
                                    class="input-xlarge search-query" placeholder="Numero de Factura">
                                    <br><br>
                                    <button type="submit" class="btn"><i class="icon-search"></i> <strong>Buscar por No de Factura</strong></button>
                                </div>
                                </form>
                            </div>
                        </td>
 	                </tr>
                </table>
				<?php
                	if(!empty($_POST['estado'])){
						$id_fact=limpiar($_POST['id_factura']);
						$cant=limpiar($_POST['cantidad']);
						$prod=limpiar($_POST['producto']);
						if($_POST['estado']=='s'){
							$conexion->query("UPDATE resumen SET estado='n' WHERE very='$id_fact'");
							
							$conexion->query("UPDATE detalle SET valor='0' WHERE factura='$id_fact'");
							
							$pwa=$conexion->query("SELECT cant FROM contenido WHERE producto='$prod'");				
										       		if($roww=$pwa->fetch_array()){	
														$new_cant=$roww['cant']+$cant;
							$conexion->query("UPDATE contenido SET cant='$new_cant' WHERE producto='$prod'");
													}
						}
						header("Location: consultar_factura.php");
					}
				?>
                <table class="table table-bordered">
                	<tr class="well">
                    	<td width="10%"><strong><center>No. Factura</center></strong></td>
                        <td width="50%"><strong>Descripcion</strong> <br><strong>(Cant. Cod. Ref. Descripcion. Importe)</strong></td>
                        <td width="20%"><strong><center>Cajero/a</center></strong></td>
                        <td width="20%"><strong><center>Deposito</center></strong></td>
                        <td width="5%"><strong>Anular</strong></td>
						<td width="5%"><strong>Imprimir</strong></td>
                    </tr>
                    <?php 
						$ini='';$fin='';
						if(!empty($_GET['factura']) or (!empty($_GET['ini']) and !empty($_GET['fin']))){
							if(!empty($_GET['factura'])){
								$factura=limpiar($_GET['factura']);
								$consultar=mysql_query("SELECT * FROM factura WHERE factura='$factura'");
							}else{
								$ini=limpiar($_GET['ini']);	
								$fin=limpiar($_GET['fin']);	
								$consultar=$conexion->query("SELECT * FROM factura WHERE fecha BETWEEN '$ini' AND '$fin'");
							}
							while($row=$consultar->fetch_array()){
								$oCajero=new Consultar_Cajero($row['usu']);
								$nombre_cajero=$oCajero->consultar('nom').' '.$oCajero->consultar('ape');
								$id_factura=$row['factura'];
								$url=cadenas().encrypt($row['id'],'URLCODIGO');
								
								$consul=$conexion->query("SELECT deposito, estado FROM resumen WHERE very='$id_factura'");
								if($date=$consul->fetch_array()){
									$oDeposito=new Consultar_Deposito($date['deposito']);
									$nombre_deposito=$oDeposito->consultar('nombre');
									
									if($date['estado']=='n'){
										$estado='<span class="label label-important">Anulada</span>';
									}else{
										$estado='';	
									}
								}
                        		$concepto='';
                        		$cantidad='';
                                $producto='';		
                    		$cons=$conexion->query("SELECT * FROM detalle WHERE factura='$id_factura'");
                    		while($info=$cons->fetch_array()){
                    			$importe=$info['valor'];
                    			$concepto=$concepto.''.$info['cant'].' | '.$info['codigo'].' | Ref. '.$info['referencia'].' | '.$info['producto'].' $ '.formato($importe).'<br>';
                    			$cantidad=$info['cant'];
                    			$producto=$info['codigo'];
		}
							
					?>
                    <tr>
                    	<td><center><?php echo $id_factura; ?></center></strong></td>
                        <td><?php echo $concepto; ?></td>
                        <td><center><?php echo $nombre_cajero; ?></center></td>
                        <td><center><?php echo $nombre_deposito; ?></center></td>
                        <td>
                        	<?php if($estado==''){ ?>
                        	<center>
                        	<a href="#m<?php echo $id_factura; ?>" role="button" class="btn btn-mini" data-toggle="modal">
                            	<i class="icon-edit"></i>
                            </a>
                            </center>
                            <?php }else{ echo $estado; } ?>
                            
                            <div id="m<?php echo $id_factura; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            	<form name="forme" action="" method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h3 id="myModalLabel" align="center">Desea Anular Esta Factura</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<strong>Anular?</strong><br>
                                    <input type="hidden" value="<?php echo $id_factura; ?>" name="id_factura">
									<input type="hidden" value="<?php echo $cantidad; ?>" name="cantidad">
									<input type="hidden" value="<?php echo $producto; ?>" name="producto">
                                    <select name="estado">
                                    	<option value="s">SI</option>
                                        <option value="n" selected>NO</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <button type="submit" class="btn btn-primary"><strong>Aceptar</strong></button>
                                </div>
                                </form>
                            </div>
                            
                        </td>
						<td>
						<center>
						<a class="btn btn-mini" href="imprimir.php?id=<?php echo $url; ?>" title="Editar">
                                <i class="icon-print"></i>
                            </a>
							</td>
							</center>
                    </tr>
                    <?php }} ?>
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
