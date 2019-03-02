<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO venta(idOrden, idEmpleado, idExportador, idDestExp, fecha, hora, total, observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

	$stmt->bindValue(1, $_POST['cbo_orden']);
	$stmt->bindValue(2, $_SESSION['id']);
	$stmt->bindValue(3, $_POST['cbo_exportador']);
	$stmt->bindValue(4, $_POST['cbo_destino']);
	$stmt->bindValue(5, date('Y-m-d'));
	$stmt->bindValue(6, date('H:i:s'));
	$stmt->bindValue(7, $_POST['total']);
	$stmt->bindValue(8, ((empty($_POST['txt_observacion'])) ? null : $_POST['txt_observacion']));

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

	$stmt = $con->prepare("UPDATE venta SET estado=0 WHERE idVenta=? AND estado=1");

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

	$stmt = $con->prepare("SELECT * FROM venta WHERE idOrden=:p");
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

	$stmt = $con->prepare("SELECT * FROM venta");
	$stmt->execute();
	$compras = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($compras);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idVenta', 'idOrden', 'empleado', 'exportador', 'destino', 'fecha', 'hora', 'total', 'observacion', 'estado'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM vista_venta WHERE idVenta LIKE :p OR idOrden LIKE :p OR empleado LIKE :p OR exportador LIKE :p OR destino LIKE :p OR fecha LIKE :p OR total LIKE :p OR observacion LIKE :p";
    if (!empty($order)) $sql .= ' ORDER BY '.$columnas[$order[0]['column']].' '.$order[0]['dir'];
    if (!$todos) $sql .= " LIMIT $start, $length";

	$stmt = $con->prepare($sql);
	$stmt->bindValue(':p', "%$search%");
	$stmt->execute();
	$ventas = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $ventas;
}

function obtenerDatos() {
	global $con;

    $draw = $_POST['draw'];
	$ventas = filtrarRegistros();

	$filas = null;
	foreach ($ventas as $venta) 
	{
		$fila = null;
		$fila[] = intval($venta->idVenta);
		$fila[] = $venta->idOrden;
		$fila[] = $venta->empleado;
		$fila[] = $venta->exportador;
		$fila[] = $venta->destino;
		$fila[] = $venta->fecha;
		$fila[] = $venta->hora;
		$fila[] = $venta->total;
		$fila[] = $venta->observacion;
		$fila[] = intval($venta->estado);
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