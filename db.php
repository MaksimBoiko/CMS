<?php

$start_db = microtime(TRUE);
$startMemory = 0;
$startMemory = memory_get_usage();
    
$dbhost = "localhost"; // Имя хоста БД
$dbusername = "user"; // Пользователь БД
$dbpass = "pass"; // Пароль к базе
$dbname = "dbname"; // Имя базы

$dbconnect = mysql_connect ($dbhost, $dbusername, $dbpass); 
if (!$dbconnect) { echo ("Не могу подключиться к серверу базы данных!"); }

if(@mysql_select_db($dbname)) { }
else die ("Не могу подключиться к базе данных $dbname!");

?>