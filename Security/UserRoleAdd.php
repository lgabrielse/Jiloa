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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO user_role (userid, roleid) VALUES (%s, %s)",
                       GetSQLValueString($_POST['userid'], "int"),
                       GetSQLValueString($_POST['roleid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "UserRoleMenu.php";
	  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_User = "Select ID, UserID from users where active = 'Y' Order by userid";
$User = mysql_query($query_User, $swmisconn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Role = "Select id, role from roles where active = 'Y' order by role";
$Role = mysql_query($query_Role, $swmisconn) or die(mysql_error());
$row_Role = mysql_fetch_assoc($Role);
$totalRows_Role = mysql_num_rows($Role);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table  width="50%" border="1" bgcolor="#BCFACC">
	<tr>
	   <td>
	  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		<table width="50%" bgcolor="#BCFACC" >
		  
		  <tr>
			<td colspan="2" align="center">Add User Roles</td>
		  </tr>
		  
		  <tr>
			<td align="right">User</td>
			<td>
			  <select name="userid" id="userid">
<?php
		do {  
?>
			  <option value="<?php echo $row_User['ID']?>"><?php echo $row_User['UserID']?></option>
<?php } 
		while ($row_User = mysql_fetch_assoc($User));
		  $rows = mysql_num_rows($User);
		  if($rows > 0) {
			  mysql_data_seek($User, 0);
			  $row_User = mysql_fetch_assoc($User);
		  }
?>
		    </select>			</td>
		  </tr>
		  <tr>
			<td align="right">Role</td>
			<td>
			  <select name="roleid" id="roleid">
<?php
do {  
?>
			 <option value="<?php echo $row_Role['id']?>"><?php echo $row_Role['role']?></option>
<?php }
		 while ($row_Role = mysql_fetch_assoc($Role));
		  $rows = mysql_num_rows($Role);
		  if($rows > 0) {
			  mysql_data_seek($Role, 0);
			  $row_Role = mysql_fetch_assoc($Role);
		  }
?>
		    </select>			</td>
		  </tr>
		  <tr>
			<td><a href="UserRoleMenu.php">Close</a></td>
			<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
		  </tr>
		</table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
<?php // } ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($User);

mysql_free_result($Role);

?>
<?php
ob_end_flush();
?>
