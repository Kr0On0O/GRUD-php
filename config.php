<?php
/*Credenciales de la base de datos*/
define('DB_SERVER','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','demo');
//Intento de conexion
$link=mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

//Prueba de conexion
if($link===false){
    die("ERROR: No es posible conectarse. ".mysqli_connect_error());
}

?>