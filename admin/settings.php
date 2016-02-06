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
    
    if (isset($_GET['WYSIWYG'])) {
        $setting_id = $_GET['setting_id'];
        $setting_parameter = $_GET['WYSIWYG'];
        $strSQL = "UPDATE `setting` SET `setting_parameter` = '$setting_parameter' WHERE `setting_id` = $setting_id";
        $sett_arr = do_query($strSQL); 
        header("Location: settings.php");
    }
    
    $strSQL = "SELECT * FROM setting";
    $sett_arr = do_query($strSQL); 
    while($sett = mysql_fetch_array($sett_arr)) {
        $saved = $sett['setting_parameter'];
        switch ($saved){
            case 'off': $off = 'checked'; break;
            case 'summernote': $summernote = 'checked'; break;
            case 'tinymce': $tinymce = 'checked'; break;
        }
        ?>
        <p><?php echo $sett['setting_title']; ?></p>
        <form>
            <input type="hidden" name="setting_id" value="<?php echo $sett['setting_id']; ?>">
            <input type="radio" name="WYSIWYG" id="1" value="off" <?php echo $off; ?>>
            <label for="1">Отключить</label>
            <input type="radio" name="WYSIWYG" id="2" value="summernote" <?php echo $summernote; ?>>
            <label for="2">Summernote</label>
            <input type="radio" name="WYSIWYG" id="3" value="tinymce" <?php echo $tinymce; ?>>
            <label for="3">TinyMCE</label>
            <input type="submit" value="Сохранить">
        </form>   
    <?php } ?>
    
      
    
    <?php include_once "footer.php";
    
}