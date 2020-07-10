<?php require_once('C:\wamp64\www\Len\Connections/backupconn.php');?>
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

<?php
// $emailaddress = "lgabrielse@verizon.net";

$host="localhost"; // database host
$dbuser="root"; // database user name
$dbpswd="jiloa7"; // database password
$mysqldb="swmisbethany"; // name of database
$path = "C:\wamp64\www\Len\Backup\AutoBethany"; // full server path to the directory where you want the backup files (no trailing slash)
// modify the above values to fit your environment
$sqlfile = "/BethanyBackup" . date("d") . ".sql";
$filename = $path . "/BethanyBackup" . date("d") . ".sql";
if ( file_exists($filename) ) unlink($filename);
//system("C:\wamp64\bin\mysql\mysql5.6.12\bin\mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb > $filename",$result); (needed on some)
system("C:\wamp64\bin\mysql\mysql5.7.23\bin\mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb > $filename",$result);
$size = filesize($filename);
switch ($size) {
  case ($size>=1048576): $size = round($size/1048576) . " MB"; break;
  case ($size>=1024): $size = round($size/1024) . " KB"; break;
  default: $size = $size . " bytes"; break;
}
?>
<?php $mydate = date("Y-m-d--H-i-s"); ?>

<?php
if (isset($result) AND $result == '0') {  
$insertSQL = sprintf("INSERT INTO backuplog (pgm, path, dabase, sqlfile, size, user, entrydt ) VALUES (%s, %s, %s, %s, %s, %s, %s)",
			 GetSQLValueString('AutoBethany', "text"),
			 GetSQLValueString($path, "text"),
			 GetSQLValueString($mysqldb, "text"),
			 GetSQLValueString($sqlfile, "text"),
			 GetSQLValueString($size, "text"),
			 GetSQLValueString('System', "text"),
			 GetSQLValueString($mydate, "text"));
			 
mysqli_select_db($backupconn, $database_backupconn);
$Result1 = mysqli_query($backupconn, $insertSQL) or die(mysqli_error($backupconn));
}
?>
