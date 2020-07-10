<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM state WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "AddrStateView.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_stat = "-1";
if (isset($_GET['id'])) {
  $colname_stat = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_stat = sprintf("SELECT id, state FROM state WHERE id = %s", $colname_stat);
$stat = mysql_query($query_stat, $swmisconn) or die(mysql_error());
$row_stat = mysql_fetch_assoc($stat);
$totalRows_stat = mysql_num_rows($stat);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Fee Schedule</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%"  bgcolor="#FBD0D7">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Delete state </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">state</div></td>
          <td><input name="state" type="text" id="state" readlonly="readonly" value="<?php echo $row_stat['state']; ?>" /></td>
        </tr>
        <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
              <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
              <input name="id" type="hidden" value="<?php echo $row_stat['id']; ?>" /></td>
          <td><p>
              <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete state" />
          </p></td>
        </tr>
        <tr>
          <td align="right" valign="top" class="RedBold_18">Warning:</td>
          <td valign="top" class="RedBold_18">Deleting a state will destroy links to all related LocGovts and Citys </td>
        </tr>
      </table>
    </form>    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($stat);

?>
