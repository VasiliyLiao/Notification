<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2015/10/23
 * Time: ¤U¤È 05:01
 */

namespace VasiliyTW\Notification;

class PushGCM implements NotificationInterface
{

    public $connectUri = 'https://gcm-http.googleapis.com/gcm/send';
    public $headers = [];

    public function __construct($apiKey)
    {
        $this->headers = [
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json',
        ];
    }

    public function push($deviceID, $message = 'test')
    {
        if (!is_array($deviceID)) {
            $deviceID = [$deviceID];
        }

        $pushData = array(
            'registration_ids' => $deviceID,
            'data' => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->connectUri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pushData));
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result . "\r\n";
    }


}
