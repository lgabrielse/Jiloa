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
  $insertSQL = sprintf("INSERT INTO permitlinks (permitid, level, file, linkname) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['permitid'], "text"),
                       GetSQLValueString($_POST['level'], "text"),
                       GetSQLValueString($_POST['file'], "text"),
                       GetSQLValueString($_POST['linkname'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PermitLinks.php?pid=".$_POST['pid'];
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="50%" border="1" bgcolor="#BCFACC">
  <tr>
    <td valign="top">
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		  <table width="100%" bgcolor="#BCFACC">
			  <tr>
				<td colspan="2"><div align="center">Add permitlinks</div></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Permit id </td>
				<td><input name="permitid" type="text" id="permitid" autocomplete="off" value="<?php echo $_GET['pid']; ?>"/></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Level</td>
				<td><select name="level" id="level" autocomplete="off">
				  <option value="1">1</option>
				  <option value="2">2</option>
				  <option value="3">3</option>
				  <option value="4">4</option>
				  </select>				</td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Link Name </td>
				<td><input name="linkname" type="text" id="linkname" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">File </td>
				<td><input name="file" type="text" id="file" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td><input type="hidden" name="MM_insert" value="form1">
				    <input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
			    <a href="permitlinks.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Permit Link" /></td>
			  </tr>
	    </table>   
      </form>
    </td>
  </tr>
</table>
</body>
</html>
<?php
ob_end_flush();
?>
