<?php
mysql_query("set names utf8");
include_once "../db.php";
include_once "function.php";
session_start();

$login = $_SESSION['login'];
$password = $_SESSION['password'];
$strSQL = "SELECT * FROM user WHERE login='$login'";
$rs = do_query($strSQL);
$row = mysql_fetch_array($rs);

if ($login == $row['login'] && md5($password) == $row['password']) {

    $url_req = $_SERVER['REQUEST_URI'];
    $url_expl = explode('/', $url_req);
    $url_two = $url_expl[2];
    
    include_once "header.php";
    
    include_once "main.php";
    
    include_once "footer.php";

}