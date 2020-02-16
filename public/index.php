<?php

use IDCT\HttpTelegramResender\HttpTelegramResender;

require "../vendor/autoload.php";

$resender = new HttpTelegramResender();
$resender->loadProjects();
$resender->run();
