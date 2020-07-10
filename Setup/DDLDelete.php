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
  $deleteSQL = sprintf("DELETE FROM dropdownlist WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "DropDownList.php?list=".$_POST['list'];
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}

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
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#FBD0D7">
        <tr>
          <td>&nbsp;</td>
          <td nowrap="nowrap" class="subtitlebk">Delete Drop Down Menu Item </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">List:</div></td>
          <td><input name="list" type="text" id="list" value="<?php echo $row_Edit['list']; ?>" /></td>
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
          <td><input name="id" type="hidden" id="id" value="<?php echo $row_Edit['id']; ?>" /></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete DDL Item" />
          </p></td>
        </tr>
      </table>
        
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Edit);
?>
