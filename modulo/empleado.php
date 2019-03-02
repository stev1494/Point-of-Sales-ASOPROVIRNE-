<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function guardar() {
	global $con;

	$stmt = $con->prepare("INSERT INTO empleado(nombre, apellido, fechaNacimiento, estadoCivil, genero, idCargo, cedula) VALUES (:nombre, :apellido, :fechaNacimiento, :estadoCivil, :genero, :idCargo, :cedula)");
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':apellido', $_POST['txt_apellido']);
	$stmt->bindValue(':fechaNacimiento', $_POST['txt_fec_nac']);
	$stmt->bindValue(':estadoCivil', $_POST['cbo_estado_civil']);	
	$stmt->bindValue(':genero', $_POST['cbo_genero']);
	$stmt->bindValue(':idCargo', $_POST['cbo_cargo']);
	$stmt->bindValue(':cedula', $_POST['txt_cedula']);

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

	$stmt = $con->prepare("UPDATE empleado SET nombre=:nombre, apellido=:apellido, fechaNacimiento=:fechaNacimiento, estadoCivil=:estadoCivil, genero=:genero, idCargo=:idCargo, cedula=:cedula WHERE idEmpleado=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->bindValue(':nombre', $_POST['txt_nombre']);
	$stmt->bindValue(':apellido', $_POST['txt_apellido']);
	$stmt->bindValue(':fechaNacimiento', $_POST['txt_fec_nac']);
	$stmt->bindValue(':estadoCivil', $_POST['cbo_est_civil']);	
	$stmt->bindValue(':genero', $_POST['cbo_genero']);
	$stmt->bindValue(':idCargo', $_POST['cbo_cargo']);
	$stmt->bindValue(':cedula', $_POST['txt_cedula']);
	
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

	$stmt = $con->prepare("SELECT nombre, apellido, fechaNacimiento, estadoCivil, genero, idCargo, cedula FROM empleado WHERE idEmpleado=:p");
	$stmt->bindValue(':p', $_POST['id']);
	$stmt->execute();
	$empleado = $stmt->fetch(PDO::FETCH_OBJ);

	echo json_encode($empleado);
}

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM empleado");
	$stmt->execute();
	$empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($empleados);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idEmpleado', 'nombre', 'apellido', 'cedula', 'fechaNacimiento', 'estadoCivil', 'genero', 'nomCargo'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM vista_empleado_cargo WHERE idEmpleado=:p OR nombre LIKE :p OR apellido LIKE :p OR fechaNacimiento LIKE :p OR estadoCivil LIKE :p OR genero LIKE ':p' OR cedula LIKE :p OR idCargo LIKE :p OR nomCargo LIKE :p";
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
	$empleados = filtrarRegistros();

	$filas = null;
	foreach ($empleados as $empleado) 
	{
		$fila = null;
		$fila[] = intval($empleado->idEmpleado);
		$fila[] = $empleado->nombre;
		$fila[] = $empleado->apellido;
		$fila[] = $empleado->fechaNacimiento;
		$fila[] = $empleado->estadoCivil;
		$fila[] = $empleado->genero;
		$fila[] = $empleado->cedula;
		$fila[] = $empleado->nomCargo;
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