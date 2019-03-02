<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: index.php');

require_once ("config/db.php");
require_once ("config/conexion.php");

$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';

switch ($modulo) {
    case 'venta':
        $title = "Venta";
        break;

    case 'orden':
        $title = "Orden";
        break;

    case 'nueva_orden':
        $title = "Nueva Orden";
        break;

    case 'compra':
        $title = "Compra";
        break;

    case 'articulo':
        $title = "Articulo";
        break;

    case 'cargo':
        $title = "Cargo";
        break;

    case 'empleado':
        $title = "Empleado";
        break;

    case 'productor':
        $title = "Productor";
        break;

    case 'exportador':
        $title = "Exportador";
        break;

    case 'destino':
        $title = "Destino";
        break;

    case 'reporte':
        $title = "Reporte";
        break;

    case 'reportedeventas':
        $title = "reportedeventas";
        break;

    case 'salir':
        session_start();
        unset ($SESSION['']);
        session_destroy();
        header('Location: index.php');

    default:
        $title="Pagina No Encontrada";
        break;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?> | ASOPROVIRNE</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="assets/datatables/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="assets/datatables/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatables/css/select.bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatables/css/keyTable.bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="assets/adminlte/css/skins/_all-skins.min.css">
    <!-- jQuery 3 -->
    <script src="assets/jquery/dist/jquery.min.js"></script>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-yellow sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="base.php?modulo=venta" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">ASO</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">ASOPROVIRNE</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i>&nbsp;&nbsp;<?= $_SESSION['nombre'].' '.$_SESSION['apellido']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="base.php?modulo=salir">
                                <i class="fa fa-sign-out"></i>&nbsp;&nbsp;Salir
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU</li>
                    <li class="<?php if($modulo == 'venta') echo 'active'; ?>">
                        <a href="base.php?modulo=venta">
                            <i class="fa fa-money"></i> <span>Venta</span>
                        </a>
                    </li>
                    <li class="<?php if($modulo == 'orden' or $modulo == 'nueva_orden') echo 'active'; ?>">
                        <a href="base.php?modulo=orden">
                            <i class="fa fa-file-text-o"></i> <span>Orden</span>
                        </a>
                    </li>
                    <li class="<?php if($modulo == 'compra') echo 'active'; ?>">
                        <a href="base.php?modulo=compra">
                            <i class="fa fa-shopping-cart"></i> <span>Compra</span>
                        </a>
                    </li> 
                    <li class="header"></li>
                    <li class="<?php if($modulo == 'articulo') echo 'active'; ?>">
                        <a href="base.php?modulo=articulo">
                            <i class="fa fa-cube"></i> <span>Articulo</span>
                        </a>
                    </li> 
                    <li class="header"></li>
                    <li class="<?php if($modulo == 'cargo') echo 'active'; ?>">
                        <a href="base.php?modulo=cargo">
                            <i class="fa fa-users"></i> <span>Cargo</span>
                        </a>
                    </li> 
                    <li class="<?php if($modulo == 'empleado') echo 'active'; ?>">
                        <a href="base.php?modulo=empleado">
                            <i class="fa fa-users"></i> <span>Empleado</span>
                        </a>
                    </li> 
                    <li class="header"></li>
                    <li class="<?php if($modulo == 'productor') echo 'active'; ?>">
                        <a href="base.php?modulo=productor">
                            <i class="fa fa-users"></i> <span>Productor</span>
                        </a>
                    </li> 
                    <li class="<?php if($modulo == 'exportador') echo 'active'; ?>">
                        <a href="base.php?modulo=exportador">
                            <i class="fa fa-plane"></i> <span>Exportador</span>
                        </a>
                    </li> 
                    <li class="<?php if($modulo == 'destino') echo 'active'; ?>">
                        <a href="base.php?modulo=destino">
                            <i class="fa fa-location-arrow"></i> <span>Destino</span>
                        </a>
                    </li> 
                    <li class="header">Reportes</li>
                    <li class="<?php if($modulo == 'reporte') echo 'active'; ?>">
                        <a href="base.php?modulo=reporte">
                            <i class="fa fa-users"></i> <span>Empleados con sueldos <br>de mas de 2000</span>
                        </a>
                    </li> 
                    <li class="<?php if($modulo == 'reportedeventas') echo 'active'; ?>">
                        <a href="base.php?modulo=reportedeventas">
                            <i class="fa fa-users"></i> <span>Reportes ventas en rangos</span>
                        </a>
                    </li> 
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php
            switch ($modulo) {
                case 'venta':
                    include("vistas/venta.php");
                    break;

                case 'orden':
                    include("vistas/orden.php");
                    break;

                case 'nueva_orden':
                    include("vistas/nueva_orden.php");
                    break;

                case 'compra':
                    include("vistas/compra.php");
                    break;                    

                case 'articulo':
                    include("vistas/articulo.php");
                    break;

                case 'cargo':
                    include("vistas/cargo.php");
                    break;

                case 'empleado':
                    include("vistas/empleado.php");
                    break;
                
                case 'productor':
                    include("vistas/productor.php");
                    break;

                case 'exportador':
                    include("vistas/exportador.php");
                    break;

                case 'destino':
                    include("vistas/destino.php");
                    break;

                case 'reporte':
                    include("vistas/reporte.php");
                    break;    

                case 'reportedeventas':
                    include("vistas/reportedeventas.php");
                    break;                 

                default:
                    include("vistas/nofound.php");
                    break;
            }
            ?>

        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> beta
            </div>
            <strong>Copyright &copy; <?php echo date('Y'); ?> - Steven Andrade</strong>
        </footer>

<!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->


<!-- Bootstrap 3.3.7 -->
<script type="text/javascript" src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script type="text/javascript" src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script type="text/javascript" src="assets/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="assets/adminlte/js/adminlte.min.js"></script>
<!-- DataTables -->
<script type="text/javascript" src="assets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/datatables/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="assets/datatables/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="assets/datatables/js/dataTables.keyTable.min.js"></script>
<script type="text/javascript" src="assets/datatables/js/keyTable.bootstrap.min.js"></script>
<script type="text/javascript" src="assets/datatables/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="assets/datatables/js/select.bootstrap.min.js"></script>
<script type="text/javascript" src="assets/jquery.numeric/jquery.numeric.js"></script>
<script>
    $(document).ready(function () {
        $('.sidebar-menu').tree()
    });
</script>
</body>
</html>
