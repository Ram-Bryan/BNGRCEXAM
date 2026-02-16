<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bngrc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Frais d'achat configurable (en pourcentage)
define('FRAIS_ACHAT_PERCENT', 10);

$app = Flight::app();

$app->set('flight.base_url', '');
