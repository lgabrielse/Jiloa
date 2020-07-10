<!--Resource:  http://mukundtopiwala.blogspot.com/2012/07/run-php-script-automatically-on-windows.html-->

<?php  $pt = "Backup-Auto"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php // require_once($_SERVER['DOCUMENT_ROOT'].'/Len/Connections/backupconn.php');?>
<?php require_once('C:\wamp64\www\Len\Connections/backupconn.php');?>
<?php //session_start()?>

<?php
mysqli_select_db($backupconn, $database_backupconn);
$query_logs = "SELECT id, pgm, `path`, dabase, sqlfile, `size`, `user`, entrydt FROM backuplog Where SUBSTR(pgm,1,4) = 'Auto' ORDER BY entrydt desc";
$logs = mysqli_query($backupconn, $query_logs) or die(mysql_error($query_logs));
$row_logs = mysqli_fetch_assoc($logs);
$totalRows_logs = mysqli_num_rows($logs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Backup_Admin</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center"><a href="BackupMenu.php" class="nav">Return to Backup Menu </a></div>

<table align="center">
  <tr>
    <td colspan="8" bgcolor="#00FFFF">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="8" bgcolor="#00FFFF"><div align="center" class="BlackBold_18">Automated Scheduled Database Backups <span class="BlackBold_10">(latest on top)</span> </div></td>
  </tr>
  <tr>
    <td bgcolor="#66CCFF"><div align="center">id</div></td>
    <td bgcolor="#66CCFF"><div align="center">Source</div></td>
    <td nowrap="nowrap" bgcolor="#66CCFF"><div align="center">Server Directory Path</div></td>
    <td bgcolor="#66CCFF"><div align="center">Database</div></td>
    <td bgcolor="#66CCFF"><div align="center">SQL File</div></td>
    <td bgcolor="#66CCFF"><div align="center">Size</div></td>
    <td bgcolor="#66CCFF"><div align="center">User</div></td>
    <td bgcolor="#66CCFF"><div align="center">Entrydt</div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['id']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['pgm']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['path']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['dabase']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['sqlfile']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['size']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['user']; ?></td>
      <td bgcolor="#FFFDDA"><?php echo $row_logs['entrydt']; ?></td>
    </tr>
    <?php } while ($row_logs = mysqli_fetch_assoc($logs)); ?>
</table>
<p>&nbsp;</p>


</body>
</html>
<?php
mysqli_free_result($logs);
?>
