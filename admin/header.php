<?php
    $URL = $_SERVER['REQUEST_URI'];
    $link = parse_url($URL, PHP_URL_PATH);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>  
        <?php 
        $strSQL = "SELECT * FROM setting";
        $sett_arr = do_query($strSQL); 
        while($sett = mysql_fetch_array($sett_arr)) {
            $saved = $sett['setting_parameter'];
            switch ($saved){
                case 'off': break;
                case 'summernote': include_once 'wysiwyg/summernote.php'; break;
                case 'tinymce': include_once 'wysiwyg/tinymce.php'; break;
            }
        }
        ?>
        
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
        <?php include_once "sidebar.php"; ?>
        <div id="wrapper" class="clearfix">
            <div id="header">
                
            </div> <!-- end header -->
            <?php if ($link == '/admin/edit.php') { ?>
            <div class="section">
                <?php include_once "navigation/filter.php"; ?>
            </div>
            <div class="section">
                <?php include_once "navigation/page_navi.php"; ?>
            </div>
            <?php } ?>
            <div id="main">