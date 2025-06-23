<?php

require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\VAPID;

// Generate VAPID keys
$keys = VAPID::createVapidKeys();
echo "Public Key: " . $keys['publicKey'] . PHP_EOL;
echo "Private Key: " . $keys['privateKey'] . PHP_EOL;
