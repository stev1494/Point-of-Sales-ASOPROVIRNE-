<?php
session_start();

if (isset($_SESSION['conectado'])) {
	header('location: base.php?modulo=empleado');
	exit();
}
$error = false;

require_once  ("modulo/login.php");

if (count($_POST) > 0) {

	if (validar($_POST['cedula'], $_POST['password'])) {
		
		$usuario = obtener($_POST['cedula']);
	    $_SESSION['conectado'] = true;
	    $_SESSION['id'] = $usuario->idEmpleado;
	    $_SESSION['nombre'] = $usuario->nombre;
	    $_SESSION['apellido'] = $usuario->apellido;
	    //$_SESSION['inicio'] = time();
	    //$_SESSION['fin'] = $_SESSION['inicio'] + (5 * 60);
		header("location: base.php?modulo=orden");
		exit();
	} else {
		$error = true;
	}

}

include 'vistas/login.php';
?>