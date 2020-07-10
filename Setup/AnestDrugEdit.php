<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
  $updateSQL = sprintf("UPDATE anestdruglist SET drug=%s, active=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['drug'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "AnestDrugView.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$drugid = 0;
if(isset($_GET['id'])) {
$drugid = $_GET['id'];
}
	
mysql_select_db($database_swmisconn, $swmisconn);
$query_anestedit = "SELECT id, drug, active FROM anestdruglist WHERE id='".$drugid."'";
$anestedit = mysql_query($query_anestedit, $swmisconn) or die(mysql_error());
$row_anestedit = mysql_fetch_assoc($anestedit);
$totalRows_anestedit = mysql_num_rows($anestedit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Anesthetic Drug</title>
</head>

<body>
<table width="20%" border="1" align="center" cellpadding="1" cellspacing="1">
<form name="form" action="<?php echo $editFormAction; ?>" method="POST">  
<tr>
    <td colspan="2" align="center">EDIT ANESTHETIC DRUG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  id=<?php echo $row_anestedit['id'];?></td>
  </tr>
  <tr>
    <td>drug</td>
    <td><input name="drug" type="text" value="<?php echo $row_anestedit['drug'];?>" size="15" maxlength="30" /></td>
  </tr>
  <tr>
    <td>active</td>
    <td><select name="active">
      <option value="Y"<?php if (!(strcmp("Y", $row_anestedit['active']))) {echo "selected=\"selected\"";} ?>>YES</option>
      <option value="N"<?php if (!(strcmp("N", $row_anestedit['active']))) {echo "selected=\"selected\"";} ?>>NO</option>
      &nbsp;</select>
    </td>
  </tr>
  <tr>
    <td align="center"><a href="AnestDrugView.php" title="AnestDrugView.php">Close</a></td>
    <input name="id" type="hidden" value="<?php echo $row_anestedit['id'] ?>" />
    <input name="entryby" type="hidden" value="<?php echo $_SESSION['user'] ?>" />
    <input name="entrydt" type="hidden" value="<?php echo date('Y-m-d') ?>" />
    <td align="right"><input name="submit" type="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Drug" /></td>
  </tr>
  <input type="hidden" name="MM_update" value="form" />
  </form>
</table>

</body>
</html>
<?php
mysql_free_result($anestedit);
?>
