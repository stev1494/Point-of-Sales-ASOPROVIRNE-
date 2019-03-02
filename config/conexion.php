<?php



try {
	$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
	$con = new PDO($dsn, DB_USER, DB_PASS);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
}

?>