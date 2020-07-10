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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formpne")) {
  $updateSQL = sprintf("UPDATE patnotes SET notes=%s, temp=%s, pulse=%s, bp_sys=%s, bp_dia=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['temp'], "text"),
                       GetSQLValueString($_POST['pulse'], "text"),
                       GetSQLValueString($_POST['bp_sys'], "text"),
                       GetSQLValueString($_POST['bp_dia'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	

	if (isset($_SESSION['ordid']) && (isset($_POST["Review"])) && ($_POST["Review"] == "Y")) {
	  $updateSQL = sprintf("UPDATE orders SET status=%s, entryby=%s, entrydt=%s WHERE id=%s",
						   GetSQLValueString('Reviewed', "text"),
						   GetSQLValueString($_POST['entryby'], "text"),
						   GetSQLValueString($_POST['entrydt'], "date"),
						   GetSQLValueString($_SESSION['ordid'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	}
	if (isset($_SESSION['ordid']) && (isset($_POST["Review"])) && ($_POST["Review"] == "N")) {
	  $updateSQL = sprintf("UPDATE orders SET status=%s, entryby=%s, entrydt=%s WHERE id=%s",
						   GetSQLValueString('Resulted', "text"),
						   GetSQLValueString($_POST['entryby'], "text"),
						   GetSQLValueString($_POST['entrydt'], "date"),
						   GetSQLValueString($_SESSION['ordid'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	}
	
		// update diagnosis in patvisit table 
	if ((isset($_POST["diag"])) && isset($_POST["visitid"]) && strlen($_POST["diag"]) > 3 ) {
		$updateSQL = sprintf("UPDATE patvisit SET diagnosis=%s WHERE id=%s",
												 GetSQLValueString($_POST['diag'], "text"),
												 GetSQLValueString($_POST['visitid'], "int"));
	
		mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

	} else {  // if diagnosis is blank, and user is a doctor, go to edit note
	
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_doctor = "SELECT userid, docflag FROM users WHERE userid = '".$_SESSION['user']."'";
	$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
	$row_doctor = mysql_fetch_assoc($doctor);
	$totalRows_doctor = mysql_num_rows($doctor);

	 if($totalRows_doctor >0 && $row_doctor['docflag'] == 'Y'){
	//Notetypes are %, Dental, InpatDocOrders, InPatientDoc, InPatientNurse, OutPatient, PhysioTherapy, Radiology, Surgery 
// cannot use note type to make diagnosis for doctors only, so better to use docflag in users table
			$_SESSION['no_diag'] = 'Diagnosis is required';
			$updateGoTo = "PatShow1.php";

			if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= $_SERVER['QUERY_STRING']; 
		}
// $updateGoTo below works too		
//	  $updateGoTo = "PatShow1.php?mrn=".$_POST['medrecnum']."&vid=".$_POST['visitid']."&visit=PatVisitView.php&act=inpat&pge=PatNotesEdit.php&notetype=OutPatient&noteid=".$_POST['id']."";
			header(sprintf("Location: %s", $updateGoTo));
	exit;  // to make it display edit page again
	}
	}
	if($_POST['notetype'] == 'Surgery' or $_POST['notetype'] == 'Radiology'){
		  $updateGoTo = "PatShow1.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= str_replace('&pge=PatNotesEdit.php','&pge=PatNotesViewPSR.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesEdit.php out of $_SERVER['QUERY_STRING'];
		  }
	} else {		
			
		  $updateGoTo = "PatShow1.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= str_replace('&pge=PatNotesEdit.php','&pge=PatNotesView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesEdit.php out of $_SERVER['QUERY_STRING'];
  		}
	}
  header(sprintf("Location: %s", $updateGoTo));
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
$query_notes = "SELECT id, medrecnum, visitid, notetype, notes, temp, pulse, bp_sys, bp_dia, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM patnotes WHERE medrecnum = $colname_notes and visitid = $colname_notes2 and id = $colname_notes3";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);
?>
<?php $colid_ordid = "0";
if (isset($_GET['ordid'])) {
  $colid_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
  $_SESSION['ordid'] = $colid_ordid;
}

  $colname_vid = 0; 
  if (isset($_GET['vid'])){
    $colname_vid = $_GET['vid'];
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
  if($colname_vid >= 1){
	$query_diag = "SELECT diagnosis FROM patvisit WHERE id = '".$colname_vid."'";
	$diag = mysql_query($query_diag, $swmisconn) or die(mysql_error());
	$row_diag = mysql_fetch_assoc($diag);
	$totalRows_diag = mysql_num_rows($diag);
  }	
}

	mysql_select_db($database_swmisconn, $swmisconn);
	$query_doctor2 = "SELECT userid, docflag FROM users WHERE userid = '".$_SESSION['user']."' and docflag='Y'";
	$doctor2 = mysql_query($query_doctor2, $swmisconn) or die(mysql_error());
	$row_doctor2 = mysql_fetch_assoc($doctor2);
	$totalRows_doctor2 = mysql_num_rows($doctor2);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script rel="text/javascript" src="../../jquery-1.11.1.js"></script>
<script src="../../autosaveform.js">

//***********************************************
//* Auto Save Form script (c) Dynamic Drive (www.dynamicdrive.com)
//* This notice MUST stay intact for legal use
//* Visit http://www.dynamicdrive.com/ for this script and 100s more.
//**********************************************
//http://www.dynamicdrive.com/dynamicindex16/autosaveform.htm
</script>

<script>
var formsave1=new autosaveform({
formid: 'formpn1',
pause: 1000 //<--no comma following last option!
})
</script>

<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  selector:'textarea#notes', 
	//mode : 'textareas',
	//editor_selector : 'notes',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	min_height: 20,
	width: 600,
	autoresize_max_height: 400,
	autoresize_min_height: 200,
	autoresize_bottom_margin: 1,
	menubar: false,
	statusbar: false,
	toolbar_items_size : 'small',
	toolbar: 'bold italic underline | bullist  numlist | indent outdent | alignleft aligncenter alignright | superscript subscript',
    plugins: 'autoresize, autosave',
		autosave_ask_before_unload: false,
		autosave_interval: "20s",
		autosave_restore_when_empty: true,
		autosave_retention: "30m"
	 });
</script>
</head>

<body>
<table width="100%">
  <caption align="top" class="subtitlebl">
    Edit <?php echo $row_notes['notetype']; ?> Notes
  </caption>
  <tr>
    <td>
	  <form id="formpne" name="formpne" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#F8FDCE">
        <?php do { ?>
        <tr>
          <td valign="top" title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>"><strong>NOTES:</strong><br />
            Type:
              <span class="flagBlackonWhite"><?php echo $row_notes['notetype']; ?></span>
            <br />
            Date:
            <span class="Black_10"><?php echo $row_notes['entrydt']; ?></span>
            <br />
            User:
            <span class="Black_10"><?php echo $row_notes['entryby']; ?></span>
            <br />
			<?php if((allow(27,4) == 1 and $colname_notetype == 'OutPatient') or (allow(37,4) == 1 and $colname_notetype == 'InpatientNurse') or (allow(38,4) == 1 and $colname_notetype == 'InpatientDoc') or (allow(48,4) == 1 and $colname_notetype == 'Radiology') or (allow(50,4) == 1 and $colname_notetype == 'PhysioTherapy') or (allow(52,4) == 1 and $colname_notetype == 'Surgery')) { ?>
                <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=lab&pge=PatNotesDelete.php&notetype=<?php echo $colname_notetype; ?>&noteid=<?php echo $row_notes['id']; ?>">Delete</a>
			<br />
                <?php } ?>
<!--              <a href="PatShow1.php?mrn=<?php //echo $_SESSION['mrn']; ?>&vid=<?php //echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a>		-->	
              </td>
          <td><textarea class="notes" name="notes" id="notes" value="<?php echo $row_notes['notes']; ?>"><?php echo $row_notes['notes']; ?> </textarea></td>
          <td align="right" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>VITALS</strong><br />
            Temp&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="temp" type="text" value="<?php echo $row_notes['temp']; ?>" size="1"/>
                <br />
            Pulse&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="pulse" type="text" value="<?php echo $row_notes['pulse']; ?>" size="1" />
            <br />
            Systolic&nbsp;
            <input name="bp_sys" type="text" value="<?php echo $row_notes['bp_sys']; ?>" size="1"/>
            <br />
            Diastolic
            <input name="bp_dia" type="text" value="<?php echo $row_notes['bp_dia']; ?>" size="1"/>
            <br />
			Reviewed
			<select name="Review">
			  <option value="Y" selected="selected">Y</option>
			  <option value="N">N</option>
			</select>
        </td>
      </tr>
<?php if(isset($_SESSION['no_diag']) && $_SESSION['no_diag'] != '') { ?>
      <tr>
				<td></td>
      	<td align="right" class="RedBold_18"><?php echo $_SESSION['no_diag'] ?></td>
      </tr>

<?php }?>
      <tr>
    <?php       if(isset($row_doctor2['docflag']) && $row_doctor2['docflag']=='Y'){ ?>

        <td>Diagnosis <br /> > 3 characters</td>
        <td><input name="diag" type="text" value="<?php echo $row_diag['diagnosis']; ?>" autocomplete="off" size="50" maxlength="60" />
        	
   <?php   } else{ ?>
             <td>&nbsp;</td> 
             <td>&nbsp;</td> 
   <?php } ?>

  			<td><input type="submit" name="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Notes" /></td>
			
            <input name="medrecnum" type="hidden" value="<?php echo $row_notes['medrecnum']; ?>" />
            <input name="visitid" type="hidden" value="<?php echo $row_notes['visitid']; ?>" />
            <input name="notetype" type="hidden" value="<?php echo $row_notes['notetype']; ?>" />
            <input name="entrydt" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input name="entryby" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
            <input name="id" type="hidden" value="<?php echo $row_notes['id']; ?>" />
      		<input type="hidden" name="MM_update" value="formpne" />
        </tr>
        <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
      </table>
    </form>
	</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($notes);
?>
