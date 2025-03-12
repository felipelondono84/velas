<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#" style="color:#FFF">Software POS</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="../../Principal.php" style="color:#FFF">Principal</a></li>
              <li><a href="crear_cliente.php" style="color:#FFF">Crear Cliente</a></li>
              <li><a href="lista_cliente.php" style="color:#FFF">Listado de Clientes</a></li>
			  
            </ul>
            <ul class="nav pull-right">
                <li class="dropdown">
              		<a href="#" style="color:#FFF" class="dropdown-toggle" data-toggle="dropdown">
                    	Hola! <?php echo $_SESSION['user_name']; ?> <b class="caret"></b>
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