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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "anestdrugadd")) {
  $insertSQL = sprintf("INSERT INTO anestdruglist (drug, active, entryby, entrydt) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['drug'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "AnestDrugView.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_anestdrugadd = "SELECT id, drug, active, entryby, entrydt FROM anestdruglist";
$anestdrugadd = mysql_query($query_anestdrugadd, $swmisconn) or die(mysql_error());
$row_anestdrugadd = mysql_fetch_assoc($anestdrugadd);
$totalRows_anestdrugadd = mysql_num_rows($anestdrugadd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Anesthetic Drug Add</title>
</head>

<body>

<table align='center' width="20%" border="1" cellspacing="1" cellpadding="1">
<form action="<?php echo $editFormAction; ?>" method="POST" name="anestdrugadd">
  <tr>
    <td colspan="2" align='center'>ADD ANESTHETIC DRUG</td>
    </tr>
  <tr>
    <td align="right">Drug</td>
    <td><input id="drug" name="drug" type="text" size="15" maxlength="30" /></td>
  </tr>
  <tr>
    <td align="right">Active</td>
    <td>
      <select name="active" id="active">
        <option value="Y">YES</option>
        <option value="N">NO</option>
      
      </select>
    </td>
    
  </tr>
  <tr>
    <td><a href="AnestDrugView.php" title="AnestDrugView.php">Close</a></td>
    <td align="right">
    <input name="entryby" type="hidden" value="$_SESSION['user']" />
    <input name="entrydt" type="hidden" value="<?php echo date('Y-m-d') ?>" />
      <input type="submit" name="Submit" id="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Drug" />
      </td>
  </tr>
  <input type="hidden" name="MM_insert" value="anestdrugadd" />
</form>
</table>

</body>
</html>
<?php
mysql_free_result($anestdrugadd);
?>
