<?php
namespace VasiliyTW\Notification;

interface NotificationInterface
{
    public function push($deviceID, $message);
}