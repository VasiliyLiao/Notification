<?php
namespace VasiliyTW\Notification;

class PushAPNS implements NotificationInterface
{

    protected $apnServer = null;
    protected $connectModel = 0;


    protected $passPhrase = '';
    protected $localCert = '';
    protected $connectUri = '';

    public function __construct($localCert, $passPhrase, $connectUri = 'dev')
    {
        $this->localCert = $localCert;
        $this->passPhrase = $passPhrase;
        if (strtolower($connectUri) != 'dev') {
            $this->connectUri = 'ssl://gateway.push.apple.com:2195';
        } else {
            $this->connectUri = 'ssl://gateway.sandbox.push.apple.com:2195';
        }
    }

    public function alwaysConnect()
    {
        $this->connectModel = 1;
        $this->connect();
    }

    public function connect()
    {

        $stream = stream_context_create();

        stream_context_set_option($stream, 'ssl', 'local_cert', $this->localCert);
        stream_context_set_option($stream, 'ssl', 'passphrase', $this->passPhrase);
        $this->apnServer = stream_socket_client(
            $this->connectUri,
            $err,
            $errStr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $stream);

        if (!$this->apnServer) {
            exit("Failed to connect: $err $errStr" . PHP_EOL);
        }
        echo 'Connected to APNS. ' . "\r\n";
    }

    public function disconnect()
    {
        fclose($this->apnServer);
    }

    public function retryConnect()
    {
        $this->disconnect();
        $this->connect();
    }

    public function push($deviceToken, $message = 'test')
    {

        if (!is_resource($this->apnServer)) {
            if (!$this->connectModel) {
                echo "APNS Server is disconnect \r\n";
                return false;
            }
            echo "APNS Server is disconnect and will Retry \r\n";
            $this->retryConnect();
        }

        $body['aps'] = array(
            'alert' => $message,
            'badge' => 1,
            'category' => 'alert',
            'sound' => 'default'
        );

        $payload = json_encode($body);

        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($this->apnServer, $msg, strlen($msg));

        if (!$result) {
            if (!$this->connectModel) {
                echo "Message not delivered.\r\n";
                return false;
            }
            echo "Retry to connect APN Server(about broken pipe).\r\n";
            $this->retryConnect();
            return $this->push($deviceToken, $message);
        }

        echo "Message successfully delivered -- Device Token:$deviceToken \r\n";
        return true;
    }
}
