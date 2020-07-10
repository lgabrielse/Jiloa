<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_notetype = "";
if (isset($notetype)) {
    $colname_notetype = $notetype;
} else {
  if (isset($_GET['notetype'])){
    $colname_notetype = $_GET['notetype'];
  	}
 } 
$colid_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colid_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
else {
 if(isset($_SESSION['mrn'])) {
  $colid_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']); 
 }
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
$_SESSION['ordid'] = "";
$colid_ordid = "-1";
if (isset($_GET['ordid'])) {
  $colid_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
  $_SESSION['ordid'] = $colid_ordid;
}
?>

<?php   if($_SESSION['ordid'] >= 1){
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_ordstatus = "SELECT o.id ordid, o.status, f.name FROM orders o join fee f on o.feeid = f.id WHERE o.id = ".$_SESSION['ordid']."";
   } else {
	$query_ordstatus = "SELECT o.id ordid, o.status, f.name FROM orders o join fee f on o.feeid = f.id WHERE visitid = ".$_SESSION['vid']." and f.dept = '".$colname_notetype."'";
   }
$ordstatus = mysql_query($query_ordstatus, $swmisconn) or die(mysql_error());
$row_ordstatus = mysql_fetch_assoc($ordstatus);
$totalRows_ordstatus = mysql_num_rows($ordstatus);
?>

<?php //$notetitle = "";
// 	if($colname_notetype <> "%"){
//    $notetitle = $colname_notetype; 
//} ?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_pt = "SELECT ptflag FROM users WHERE active = 'Y' and userid = '".$_SESSION['user']."' ORDER BY userid ASC";
$pt = mysql_query($query_pt, $swmisconn) or die(mysql_error());
$row_pt = mysql_fetch_assoc($pt);
$totalRows_pt = mysql_num_rows($pt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  	//selector:'textarea#visitreason',
	mode : 'textareas',
	editor_selector : 'notes',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	//inline: true,
	min_height: 20,
	width: 600,
    plugins: 'autoresize, preview',
	autoresize_max_height: 400,
	autoresize_min_height: 200,
	autoresize_bottom_margin: 1,
	//readonly: true,  //inactivates toolbar too so preview doesn't work, but textarea is readonly
	menubar: false,
	statusbar: false,
	toolbar: 'preview'
	 });
</script>
</head>

<body>
<!-- if no notes and not % allow adding notes for that notetype-->

<?php //echo 'Notetype  '.$colname_notetype; ?></br>
<?php //echo 'MRN  '.$colid_mrn ;?></br>
<?php //echo 'Visit  '.$colid_visit ;?></br>
<?php //echo 'Order  '.$colid_ordid ?></br>
<?php //echo 'Ordid   '.$row_ordstatus['ordid'] ?></br>
<?php //echo 'vid   '.$_SESSION['vid']; ?>


