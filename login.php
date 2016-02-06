<?php
    session_start();
    mysql_query("set names utf8");
    include_once "db.php";
    $url = $_SERVER['REQUEST_URI'];
    $server_name = $_SERVER['SERVER_NAME'];
    $login = $_POST['login'];
	$password = $_POST['password'];
        
    $strSQL = "SELECT * FROM user WHERE login='$login'";
    $rs = mysql_query($strSQL);
    $row = mysql_fetch_array($rs);
    
    if($_SESSION["login_status"] == "access_granted"){
        header('Location:/admin/edit.php');
    }
    
    if(isset($_POST['login']) && isset($_POST['password'])){
        
		if($login == $row['login'] && md5($password) == $row['password']) {
			$_SESSION["login_status"] = "access_granted";
				
			
			$_SESSION['login'] = $login;
			$_SESSION['password'] = $password;
			header('Location:/admin/edit.php');
		}
		else {
			$_SESSION["login_status"] = "access_denied";
		}
	}
    
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="http://<?php $server_name; echo $server_name; ?>/css/style.css" type="text/css">
    </head>
    <body>
        
        <form action="" method="post">
            <p><input type="text" name="login" placeholder="Ваш логин"></p>
            <p><input type="password" name="password" placeholder="Ваш пароль"></p>
            <p><input type="submit" value="Войти"></p>
        </form>
        
    </body>
</html>