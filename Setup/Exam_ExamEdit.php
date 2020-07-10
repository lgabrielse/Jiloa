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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE exam_exam SET seq=%s, active=%s, entrydt=%s, entryby=%s WHERE id=%s",
                       GetSQLValueString($_POST['seq'], "int"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['examid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "Exam_ExamView.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_Edit = "-1";
if (isset($_GET['examid'])) {
  $colname_examid = (get_magic_quotes_gpc()) ? $_GET['examid'] : addslashes($_GET['examid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_ExamEdit = "Select e.id, e.examname, e.seq, e.active, e.locfeeid, f.name locname from exam_exam e join fee f on e.locfeeid = f.id where e.id = '".$colname_examid."'";
$ExamEdit = mysql_query($query_ExamEdit, $swmisconn) or die(mysql_error());
$row_ExamEdit = mysql_fetch_assoc($ExamEdit);
$totalRows_ExamEdit = mysql_num_rows($ExamEdit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="40%" border="1" align="center" class="tablebc">
 <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <tr>
    <td><a href="Exam_ExamView.php">Close</a></td>
    <td colspan="3"><div align="center" class="GreenBold_24">Edit Exam </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ID</td>
    <td>Exam Name</td>
    <td>Location</td>
    <td>Seq</td>
    <td>Active</td>
  </tr>
  <tr>
    <td><?php echo $row_ExamEdit['id']; ?></td>
    <td><?php echo $row_ExamEdit['examname']; ?></td>
    <td><?php echo $row_ExamEdit['locname']; ?></td>
    <td><input name="seq" type="text" value="<?php echo $row_ExamEdit['seq']; ?>" size="3" autocomplete="off"/></td>
    <td><select name="active">
      <option value="Y" <?php if (!(strcmp("Y", $row_ExamEdit['active']))) {echo "selected=\"selected\"";} ?>>Y</option>
      <option value="N" <?php if (!(strcmp("N", $row_ExamEdit['active']))) {echo "selected=\"selected\"";} ?>>N</option>
       </select>    </td>
   </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" value="Edit" />
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>;" />
        <input name="examid" type="hidden" id="examid" value="<?php echo $row_ExamEdit['id']; ?>;" /></td>

</td>
  </tr>
  <input type="hidden" name="MM_update" value="form1">
 </form>
</table>



</body>
</html>
<?php
mysql_free_result($ExamEdit);
?>
