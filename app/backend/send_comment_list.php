<?php
ini_set('display_errors',1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true ');
try {
	//print_r(file_get_contents('php://input'));
	//print_r($_POST);
	$tag = $_POST["tag"];
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
	$prepare = $dbh->prepare('select * from comment_list where tag = ?');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
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
