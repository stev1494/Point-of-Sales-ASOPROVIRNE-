<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO destino_exporta(destino) VALUES (:destino)");
	$stmt->bindValue(':destino', $_POST['txt_destino']);

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

	$stmt = $con->prepare("UPDATE destino_exporta SET destino=:destino WHERE idDestExp=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->bindValue(':destino', $_POST['txt_destino']);
	
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

	$stmt = $con->prepare("SELECT * FROM destino_exporta WHERE idDestExp=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$destino_exporta = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($destino_exporta);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM destino_exporta");
	$stmt->execute();
	$destino_exportas = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($destino_exportas);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idDestExp', 'destino'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM destino_exporta WHERE idDestExp=:p OR destino LIKE :p";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$destino_exportas = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $destino_exportas;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$destinos = filtrarRegistros();

	$filas = null;
	foreach ($destinos as $destino) 
	{
		$fila = null;
		$fila[] = intval($destino->idDestExp);
		$fila[] = $destino->destino;
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