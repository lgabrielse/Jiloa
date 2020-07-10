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
  $updateSQL = sprintf("UPDATE permitlinks SET permitid=%s, level=%s, file=%s, linkname=%s WHERE id=%s",
                       GetSQLValueString($_POST['permitid'], "text"),
                       GetSQLValueString($_POST['level'], "text"),
                       GetSQLValueString($_POST['file'], "text"),
                       GetSQLValueString($_POST['linkname'], "text"),
                       GetSQLValueString($_POST['id'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "PermitLinks.php?pid=".$_POST['pid'];
  header(sprintf("Location: %s", $updateGoTo));
}

$MMID_EDIT = "-1";
if (isset($_GET["id"])) {
  $MMID_EDIT = (get_magic_quotes_gpc()) ? $_GET["id"] : addslashes($_GET["id"]);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_EDIT = sprintf("SELECT id, permitid, level, file, linkname FROM permitlinks WHERE id = %s", $MMID_EDIT);
$EDIT = mysql_query($query_EDIT, $swmisconn) or die(mysql_error());
$row_EDIT = mysql_fetch_assoc($EDIT);
$totalRows_EDIT = mysql_num_rows($EDIT);
?>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">


<table width="50%" border="1" bgcolor="#F8FDCE">
  <tr>
	<td>
      <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
		  <table  bgcolor="#F8FDCE">
			  <tr>
				<td colspan="2"><div align="center">Edit PermitLink</div></td>
			  </tr>
			  <tr>
				<td align="right">Permitid</td>
				<td><input name="permitid" type="text" id="permitid" value="<?php echo $row_EDIT['permitid']; ?>" /> </td>
			  </tr>
			  <tr>
				<td align="right">Level</td>
				<td><select name="level" id="level" value="<?php echo $row_EDIT['level']; ?>"  >
				  <option value="1" <?php if (!(strcmp("1", $row_EDIT['level']))) {echo "selected=\"selected\"";} ?>>1</option>
				  <option value="2" <?php if (!(strcmp("2", $row_EDIT['level']))) {echo "selected=\"selected\"";} ?>>2</option>
				  <option value="3" <?php if (!(strcmp("3", $row_EDIT['level']))) {echo "selected=\"selected\"";} ?>>3</option>
				  <option value="4" <?php if (!(strcmp("4", $row_EDIT['level']))) {echo "selected=\"selected\"";} ?>>4</option>
				  </select>				</td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Link Name </td>
				<td><input name="linkname" type="text" id="linkname" value="<?php echo $row_EDIT['linkname']; ?>" /></td>
			  </tr>
			  <tr>
			    <td align="right">File</td>
				<td><input name="file" type="text" id="file" value="<?php echo $row_EDIT['file']; ?>" /> </td>
			  </tr>
			  <tr>
				<td><a href="PermitLinks.php">Close</a>
				  <input type="hidden" name="id" value="<?php echo $row_EDIT['id']; ?>">
				  <input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
				  <input type="hidden" name="MM_update" value="form1"></td>
				  <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Permit Link" /></td>
			  </tr>
		</table>   
      </form>
	  </td>
  </tr>
</table>

<br />
<?php
mysql_free_result($EDIT);
?>
<?php
ob_end_flush();
?>
