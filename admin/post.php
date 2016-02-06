<?php

include_once "../db.php";
session_start();
include_once "function.php";

$login = $_SESSION['login'];
$password = $_SESSION['password'];
$strSQL = "SELECT * FROM user WHERE login='$login'";
$rs = do_query($strSQL);
$row = mysql_fetch_array($rs);

if($login == $row['login'] && md5($password) == $row['password']) {
    mysql_query("set names utf8");
    global $URL;
    $URL = $_SERVER['REQUEST_URI'];
    $query_link = parse_url($URL, PHP_URL_QUERY);
    include_once "header.php";
    ?>
    
    <?php if (isset($_GET['content_id'])) { ?> <!-- (2) страница добавления работы, задания -->
    
        <?php
        
        $strSQL = "SELECT * FROM setting";
        $sett_arr = do_query($strSQL); 
        while($sett = mysql_fetch_array($sett_arr)) {
            $saved = $sett['setting_parameter'];
            switch ($saved){
                case 'off': break;
                case 'summernote': $textarea_id = 'summernote'; break;
                case 'tinymce': $textarea_id = 'mytextarea'; break;
            }
        }
        
        ?>
        
        <div class="section-post">
            
            <?php if (isset($_GET['task'])) { // (4) редактирование задания
                $content_id = $_GET['content_id'];
                if (isset($_POST['index']) && isset($_POST['task']) && isset($_POST['content'])) {
                    $task_id = $_GET['task'];
                    $UPDindex = $_POST['index'];
                    $UPDtask = trim(preg_replace('/\s{2,}/', ' ',$_POST['task']));
                    $UPDanswer = trim(preg_replace('/\s{2,}/', ' ', $_POST['content']));
                    $UPDtask = htmlspecialchars($UPDtask, ENT_QUOTES);
                    $strSQL =   "UPDATE `task` SET
                                `index` = '$UPDindex',
                                `task` = '$UPDtask',
                                `answer` = '$UPDanswer'
                                WHERE `task_id` = $task_id";
                    $ins_paragraph = mysql_query($strSQL);
                    header("Location: post.php?content_id=".$content_id);
                }
                
                $task_id = $_GET['task'];
                $strSQL = "SELECT * FROM task WHERE task_id=$task_id";
                $content_arr = do_query($strSQL);
                while($content = mysql_fetch_array($content_arr)) { ?>
                    <form method="POST" action="" class="addtask">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td><input type="number" name="index" min="1" placeholder="№" value="<?php echo $content['index']; ?>">
                                    <input type="text" name="task" placeholder="Текст задания" autocomplete="off" value="<?php echo $content['task']; ?>"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <textarea id="<?php echo $textarea_id; ?>" name="content"><?php echo htmlspecialchars($content['answer']); ?></textarea>
                                        <!-- <textarea id="mytextarea" name="content"></textarea> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p><input type="submit" value="Сохранить"></p>
                    </form>
                <?php } ?>
            <?php } else { 
                
                $content_id = $_GET['content_id'];
                $strSQL = "SELECT * FROM content WHERE content_id=$content_id";
                $content_arr = do_query($strSQL);
                while($content = mysql_fetch_array($content_arr)) { ?>
                   <h1><?php echo '§ '.$content['index'].'. '.$content['title']; $status = $content['status']; ?></h1>
                <?php } 
                
                if (!isset($_GET['task'])) {
                $strSQL = "SELECT `index` FROM `task` WHERE `content_id`=$content_id ORDER BY `index` DESC LIMIT 1";
                $res = do_query($strSQL);
                $count = mysql_fetch_array($res);
                
                // Проверка, оубликована ли работа
                switch ($status){
                    case 'show': $button = 'Скрыть'; $cmd = 'hide'; break;
                    default: $button = 'Опубликовать'; $cmd = 'publish'; break;
                }
                
                ?>
                
                    <form method="POST" action="" class="addtask" id="addtask">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td><input type="number" name="index" min="1" placeholder="№" value="<?php echo $count[0]+1; ?>">
                                    <input type="text" name="task" placeholder="Текст задания" autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <textarea id="<?php echo $textarea_id; ?>" name="content"></textarea>
                                        <!-- <textarea id="mytextarea" name="content"></textarea> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <form id="status"><input type="hidden" name="content_id" value="<?php echo $content_id; ?>"></form>
                    <p><input type="submit" form="addtask" value="Добавить">
                    <input type="submit" name="<?php echo $cmd; ?>" form="status" value="<?php echo $button; ?>"></p>
                
                <?php }
                
                // кнопка "Опубликовать"
                if (isset($_GET['publish'])) {
                    $strSQL = "UPDATE `content` SET `status` = 'show' WHERE `content_id` = $content_id";
                    $publ = do_query($strSQL);
                    echo "<meta http-equiv='refresh' content='0; url=post.php?content_id=$content_id'>";
                }
                else if (isset($_GET['hide'])) {
                    $strSQL =   "UPDATE `content` SET `status` = 'hide' WHERE `content_id` = $content_id";
                    $publ = do_query($strSQL);
                    echo "<meta http-equiv='refresh' content='0; url=post.php?content_id=$content_id'>";
                }
            
                // (3) добавление задания
                if ( isset($_POST['index']) && isset($_POST['content']) ) {
                    $index = $_POST['index'];
                    $task = trim(preg_replace('/\s{2,}/', ' ', $_POST['task']));
                    $answer = trim(preg_replace('/\s{2,}/', ' ', $_POST['content']));
                    $task = htmlspecialchars($task, ENT_QUOTES);
                    $strSQL =   "INSERT INTO `task` (`content_id`, `index`, `task`, `answer`)
                                VALUES ('$content_id', '$index', '$task', '$answer')";
                    $ins_paragraph = do_query($strSQL);
                    echo "<meta http-equiv='refresh' content='0; url=post.php?content_id=$content_id'>";
                    // header("Location: post.php?content_id=".$content_id);
                } ?>
                
                <div class="content">
                    
                <?php
                $strSQL = "SELECT * FROM task WHERE content_id=$content_id ORDER BY `index` ASC";
                $content_arr = do_query($strSQL);
                while($content = mysql_fetch_array($content_arr)) { ?>
                    <p>
                       <div class="task"><?php echo $content['index'].'. '.($content['task']); ?></div>
                       <div class="answer"><?php echo '<p>'.$content['answer'].'</p>'; ?></div>
                       <div><a href="<?php echo '?content_id='.$content_id.'&task='.$content['task_id']; ?>">Изменить</a></div>
                    </p>
                <?php } ?>
                
                </div>
        <?php }
        } ?>
        
        </div> <!-- end section-left -->
    
    <?php
    include_once "footer.php";
    
}