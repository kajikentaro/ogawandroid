<?php
header('Access-Control-Allow-Origin: null');
header('Access-Control-Allow-Credentials: true ');

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

	$input = $_POST['idx_rnd'];
        $prepare = $dbh->prepare('select * from urlrequest;');
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	$row_cnt = count($result);
	$idx_rnd = (int)$input;

	if($row_cnt==0){
		$test = array("url" =>('https://www.youtube.com/watch?v=WJzSBLCaKc8'));

		print_r($test);
	}else{
		$test = array("url" =>$result[$idx_rnd%$row_cnt]['url']);
		print_r($test);
	}
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}

?>
