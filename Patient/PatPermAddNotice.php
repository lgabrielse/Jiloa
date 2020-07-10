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
if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Add Notice' AND isset($_POST['mrn'])) {
//echo 'got here';
//exit;
  $insertSQL = sprintf("INSERT INTO patnotices (medrecnum, noticeid, entryby, entrydt) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['mrn'], "int"),
                       GetSQLValueString($_POST['notices'], "int"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	$saved = "true";
}
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_notices = "Select id, notice from notices order by notice";
$notices = mysql_query($query_notices, $swmisconn) or die(mysql_error());
$row_notices = mysql_fetch_assoc($notices);
$totalRows_notices = mysql_num_rows($notices);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Notice</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>

</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php } else {?>
<body>
<table width="40%" border="1" align="center" cellpadding="2" cellspacing="2">
<form name="AddNotice" method="post" action="<?php echo $editFormAction; ?>">  <tr>
    <td colspan="2" nowrap="nowrap">Add Notice to MedRecNum <?php echo $_SESSION['mrn'] ?> </td>
    </tr>
  <tr>
    <td><select name="notices" id="notices">
      <?php
do {  
?>
      <option value="<?php echo $row_notices['id']?>"><?php echo $row_notices['notice']?>      </option>
      <?php
} while ($row_notices = mysql_fetch_assoc($notices));
  $rows = mysql_num_rows($notices);
  if($rows > 0) {
      mysql_data_seek($notices, 0);
	  $row_notices = mysql_fetch_assoc($notices);
  }
?>
    </select></td>
	
    <td>
		<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
		<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		<input name="mrn" type="hidden" value="<?php echo $_SESSION['mrn']; ?>" />
	  <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Notice" /></td>
  </tr>
</form></table>

</body>
</html>
<?php
}
mysql_free_result($notices);
?>
