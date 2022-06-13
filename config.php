<?php

define("ROLE_ADMIN", 1);
define("ROLE_USER", 2);
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_clinicamedica');
define('DB_ADMIN_NAME', 'db_administracion');

$adminLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_ADMIN_NAME);
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($adminLink === false) {
    die("ERROR: " . mysqli_connect_error());
} else {
    if($link === false){
        die("ERROR: " . mysqli_connect_error());
    }
}
