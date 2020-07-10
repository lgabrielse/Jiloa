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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE dropdownlist SET list=%s, name=%s, seq=%s, entrydt=%s, entryby=%s WHERE id=%s",
                       GetSQLValueString($_POST['list'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['seq'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "DropDownList.php?list=".$_POST['list'];
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_list = "SELECT name FROM dropdownlist where list = 'list' ORDER BY seq,list ASC";
$list = mysql_query($query_list, $swmisconn) or die(mysql_error());
$row_list = mysql_fetch_assoc($list);
$totalRows_list = mysql_num_rows($list);

$colname_Edit = "-1";
if (isset($_GET['id'])) {
  $colname_Edit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Edit = sprintf("SELECT id, list, name, seq FROM dropdownlist WHERE id = %s", $colname_Edit);
$Edit = mysql_query($query_Edit, $swmisconn) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
      <table width="100%" bgcolor="#F8FDCE">
        <tr>
          <td>&nbsp;</td>
          <td nowrap="nowrap" class="subtitlebk">Edit Drop Down Menu Item </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">List:</div></td>
          <td><input name="list" type="text" id="list" readonly="readonly" value="<?php echo $row_Edit['list'];?>" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Name:</div></td>
          <td><input name="name" type="text" id="name" value="<?php echo $row_Edit['name']; ?>" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Seq:</div></td>
          <td><input name="seq" type="text" id="seq" value="<?php echo $row_Edit['seq']; ?>" /></td>
        </tr>
        <tr>
          <td>
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
    			<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
            <input name="id" type="hidden" id="id" value="<?php echo $row_Edit['id']; ?>" /></td>
          <td>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit DDL Item" />
			 </td>
        </tr>
      </table>
        
      <input type="hidden" name="MM_update" value="form1">
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($list);

mysql_free_result($Edit);
?>
