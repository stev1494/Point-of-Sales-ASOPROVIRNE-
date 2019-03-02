<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO compra(idEmpleado, idProductor, fecha, hora, idArt, precio, cantidad, total, observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

	$stmt->bindValue(1, $_SESSION['id']);
	$stmt->bindValue(2, $_POST['cbo_productor']);
	$stmt->bindValue(3, date('Y-m-d'));
	$stmt->bindValue(4, date('H:i:s'));
	$stmt->bindValue(5, $_POST['cbo_articulo']);
	$stmt->bindValue(6, $_POST['txt_precio']);
	$stmt->bindValue(7, $_POST['txt_cantidad']);
	$stmt->bindValue(8, $_POST['txt_total']);
	$stmt->bindValue(9, ((empty($_POST['txt_observacion'])) ? null : $_POST['txt_observacion']));

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

function anular() {
	global $con;

	$stmt = $con->prepare("UPDATE compra SET estado=0 WHERE idComCab=? AND estado=1");

	$stmt->bindValue(1, $_POST['id']);
	
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

	$stmt = $con->prepare("SELECT * FROM vista_compra WHERE idComCab=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$compra = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($compra);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM compra");
	$stmt->execute();
	$compras = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($compras);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idComCab', 'empleado', 'productor', 'fecha', 'hora', 'nombreArticulo', 'precio', 'cantidad', 'total', 'observacion', 'estado'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM vista_compra WHERE idComCab LIKE :p OR empleado LIKE :p OR productor LIKE :p OR fecha LIKE :p OR idArt LIKE :p OR nombreArticulo LIKE :p OR precio LIKE :p OR cantidad LIKE :p OR total LIKE :p OR observacion LIKE :p";
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
	$compras = filtrarRegistros();

	$filas = null;
	foreach ($compras as $compra) 
	{
		$fila = null;
		$fila[] = intval($compra->idComCab);
		$fila[] = $compra->empleado;
		$fila[] = $compra->productor;
		$fila[] = $compra->fecha;
		$fila[] = $compra->hora;
		$fila[] = $compra->nombreArticulo;
		$fila[] = floatval($compra->precio);
		$fila[] = floatval($compra->cantidad);
		$fila[] = floatval($compra->total);
		$fila[] = $compra->observacion;
		$fila[] = intval($compra->estado);
		$fila[] = $compra->idEmpleado;
		$fila[] = $compra->idProductor;
		$fila[] = $compra->idArt;
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

    case 'anular':
        anular();
        break;   

    default:
        noEncontrado();
        break;
}