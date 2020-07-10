<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_tokoutreport = "SELECT id, tokenid, userid, last_activity, timed_out FROM token_out";
$tokoutreport = mysql_query($query_tokoutreport, $swmisconn) or die(mysql_error());
$row_tokoutreport = mysql_fetch_assoc($tokoutreport);
$totalRows_tokoutreport = mysql_num_rows($tokoutreport);

mysql_select_db($database_swmisconn, $swmisconn);
$query_tokreport = "SELECT id, userid, token, entrydt FROM token";
$tokreport = mysql_query($query_tokreport, $swmisconn) or die(mysql_error());
$row_tokreport = mysql_fetch_assoc($tokreport);
$totalRows_tokreport = mysql_num_rows($tokreport);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Token Out Report</title>
</head>
<body>
<table align="center" border="1" cellpadding="1">
<tr>
   <td align="Left" style="background-color:black;"><a href="SetUpMenu.php" title='SetUpMenu.php'>Menu</a></td>

   <td align="center" colspan="5" style="color:Orange; background-color:black;"><b>Token Report</b></td>
</tr>
  <tr style="background-color:orange;">
    <td align="center">id</td>
    <td align="center">userid</td>
    <td align="center">entrydt</td>
    <td align="center">Login Duration</td>
  </tr>
  <?php do { ?>
    <tr style="background-color:Orange;">
      <td align="center"><?php echo $row_tokreport['id']; ?></td>
      <td align="center"><?php echo $row_tokreport['userid']; ?></td>
      <td align="center"><?php echo  date('D M d, Y h:i A', $row_tokreport['entrydt']);?></td>
      <td align="center"><?php echo intval((strtotime('now') - $row_tokreport['entrydt'])/60);?>
      </td>    
   </tr>
    <?php } while ($row_tokreport = mysql_fetch_assoc($tokreport)); ?>
</table>
<p></p>
<table align="center" border="1" cellpadding="1">
<tr>
   <td align="center" colspan="6" style="color:Orange; background-color:black;"><b>Token Out Report</b></td>
</tr>
  <tr style="background-color:orange;" >
    <td align="center">id</td>
    <td align="center">tokenid</td>
    <td align="center">userid</td>
    <td align="center">last_activity</td>
    <td align="center">timed_out</td>
    <td align="center">Minutes</td>
  </tr>
  <?php do { ?>
    <tr style="background-color:Orange;">
      <td align="center"><?php echo $row_tokoutreport['id']; ?></td>
      <td align="center"><?php echo $row_tokoutreport['tokenid']; ?></td>
      <td align="center"><?php echo $row_tokoutreport['userid']; ?></td>
      <td align="center"><?php echo  date('D M d, Y h:i A', $row_tokoutreport['last_activity']);?></td>
      <td align="center"><?php echo  date('D M d, Y h:i A', $row_tokoutreport['timed_out']);?></td>
      <td align="center"><?php echo  intval(($row_tokoutreport['timed_out'] - $row_tokoutreport['last_activity'])/60);?></td>
    </tr>
    <?php } while ($row_tokoutreport = mysql_fetch_assoc($tokoutreport)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($tokoutreport);

mysql_free_result($tokreport);
?>
