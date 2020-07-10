<?php require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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
$saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO develcomnts (devdocid, entryby, entrydt, comments) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
					   GetSQLValueString($_POST['comments'], "text"));							
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	
	echo "<script>function out(){opener.location.reload(1); self.close()};</script>"; };
    echo "<script>out();</script>";
	
	//echo "<script>opener.location.reload(1); self.close();
?>

<!--  $insertGoTo = "DevTrackSum.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (stripos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
-->
 
<?php  $id_DEVLIST = "-1";
if (isset($_GET['id'])) {
  $id_DEVLIST = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Dev Tracking</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//     if (window.opener) {  //if opener is not accessible then we need this check to do not have error in the next line.
//        opener.location.reload(1); //This updates the data on the calling page
//    }
//    window.close();
//}
</script>

</head>

<body>

<table width="50%" border="1" align="center" cellpadding="1" cellspacing="1">
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST">  
  <tr>
    <td colspan="4"><div align="center" class="BlueBold_20"><span class="BlueBold_20">ADD SWMIS </span>Development Comment </div></td>
  </tr>
  <tr>
    <td><div align="right">Comments</div></td>
    <td colspan="3"><textarea name="comments" cols="50" rows="5" id="comments"></textarea></td>
  </tr>
  <tr>
    <td><?php echo $_SESSION['user']; ?>&nbsp;</td>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="ADD" />
      <input type="hidden" name="id" Value = "<?php echo $id_DEVLIST; ?>"/> 
      <input type="hidden" name="entryby" Value = "<?php echo $_SESSION['user']; ?>"/> 
      <input type="hidden" name="entrydt" Value = "<?php echo date("Y-m-d H:i"); ?>" /></td>
  </tr>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</table>

</body>
</html>
