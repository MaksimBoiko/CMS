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
    
    include_once "function.php";
    mysql_query("set names utf8");
    
    $url = $_SERVER['REQUEST_URI'];
    $url_req = $_SERVER['REQUEST_URI'];
    $url_expl = explode('/', $url_req);
    $url_two = $url_expl[2];
    $query_link = parse_url($url, PHP_URL_QUERY);
    
    include_once "header.php";
    ?>
        
        <table class="list-work">
            <thead>
                <tr>
                    <td>Предмет</td>
                    <td>Название</td>
                    <td>Тип работы</td>
                    <td>Класс</td>
                </tr>
            <thead>
            <tbody>
        <?php
        if (isset($filter_query)) $orderby = "ORDER BY `index` ASC"; // если выбран фильтр, сортировка по индексу
        $strSQL = "SELECT * FROM content, section WHERE content.section_id = section.section_id $filter_query $orderby $page_reliz";
        $content_arr = do_query($strSQL); 
        while($content = mysql_fetch_array($content_arr)) {
            switch ($content['work_type']) {
                case 'theme': $work_type = 'Тема'; break;
                case 'pract': $work_type = 'Практическая'; break;
                case 'lab': $work_type = 'Лабораторная'; break;
                case 'ticket': $work_type = 'Билет'; break;
                case 'less': $work_type = 'Урок'; break;
                default: $work_type = 'Не указано'; break;
            }
            $status = $content['status'];
            switch ($status){
                case 'show': $c_status = ''; break;
                default: $c_status = 'style="opacity: .5;"';
            }
            ?>
            <tr>
                <td><?php echo $content['section_title']; ?></td>
                <td><a <?php echo $c_status; ?> href="<?php echo 'post.php?content_id='.$content['content_id']; ?>"><?php echo $content['prefix'].' '.$content['index'].'. '.$content['title']; ?></a></td>
                <td><?php echo $work_type; ?></td>
                <td><?php echo $content['class']; ?></td>
            </tr>
        <?php
        } ?>
            </tbody>
        </table>
    
    <?php
    include_once "footer.php";
    
}