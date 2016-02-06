<p><h1><?php echo $content['index'].'. '.$content['title']; ?></h1></p>

<?php while ($task = mysql_fetch_array($res)) { ?>

<p><b><?php echo $task['index'].'. '.$task['task']; ?></b></p>
<p><?php echo $task['answer']; ?></p>

<?php } ?>