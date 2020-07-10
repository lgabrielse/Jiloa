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
  $insertSQL = sprintf("INSERT INTO roles (`role`, descr, active) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['role'], "text"),
                       GetSQLValueString($_POST['descr'], "text"),
                       GetSQLValueString($_POST['active'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "RoleMenu.php";
	  header(sprintf("Location: %s", $insertGoTo));
}

?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">


<table width="50%" align="center" border="1" bgcolor="#BCFACC">
  <tr>
    <td>
	  <table width="100%" bgcolor="#BCFACC">
		  <caption class="subtitle">
			Add Role
		</caption>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
			  <tr>
				<td align="right">Role ID </td>
				<td><input name="role" type="text" id="role" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right">Description </td>
				<td><input name="descr" type="text" id="descr" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right">Active</td>
				<td><select name="active" autocomplete="off">
				  <option value="Y">Y</option>
				  <option value="N">N</option>
				  </select>
				</td>
			  </tr>
			  <tr>
				<td><input type="hidden" name="MM_insert" value="form1">
			    <a href="RoleMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Role" /></td>
			  </tr>
      </form>
	  </table>   
    </td>
  </tr>
</table>



      
<?php
ob_end_flush();
?>
