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
  $updateSQL = sprintf("UPDATE exam_sympt SET seq=%s, active=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['seq'], "int"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "int"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['symptid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "Exam_ExamView.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= str_replace('&act2=Exam_SymptEdit.php','',$_SERVER['QUERY_STRING']);
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_symptid = "-1";
if (isset($_GET['symptid'])) {
  $colname_symptid = (get_magic_quotes_gpc()) ? $_GET['symptid'] : addslashes($_GET['symptid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_SymptEdit = "Select id, examid, symptom, seq, active from exam_sympt where id = '".$colname_symptid."'";
$SymptEdit = mysql_query($query_SymptEdit, $swmisconn) or die(mysql_error());
$row_SymptEdit = mysql_fetch_assoc($SymptEdit);
$totalRows_SymptEdit = mysql_num_rows($SymptEdit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" border="1" align="center" class="tablebc">
 <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <tr>
    <td><a href="Exam_ExamView.php">Close</a></td>
    <td colspan="3"><div align="center" class="GreenBold_24">Edit Symptom </div></td>
  </tr>
  <tr>
    <td>(E)-S IDs</td>
    <td>Symptom</td>
    <td>Seq</td>
    <td>Active</td>
  </tr>
  <tr>
    <td>(<?php echo $row_SymptEdit['examid']; ?>)- <?php echo $row_SymptEdit['id']; ?></td>
    <td><?php echo $row_SymptEdit['symptom']; ?></td>
    <td><input name="seq" type="text" value="<?php echo $row_SymptEdit['seq']; ?>" size="3" autocomplete="off"/></td>
    <td><select name="active">
      <option value="Y" <?php if (!(strcmp("Y", $row_SymptEdit['active']))) {echo "selected=\"selected\"";} ?>>Y</option>
      <option value="N" <?php if (!(strcmp("N", $row_SymptEdit['active']))) {echo "selected=\"selected\"";} ?>>N</option>
       </select>    </td>
    <td><input name="submit" type="submit" value="Edit" />
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>;" />
        <input name="symptid" type="hidden" id="symptid" value="<?php echo $row_SymptEdit['id']; ?>;" /></td>
  </tr>
  <input type="hidden" name="MM_update" value="form1">
  </form>
</table>



</body>
</html>
<?php
mysql_free_result($SymptEdit);
?>
