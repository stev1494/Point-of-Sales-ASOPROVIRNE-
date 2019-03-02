<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO cargo(nombre, descripcion, sueldo) VALUES (:nombre, :descripcion, :sueldo)");
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':descripcion', $_POST['txt_descripcion']);
	$stmt->bindValue(':sueldo', $_POST['txt_sueldo']);

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

	$stmt = $con->prepare("UPDATE cargo SET nombre=:nombre, descripcion=:descripcion, sueldo=:sueldo WHERE idCargo=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':descripcion', $_POST['txt_descripcion']);
	$stmt->bindValue(':sueldo', $_POST['txt_sueldo']);
	
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

	$stmt = $con->prepare("SELECT * FROM cargo WHERE idCargo=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$cargo = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($cargo);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM cargo");
	$stmt->execute();
	$cargos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($cargos);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idCargo', 'nombre', 'descripcion', 'sueldo'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM cargo WHERE idCargo=:p OR nombre LIKE :p OR descripcion LIKE :p OR sueldo LIKE :p";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$cargos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $cargos;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$cargos = filtrarRegistros();

	$filas = null;
	foreach ($cargos as $cargo) 
	{
		$fila = null;
		$fila[] = intval($cargo->idCargo);
		$fila[] = $cargo->nombre;
		$fila[] = $cargo->descripcion;
		$fila[] = $cargo->sueldo;
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