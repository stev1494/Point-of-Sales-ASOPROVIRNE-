<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM venta");
	$stmt->execute();
	$idVenta = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($idVenta);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idVenta', 'idExportador', 'fecha', 'total'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM venta where fecha between '2018-07-31' and '2018-08-15' ";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$idVenta = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $idVenta;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$idVenta = filtrarRegistros();

	$filas = null;
	foreach ($idVenta as $idVenta) 
	{
		$fila = null;
		$fila[] = intval($idVenta->idVenta);
		$fila[] = $idVenta->idExportador;
		$fila[] = ($idVenta->fecha);
		$fila[] = floatval($idVenta->total);
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

    default:
        noEncontrado();
        break;
}