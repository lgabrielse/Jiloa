<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>

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
 
		if (isset($_POST['SubmitRec']) AND $_POST['SubmitRec']  == 'Confirm Resulted')  {
		  $updateSQL = sprintf("UPDATE orders SET status=%s WHERE id=%s",
							   GetSQLValueString($_POST['status'], "text"),
							   GetSQLValueString($_POST['ordid'], "int"));
		
		  mysql_select_db($database_swmisconn, $swmisconn);
		  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  		  $saved = "true";
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
	<?php }?>

<body>
<div align="center" class="BlueBold_20">RESET TO RESULTED </div>
<table width="300" border="0" align="center">
<form id="form1" name="form1" method="post" action="LabPopReview.php">
  <tr>
    <td><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F8FDCE" class="BlueBold_14"><div align="center">Section</div></td>
    <td bgcolor="#F8FDCE" class="BlueBold_14"><div align="center">Specimen</div></td>
    <td bgcolor="#F8FDCE" class="BlueBold_14"><div align="center">Test</div></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><?php echo $_GET['section'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $_GET['spec'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $_GET['test'] ?></td>
  </tr>
  <tr>
    <td bgcolor="#F8FDCE">Ord ID: <?php echo $_GET['ordid'] ?></td>
    <td colspan="2" bgcolor="#F8FDCE"><label>
		<input name="status" type="hidden" value="Resulted" />		
		<input name="ordid" type="hidden" value="<?php echo $_GET['ordid'] ?>" />		
		<input type="submit" name="SubmitRec" value="Confirm Resulted" />
    </label></td>
    </tr>
</form>
</table>


</body>
</html>
