<?php
ini_set('display_errors',1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true ');
try {
	$dbh = new PDO(
	'mysql:host=localhost;dbname=ogawandroid;charset=utf8;',
	'ogawandroid',
	'password',
	array(
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_EMULATE_PREPARES => false,
	)
	);
	if($_POST['time'] == "-1"){
		$prepare = $dbh->prepare('select * from time_keeper where tag=?');
		$prepare->bindValue(1,$_POST['tag'],PDO::PARAM_STR); 
		$prepare->execute();
		$result = $prepare->fetchAll(PDO::FETCH_BOTH);
		echo json_encode($result);
	}else{
		$prepare = $dbh->prepare('update time_keeper set time=? where tag=?');
		$prepare->bindValue(1,$_POST['time'],PDO::PARAM_STR); 
		$prepare->bindValue(2,$_POST['tag'],PDO::PARAM_STR); 
		$prepare->execute();
		//$result = $prepare->fetchAll(PDO::FETCH_BOTH);
		//echo json_encode($result);
		echo json_encode(array("time status:" => "channel ".$_POST['tag']." set ".$_POST['time']." sec"));
	}
} catch (PDOException $e) {
	$error = $e->getMessage();
	die("pdo接続に失敗<br>$error");
} catch (Exception $e){
	$error = $e->getMessage();
	die("接続に失敗<br>$error");
}

?>
