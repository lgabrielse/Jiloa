<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
if(!function_exists("GetSQLValueString")) {
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
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formpn1")) {
  $insertSQL = sprintf("INSERT INTO patnotes (medrecnum, visitid, ordid, notetype, notes, temp, pulse, bp_sys, bp_dia, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['ordid'], "int"),
                       GetSQLValueString($_POST['notetype'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['temp'], "text"),
                       GetSQLValueString($_POST['pulse'], "text"),
                       GetSQLValueString($_POST['bp_sys'], "text"),
                       GetSQLValueString($_POST['bp_dia'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	
 	$updateSQL = "UPDATE orders SET status = 'Resulted' WHERE id = '" . $_POST['ordid'] . "' ";
		
 mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());


// update diagnosis in patvisit table 
$_SESSION['no_diag'] = ''; 
if ((isset($_POST["diag"])) && isset($_POST["visitid"]) && strlen($_POST["diag"]) >3) {
  $updateSQL = sprintf("UPDATE patvisit SET diagnosis=%s WHERE id=%s",
                       GetSQLValueString($_POST['diag'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
} else {    // if diagnosis is blank, and user is a doctor, go to edit note
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_doctor = "SELECT userid, docflag FROM users WHERE userid = '".$_SESSION['user']."' and docflag='Y'";
	$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
	$row_doctor = mysql_fetch_assoc($doctor);
	$totalRows_doctor = mysql_num_rows($doctor);

	 if($totalRows_doctor >0){
// find id of last note
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_noteid = "SELECT id, notetype FROM patnotes WHERE id = (Select MAX(id) from patnotes)";
		$noteid = mysql_query($query_noteid, $swmisconn) or die(mysql_error());
		$row_noteid = mysql_fetch_assoc($noteid);
		$totalRows_noteid = mysql_num_rows($noteid);
// open note edit for user to add dignosis
		$_SESSION['no_diag'] = 'Diagnosis is required';
	  $insertGoTo = "PatShow1.php?mrn=".$_POST['medrecnum']."&vid=".$_POST['visitid']."&visit=PatVisitView.php&act=inpat&pge=PatNotesEdit.php&notetype=".$row_noteid['notetype']."&noteid=".$row_noteid['id']."";
    header(sprintf("Location: %s", $insertGoTo));
		exit;  //exit prevents it from going to the next code
	}
 }
	if($_POST['notetype'] == 'Surgery' or $_POST['notetype'] == 'Radiology'){
		  $insertGoTo = "PatShow1.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
			$insertGoTo .= str_replace('&pge=PatNotesAdd.php','&pge=PatNotesViewPSR.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
		  }
	} else {		
	  $insertGoTo = "PatShow1.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= str_replace('&pge=PatNotesAdd.php','&pge=PatNotesView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
		}
  }
  header(sprintf("Location: %s", $insertGoTo));

}


//$colname_notetype = "%";
if (isset($notetype)) { //from PatShow1.php
    $colname_notetype = $notetype;
} else {
  if (isset($_GET['notetype'])){
    $colname_notetype = $_GET['notetype'];
  	}
 }
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
  $colname_vid = 0; 
  if (isset($_SESSION['vid'])){
    $colname_vid = $_SESSION['vid'];
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

//echo 'visitid'.$_GET['vid'];
//echo '   diag: '.$row_diag['diagnosis']; 
//echo 'sess  '.$_SESSION['vid'];
//exit;

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
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  selector:'textarea#notes', 
	//mode : 'textareas',
	//editor_selector : 'notes',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	min_height: 0,
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
		autosave_retention: "60m"
	 });
</script>
</head>

<body>
<table width="60%" align="center">
  <caption align="top" class="subtitlebl">
  Add <?php echo $colname_notetype; ?> Notes
  <?php //SSif(isset($notetype)) { echo 'Notetype:'.$notetype;} 
        // if(isset($_GET['notetype'])) { echo 'GetNotetype:'.$_GET['notetype'];}?>
  <?php   if(isset($row_ordstatus['ordid']) && $row_ordstatus['ordid'] >= 1){?>
  <span class="GreenBold_16" title="<?php echo $colname_ordid; ?>">For <?php echo $row_ordstatus['name']; ?></span>
  <?php 	}  ?>
  </caption>
  <tr>
    <td>
      <table width="100%" bgcolor="#BCFACC">
        <form  id="formpn1" name="formpn1" method="POST" action="<?php echo $editFormAction; ?>">
          <tr>
            <td valign="middle" nowrap="nowrap"><span class="flagBlackonWhite"><?php echo $colname_notetype; ?></span><br />
              Notes:<br />
              <br />
              <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></td>
            <!--   Chome does not accept autocomplete="off", so the 2 lines below trick it into not displaying autofill data-->
            <!--<textarea name="notes" id="notes" style='width:1px; height:1px; visibility:hidden'></textarea>-->
            <td>
              <textarea name="notes" class="notes" id="notes"></textarea>
            </td>
            <td valign="top" nowrap="nowrap">
              <div align="right"> temp
                <input name="temp" type="text" id="temp" size="3" />
                <br />
                pulse
                <input name="pulse" type="text" id="pulse" size="3"/>
                <br />
                systolic
                <input name="bp_sys" type="text" id="bp_sys" size="3"/>
                <br />
                diastolic
                <input name="bp_dia" type="text" id="bp_dia" size="3"/>
              </div>
            </td>
          </tr>
          <tr>

     <?php if(isset($row_doctor2['docflag']) && $row_doctor2['docflag'] == 'Y') {?>
            <td>Diagnosis<br> >3 characters</td>
            <td>
            <input name="diag" id="diag" type="text" value="<?php echo trim($row_diag['diagnosis'])?>" autocomplete="off" size="50" maxlength="60" />
            </td>
    <?php } else { ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
    <?php   }?>
            <td valign="top" nowrap="nowrap">
              <input name="submit" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Notes" />
            </td>
            <!--onclick="MM_validateForm('notes','','R');MM_validateForm('diag','','R');return document.MM_returnValue"-->
            
            <input name="notetype" type="hidden" id="notetype" value="<?php echo $colname_notetype; ?>" />
            <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
            <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
            <?php if($colname_ordid > 0){?>
            <input name="ordid" type="hidden" id="ordid" value="<?php echo $colname_ordid ?>" />
            <?php   } else { ?>
            <input name="ordid" type="hidden" id="ordid" value="0" />
            <?php }  ?>
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="MM_insert" value="formpn1">
          </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
