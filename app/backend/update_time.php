<?php
header('Access-Control-Allow-Origin: null');
header('Access-Control-Allow-Credentials: true ');
ini_set('display_errors',1);
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
	$tag = $_POST['tag'];
        $prepare = $dbh->prepare('update choiced_url set time = ? where tag = ?');
	$prepare->bindValue(1,time(),PDO::PARAM_STR); 
	$prepare->bindValue(2,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	echo(json_encode($result));
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}

?>
