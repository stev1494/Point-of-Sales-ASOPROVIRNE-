<?php
session_start();

if (!isset($_SESSION['conectado'])) header('location: ./..');

require_once ("./../config/db.php");
require_once ("./../config/conexion.php");

function totalRegistros() {
	global $con;

	$stmt = $con->prepare("SELECT * FROM vista_empleado_cargo WHERE idCargo= 20");
	$stmt->execute();
	$cargos = $stmt->fetchAll(PDO::FETCH_OBJ);

	return count($cargos);
}

function filtrarRegistros($todos = false) {
	global $con;

    $columnas = ['idEmpleado', 'nombre', 'apellido', 'cedula', 'fechaNacimiento', 'estadoCivil', 'genero', 'nomCargo', 'sueldo'];
    $order = isset($_POST['order']) ? $_POST['order'] : "";

    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    $sql = "SELECT * FROM vista_empleado_cargo WHERE genero = 'masculino' AND ( idEmpleado=:p OR nombre LIKE :p OR apellido LIKE :p OR fechaNacimiento LIKE :p OR estadoCivil LIKE :p OR genero LIKE :p OR cedula LIKE :p OR idCargo LIKE :p OR nomCargo LIKE :p OR sueldo LIKE :p )";
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
		$fila[] = intval($empleado->idEmpleado);
		$fila[] = $empleado->nombre;
		$fila[] = $empleado->apellido;
		$fila[] = $empleado->fechaNacimiento;
		$fila[] = $empleado->estadoCivil;
		$fila[] = $empleado->genero;
		$fila[] = $empleado->cedula;
		$fila[] = $empleado->nomCargo;
		$fila[] = $empleado->sueldo;
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