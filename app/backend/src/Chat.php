<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
	split_tag_message($msg);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

    function split_tag_message($msg){
	$keywords = preg_split("/[\s,]+/",$msg);
	$message = "";
	for($i=1;$i<count($keywords);$i++){
		$message = $message." ".$keywords[$i];
	}
	if($message != ""){
		writeDB($keywords[0],$message);
	}
    }
 function writeDB($tag,$message){
	    try {
	    $dbh = new \PDO(
	    'mysql:host=localhost;dbname=ogawandroid;charset=utf8;',
	    #'mysql:host=54.238.75.103;dbname=ogawandroid;charset=utf8;',
	    'ogawandroid',
	    'password',
	    array(
		    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		    \PDO::ATTR_EMULATE_PREPARES => false,
	    )
	    );
	    $prepare = $dbh->prepare('insert into comment_list (tag,comment) values(?,?);');
	    $prepare->bindValue(1,$tag,\PDO::PARAM_STR); 
	    $prepare->bindValue(2,$message,\PDO::PARAM_STR); 
	    $prepare->execute();
	    //$result = $prepare->fetchAll(\PDO::FETCH_BOTH);
	    //echo json_encode($result);
	    } catch (\PDOException $e) {
	    $error = $e->getMessage();
	    die("pdo接続に失敗<br>$error");
	    } catch (Exception $e){
	    $error = $e->getMessage();
	    die("接続に失敗<br>$error");
	    }
    }

