# Notification Package For Android/IOS Phone

You can quickly use this package to build your Push Notification Server by PHP.

### 1. How to install the package

```
require:{
   ...
    "VasiliyTW/Notification":"*"
   ...
}
```
OR 
```
composer install VasiliyTW/Notifiaction
```

### 2. How to use the package 

```
<?php

require 'vendor/autoload.php';

$config = [
    'apn_server' => 'dev',
    'apn_gem_file' => 'pem dir',
    'apn_password' => 'pem password',
    'gcm_key' => 'api key'
];

$apns = new VasiliyTW\Notification\PushAPNS(
        $config['apn_gem_file'],
        $config['apn_password'],
        $config['apn_server']);

$gcm = new VasiliyTW\Notification\PushGCM($config['gcm_key']);

$apns->connect(); //or $apns->alwaysConnect();
$apns->push('device_token','your_notification_info');

//push one device
$gcm->push('device_token','your_notification_info');
//push multi device
$gcm->push('device_token_array','your_notification_info');

```

### 3. Simple Example
you can easily use to push Notification for Your Android/IOS Phone.

```
require 'vendor/autoload.php';

$config = [
    'apn_server' => 'dev',
    'apn_gem_file' => '/var/www/test.pem',
    'apn_password' => '12345678',
    'gcm_key' => '87654321'
];

$apns = new VasiliyTW\Notification\PushAPNS(
    $config['apn_gem_file'],
    $config['apn_password'],
    $config['apn_server']);

$gcm = new VasiliyTW\Notification\PushGCM($config['gcm_key']);

$apns->connect(); //or $apns->alwaysConnect();
$apns->push('1234455',
    [
        'body' => 'just test message',
        'detail' =>
            [
                'id' => 1
            ]
    ]);

//push one device
$gcm->push('1234455',
    [
        'message' => 'Test',
        'detail' =>
            [
                'id' => 1
            ]
    ]);
//push multi device
$gcm->push(
    [
        '12345',
        '67890'
    ],
    [
        'message' => 'Test',
        'detail' =>
            [
                'id' => 1
            ]
    ]);
```