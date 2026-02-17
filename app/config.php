<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bngrc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// define('DB_HOST', 'localhost');
// define('DB_NAME', 'db_s2_ETU004175');
// define('DB_USER', 'ETU004175');
// define('DB_PASS', 'cVVU0Bpx');
// define('DB_CHARSET', 'utf8mb4');

//$bdd = mysqli_connect('localhost', 'ETU004175', 'cVVU0Bpx', 'db_s2_ETU004175');

$app = Flight::app();

$app->set('flight.base_url', '');
//$app->set('flight.base_url', '/ETU004175/BNGRCEXAM');

