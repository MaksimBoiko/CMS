<?php
include_once "../db.php";
include_once "function.php";
session_start();

$login = $_SESSION['login'];
$password = $_SESSION['password'];
$strSQL = "SELECT * FROM user WHERE login='$login'";
$rs = do_query($strSQL);
$row = mysql_fetch_array($rs);

if ($login == $row['login'] && md5($password) == $row['password']) {
    
    mysql_query("set names utf8");
    include_once "header.php";
    ?>
    
    <div class="section-left">
    
    <?php if (!isset($_GET['section_id'])) { ?>
    
        <ul>
        <?php
        
        $strSQL = "SELECT section_id, section_title FROM section";
        $sections_arr = do_query($strSQL);
        while($sections = mysql_fetch_array($sections_arr)) { ?>
           <li><a href="?section_id=<?php echo $sections['section_id']; ?>"><?php echo $sections['section_title']; ?></a></li> 
        <?php } ?>
        </ul>
        
    <?php } else if (isset($_GET['section_id'])) {
        $section_id = $_GET['section_id'];
        
        if (isset($_POST['number']) && isset($_POST['title']) && isset($_POST['class']) && ($_POST['class'] == '' || $_POST['number'] == '' || $_POST['title'] == '')) { echo "<p>Заполните все поля.</p>"; }
                if ($_POST['number'] != '' && $_POST['title'] != '' && $_POST['class'] != '') {
                    echo "<p>Усё, вроде, ок...</p>";
                    $cnt_id = $_POST['cnt_id'];
                    $work_type = trim(preg_replace('/\s{2,}/', ' ', $_POST['work_type']));
                    $section = $_POST['section_id'];
                    $index = $_POST['number'];
                    $title = trim(preg_replace('/\s{2,}/', ' ', $_POST['title']));
                    $class = $_POST['class'];
                    $strSQL =   "INSERT INTO `content` (`content_id`, `section_id`, `index`, `title`, `work_type`, `class`)
                                VALUES ('$cnt_id', '$section', '$index', '$title', '$work_type', '$class')";
                    $ins_paragraph = do_query($strSQL);
                    $last_id = mysql_insert_id();
                    
                    $_SESSION['class'] = $_POST['class'];
                    $_SESSION['work_type'] = $_POST['work_type'];
                    
    /* ВРЕМЕННО */  header("Location: post-new.php?section_id=".$section_id); 
    //              header("Location: edit.php?content_id=".$cnt_id);
                } 
                
        $typehist = $_SESSION['work_type'];
        $classhist = $_SESSION['class'];
        if (isset($classhist)) { $class = $classhist; $cw_f = "AND `class`=$class"; }
        if (isset($typehist)) { $type = $typehist; $cw_f = $cw_f." AND `work_type`='$type'"; }
        $strSQL = "SELECT * FROM content WHERE section_id=$section_id $cw_f ORDER BY `index` DESC";
        $histadded = do_query($strSQL);
        $strSQL2 = "SELECT `index` FROM `content` WHERE `section_id`=$section_id $cw_f ORDER BY `index` DESC LIMIT 1";
        $res2 = do_query($strSQL2);
        $count = mysql_fetch_array($res2); $count1 = $count[0]+1;
        
        switch($typehist){
            case 'theme': $theme = 'checked'; break;
            case 'pract': $pract = 'checked'; break;
            case 'lab': $lab = 'checked'; break;
            case 'ticket': $ticket = 'checked'; break;
            case 'less': $less = 'checked'; break;
            default: $theme = 'checked'; break;
        }
        
        ?>
        
        <form action="" method="POST">
            <input type="hidden" name="section_id" value="<?php echo $_GET['section_id']; ?>">
            <p>
                <input name="work_type" id="th" type="radio" value="theme" <?php echo $theme; ?>>
                <label for="th">Тема</label><br>
                <input name="work_type" id="pr" type="radio" value="pract" <?php echo $pract; ?>>
                <label for="pr">Практическая</label><br>
                <input name="work_type" id="lb" type="radio" value="lab" <?php echo $lab; ?>>
                <label for="lb">Лабораторная</label><br>
                <input name="work_type" id="tick" type="radio" value="ticket" <?php echo $ticket; ?>>
                <label for="tick">Экзаменационный билет</label>
                <hr>
                <input name="class" id="11" type="radio" value="11">
                <label for="11">11</label>
                <input name="class" id="10" type="radio" value="10">
                <label for="10">10</label>
                <input name="class" id="9" type="radio" value="9">
                <label for="9">9</label>
                <input name="class" id="8" type="radio" value="8">
                <label for="8">8</label>
                <input name="class" id="7" type="radio" value="7" checked>
                <label for="7">7</label>
                <input name="class" id="6" type="radio" value="6">
                <label for="6">6</label>
                <input name="class" id="5" type="radio" value="5">
                <label for="5">5</label>
                <input name="class" id="4" type="radio" value="4">
                <label for="4">4</label>
                <input name="class" id="3" type="radio" value="3">
                <label for="3">3</label>
                <input name="class" id="2" type="radio" value="2">
                <label for="2">2</label>
                <input name="class" id="1" type="radio" value="1">
                <label for="1">1</label>
            </p>
            <p>
                <input type="number" name="cnt_id" min="1" placeholder="id" style="width: 240px">
                <input type="number" name="number" min="1" placeholder="№"style="width: 240px" value="<?php echo $count1; ?>">
            </p>
            <p>
                <input type="text" name="title" placeholder="Название параграфа" autocomplete="off" style="width: 400px">
                <input type="submit" value="Добавить">
            </p>
        </form>
        
        <ul>
        <?php while($res = mysql_fetch_array($histadded)) { ?>
           <li><?php echo $res['index'].'. '.$res['title']; ?></li>
        <?php } ?>
        </ul>
        
    <?php } ?>
    
    </div> <!-- end section-left -->
    
    <?php
    include_once "footer.php";

}