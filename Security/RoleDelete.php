<?php ob_start(); ?>
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
  $deleteSQL = sprintf("DELETE FROM roles WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "RoleMenu.php?list=".$_POST['list'];
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_del = "-1";
if (isset($_GET['id'])) {
  $colname_del = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_roleS = sprintf("Select id, role, descr, active from roles WHERE id = %s order by role", $colname_del);
$roleS = mysql_query($query_roleS, $swmisconn) or die(mysql_error());
$row_roleS = mysql_fetch_assoc($roleS);
$totalRows_roleS = mysql_num_rows($roleS);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="50%" align="center" border="1">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#FBD0D7" >
        <tr>
          <td>&nbsp;</td>
          <td nowrap="nowrap" class="subtitlebk">Delete Role </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">ID:</div></td>
          <td><input name="list" type="text" id="list" value="<?php echo $row_roleS['id']; ?>" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Role:</div></td>
          <td><input name="name" type="text" id="name" value="<?php echo $row_roleS['role']; ?>" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Descr:</div></td>
          <td><input name="seq" type="text" id="seq" value="<?php echo $row_roleS['descr']; ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Active:</div></td>
          <td><input name="seq" type="text" id="seq" value="<?php echo $row_roleS['active']; ?>" /></td>
        </tr>
        <tr>
          <td><input name="id" type="hidden" id="id" value="<?php echo $row_roleS['id']; ?>" />
            <a href="RoleMenu.php">Close</a></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Role" />
          </p></td>
        </tr>
      </table>
        
    </form>
    </td>
  </tr>
</table>

<?php
mysql_free_result($roleS);
?>
<?php
ob_end_flush();
?>
