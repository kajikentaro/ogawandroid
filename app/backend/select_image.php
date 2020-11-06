<?php
header('Access-Control-Allow-Origin: null');
header('Access-Control-Allow-Credentials: true ');
ini_set('display_errors',1);
while(1){
    sleep(1);
    if(time()%10 == 9)select();
}
function select(){
try {
        $dbh = new PDO(
        #'mysql:host=54.238.75.103;dbname=ogawandroid;charset=utf8;',
        'ogawandroid',
        'password',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
        );
	$tag = $_POST['tag'];//要改善
        $prepare = $dbh->prepare('select * from choiced_url where tag = ?;');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	//echo $result[0]['url'];
	if($result[0]['url'] != $client_url){
		exit("URL already updated. nothing to do");
	}
        $prepare = $dbh->prepare('select * from image_list where tag = ?;');
	$prepare->bindValue(1,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	$row_cnt = count($result);
	$idx_rnd = rand();
	$choiced_url = "";
	if($row_cnt==0){
		$choiced_url = $tag.'.jpg' ;
	}else{
		$choiced_url =$result[$idx_rnd%$row_cnt]['name'];
		delete_img($choiced_url);
	}
        $prepare = $dbh->prepare('update choiced_image set name = ? where tag = ?');
	$prepare->bindValue(1,$choiced_url,PDO::PARAM_STR); 
	$prepare->bindValue(2,$tag,PDO::PARAM_STR); 
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_BOTH);
	echo(json_encode(array("status"=>"ok")));
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}
}
function delete_img($name){
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
	$prepare = $dbh->prepare('delete from image_list where name = ?');
	$prepare->bindValue(1,$name,PDO::PARAM_STR); 
	$prepare->execute();
} catch (PDOException $e) {
        $error = $e->getMessage();
        die("pdo接続に失敗<br>$error");
} catch (Exception $e){
        $error = $e->getMessage();
        die("接続に失敗<br>$error");
}

}


?>