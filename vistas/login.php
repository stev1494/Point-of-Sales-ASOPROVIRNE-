<?php
if (!isset($error)) header('location: ./..');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ingreso | ASOPROVIRNE</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="assets/adminlte/css/skins/_all-skins.min.css">
    <!-- jQuery 3 -->
    <script src="assets/jquery/dist/jquery.min.js"></script>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style type="text/css">
		.login-page #back{
			position: absolute;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background: url(assets/img/fondo.jpg);
			background-size: cover;
			overflow: hidden;
			z-index: -1;
		}
		.login-page #error {
			margin-top: 15px;
		}
    </style>
</head>
<body class="hold-transition login-page">

    <div id="back"></div>
    <div class="login-box">
        <div class="login-logo" id="logo">
            <img src="assets/img/logo.jpg" class="img-responsive img-thumbnail">
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">ACCESO AL SISTEMA</p>

            <form method="POST">
                <div class="form-group has-feedback">
                    <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Cedula" pattern="\d+" minlength="10" maxlength="10" required="" autofocus>
                    <span class="form-control-feedback"><i class="fa fa-address-card" aria-hidden="true"></i></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required="">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-md btn-block">ENTRAR</button>
                    </div>
                    <!-- /.col -->
                </div>
                <?php if($error): ?>
                    <div class="row" id="error">
                        <div class="col-xs-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                                <p>CREDENCIALES INCORRECTAS</p>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

	<!-- Bootstrap 3.3.7 -->
	<script type="text/javascript" src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- FastClick -->
	<script type="text/javascript" src="assets/fastclick/lib/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script type="text/javascript" src="assets/adminlte/js/adminlte.min.js"></script>

</body>
</html>