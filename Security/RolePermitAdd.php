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
  $insertSQL = sprintf("INSERT INTO role_permit (roleid, permitid, level) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['roleid'], "int"),
                       GetSQLValueString($_POST['permitid'], "int"),
                       GetSQLValueString($_POST['level'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "RolePermitMenu.php";
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_Permit = "Select id, permit from permits Order by permit";
$Permit = mysql_query($query_Permit, $swmisconn) or die(mysql_error());
$row_Permit = mysql_fetch_assoc($Permit);
$totalRows_Permit = mysql_num_rows($Permit);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Role = "Select id, role from roles order by role";
$Role = mysql_query($query_Role, $swmisconn) or die(mysql_error());
$row_Role = mysql_fetch_assoc($Role);
$totalRows_Role = mysql_num_rows($Role);

mysql_select_db($database_swmisconn, $swmisconn);
$query_RolePermit = "SELECT r.id r_id, r.role, p.id p_id, p.permit, rp.id rp_id, rp.level FROM permits p join role_permit rp on p.id = rp.permitid join roles r on rp.roleid = r.id order by main, permit";
$RolePermit = mysql_query($query_RolePermit, $swmisconn) or die(mysql_error());
$row_RolePermit = mysql_fetch_assoc($RolePermit);
$totalRows_RolePermit = mysql_num_rows($RolePermit);
?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<table  width="50%" border="1" bgcolor="#BCFACC">
  <tr>
	<td colspan="2"><div align="center">Add Role Permits</div></td>
  </tr>
  <tr>
    <td>
	  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		<table bgcolor="#BCFACC">
		  <tr>
			<td>Role</td>
			<td>
			  <select name="roleid" id="roleid">
			    <?php
do {  
?>
			    <option value="<?php echo $row_Role['id']?>"><?php echo $row_Role['role']?></option>
			    <?php
} while ($row_Role = mysql_fetch_assoc($Role));
  $rows = mysql_num_rows($Role);
  if($rows > 0) {
      mysql_data_seek($Role, 0);
	  $row_Role = mysql_fetch_assoc($Role);
  }
?>
		    </select>			</td>
			</tr>
		  <tr>
			<td>Permit</td>
			<td>
			  <select name="permitid" id="permitid">
			    <?php
do {  
?>
			    <option value="<?php echo $row_Permit['id']?>"><?php echo $row_Permit['permit']?></option>
			    <?php
} while ($row_Permit = mysql_fetch_assoc($Permit));
  $rows = mysql_num_rows($Permit);
  if($rows > 0) {
      mysql_data_seek($Permit, 0);
	  $row_Permit = mysql_fetch_assoc($Permit);
  }
?>
		    </select>			</td>
			</tr>
		  <tr>
		    <td>level</td>
		    <td><select name="level" id="level">
		      <option value="1">Read</option>
		      <option value="2">Read,Edit</option>
		      <option value="3">Read,Edit,Add</option>
		      <option value="4">Read,Edit,Add,Delete</option>
		      </select>
		    </td>
	      </tr>
		  <tr>
			<td><a href="RolePermitMenu.php">Close</a></td>
			<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
		  </tr>
		</table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
    </td>
  </tr>
</table>

<?php
mysql_free_result($Permit);

mysql_free_result($Role);

mysql_free_result($RolePermit);
?>
