<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_notes = "-1";
if (isset($_GET['mrn'])) {
  $colname_notes = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
$colid_visit = "-1";
if (isset($_GET['vid'])) {
  $colid_visit = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
else {
 if(isset($_SESSION['vid'])) {
  $colid_visit = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']); 
 }
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, notes, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y  %H:%i') entrydt FROM patnotes WHERE notetype = 'InpatDocOrders' and medrecnum = '".$colname_notes."' and visitid = '".$colid_visit."'";
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

<?php if($totalRows_notes == 0){?>

<table width="50%" border="1" align="center">
  <tr>
    <td nowrap="nowrap"><div align="center" class="BlueBold_14"><span class="BlackBold_16">No Doctors Orders Yet</span> -----> <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatDocOrdersAdd.php">Add</a></div></td>
  </tr>
</table>
<?php } else { ?>
<table align="center">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table>
        <tr>
<?php if(allow(39,3) == 1) {?>
          <td><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatDocOrdersAdd.php">Add</a></div></td>
<?php	}
	else{  ?>
          <td><div align="center">&nbsp;</div></td>
<?php 	}?>
          <td><div align="center">DateTime</div></td>
          <td><div align="center">User</div></td>
          <td><div align="center" class="subtitlebl">View InPatient Doctor Orders</div></td>
        </tr>
<?php do { ?>
        <tr>
<?php if(allow(39,2) == 1) { ?>
          <td><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatDocOrdersEdit.php&noteid=<?php echo $row_notes['id']; ?>">Edit </a></td>
          <?php }
	else {?>
          <td><div align="center">&nbsp;</div></td>
<?php } ?>
          <td title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>"><input name="entrydt" type="text" value="<?php echo $row_notes['entrydt']; ?>" size="13S" readonly="readonly"/></td>
          <td><input name="entryby" type="text" readonly="readonly" value="<?php echo $row_notes['entryby']; ?>" size="12"/></td>
          <td title="<?php echo $row_notes['notes']; ?>"><textarea name="notes" cols="100" readonly="readonly" value="<?php echo $row_notes['notes']; ?>"><?php echo $row_notes['notes']; ?> </textarea></td>
        </tr>
    <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
      </table>
        </form>
    </td>
  </tr>
</table>
<?php } ?> 



</body>
</html>
<?php
mysql_free_result($notes);
?>
