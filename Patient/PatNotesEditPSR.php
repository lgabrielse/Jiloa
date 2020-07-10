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

	if ((isset($_POST["oStatus"]))) {
	  $updateSQL = sprintf("UPDATE orders SET status=%s, entryby=%s, entrydt=%s WHERE id=%s",
						   GetSQLValueString($_POST['oStatus'], "text"),
						   GetSQLValueString($_POST['entryby'], "text"),
						   GetSQLValueString($_POST['entrydt'], "date"),
						   GetSQLValueString($_POST['ordid'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	}
	if($_POST['notetype'] == 'Surgery' or $_POST['notetype'] == 'Radiology'){
		  $updateGoTo = "PatShow1.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
			$updateGoTo .= str_replace('&pge=PatNotesEditPSR.php','&pge=PatNotesViewPSR.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesEdit.php out of $_SERVER['QUERY_STRING'];
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
<?php 
	$colname_ordid = 0; 
  		if (isset($_GET['ordid'])){
    $colname_ordid = $_GET['ordid'];
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
  if($colname_ordid >= 1){
	$query_ordstatus = "SELECT o.id ordid, o.status, f.name name FROM orders o join fee f on o.feeid = f.id WHERE o.id = '".$colname_ordid."'";
	$ordstatus = mysql_query($query_ordstatus, $swmisconn) or die(mysql_error());
	$row_ordstatus = mysql_fetch_assoc($ordstatus);
	$totalRows_ordstatus = mysql_num_rows($ordstatus);
    }	
  }
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  	//selector:'textarea#diagnosis', 
	mode : 'textareas',
	editor_selector : 'notes',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	min_height: 20,
	width: 600,
	autoresize_max_height: 50,
	autoresize_min_height: 50,
	autoresize_bottom_margin: 1,
	menubar: false,
	statusbar: false,
	toolbar_items_size : 'small',
	toolbar: 'bold italic underline | bullist  numlist | indent outdent | alignleft aligncenter alignright | superscript subscript | preview | save',
    plugins: 'autoresize, preview, autosave, save',
		autosave_ask_before_unload: false,
		autosave_interval: "20s",
		autosave_restore_when_empty: true,
		autosave_retention: "30m"
	 });
</script>
</head>

<body>
<table width="100%">
	  <form id="formpne" name="formpne" method="POST" action="<?php echo $editFormAction; ?>">
   <tr>
   	<td colspan="3" align="center" class="subtitlebl">Edit <?php echo $row_notes['notetype']; ?> Notes&nbsp;for &nbsp;&nbsp;<?php echo $row_ordstatus['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Status: 
		 <select name="oStatus">
		   <option value="Reviewed" <?php if (!(strcmp("Reviewed", $row_ordstatus['status']))) {echo "selected=\"selected\"";} ?>>Reviewed</option>
		   <option value="Resulted" <?php if (!(strcmp("Resulted", $row_ordstatus['status']))) {echo "selected=\"selected\"";} ?>>Resulted</option>
	    </select>
</td>
   </tr>	    
  <tr>
    <td>
      <table width="100%" bgcolor="#F8FDCE">
        <?php do { ?>
        <tr>
          <td valign="top" title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>&#10;Order#: <?php echo $row_ordstatus['ordid'] ?>&#10;OStatus: <?php echo $row_ordstatus['status']; ?>"><strong>NOTES:</strong><br />
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
                <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=lab&pge=PatNotesDelete.php&noteid=<?php echo $row_notes['id']; ?>">Delete</a>
			<br />
                <?php } ?>
              <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a>			</td>
          <td><textarea class="notes" name="notes" value="<?php echo $row_notes['notes']; ?>" data-validation="required"><?php echo $row_notes['notes']; ?> </textarea></td>
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
			<br />
			<input type="submit" name="Submitbtn" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Notes" /></td>
			<input name="ordid" type="hidden" value="<?php echo $row_ordstatus['ordid'] ?>" />
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
	</td>
  </tr>
    </form>
</table>
<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>
  $.validate({
  validateOnBlur : false, // disable validation when input looses focus
    errorMessagePosition : 'top', // Instead of 'element' which is default
    scrollToTopOnError : false // Set this property to true if you have a long form
  });
</script>
	   <script language="JavaScript" type="text/javascript"
		xml:space="preserve">//<![CDATA[
	//You should create the validator only after the definition of the HTML form
	  var frmvalidator  = new Validator("formpne");
	  frmvalidator.EnableMsgsTogether();
	  
 	  frmvalidator.addValidation("notes","req","Please enter Notes");
      frmvalidator.addValidation("notes","minlen=3", "Minumun length for Notes is 3");

	  frmvalidator.addValidation("pulse","num","Pulse: Numbers Only");
	  frmvalidator.addValidation("pulse","gt=0","Pulse: Min = 1");
	  frmvalidator.addValidation("pulse","lt=301","Pulse: Max = 300");

	  frmvalidator.addValidation("temp","num","Temp: Numbers Only");
	  frmvalidator.addValidation("temp","gt=29","Temp: Min = 30");
	  frmvalidator.addValidation("temp","lt=46","Temp: Max = 45");

	  frmvalidator.addValidation("bp_sys","num","Systolic: Numbers Only");
	  frmvalidator.addValidation("bp_sys","gt=9","Systolic: Min = 10");
	  frmvalidator.addValidation("bp_sys","lt=501","Systolic: Max = 500");

	  frmvalidator.addValidation("bp_dia","num","Diastolic: Numbers Only");
	  frmvalidator.addValidation("bp_dia","gt=9","Diastolic: Min = 10");
	  frmvalidator.addValidation("bp_dia","lt=501","Diastolic: Max = 500");

	  </script>


</body>
</html>
<?php
mysql_free_result($notes);
?>
