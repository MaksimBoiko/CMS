<?php
$strSQL = "SELECT section_id, section_title FROM section";
$content_arr = do_query($strSQL);
$my_query = $query_link;
global $url_req; 
$i = 0; ?>

<div class="filter">
    <div class="work-list">
        <form>
            <?php if (isset($_GET['cls'])) { ?><input type='hidden' name='cls' value='<?php echo $_GET['cls']; ?>'> <?php } ?>
            <?php if (isset($_GET['type'])) { ?><input type='hidden' name='type' value='<?php echo $_GET['type']; ?>'> <?php } ?>
        <?php while($content = mysql_fetch_array($content_arr)) { 
            
            // проверяем, активна ли ссылка
            if ($_GET['sect'] == $content['section_id']) $checked = "checked";
            
            ?>
            <input type="radio" name="sect" id="<?php echo $i; ?>" value="<?php echo $content['section_id']; ?>" <?php echo $checked; $checked=''; ?> onchange="this.form.submit()">
            <label for="<?php echo $i++; ?>"><?php echo $content['section_title']; ?></label>
        <?php } ?>
        </form>
    </div>
    
<?php $strSQL = "SELECT DISTINCT class FROM content ORDER BY `class` DESC";
$class_arr = do_query($strSQL); ?>

    <div class="class-list">
        <form>
            <?php if (isset($_GET['sect'])) { ?><input type='hidden' name='sect' value='<?php echo $_GET['sect']; ?>'> <?php } ?>
            <?php if (isset($_GET['type'])) { ?><input type='hidden' name='type' value='<?php echo $_GET['type']; ?>'> <?php } ?>
        <?php while($content = mysql_fetch_array($class_arr)) { 
            
            // проверяем, активна ли ссылка
            if ($_GET['cls'] == $content['class']) $checked = "checked";
            
            ?>
            <input type="radio" name="cls" id="<?php echo $i; ?>" value="<?php echo $content['class']; ?>" <?php echo $checked; $checked=''; ?> onchange="this.form.submit()">
            <label for="<?php echo $i++; ?>"><?php echo $content['class'].' класс'; ?></label>
        <?php } ?>
        </form>
    </div>
    
<?php $strSQL = "SELECT DISTINCT work_type FROM content";
$wt_arr = do_query($strSQL); ?>
    
    <div class="class-list">
        <form>
            <?php if (isset($_GET['sect'])) { ?><input type='hidden' name='sect' value='<?php echo $_GET['sect']; ?>'> <?php } ?>
            <?php if (isset($_GET['cls'])) { ?><input type='hidden' name='cls' value='<?php echo $_GET['cls']; ?>'> <?php } ?>
        <?php while($content = mysql_fetch_array($wt_arr)) { 
            switch ($content['work_type']) {
                case 'theme': case 'less': $name_type = 'Тема'; break;
                case 'pract': $name_type = 'Практическая'; break;
                case 'lab': $name_type = 'Лабораторная'; break;
                case 'ticket': $name_type = 'Экзамен'; break;
                case 'resh': $name_type = 'Решебник'; break;
                default: $name_type = 'Неизвестно'; break;
            }
            // проверяем, активна ли ссылка
            if ($_GET['type'] == $content['work_type']) $checked = "checked";
            
            ?>
            <input type="radio" name="type" id="<?php echo $i; ?>" value="<?php echo $content['work_type']; ?>" <?php echo $checked; $checked=''; ?> onchange="this.form.submit()">
            <label for="<?php echo $i++; ?>"><?php echo $name_type; ?></label>
        <?php } ?>
        </form>
    </div>
</div>

<?php
    if (isset($_GET['sect'])) $filter_query = " AND content.section_id=".$_GET['sect'];
    if (isset($_GET['cls'])) $filter_query = $filter_query." AND content.class='".$_GET['cls']."'";
    if (isset($_GET['type'])) $filter_query = $filter_query." AND content.work_type='".$_GET['type']."'";
?>