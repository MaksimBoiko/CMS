<?php
    global $filter_query, $page_reliz;
    
    $page = $_GET['page'];
    $lim = 25;
    $strSQL = "SELECT COUNT(*) FROM content, section WHERE content.section_id = section.section_id $filter_query";
    $result00 = do_query($strSQL);
    $temp = mysql_fetch_array($result00);
    $posts = $temp[0];
    //echo 'всего постов: '.$posts.'<br>';
    
    if ($posts > $lim) {
    
        if (isset($_GET['cls'])) { ?><input type='hidden' name='cls' value='<?php echo $_GET['cls']; ?>'> <?php }
        if (isset($_GET['type'])) { ?><input type='hidden' name='type' value='<?php echo $_GET['type']; ?>'> <?php }
        
        $start = $page * $lim;
        $page_reliz = "LIMIT $start, $lim";
        
        $total = intval(($posts - 1) / $lim) + 1;  
        // Определяем начало сообщений для текущей страницы  
        $page = intval($page);  
        // Если значение $page меньше единицы или отрицательно  
        // переходим на первую страницу  
        // А если слишком большое, то переходим на последнюю  
        if(empty($page) or $page < 0) $page = 1;  
          if($page > $total) $page = $total;  
        // Вычисляем начиная к какого номера  
        // следует выводить сообщения  
        $start = $page * $lim - $lim;  
        // Выбираем $num сообщений начиная с номера $start 
        
        if ($page == 1) $opacity1 = "style='opacity: 0.5;'";
        if ($page == $total-1) $opacity2 = "style='opacity: 0.5;'";
        ?>
        
        <form id="prev">
            <?php if (isset($_GET['sect'])) { ?><input type='hidden' name='sect' value='<?php echo $_GET['sect']; ?>'> <?php } ?>
            <?php if (isset($_GET['cls'])) { ?><input type='hidden' name='cls' value='<?php echo $_GET['cls']; ?>'> <?php } ?>
            <?php if (isset($_GET['type'])) { ?><input type='hidden' name='type' value='<?php echo $_GET['type']; ?>'> <?php } ?>
            <input type="hidden" name="page" value="<?php if ($page == 1) echo $page; else echo $page-1; ?>">
        </form>
        <form id="next">
            <?php if (isset($_GET['sect'])) { ?><input type='hidden' name='sect' value='<?php echo $_GET['sect']; ?>'> <?php } ?>
            <?php if (isset($_GET['cls'])) { ?><input type='hidden' name='cls' value='<?php echo $_GET['cls']; ?>'> <?php } ?>
            <?php if (isset($_GET['type'])) { ?><input type='hidden' name='type' value='<?php echo $_GET['type']; ?>'> <?php } ?>
            <input type="hidden" name="page" value="<?php if ($page == $total-1) echo $page; else echo $page+1; ?>">
        </form>
        
        <input type="submit" form="prev" value="<" <?php echo $opacity1; $opacity1='' ?>>
        <input type="submit" form="next" value=">" <?php echo $opacity2; $opacity2='' ?>>
        
    <?php   
    }
?>