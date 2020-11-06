<?php
ini_set('display_errors',1);
if($_FILES['file_name']['name']){
    switch ($_FILES['file_name']['error']){
	case 0:
	    $tmppath =  $_FILES['file_name']['tmp_name'];
	    $extension_tmp =  explode('.',$_FILES['file_name']['name']);
	    $extension =  $extension_tmp[count($extension_tmp)-1];
	    $tmpname =  split('/',$tmppath)[2];
	    //$flg = copy($tmppath, '/home/ec2-user/receive_image/'.$tmpname.'.png');
	    $flg = copy($tmppath, '/var/www/html/tmp/'.$tmpname.".".$extension);
	    writeDB($tmpname.".".$extension);
	    break;
	case 1:
	    echo("php:php.ini size over");
	    break;
	case 2:
	    echo("php:browser over size");
	    break;
	default:
	    echo("php:cant upload");
	    break;
    }
}else{
    echo "ファイルがありません";
}

function writeDB($filename){
try{
    $tag = $_POST["tag"];//要改善
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
    $prepare = $dbh->prepare('insert into image_list (name,tag) values(?,?);');
    $prepare->bindValue(1,$filename,PDO::PARAM_STR); 
    $prepare->bindValue(2,$tag,PDO::PARAM_STR); 
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_BOTH);
    echo json_encode(array("status"=>"ok"));
} catch (PDOException $e) {
    $error = $e->getMessage();
    die("pdo接続に失敗<br>$error");
} catch (Exception $e){
    $error = $e->getMessage();
    die("接続に失敗<br>$error");
}
}

?>
