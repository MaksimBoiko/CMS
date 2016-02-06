<?php
    function do_query($query) { 
        $queries = 0; 
        $GLOBALS['queries']++;
        return mysql_query($query); 
    } 
?>

