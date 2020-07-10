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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE user_role SET userid=%s, roleid=%s WHERE id=%s",
                       GetSQLValueString($_POST['userid'], "int"),
                       GetSQLValueString($_POST['roleid'], "int"),
                       GetSQLValueString($_POST['urid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "UserRoleMenu.php";
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_User = "Select ID, UserID from users Where Active = 'Y' Order by userid";
$User = mysql_query($query_User, $swmisconn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Role = "Select id, role from roles where active = 'Y' Order by role";
$Role = mysql_query($query_Role, $swmisconn) or die(mysql_error());
$row_Role = mysql_fetch_assoc($Role);
$totalRows_Role = mysql_num_rows($Role);

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
<table width="50%" border="1" bgcolor="#F8FDCE">
	<tr>
		<td>
			<table>
			  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">  <tr>
				<td colspan="2"><div align="center">Edit User Role </div></td>
			  </tr>
			  <tr>
				<td><div align="right">User</div></td>
				<td>
				  <select name="userid" id="userid">
					<?php
			do {  
			?>
					<option value="<?php echo $row_User['ID']?>"<?php if (!(strcmp($row_User['ID'], $row_Edit['u_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_User['UserID']?></option>
					<?php
			} while ($row_User = mysql_fetch_assoc($User));
			  $rows = mysql_num_rows($User);
			  if($rows > 0) {
				  mysql_data_seek($User, 0);
				  $row_User = mysql_fetch_assoc($User);
			  }
			?>
				  </select>    </td>
			  </tr>
			  <tr>
				<td><div align="right">Role</div></td>
				<td><select name="roleid" id="roleid">
				  <?php
			do {  
			?>
				  <option value="<?php echo $row_Role['id']?>"<?php if (!(strcmp($row_Role['id'], $row_Edit['r_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Role['role']?></option>
				  <?php
			} while ($row_Role = mysql_fetch_assoc($Role));
			  $rows = mysql_num_rows($Role);
			  if($rows > 0) {
				  mysql_data_seek($Role, 0);
				  $row_Role = mysql_fetch_assoc($Role);
			  }
			?>
				</select>
			</td>
			  </tr>
			  <tr>
				<td><input name="urid" type="hidden" id="urid" value="<?php echo $row_Edit['ur_id']; ?>" />
				  <a href="UserRoleMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit User Role" /></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <input type="hidden" name="MM_update" value="form1">
				</form>
			</table>
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($User);

mysql_free_result($Role);

mysql_free_result($Edit);
?>
<?php
ob_end_flush();
?>
