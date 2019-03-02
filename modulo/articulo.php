<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO articulo(nombreArticulo, precio, cantidad) VALUES (:nombreArticulo, :precio, :cantidad)");
	$stmt->bindValue(':nombreArticulo', $_POST['txt_nombre']);
	$stmt->bindValue(':precio', $_POST['txt_precio']);
	$stmt->bindValue(':cantidad', $_POST['txt_cantidad']);

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

	$stmt = $con->prepare("UPDATE articulo SET nombreArticulo=:nombreArticulo, precio=:precio, cantidad=:cantidad WHERE idArt=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->bindValue(':nombreArticulo', $_POST['txt_nombre']);
	$stmt->bindValue(':precio', $_POST['txt_precio']);
	$stmt->bindValue(':cantidad', $_POST['txt_cantidad']);
	
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

	$stmt = $con->prepare("SELECT * FROM articulo WHERE idArt=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$cargo = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($cargo);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM articulo");
	$stmt->execute();
	$cargos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($cargos);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idArt', 'nombreArticulo', 'precio', 'cantidad'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM articulo WHERE idArt=:p OR nombreArticulo LIKE :p";
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
	$articulos = filtrarRegistros();

	$filas = null;
	foreach ($articulos as $articulo) 
	{
		$fila = null;
		$fila[] = intval($articulo->idArt);
		$fila[] = $articulo->nombreArticulo;
		$fila[] = floatval($articulo->precio);
		$fila[] = floatval($articulo->cantidad);
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