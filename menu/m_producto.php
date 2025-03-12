<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#" style="color:#FFF">L.F.L Software</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="../../Principal.php" style="color:#FFF">Principal</a></li>
              <li class="dropdown">
              	<a href="#" id="drop2" style="color:#FFF" role="button" class="dropdown-toggle" data-toggle="dropdown">
                	Producto <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
                	<li role="presentation"><a role="menuitem" tabindex="-1" href="crear_producto.php">
                    	<i class="icon-plus"></i> Crear Producto</a>
                    </li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="listado_producto.php">
                    	<i class="icon-list"></i> Listado Producto</a>
                    </li>
                </ul>
              </li> 
              <li><a href="listado_proveedor.php" style="color:#FFF">Proveedores</a></li>
            </ul>
            <ul class="nav pull-right">
                <li class="dropdown">
              		<a href="#" style="color:#FFF" class="dropdown-toggle" data-toggle="dropdown">
                    	Hola! <?php echo @$_SESSION['user_name']; ?> <b class="caret"></b>
                    </a>
                	<ul class="dropdown-menu">
	                    <li><a href="../../perfil.php"><i class="icon-user"></i> Mi Perfil</a></li>
                      	<li class="divider"></li>
                      	<li><a href="../../php_cerrar.php"><i class="icon-off"></i> Salir</a></li>
                    </ul>
                </li>
          	</ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<!-- /container -->