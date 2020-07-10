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
$query_ProjView = "SELECT id, projname, projversion, projdescr, projpriority, projstatus, entryby, entrydt FROM devproject";
$ProjView = mysql_query($query_ProjView, $swmisconn) or die(mysql_error());
$row_ProjView = mysql_fetch_assoc($ProjView);
$totalRows_ProjView = mysql_num_rows($ProjView);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p></p>
<table border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><a href="../Setup/SetUpMenu.php">Menu</a></td>
    <td>&nbsp;</td>
    <td colspan="2" align="center" class="BlueBold_24">Development Project View</td>
    <td>&nbsp;</td>
    <td><a href="DevProjectAdd.php">Add Project</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>id</td>
    <td>projname</td>
    <td>projversion</td>
    <td>projpriority</td>
    <td>projstatus</td>
    <td>projdescr</td>
  </tr>
  <?php do { ?>
    <tr>
      <td>Select</td>
      <td>Edit</td>
      <td><?php echo $row_ProjView['id']; ?></td>
      <td title="Entry By: <?php echo $row_ProjView['entryby']; ?>&#10;Entry Date: <?php echo $row_ProjView['entrydt']; ?>"><?php echo $row_ProjView['projname']; ?></td>
      <td><?php echo $row_ProjView['projversion']; ?></td>
      <td><?php echo $row_ProjView['projpriority']; ?></td>
      <td><?php echo $row_ProjView['projstatus']; ?></td>
      <td><?php echo $row_ProjView['projdescr']; ?></td>
    </tr>
    <?php } while ($row_ProjView = mysql_fetch_assoc($ProjView)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($ProjView);
?>
