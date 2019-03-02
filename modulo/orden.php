<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO orden_despacho_cabecera(idEmpleado, fecha, hora, observacion) VALUES (?, ?, ?, ?)");

	$stmt->bindValue(1, $_SESSION['id']);
	$stmt->bindValue(2, date('Y-m-d'));
	$stmt->bindValue(3, date('H:i:s'));
	$stmt->bindValue(4, ((empty($_POST['txt_observacion'])) ? null : $_POST['txt_observacion']));

	if ($stmt->execute()) {

		$idOrden = $con->lastInsertId();
		$articulos = $_POST['articulos'];
		$precios = $_POST['precios'];
		$cantidades = $_POST['cantidades'];

		$stmt = $con->prepare("INSERT INTO orden_despacho_detalle(idOrden, idArt, precio, cantidad) VALUES (?, ?, ?, ?)");

		foreach ($articulos as $key => $id) {
			$stmt->bindValue(1, $idOrden);
			$stmt->bindValue(2, $id);
			$stmt->bindValue(3, $precios[$key]);
			$stmt->bindValue(4, $cantidades[$key]);
			$stmt->execute();
		}

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

function confirmar() {
	global $con;

	$stmt = $con->prepare("UPDATE orden_despacho_cabecera SET estado=1 WHERE idOrden=? AND estado=2");

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

function pendiente() {
	global $con;

	$stmt = $con->prepare("UPDATE orden_despacho_cabecera SET estado=2 WHERE idOrden=? AND estado=1");

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

function anular() {
	global $con;

	$stmt = $con->prepare("UPDATE orden_despacho_cabecera SET estado=0 WHERE idOrden=? AND estado=2");

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

	$stmt = $con->prepare("SELECT * FROM vista_orden WHERE idOrden=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$orden['cabecera'] = $stmt->fetch(PDO::FETCH_OBJ);

	$stmt = $con->prepare("SELECT * FROM vista_orden_detalle WHERE idOrden=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$orden['detalle'] = $stmt->fetchAll(PDO::FETCH_OBJ);

	echo json_encode($orden);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM orden_despacho_cabecera");
	$stmt->execute();
	$compras = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($compras);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idOrden', 'empleado', 'fecha', 'hora', 'observacion', 'estado'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM vista_orden WHERE idOrden LIKE :p OR empleado LIKE :p OR fecha LIKE :p OR observacion LIKE :p";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$ordenes = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $ordenes;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$ordenes = filtrarRegistros();

	$filas = null;
	foreach ($ordenes as $orden) 
	{
		$fila = null;
		$fila[] = intval($orden->idOrden);
		$fila[] = $orden->empleado;
		$fila[] = $orden->fecha;
		$fila[] = $orden->hora;
		$fila[] = $orden->observacion;
		$fila[] = intval($orden->estado);
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

    case 'confirmar':
        confirmar();
        break;

    case 'pendiente':
        pendiente();
        break;

    case 'anular':
        anular();
        break;        

    default:
        noEncontrado();
        break;
}