<?php

require 'config.php';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
	$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	$pdo = new PDO($dsn, $user, $password, $options);

	/*if ($pdo) {
		echo "Connected to the $db database successfully!";
	}*/
} catch (PDOException $e) {
	echo $e->getMessage();
}