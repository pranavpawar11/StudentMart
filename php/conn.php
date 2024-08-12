<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "studentmart";
	$local_mode = true;

	try {
		$pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		// Set PDO error mode to exception
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}
?>