<?php  $pt = "Backup-Admin Manual"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Len/Connections/backupconn.php');?>
<?php //session_start()?>
<?php function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
?>

<?php if (isset($_POST['dobackup'])) {?>
<!--   Resource: http://tips-scripts.com/sql_backups    -->
<?php $host="localhost"; // database host
	$dbuser="root"; // database user name
	$dbpswd="jiloa7"; // database password
//	$mysqldb="swmis"; // name of database
	$mysqldb=$_POST['database']; // name of database
//	$path = "C:\wamp64\www\Len\Jiloa\Backup"; // full server path to the directory where you want the backup files (no trailing slash)
	$path = $_POST['backuppath']; // full server path to the directory where you want the backup files (no trailing slash)
// modify the above values to fit your environment
	$sqlfile = "/".$_POST['database'].$_POST['file']. ".sql";
	$filename = $path . "/".$_POST['database'].$_POST['file']. ".sql";
	if ( file_exists($filename) ) unlink($filename);
	system("C:\wamp64\bin\mysql\mysql5.7.23\bin\mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb > $filename",$result);
	$size = filesize($filename);
	switch ($size) {
	  case ($size>=1048576): $size = round($size/1048576) . " MB"; break;
	  case ($size>=1024): $size = round($size/1024) . " KB"; break;
	  default: $size = $size . " bytes"; break;
	}
	$message = "The database backup for " . $mysqldb . " has been run.\n\n";
	$message .= "The return code was: " . $result . "\n\n";
	$message .= "The file path is: " . $filename . "\n\n";
	$message .= "Size of the backup: " . $size . "\n\n";

	$msgdb = "The database backup for " . $mysqldb . " has been run.\n\n";
	$msgcode ="The return code was:   " . $result . "\n\n";
	$msgpath = "The file path is:     " . $path . "\n\n";
	$msgfile = "The file Name is:     " . "/".$_POST['database'].$_POST['file']. ".sql". "\n\n";	
	$msgsize = "Size of the backup:   " . $size . "\n\n";
}
?>
<?php $mydate = date("Y-m-d--H-i-s"); ?>

<?php
if (isset($result) AND $result == '0' AND isset($_POST['database'])) {  //       %s, %s, %s, 
$insertSQL = sprintf("INSERT INTO backuplog (pgm, path, dabase, sqlfile, size, user, entrydt ) VALUES (%s, %s, %s, %s, %s, %s, %s)",
			 GetSQLValueString('manual', "text"),
			 GetSQLValueString($path, "text"),
			 GetSQLValueString($mysqldb, "text"),
			 GetSQLValueString($sqlfile, "text"),
			 GetSQLValueString($size, "text"),
			 GetSQLValueString($_SESSION['user'], "text"),
			 GetSQLValueString($mydate, "text"));
			 
mysqli_select_db($backupconn, $database_backupconn);
$Result1 = mysqli_query($backupconn, $insertSQL) or die(mysqli_error($insertSQL));
}
?>
<?php
mysqli_select_db($backupconn, $database_backupconn);
$query_logs = "SELECT id, pgm, `path`, dabase, sqlfile, `size`, `user`, entrydt FROM backuplog Where pgm = 'manual' ORDER BY entrydt desc";
$logs = mysqli_query($backupconn, $query_logs) or die(mysqli_error($query_logs));
$row_logs = mysqli_fetch_assoc($logs);
$totalRows_logs = mysqli_num_rows($logs);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Backup-Admin</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p align="center"><a href="BackupMenu.php" class="nav">Return to Backup Menu </a></p>
<table width="50%" border="0" align="center">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="1">
        <tr>
          <td>&nbsp;</td>
          <td><div align="center">Backup Database</div></td>
        </tr>
        <tr>
          <td>Database</td>
          <td><select name="database" id="database">
            <option value="swmisbethany" selected="selected">BETHANY</option>
            <option value="swmistraining">TRAINING</option>
            <option value="swmisswmis">SWMIS</option>
          </select> 
            Used for filename prefix </td>
        </tr>
        <tr>
          <td>Store in  Directory </td>
          <td nowrap="nowrap">
		  <select name="backuppath" id="backuppath" >
            <option value="C:\wamp64\www\Len\Backup\AdminManualFiles">In Backup directory on Local Drive C</option>
            <option value="E:\BETHANY">BETHANY folder in External Drive E</option>
            <option value="E:\SWMIS">SWMIS folder in External Drive E</option>
            <option value="E:\TRAINING">TRAINING folder in External Drive E</option>
          </select> 
<!--		  <input name="backuppath" type="text" id="backuppath" value="C:\wamp64\www\Len\Backup\AdminManualFiles" size="60" /> -->           
            default = C:/wamp64/www/Len/Backup/</td>
        </tr>
        <tr>
          <td>FileName Suffix</td>
          <td><input name="file" type="text" value="_<?php echo date("Y-m-d--H-i-s"); ?>" size="40"/>
            default = /database_name/current date &amp; time </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="center"><a href="BackupMenu.php">Exit</a></div></td>
          <td><input name="saveBackup" type="submit" id="saveBackup" value="Save Backup File" /> 
		  <input name="dobackup" type="hidden" value="dobackup" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="BlackBold_16">Message: <!--- Host:<?php // echo $host; ?>  User: <?php //echo $dbuser; ?> Password: <?php //echo $dbpswd; ?>--></td>
        </tr>
<?php if (!empty($message)){
?>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td class="GreenBold_18"><?php echo $msgdb ?></td>
        </tr>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td class="GreenBold_18"><?php echo $msgcode ?>    (0 =  Success)</td>
        </tr>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td class="GreenBold_18"><?php echo $msgpath ?></td>
        </tr>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td class="GreenBold_18"><?php echo $msgfile ?></td>
        </tr>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td class="GreenBold_18"><?php echo $msgsize ?></td>
        </tr>
        <tr>
          <td class="BlackBold_16">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
 <?php } ?>
    </table>
        </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>

<table align="center">
  <tr>
    <td bgcolor="#66CCFF"><div align="center">id</div></td>
    <td bgcolor="#66CCFF"><div align="center">Source</div></td>
    <td bgcolor="#66CCFF"><div align="center">Path</div></td>
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
