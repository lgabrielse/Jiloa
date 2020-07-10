<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/bethanyconn.php'); ?>
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
$query_anestdruglist = "SELECT id, drug, active FROM anestdruglist";
$anestdruglist = mysql_query($query_anestdruglist, $swmisconn) or die(mysql_error());
$row_anestdruglist = mysql_fetch_assoc($anestdruglist);
$totalRows_anestdruglist = mysql_num_rows($anestdruglist);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Anesthetic Drug</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="25%" border="1" align="center" cellpadding="1" cellspacing="1">
  <caption class="BlueBold_24">
    View Anesthetic Drug
  </caption>
  <tr>
    <td><a href="SetUpMenu.php" title='SetUpMenu.php'>Menu</a></td>
    <td align="right"><a href="AnestDrugAdd.php" title="AnestDrugAdd.php">Add</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
  <td>&nbsp;</td>
    <td>id</td>
    <td>drug</td>
    <td>Active</td>
  </tr>
  <?php do { ?>
    <tr>
    <td><a href="AnestDrugEdit.php?id=<?php echo $row_anestdruglist['id']; ?>" title="AnestDrugEdit.php">Edit</a></td>
      <td><?php echo $row_anestdruglist['id']; ?></td>
      <td><?php echo $row_anestdruglist['drug']; ?></td>
      <td><?php echo $row_anestdruglist['active']; ?></td>
    </tr>
    <?php } while ($row_anestdruglist = mysql_fetch_assoc($anestdruglist)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($anestdruglist);
?>
