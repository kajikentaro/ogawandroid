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
/*
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
 */
	$client_url = $_POST['current_url'];
	$tag = $_POST['tag'];
        $prepare = $dbh->prepare('select * from choiced_url where tag = ?;');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	//echo $result[0]['url'];
	if($result[0]['url'] != $client_url){
	    	echo json_encode(array("URL has been updated"=>"status ok other"));
		exit();
	}
        $prepare = $dbh->prepare('select * from urlrequest where tag = ?;');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	$row_cnt = count($result);
	//$idx_rnd = (int)$input;
	$idx_rnd = rand();
	$choiced_url = "";
	if($row_cnt==0){
	    	$choiced_url = getDefaultURL($tag);
		//print_r($choiced_url);
	}else{
		//$test = array("url" =>$result[$idx_rnd%$row_cnt]['url']);
		$choiced_url =$result[$idx_rnd%$row_cnt]['url'];
		//print_r($test);
	}
	require("./delete_url.php");
        $prepare = $dbh->prepare('update choiced_url set url = ? ,time = ? where tag=?');
	$prepare->bindValue(1,$choiced_url,PDO::PARAM_STR); 
	$prepare->bindValue(2,time()+10,PDO::PARAM_STR); 
	$prepare->bindValue(3,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	echo json_encode(array("URL has been updated"=>"status ok myself"));
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}
function getDefaultURL($tag){
        $dbh = new PDO(
        'mysql:host=localhost;dbname=ogawandroid;charset=utf8;',
        'ogawandroid',
        'password',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
        );
        $prepare = $dbh->prepare('select * from default_video where tag = ?');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	return $result[0]['url'];
}

?>
