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
	$prepare = $dbh->prepare('insert into urlrequest(id,url,tag)values(1,?,?)');
	$url=$_POST['urlrequest'];
	$tag=$_POST['tag'];
	$f=1;
	$youtube_link_prefix='https://www.youtube.com/watch?v=';
	if(strlen($url)<32)$f=0;
	else{
		for($i=0;$i<32;$i++){
			if($url[$i]!=$youtube_link_prefix[$i])$f=0;
		}
	}
	$fp = @fopen($url, 'r');
	if ($fp&&$f==1) {
    		fclose($fp);
		$prepare->bindValue(1,$url,PDO::PARAM_STR);
		$prepare->bindValue(2,$tag,PDO::PARAM_STR);
                $prepare->execute();
		$result = $prepare->fetchAll(PDO::FETCH_BOTH);
	}
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}

?>
