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
  $deleteSQL = sprintf("DELETE FROM permitlinks WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "PermitLinks.php?pid=".$_POST['pid'];
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_del = "-1";
if (isset($_GET['id'])) {
  $colname_del = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_permits = sprintf("Select id, permitid, file, level, linkname from permitlinks WHERE id = %s", $colname_del);
$permits = mysql_query($query_permits, $swmisconn) or die(mysql_error());
$row_permits = mysql_fetch_assoc($permits);
$totalRows_permits = mysql_num_rows($permits);
?>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<table width="50%" border="1" bgcolor="#FBD0D7">
  <tr>
    <td>
	  <table bgcolor="#FBD0D7">
		<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		  <tr>
			<td colspan="2"><div align="center">Delete Permit</div></td>
		  </tr>	      
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><div align="center"><strong>id</strong></div></td>
			  <td class="sidebarFooter"><input name="id" type="text" readonly="readonly" value="<?php echo $row_permits['id']; ?>" /></td>
		  </tr>	      
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><div align="center"><strong>permitid</strong></div></td>
			  <td class="sidebarFooter"><input name="permitid" type="text" readonly="readonly" value="<?php echo $row_permits['permitid']; ?>" /></td>
		  </tr>	      
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><div align="center"><strong>level</strong></div></td>
			  <td class="sidebarFooter"><input name="level" type="text" readonly="readonly" value="<?php echo $row_permits['level']; ?>" /></td>
		  </tr>	      
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><div align="center"><strong>linkname</strong></div></td>
			  <td class="sidebarFooter"><input name="linkname" type="text" readonly="readonly" value="<?php echo $row_permits['linkname']; ?>" /></td>
		  </tr>
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><div align="center"><strong>file</strong></div></td>
			  <td class="sidebarFooter"><input name="file" type="text" readonly="readonly" value="<?php echo $row_permits['file']; ?>" /></td>
		  </tr>
		  <tr>
			  <td bgcolor="#FFFFFF" class="big"><a href="PermitLinks.php">Close</a></td>
			  <td>
			  <input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
			  <input name="Submit" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" /></td>
		  </tr>
	    </form>
	  </table>
	</td>
  </tr>
</table>
<?php
mysql_free_result($permits);
?>
<?php
ob_end_flush();
?>
