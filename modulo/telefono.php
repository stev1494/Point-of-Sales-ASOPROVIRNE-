<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO telefono(idEmpleado, numeroTelefono) VALUES (:idEmpleado, :numeroTelefono)");
	$stmt->bindValue(':idEmpleado', $_POST['idEmpleadoTelefono']);
	$stmt->bindValue(':numeroTelefono', $_POST['txt_numero']);

	if ($stmt->execute()) {
		$resultado = true;
	} else {
		$resultado = false;
	}

	$data =
	[
		'resultado' => $resultado
	];

	echo json_encode($data);
}

function editar() {
	global $con;

	$stmt = $con->prepare("UPDATE telefono SET numeroTelefono=:numeroTelefono WHERE idTelefono=:p");
	$stmt->bindValue(':p', $_POST['idTelefono']);
	$stmt->bindValue(':numeroTelefono', $_POST['txt_numero']);
	
	if ($stmt->execute()) {
		$resultado = true;
	} else {
		$resultado = false;
	}

	$data =
	[
		'resultado' => $resultado
	];

	echo json_encode($data);
}

function eliminar() {
	global $con;

	$stmt = $con->prepare("DELETE FROM telefono WHERE idTelefono=?");
	$stmt->bindParam(1, $_POST['idTelefono']);

	if ($stmt->execute()) {
		$resultado = true;
	} else {
		$resultado = false;
	}

	$data =
	[
		'resultado' => $resultado
	];

	echo json_encode($data);
}

function obtener() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM telefono WHERE idEmpleado=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$cargo = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($cargo);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM telefono");
	$stmt->execute();
	$telefonos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($telefonos);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idTelefono', 'numeroTelefono'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $idEmpleado = $_POST['idEmpleadoTelefono'];

    $sql = "SELECT idTelefono, numeroTelefono FROM telefono WHERE idEmpleado=:id";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':id', $idEmpleado);
	$stmt->execute();
	$telefonos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $telefonos;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$telefonos = filtrarRegistros();

	$filas = null;
	foreach ($telefonos as $telefono) 
	{
		$fila = null;
		$fila[] = intval($telefono->idTelefono);
		$fila[] = $telefono->numeroTelefono;
		$filas[] = $fila;
	}

	$data = 
	[
		"draw" => intval($draw),
		"recordsTotal" => intval(totalRegistros()),
		"recordsFiltered" => intval(count(filtrarRegistros(true))),
		"data" => is_null($filas) ? [] : $filas
	];

	echo json_encode($data);
}

function noEncontrado() {
	$data =
	[
		'error' => 'metodo no encontrado'
	];
	echo json_encode($data);
}

$metodo = isset($_GET['metodo']) ? $_GET['metodo'] : '';

switch ($metodo) {
    case 'listado':
        obtenerDatos();
        break;
    
    case 'guardar':
        guardar();
        break;

    case 'obtener':
        obtener();
        break;

    case 'editar':
        editar();
        break;

    case 'eliminar':
        eliminar();
        break;

    default:
        noEncontrado();
        break;
}