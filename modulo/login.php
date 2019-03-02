<?php

if (!isset($error)) header('location: ./..');

require_once ("config/db.php");
require_once ("config/conexion.php");

function validar($cedula, $password) {
	global $con;

	$stmt = $con->prepare("SELECT password FROM empleado WHERE cedula=:cedula");

	$stmt->bindValue(':cedula', $cedula);
	$stmt->execute();

	$usuario = $stmt->fetch(PDO::FETCH_OBJ);

	if (!$usuario) return false;

	return password_verify($password, $usuario->password);
}

function obtener($cedula) {
	global $con;

	$stmt = $con->prepare("SELECT idEmpleado, nombre, apellido FROM empleado WHERE cedula=:cedula");

	$stmt->bindValue(':cedula', $cedula);
	$stmt->execute();

	return $stmt->fetch(PDO::FETCH_OBJ);
}