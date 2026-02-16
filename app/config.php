<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bngrc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Frais d'achat configurable (en pourcentage)
// FRAIS_ACHAT_PERCENT is now stored in the configuration table.
// Use models\Configuration::getValue(Flight::db(), 'FRAIS_ACHAT_PERCENT', 10, 'int') to read it.

$app = Flight::app();

$app->set('flight.base_url', '');
