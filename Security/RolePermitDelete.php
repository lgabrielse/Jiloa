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
  $deleteSQL = sprintf("DELETE FROM role_permit WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "RolePermitMenu.php";
   header(sprintf("Location: %s", $deleteGoTo));
}

$mm_delete = "-1";
if (isset($_GET['id'])) {
  $mm_delete = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_swmisconn, $swmisconn);		
$query_role_permit = sprintf("SELECT r.id r_id, r.role, p.id p_id, p.permit, rp.id rp_id FROM permits p join role_permit rp on p.id = rp.permitid join roles r on rp.roleid = r.id where rp.id = %s", $mm_delete);
$role_permit = mysql_query($query_role_permit, $swmisconn) or die(mysql_error());
$row_role_permit = mysql_fetch_assoc($role_permit);
$totalRows_role_permit = mysql_num_rows($role_permit);
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
				<td colspan="2"><div align="center">Delete Role Permit </div></td>
			  </tr>
			  <tr>
				<td><div align="right">Role</div></td>
				<td><input name="role" type="text" id="role" readonly="readonly"value="<?php echo $row_role_permit['role']; ?>" /></td>
			  </tr>
			  <tr>
				<td><div align="right">Role</div></td>
				<td><input name="permit" type="text" id="permit" readonly="readonly" value="<?php echo $row_role_permit['permit']; ?>" /></td>
			  </tr>
			  <tr>
				<td><input type="hidden" name="MM_delete" value="form1"/>
					<input name="id" type="hidden" id="id" value="<?php echo $row_role_permit['rp_id']; ?>" />
				  <a href="RolePermitMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Role Permit" /></td>
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
mysql_free_result($role_permit);
?>
