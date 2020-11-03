<?php
ini_set('display_errors',1);
try {
	$dbh = new PDO(
	'mysql:host=localhost;dbname=ogawandroid;charset=utf8;',
	#'mysql:host=54.238.75.103;dbname=ogawandroid;charset=utf8;',
	'ogawandroid',
	'password',
	array(
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_EMULATE_PREPARES => false,
	)
	);
	$prepare = $dbh->prepare('select * from player;');
	$prepare->execute();
	$result = $prepare->fetchAll(PDO::FETCH_BOTH);
	echo json_encode($result);
} catch (PDOException $e) {
	$error = $e->getMessage();
	die("pdo接続に失敗<br>$error");
} catch (Exception $e){
	$error = $e->getMessage();
	die("接続に失敗<br>$error");
}

?>
