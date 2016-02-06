<?php
include_once "../db.php";
mysql_query("set names utf8");
global $URL;
$URL = $_SERVER['REQUEST_URI'];
$query_link = parse_url($URL, PHP_URL_QUERY);
include_once "header.php";
?>

<?php if (isset($_GET['content_id'])) { ?> <!-- (2) страница добавления работы, задания -->

    <div class="section-left">
        
        <?php if (isset($_GET['task'])) { // (4) редактирование задания
            $content_id = $_GET['content_id'];
            if (isset($_POST['index']) && isset($_POST['task']) && isset($_POST['content'])) {
                $task_id = $_GET['task'];
                $UPDindex = $_POST['index'];
                $UPDtask = $_POST['task'];
                $UPDanswer = $_POST['content'];
                $strSQL =   "UPDATE `task` SET
                            `index` = '$UPDindex',
                            `task` = '$UPDtask',
                            `answer` = '$UPDanswer'
                            WHERE `task_id` = $task_id";
                $ins_paragraph = mysql_query($strSQL);
                header("Location: edit.php?content_id=".$content_id);
            }
            
            $task_id = $_GET['task'];
            $strSQL = "SELECT * FROM task WHERE task_id=$task_id";
            $content_arr = mysql_query($strSQL);
            while($content = mysql_fetch_array($content_arr)) { ?>
                <form method="POST" action="" class="addtask">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td><input type="number" name="index" min="1" placeholder="№" value="<?php echo htmlspecialchars($content['index']); ?>">
                                <input type="text" name="task" placeholder="Текст задания" autocomplete="off" value="<?php echo htmlspecialchars($content['task']); ?>"></td>
                            </tr>
                            <tr>
                                <td><textarea id="mytextarea" name="content" rows="20"><? echo htmlspecialchars($content['answer']);?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Сохранить"></p>
                </form>
            <?php } ?>
        <?php } else { 
        
            $content_id = $_GET['content_id']; // (3) добавление задания
            if ($_POST) {
                $index = $_POST['index'];
                $task = trim(preg_replace('/\s{2,}/', ' ', $_POST['task']));
                $answer = trim(preg_replace('/\s{2,}/', ' ', $_POST['content']));
                $query =    "INSERT INTO `task` (`content_id`, `index`, `task`, `answer`)
                            VALUES ('$content_id', '$index', '$task', '$answer')";
                $ins_paragraph = mysql_query($query);
                header("Location: edit.php?content_id=".$content_id);
            }
            $strSQL = "SELECT * FROM content WHERE content_id=$content_id";
            $content_arr = mysql_query($strSQL);
            while($content = mysql_fetch_array($content_arr)) { ?>
               <h1><?php echo '§ '.$content['index'].'. '.$content['title']; ?></h1>
            <?php } 
            $strSQL = "SELECT * FROM task WHERE content_id=$content_id ORDER BY `index` ASC";
            $content_arr = mysql_query($strSQL);
            while($content = mysql_fetch_array($content_arr)) { ?>
                <p>
                   <div class="task"><?php echo $content['index'].'. '.$content['task']; ?></div>
                   <div class="answer"><?php echo '<p>'.$content['answer'].'</p>'; ?></div>
                   <div><a href="<?php echo '?content_id='.$content_id.'&task='.$content['task_id']; ?>">Изменить</a></div>
                </p>
            <?php } ?>
            
        <?php } if (!isset($_GET['task'])) {
        $strSQL = "SELECT COUNT(*) FROM task WHERE content_id=$content_id";
        $count = mysql_fetch_array(mysql_query($strSQL)); ?>
        
            <form method="POST" action="" class="addtask">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td><input type="number" name="index" min="1" placeholder="№" value="<?php echo $count[0]+1; ?>">
                            <input type="text" name="task" placeholder="Текст задания" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td><textarea id="mytextarea" name="content" rows="20"><? echo htmlspecialchars($content);?></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <p><input type="submit" value="Добавить"></p>
            </form>
        
        <?php } ?>
    
    </div> <!-- end section-left -->
        
<?php } else if (!isset($_GET['content_id'])) { ?> <!-- (1) страница обзора работ -->
    
    <?php
    $strSQL = "SELECT * FROM section";
    $content_arr = mysql_query($strSQL);
    
    // генерация запроса для фильтра
    if (isset($_GET['fltr_cls'])) $query_link = '?fltr_cls='.$_GET['fltr_cls'].'&fltr_sect=';
    else $query_link = '?fltr_sect='; ?>
    
    <div class="class-list">
    
        <?php
        while($content = mysql_fetch_array($content_arr)) { ?>
            <a href="<?php echo $query_link.$content['section_id']; ?>"><?php echo $content['section_title']; ?></a>
        <?php } ?>
    
    </div>
    
    <?php
    $strSQL = "SELECT DISTINCT class FROM content";
    $class_arr = mysql_query($strSQL);
    
    // генерация запроса для фильтра
    if (isset($_GET['fltr_sect'])) $query_link = '?fltr_sect='.$_GET['fltr_sect'].'&fltr_cls=';
    else $query_link = '?fltr_cls='; ?>
    
    <div class="list class">
        
        <?php
        while($class = mysql_fetch_array($class_arr)) { ?>
            <a href="<?php echo $query_link.$class['class']; ?>"><?php echo $class['class']; ?> класс</a>
        <?php } ?>
    
    </div>
    
    <?php
    if (isset($_GET['fltr_sect'])) {
        $filter = $_GET['fltr_sect'];
        $filter_query = "AND content.section_id=$filter";
    } ?>
    
    <table class="list-work">
        <thead>
            <tr>
                <td>Название</td>
                <td>Тип работы</td>
                <td>Предмет</td>
                <td>Класс</td>
            </tr>
        <thead>
        <tbody>
    <?php
    $strSQL = "SELECT * FROM content, section WHERE content.section_id = section.section_id $filter_query ORDER BY `index` ASC";
    $content_arr = mysql_query($strSQL);
    while($content = mysql_fetch_array($content_arr)) {
        switch ($content['work_type']) {
            case 'theme': $work_type = 'Тема'; break;
            case 'pract': $work_type = 'Практическая'; break;
            case 'lab': $work_type = 'Лабораторная'; break;
            default: $work_type = 'Не указано'; break;
        } ?>
        <tr>
            <td><a href="<?php echo 'post.php?content_id='.$content['content_id']; ?>"><?php echo '§ '.$content['index'].'. '.$content['title']; ?></a></td>
            <td><?php echo $work_type; ?></td>
            <td><?php echo $content['section_title']; ?></td>
            <td><?php echo $content['class']; ?></td>
        </tr>
    <?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php
include_once "footer.php";