<?php $X = 'Y';
if($X == 'Y') {   ?>

<?php if($totalRows_ordstatus > 0){?>
<table width="50%" border="1" align="center">

<?php do {  // for each order
			// look for notes 

mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, ordid, notetype, notes, temp, pulse, bp_sys, bp_dia, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y  %H:%i') entrydt FROM patnotes WHERE ordid = '".$row_ordstatus['ordid']."'";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);

//if($totalRows_notes == 0) {  //if notes does not have order id in column (old ones)
//
//mysql_select_db($database_swmisconn, $swmisconn);
//$query_notes = "SELECT id, medrecnum, visitid, ordid, notetype, notes, temp, pulse, bp_sys, bp_dia, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y  %H:%i') entrydt FROM patnotes WHERE visitid = ".$_SESSION['vid']." and notetype = '".$colname_notetype."'";
//$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
//$row_notes = mysql_fetch_assoc($notes);
//$totalRows_notes = mysql_num_rows($notes);
//}
 if($totalRows_notes == 0 and $colname_notetype <> "%"){    //display ADD notes link
?>
 
  <tr>
    <td colspan="10" nowrap="nowrap" title="<?php echo $row_ordstatus['ordid']; ?>"><div align="center" class="BlueBold_14"><span class="BlackBold_16">No <?php echo $colname_notetype ?> Notes Yet</span> for <?php echo $row_ordstatus['name']; ?>-----> <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&ordid=<?php echo $row_ordstatus['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesAdd.php&notetype=<?php echo $colname_notetype ?>">Add</a></div></td>
  </tr>
<?php } else {?>
	  <tr>
	  		<td colspan="10"> <div align="left" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<span class="BlackBold_16">View <?php echo $colname_notetype; ?> Notes </span><?php echo $row_ordstatus['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Status: <?php echo $row_ordstatus['status']; ?></div></td>
	  </tr>
<?php } ?>
  
<?php if($totalRows_notes >= 1) {   // show notes?>

	<?php do {  // for each note ?>
	  <tr>
		<td valign="top" nowrap="nowrap" title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>&#10;Ordid: <?php echo $row_ordstatus['ordid']; ?>&#10;OStatus: <?php echo $row_ordstatus['status']; ?>"><strong>NOTES: <?php echo $row_ordstatus['name']; ?> <br />
		  </strong>Type:<strong><?php echo $row_notes['notetype']; ?></strong><br />
		  Date:<?php echo $row_notes['entrydt']; ?><br/>
		  User:<?php echo $row_notes['entryby']; ?><br />
		  
      <?php if((allow(27,3) == 1 and $colname_notetype == 'OutPatient') or (allow(37,3) == 1 and $colname_notetype == 'InpatientNurse') or (allow(38,3) == 1 and $colname_notetype == 'InpatientDoc') or (allow(48,3) == 1 and $colname_notetype == 'Radiology') or (allow(50,3) == 1 and $colname_notetype == 'PhysioTherapy' and $row_pt['ptflag']=='Y' ) or (allow(52,3) == 1 and $colname_notetype == 'Surgery') or (allow(27,3) == 1 and $colname_notetype == 'Dental') or $colname_notetype == '%') { ?>
		  
		  <a href="PatShow1.php?mrn=<?php echo $colid_mrn; ?>&vid=<?php echo $colid_visit; ?>&ordid=<?php echo $row_notes['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesAdd.php&notetype=<?php echo $colname_notetype; ?>">Add</a>&nbsp;&nbsp;&nbsp;
<?php } ?>

      <?php if((allow(27,2) == 1 and $colname_notetype == 'OutPatient') or (allow(37,2) == 1 and $colname_notetype == 'InpatientNurse') or (allow(38,2) == 1 and $colname_notetype == 'InpatientDoc') or (allow(48,2) == 1 and $colname_notetype == 'Radiology') or (allow(50,2) == 1 and $colname_notetype == 'PhysioTherapy' and $row_pt['ptflag']=='Y' ) or (allow(52,2) == 1 and $colname_notetype == 'Surgery') or (allow(27,3) == 1 and $colname_notetype == 'Dental') or $colname_notetype == '%') { ?>
	  
		  <a href="PatShow1.php?mrn=<?php echo $row_notes['medrecnum']; ?>&vid=<?php echo $row_notes['visitid']; ?>&ordid=<?php echo $row_ordstatus['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesEditPSR.php&notetype=<?php echo $colname_notetype; ?>&noteid=<?php echo $row_notes['id']; ?>">Edit </a></td>
<?php } ?>
		<td colspan="8" title="<?php echo $row_notes['notes']; ?>"><textarea class="notes" name="notes" readonly="readonly" value="<?php echo $row_notes['notes']; ?>"><?php echo $row_notes['notes']; ?> </textarea></td>
		<td valign="top" nowrap="nowrap"><div align="right"> temp
				<input name="temp" type="text" readonly="readonly" style="text-align: right;" value="<?php echo $row_notes['temp']; ?>" size="2"/>
		  <br />
		  pulse
		  <input name="pulse" type="text" readonly="readonly" style="text-align: right;" value="<?php echo $row_notes['pulse']; ?>" size="2"/>
		  <br />
		  systolic
		  <input name="bp_sys" type="text" readonly="readonly" style="text-align: right;" value="<?php echo $row_notes['bp_sys']; ?>" size="2"/>
		  <br />
		  diastolic
		  <input name="bp_dia" type="text" readonly="readonly" style="text-align: right;" value="<?php echo $row_notes['bp_dia']; ?>" size="2"/>
		</div></td>
	  </tr>
  <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>

<?php } ?>

<?php   } while ($row_ordstatus = mysql_fetch_assoc($ordstatus)); ?>
</table>

<?php }  ///X = 0?>
<?php } //if orders ?>
</body>
</html>
<?php
mysql_free_result($notes);
?>
