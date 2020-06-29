<?php 
try
{
    $database = new PDO(
        'mysql:dbname=date_simulator;host=127.0.0.1;charset=utf8', 
        'root', 
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}
catch (Exception $error)
{
    die('Error : ' . $error->getMessage());
}
?>