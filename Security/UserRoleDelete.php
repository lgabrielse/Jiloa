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

if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "form1")) {
  $deleteSQL = sprintf("DELETE FROM user_role WHERE id=%s",
                       GetSQLValueString($_POST['urid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "UserRoleMenu.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

$mm_Edit = "-1";
if (isset($_GET['urid'])) {
  $mm_Edit = (get_magic_quotes_gpc()) ? $_GET['urid'] : addslashes($_GET['urid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Edit = sprintf("SELECT u.id u_id, u.userid, r.id r_id, r.role, ur.id ur_id FROM users u join user_role ur on u.id = ur.userid join roles r on ur.roleid = r.id WHERE ur.id = %s", $mm_Edit);
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
<p>&nbsp;</p>
<table width="50%" border="1" bgcolor="#FBD0D7">
	<tr>
		<td>
			<table>
			  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">  <tr>
				<td colspan="2"><div align="center">Delete User Role </div></td>
			  </tr>
			  <tr>
				<td><div align="right">User</div></td>
				<td><input name="userid" type="text" id="userid" readonly="readonly"value="<?php echo $row_Edit['userid']; ?>" /></td>
			  </tr>
			  <tr>
				<td><div align="right">Role</div></td>
				<td><input name="roleid" type="text" id="roleid" readonly="readonly" value="<?php echo $row_Edit['role']; ?>" /></td>
			  </tr>
			  <tr>
				<td><input type="hidden" name="MM_delete" value="form1"/>
					<input name="urid" type="hidden" id="urid" value="<?php echo $row_Edit['ur_id']; ?>" />
				  <a href="UserRoleMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete User Role" /></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  </form>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mysql_free_result($Edit);
?>
<?php
ob_end_flush();
?>
