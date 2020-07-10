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

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM patnotes WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= str_replace('&pge=PatNotesDelete.php','&pge=PatNotesView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesDelete.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<?php
$colname_notes = "-1";
if (isset($_GET['mrn'])) {
  $colname_notes = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
$colname_notes2 = "-1";
if (isset($_GET['vid'])) {
  $colname_notes2 = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
$colname_notes3 = "-1";
if (isset($_GET['noteid'])) {
  $colname_notes3 = (get_magic_quotes_gpc()) ? $_GET['noteid'] : addslashes($_GET['noteid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, notes, temp, pulse, bp_sys, bp_dia, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y     %H:%i') entrydt FROM patnotes WHERE medrecnum = $colname_notes and visitid = $colname_notes2 and id = $colname_notes3";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%">
  <caption align="top" class="subtitlebl">
    Delete Patient Notes
  </caption>
  <tr>
    <td><form id="form1" name="form1" method="POST">
      <table width="100%" bgcolor="#FBD0D7">
        <tr>
          <td>&nbsp;</td>
          <td>DateTime -- User</td>
          <td>Notes </td>
          <td>temp</td>
          <td>pulse</td>
          <td>systolic</td>
          <td>diastolic</td>
          <td>&nbsp;</td>
        </tr>
  <?php do { ?>
        <tr>
          <td>&nbsp;<br />
          <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></td>
		  <td title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>"><input type="text" name="entrydt2" readonly="readonly" value="<?php echo $row_notes['entrydt']; ?>"/>
            <br />
          <input name="entryby" type="text" readonly="readonly" value="<?php echo $row_notes['entryby']; ?>" size="15"/></td>
          <td><textarea name="notes" cols="80" rows="3" value="<?php echo $row_notes['notes']; ?>"><?php echo $row_notes['notes']; ?></textarea></td>
          <td><input name="temp" type="text" value="<?php echo $row_notes['temp']; ?>" size="5"/></td>
          <td><input name="pulse" type="text" value="<?php echo $row_notes['pulse']; ?>" size="5"/></td>
          <td><input name="bp_sys" type="text" value="<?php echo $row_notes['bp_sys']; ?>" size="5"/></td>
          <td><input name="bp_dia" type="text" value="<?php echo $row_notes['bp_dia']; ?>" size="5"/></td>
          <td><input name="medrecnum" type="hidden" value="<?php echo $row_notes['medrecnum']; ?>" />
		  	  <input name="visitid" type="hidden" value="<?php echo $row_notes['visitid']; ?>" />
		  	  <input name="entrydt" type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>" />
		  	  <input name="entryby" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
		  	  <input name="id" type="hidden" value="<?php echo $row_notes['id']; ?>" />
		  	  <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Notes" /></td>
        </tr>
    <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
      </table>
        <input type="hidden" name="MM_update" value="form1">
    </form>
    </td>
  </tr>
</table>



</body>
</html>
<?php
mysql_free_result($notes);
?>
