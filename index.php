<?php
header('Content-Type: text/html; charset=utf-8');

include_once "db.php";
mysql_query("set names utf8");

include_once "auth.php";
include_once "admin/function.php";

$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);

$module = $url[1];
$action = $url[2];


// ROUTING

// если запись
/*
if ($module == 'r' && isset($action)) {
    if (preg_match("/^[0-9]+\z/", $action)) {
        $is_single = true;
        $content_id = $action;
    }
    else $error = 'Ошибка';
}
// если категория

else if (isset($module) && !isset($action)) {
    $is_module = true;
    if (preg_match("/^[0-9]*\z/", $module))
        $is_class = true;
    else if (preg_match("/^[0-9]{1,2}-[a-zA-Z]*\z/", $module))
        $is_book = true;
    else $error = 'Ошибка';
}*/

if ($module == 'r' && preg_match("/^[0-9]+\z/", $action)){
    $is_single = true;
    $content_id = $action;
    
    $strSQL = "SELECT * FROM content WHERE content_id=$content_id";
    $res = do_query($strSQL);
    $content = mysql_fetch_array($res);
    $strSQL = "SELECT * FROM task WHERE content_id=$content_id";
    $res = do_query($strSQL);
    
    include "header.php";
    include "single.php";
    include "sidebar.php";
    include "footer.php";
    
}

else if (!isset($action) && preg_match("/^[0-9]*\z/", $module)) {
    $is_class = true;
    
    include "dbwork/class_sql.php";
    include "header.php";
    include "category.php";
    include "footer.php";
}

else if (!isset($action) && preg_match("/^[0-9]{1,2}-[a-zA-Z]*\z/", $module)){
    $is_book = true;
    
    include "dbwork/book_sql.php";
    include "header.php";
    include "category.php";
    include "footer.php";
}

else {
    include "header.php";
    echo $error;
    include "footer.php";
}

/*
if (isset($module) && !isset($action)){
    $is_module = true;
    if (preg_match("/^[0-9]*\z/", $module)){
        $is_class = true;
        include "dbwork/class_sql.php";
        include "header.php";
        include "category.php";
        include "footer.php";
        
        echo "категория класса";
    }
    else if (preg_match("/^[0-9]{1,2}-[a-zA-Z]*\z/", $module)){
        $is_book = true;
        include "dbwork/book_sql.php";
        include "header.php";
        include "category.php";
        include "footer.php";
        
        echo "категория решебника";
    }
    else echo "Ошибка";
}


else if ($module == 'r' && isset($action)){
    
    
    if (preg_match("/^[0-9]+\z/", $action)) {
        $is_single = true;
        include "dbwork/single_sql.php";
        
        include "header.php";
        include "single.php";
        include "footer.php";
        
        echo "запись сайта";
    }
    else echo "Ошибка";
}
else echo "Ошибка";
*/