<?php

define('DB_SERVER', 'db');
define('DB_USERNAME', 'tractor_user');
define('DB_PASSWORD', 'tractor_password');
define('DB_NAME', 'tractores_clavijo_db');
define('DB_PORT', 3306);

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

if ($conn === false) {
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}

if (!mysqli_set_charset($conn, "utf8mb4")) {
}
