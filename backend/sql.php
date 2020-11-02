<?php
echo("aaa");
try {
	$dbh = new PDO(
	'mysql:host=localhost;dbname=player;charset=utf8;',
	'youtubesync',
	'password',
	array(
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_EMULATE_PREPARES => false,
	)
	);
	echo("SQLサービス接続成功");
	$prepare = $dbh->prepare('show tables;');
	$prepare->execute();
	$result = $prepare->fetch(PDO::FETCH_BOTH);
	print_r($result);
	echo("接続終了");
} catch (PDOException $e) {
	$error = $e->getMessage();
	die("接続に失敗<br>$error");
}
?>
