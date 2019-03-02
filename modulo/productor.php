<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO productor(cedula, nombre, apellido) VALUES (:cedula, :nombre, :apellido)");
	$stmt->bindValue(':cedula', $_POST['txt_cedula']);
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':apellido', $_POST['txt_apellido']);

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

	$stmt = $con->prepare("UPDATE productor SET cedula=:cedula, nombre=:nombre, apellido=:apellido WHERE idProductor=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->bindValue(':cedula', $_POST['txt_cedula']);
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':apellido', $_POST['txt_apellido']);
	
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

	$stmt = $con->prepare("SELECT * FROM productor WHERE idProductor=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$empleado = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($empleado);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM productor");
	$stmt->execute();
	$empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($empleados);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idProductor', 'cedula', 'nombre', 'apellido'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM productor WHERE idProductor=:p OR nombre LIKE :p OR apellido LIKE :p OR cedula LIKE :p";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $empleados;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$productores = filtrarRegistros();

	$filas = null;
	foreach ($productores as $productor) 
	{
		$fila = null;
		$fila[] = intval($productor->idProductor);
		$fila[] = $productor->cedula;
		$fila[] = $productor->nombre;
		$fila[] = $productor->apellido;
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

    default:
        noEncontrado();
        break;
}