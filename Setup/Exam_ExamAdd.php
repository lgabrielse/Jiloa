<?php require_once('../../Connections/swmisconn.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO exam_exam (examname, seq, locfeeid, active, entrydt, entryby) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['seq'], "int"),
                       GetSQLValueString($_POST['locid'], "int"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "Exam_ExamView.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));


}

mysql_select_db($database_swmisconn, $swmisconn);
$query_location = "SELECT id, name FROM fee WHERE Active = 'Y' and dept = 'Records'  ORDER BY section, name ASC";
$location = mysql_query($query_location, $swmisconn) or die(mysql_error());
$row_location = mysql_fetch_assoc($location);
$totalRows_location = mysql_num_rows($location);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exam_Exam Add</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp; </p>
<table width="40%" border="1" align="center" class="tablebc">
  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">  <tr>
    <td><a href="Exam_ExamView.php">Close</a></td>
    <td colspan="2" class="GreenBold_24"><div align="center">Exam Add </div></td>
  </tr>
  <tr>
    <td nowrap="nowrap">Exam Name </td>
    <td colspan="2" nowrap="nowrap"><input name="name" type="text" id="name" size="30" maxlength="30" autocomplete="off" /> 
    30 char max 
    </td>
  </tr>
  <tr>
    <td>Location</td>
    <td colspan="2">
      <select name="locid" id="locid">
        <?php
do {  
?>
        <option value="<?php echo $row_location['id']?>"><?php echo $row_location['name'].':('.$row_location['id'].')'?></option>
        <?php
} while ($row_location = mysql_fetch_assoc($location));
  $rows = mysql_num_rows($location);
  if($rows > 0) {
      mysql_data_seek($location, 0);
	  $row_location = mysql_fetch_assoc($location);
  }
?>
      </select>
    </td>
  </tr>
  <tr>
    <td>Sequence</td>
    <td colspan="2"><input name="seq" type="text" id="seq" size="5" autocomplete="off"/>    </td>
  </tr>
  <tr>
    <td>Active</td>
    <td><select name="active" id="active">
      <option value="Y" selected="selected">Y</option>
      <option value="N">N</option>
    </select></td>
    <td><input type="submit" name="Submit" value="Submit" />   
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>;" /></td>
	</td>
  </tr>
  <input type="hidden" name="MM_insert" value="form1">
  </form>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($location);
?>
