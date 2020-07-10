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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && $_POST['id'] > 0) {
//  $updateSQL = sprintf("UPDATE develcomnts SET entryby=%s, entrydt=%s, comments=%s, "
  $updateSQL = sprintf("UPDATE develcomnts SET entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

	
	echo "<script>function out(){opener.location.reload(1); self.close()};</script>"; 
    echo "<script>out();</script>";
}
//  $updateGoTo = "DevTrackSum.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
//  }
//  header(sprintf("Location: %s", $updateGoTo));

 $id_comments = "-1";
if (isset($_GET['id'])) {
  $id_comments = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_source = "Select id, comments, entryby, entrydt from develcomnts where id = ".$id_comments."";
$source = mysql_query($query_source, $swmisconn) or die(mysql_error());
$row_source = mysql_fetch_assoc($source);
$totalRows_source = mysql_num_rows($source);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Dev Tracking</title>
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
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST">  <tr>
    <td colspan="4"><div align="center" class="BlueBold_20"><span class="BlueBold_20">Edit SWMIS </span>Development Comments </div></td>
    </tr>
  <tr>
    <td><div align="right">Comments</div></td>
    <td colspan="3"><textarea name="comments" cols="50" rows="5" id="comments"><?php echo $row_source['comments']; ?></textarea></td>
  </tr>
  <tr>
    <td nowrap="nowrap">ID: <?php echo $row_source['id']; ?></td>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="EDIT" />
      <input type="hidden" name="id" value="<?php echo $row_source['id'];?>" />
      <input type="hidden" name="entryby" Value = "<?php echo $_SESSION['user']; ?>"/>
      <input type="hidden" name="entrydt" Value = "<?php echo date("Y-m-d H:i"); ?>" /></td>
  </tr>
  <input type="hidden" name="MM_update" value="form1">
</form>
</table>

</body>
</html>
<?php
mysql_free_result($source);
?>
