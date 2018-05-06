<?php

/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 05-05-2018
 * Time: 13:27
 */
class smsgwDbModel extends BaseInit
{
    private $con;
    public function __construct()
    {
        parent::__construct();
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DB, DBPORT) or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger->error(__METHOD__.": Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    public function insertSMS(array $inboundSMS, $direction) {
        $inboundJsonSMS = json_encode($inboundSMS);
        $messageId = $inboundSMS['messageId'];
        $this->logger->info(__METHOD__.": ". $messageId ." - Received message " . $inboundJsonSMS);
        if($inboundSMS['to'] === $inboundSMS['msisdn']) {
            $this->logger->warning(__METHOD__.": ". $messageId ." - Receiver and sender are the same, cancelling. 'to': " . $inboundSMS['to'] . " 'msisdn': " . $inboundSMS['msisdn']);
        }
        else if($inboundSMS['text'] === "") {
            $this->logger->warning(__METHOD__.": ". $messageId ." - Text in message empty, cancelling.");
        }
        else {
            $inboundSMS['direction'] = $direction;
            $keys = "`".implode("`,`",array_keys($inboundSMS))."`";
            $values = "'".implode("','",$inboundSMS)."'";
            $sql = "INSERT INTO tivoli2018_smsgw ($keys) VALUES ($values)";
            if ($this->con->query($sql) === TRUE) {
                $this->logger->info(__METHOD__.": ". $messageId ." - New DB record created successfully");
                return true;
            } else {
                $this->logger->warning(__METHOD__.": ". $messageId ." - Error: \n\n" . $sql . "\n\n" . $this->con->error);
            }
        }
        return false;
    }

    public function getSMS($limit = 10, $direction = 'in') {
        $array = array();
        if ($result = $this->con->query("SELECT * FROM tivoli2018_smsgw where processing not in ('running','processed') direction = '".$direction."' FOR UPDATE LIMIT ".$limit)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->con->query("UPDATE tivoli2018_smsgw set status = 'running'");
                $array[] = $row;
            }
        }
        return $array;
    }

    public function __destruct()
    {
        $this->con->close();
    }
}



/*


INBOUND
{
"messageId": "0A0000000123ABCD1",
"msisdn": "447700900001",
"to": "447700900000",
"text": "Hello world",
"type": "text",
"keyword": "Hello",
"message-timestamp": "2020-01-01T12:00:00.000+00:00",
"timestamp": "1578787200",
"nonce": "aaaaaaaa-bbbb-cccc-dddd-0123456789ab",
"concat": "true",
"concat-ref": "1",
"concat-total": "3",
"concat-part": "2",
"data": "abc123",
"udh": "abc123"
}

DLVR
{
"messageId": "0A0000001234567B",
"msisdn": "447700900000",
"to": "Acme Inc",
"message-timestamp": "2020-01-01T12:00:00.000+00:00"
"network-code": "12345",
"price": "0.03330000",
"status": "delivered",  <-- delivered, expired, failed, rejected, accepted, buffered, unknown
"scts": "2001011400",
"err-code": "0",
}


*/