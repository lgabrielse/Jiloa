<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
  $saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM patnotices WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
}

$colname_notice = "-1";
if (isset($_GET['id'])) {
  $colname_notice = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_notice = sprintf("SELECT p.id, p.medrecnum, p.noticeid, n.notice, n.tooltip FROM patnotices p join notices n on p.noticeid = n.id  WHERE p.id = %s", $colname_notice);
$notice = mysql_query($query_notice, $swmisconn) or die(mysql_error());
$row_notice = mysql_fetch_assoc($notice);
$totalRows_notice = mysql_num_rows($notice);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DeleteNotice</title>
</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php } else {?>
<body>
<p align="center">Delete This Notice </p>

<table width="40%" border="1" align="center" cellpadding="2" cellspacing="2">
<form name="DeleteNotice" method="post" action="">  <tr>
    <td>notice</td>
    <td><input name="notice" type="text" id="notice" readonly="readonly" value="<?php echo $row_notice['notice']; ?>" /></td>
  </tr>
  <tr>
    <td>tooltip</td>
    <td><input name="notice" type="text" id="notice" readonly="readonly" value="<?php echo $row_notice['tooltip']; ?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="id" type="hidden" value="<?php echo $row_notice['id']; ?>" />
      <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" /></td>
  </tr>
</form></table>
<?php } ?>
</body>
</html>
