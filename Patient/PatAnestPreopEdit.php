<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
?>
<?php $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formAnestPreop")) {
?>
<?php

//			patient hist
if(isset($_POST['pathisthtn']) && $_POST['pathisthtn'] == 'on'){$_POST['pathisthtn'] = 'Y';} else {$_POST['pathisthtn'] = 'N';}
if(isset($_POST['pathistdm']) && $_POST['pathistdm'] == 'on'){$_POST['pathistdm'] = 'Y';} else {$_POST['pathistdm'] = 'N';}
if(isset($_POST['pathistchestdx']) && $_POST['pathistchestdx'] == 'on'){$_POST['pathistchestdx'] = 'Y';} else {$_POST['pathistchestdx'] = 'N';}
if(isset($_POST['pathisthrtdx']) && $_POST['pathisthrtdx'] == 'on'){$_POST['pathisthrtdx'] = 'Y';} else {$_POST['pathisthrtdx'] = 'N';}
if(isset($_POST['pathistscd']) && $_POST['pathistscd'] == 'on'){$_POST['pathistscd'] = 'Y';} else {$_POST['pathistscd'] = 'N';}
if(isset($_POST['pathisttb']) && $_POST['pathisttb'] == 'on'){$_POST['pathisttb'] = 'Y';} else {$_POST['pathisttb'] = 'N';}
if(isset($_POST['pathistseiz']) && $_POST['pathistseiz'] == 'on'){$_POST['pathistseiz'] = 'Y';} else {$_POST['pathistseiz'] = 'N';}
if(isset($_POST['pathistkidneydx']) && $_POST['pathistkidneydx'] == 'on'){$_POST['pathistkidneydx'] = 'Y';} else {$_POST['pathistkidneydx'] = 'N';}
if(isset($_POST['pathistprevop']) && $_POST['pathistprevop'] == 'on'){$_POST['pathistprevop'] = 'Y';} else {$_POST['pathistprevop'] = 'N';}

//			family hist
if(isset($_POST['famhisthtn']) && $_POST['famhisthtn'] == 'on'){$_POST['famhisthtn'] = 'Y';} else {$_POST['famhisthtn'] = 'N';}
if(isset($_POST['famhisthrtdx']) && $_POST['famhisthrtdx'] == 'on'){$_POST['famhisthrtdx'] = 'Y';} else {$_POST['famhisthrtdx'] = 'N';}
if(isset($_POST['famhistdm']) && $_POST['famhistdm'] == 'on'){$_POST['famhistdm'] = 'Y';} else {$_POST['famhistdm'] = 'N';}
if(isset($_POST['famhistkidneydx']) && $_POST['famhistkidneydx'] == 'on'){$_POST['famhistkidneydx'] = 'Y';} else {$_POST['famhistkidneydx'] = 'N';}
if(isset($_POST['famhistchestdx']) && $_POST['famhistchestdx'] == 'on'){$_POST['famhistchestdx'] = 'Y';} else {$_POST['famhistchestdx'] = 'N';}
if(isset($_POST['famhistseiz']) && $_POST['famhistseiz'] == 'on'){$_POST['famhistseiz'] = 'Y';} else {$_POST['famhistseiz'] = 'N';}
if(isset($_POST['famhistscd']) && $_POST['famhistscd'] == 'on'){$_POST['famhistscd'] = 'Y';} else {$_POST['famhistscd'] = 'N';}
if(isset($_POST['famhisttb']) && $_POST['famhisttb'] == 'on'){$_POST['famhisttb'] = 'Y';} else {$_POST['famhisttb'] = 'N';}

//			social Hist
if(isset($_POST['sociahistedu']) && $_POST['sociahistedu'] == 'on'){$_POST['sociahistedu'] = 'Y';} else {$_POST['sociahistedu'] = 'N';}
if(isset($_POST['sociahistfinstatus']) && $_POST['sociahistfinstatus'] == 'on'){$_POST['sociahistfinstatus'] = 'Y';} else {$_POST['sociahistfinstatus'] = 'N';}
if(isset($_POST['sociahistsupports']) && $_POST['sociahistsupports'] == 'on'){$_POST['sociahistsupports'] = 'Y';} else {$_POST['sociahistsupports'] = 'N';}
if(isset($_POST['sociahisthome']) && $_POST['sociahisthome'] == 'on'){$_POST['sociahisthome'] = 'Y';} else {$_POST['sociahisthome'] = 'N';}
if(isset($_POST['sociahistsexual']) && $_POST['sociahistsexual'] == 'on'){$_POST['sociahistsexual'] = 'Y';} else {$_POST['sociahistsexual'] = 'N';}
if(isset($_POST['sociahisthabit']) && $_POST['sociahisthabit'] == 'on'){$_POST['sociahisthabit'] = 'Y';} else {$_POST['sociahisthabit'] = 'N';}
if(isset($_POST['sociahistwork']) && $_POST['sociahistwork'] == 'on'){$_POST['sociahistwork'] = 'Y';} else {$_POST['sociahistwork'] = 'N';}
if(isset($_POST['socishisttravels']) && $_POST['socishisttravels'] == 'on'){$_POST['socishisttravels'] = 'Y';} else {$_POST['socishisttravels'] = 'N';}
if(isset($_POST['sociahistsickcontact']) && $_POST['sociahistsickcontact'] == 'on'){$_POST['sociahistsickcontact'] = 'Y';} else {$_POST['sociahistsickcontact'] = 'N';}
?>
<?php
		$dentalstatus = '';
	  	for ($i = 0; $i <= 31; $i++) { 
	    $dentalstatus = $dentalstatus.$_POST[$i];
	    }  
//			echo $dentalstatus;
//			exit; 
// convert date/time to timestamp (databse stores timestamp)	
  $schedt = strtotime($_POST['preopddt'].' '.$_POST['preopddt_alt']);
  $loidt = strtotime($_POST['loiddt'].' '.$_POST['loiddt_alt']);

?>
<?php // echo 'dt#:'.$schedt.'    ';
     // echo 'dt: '.date('D, Y-M-d :h:i a',$schedt).'<br>';
		//	  echo 'loidt#:'.$loidt.'    ';
    //  echo '$loidt: '.date('D, Y-M-d :h:i a',$loidt);
//      exit; ?>     
     
<?php
  $updateSQL = sprintf("UPDATE anesthesia SET preopexamdt=%s, preopdiag=%s, anesthetist=%s, pathisthtn=%s, pathistdm=%s, pathistchestdx=%s, pathisthrtdx=%s, pathistscd=%s, pathisttb=%s, pathistseiz=%s, pathistkidneydx=%s, pathistprevop=%s, pathistothers=%s, famhisthtn=%s, famhisthrtdx=%s, famhistdm=%s, famhistkidneydx=%s, famhistchestdx=%s, famhistseiz=%s, famhistscd=%s, famhisttb=%s, famhistothers=%s, sociahistedu=%s, sociahistfinstatus=%s, sociahistsupports=%s, sociahisthome=%s, sociahistsexual=%s, sociahisthabit=%s, sociahistwork=%s, socishisttravels=%s, sociahistsickcontact=%s, sociahistothers=%s, allergies=%s, dentalstatus=%s,  labstatus=%s, nutritionstatus=%s, physicalstatus=%s, asagrading=%s, lastoralintake=%s, preanestorders=%s, status=%s, entryby=%s, entrydt=%s WHERE surgid=%s",
                       GetSQLValueString($schedt, "int"),
                       GetSQLValueString($_POST['preopdiag'], "text"),
                       GetSQLValueString($_POST['anesthetist'], "int"),
                       GetSQLValueString($_POST['pathisthtn'], "text"),
                       GetSQLValueString($_POST['pathistdm'], "text"),
                       GetSQLValueString($_POST['pathistchestdx'], "text"),
                       GetSQLValueString($_POST['pathisthrtdx'], "text"),
                       GetSQLValueString($_POST['pathistscd'], "text"),
                       GetSQLValueString($_POST['pathisttb'], "text"),
                       GetSQLValueString($_POST['pathistseiz'], "text"),
                       GetSQLValueString($_POST['pathistkidneydx'], "text"),
                       GetSQLValueString($_POST['pathistprevop'], "text"),
                       GetSQLValueString($_POST['pathistothers'], "text"),
                       GetSQLValueString($_POST['famhisthtn'], "text"),
                       GetSQLValueString($_POST['famhisthrtdx'], "text"),
                       GetSQLValueString($_POST['famhistdm'], "text"),
                       GetSQLValueString($_POST['famhistkidneydx'], "text"),
                       GetSQLValueString($_POST['famhistchestdx'], "text"),
                       GetSQLValueString($_POST['famhistseiz'], "text"),
                       GetSQLValueString($_POST['famhistscd'], "text"),
                       GetSQLValueString($_POST['famhisttb'], "text"),
                       GetSQLValueString($_POST['famhistothers'], "text"),
                       GetSQLValueString($_POST['sociahistedu'], "text"),
                       GetSQLValueString($_POST['sociahistfinstatus'], "text"),
                       GetSQLValueString($_POST['sociahistsupports'], "text"),
                       GetSQLValueString($_POST['sociahisthome'], "text"),
                       GetSQLValueString($_POST['sociahistsexual'], "text"),
                       GetSQLValueString($_POST['sociahisthabit'], "text"),
                       GetSQLValueString($_POST['sociahistwork'], "text"),
                       GetSQLValueString($_POST['socishisttravels'], "text"),
                       GetSQLValueString($_POST['sociahistsickcontact'], "text"),
                       GetSQLValueString($_POST['sociahistothers'], "text"),
                       GetSQLValueString($_POST['allergies'], "text"),
                       GetSQLValueString($dentalstatus, "text"),
                       GetSQLValueString($_POST['labstatus'], "text"),
                       GetSQLValueString($_POST['nutritionstatus'], "text"),
                       GetSQLValueString($_POST['physicalstatus'], "text"),
                       GetSQLValueString($_POST['asagrading'], "text"),
                       GetSQLValueString($loidt, "int"),
                       GetSQLValueString($_POST['preanestorders'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['sid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	
	// keep surgery status same as ansthesia
  $updateSQL = sprintf("UPDATE surgery SET status=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['sid'], "int"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "PatAnestPreopEdit.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING']; 
  }
}
?>


<?php 

if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
	}
if (isset($_GET['vid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
	}
if (isset($_GET['sid'])) {
  $colname_sid = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
	}

mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.rate, o.doctor, substr(o.status,1,7) status, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtdue, o.amtpaid, f.section, f.name, f.descr FROM orders o, fee f WHERE o.feeid = f.id and f.dept = 'Surgery' and Section = 'Anesthesia' and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_visitid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);
?>

<?php //  query for anesthetist ddl
mysql_select_db($database_swmisconn, $swmisconn);
$query_anesthetist = "SELECT id uid, userid FROM users WHERE active = 'Y' and anflag = 'Y' Order BY userid";
$anesthetist = mysql_query($query_anesthetist, $swmisconn) or die(mysql_error());
$row_anesthetist = mysql_fetch_assoc($anesthetist);
$totalRows_anesthetist = mysql_num_rows($anesthetist);

?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_anestpreop = "SELECT a.id aid, a.surgid, a.medrecnum, a.visitid, a.surgid, a.surgfeeid, a.preopexamdt, a.preopdiag, a.anesthetist, a.pathisthtn, a.pathistdm, a.pathistchestdx, a.pathisthrtdx, a.pathistscd, a.pathisttb, a.pathistseiz, a.pathistkidneydx, a.pathistprevop, a.pathistothers, a.famhisthtn, a.famhisthrtdx, a.famhistdm, a.famhistkidneydx, a.famhistchestdx, a.famhistseiz, a.famhistscd, a.famhisttb, a.famhistothers, a.sociahistedu, a.sociahistfinstatus, a.sociahistsupports, a.sociahisthome, a.sociahistsexual, a.sociahisthabit, a.sociahistwork, a.socishisttravels, a.sociahistsickcontact, a.sociahistothers, a.allergies, a.dentalstatus, a.labstatus, a.nutritionstatus, a.physicalstatus, a.asagrading, a.lastoralintake, a.preanestorders, a.anesttechnique, a.status, a.entrydt, a.entryby, f.name, f.descr FROM anesthesia a Join fee f on f.id = a.surgfeeid WHERE a.surgid = ".$colname_sid."";
$anestpreop = mysql_query($query_anestpreop, $swmisconn) or die(mysql_error());
$row_anestpreop = mysql_fetch_assoc($anestpreop);
$totalRows_anestpreop = mysql_num_rows($anestpreop);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Preop Exam</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=50,top=50,screenX=1600,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

<!--<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>-->
<script rel="text/javascript" src="../../jquery-1.11.1.js"></script>
<!-- for autosave-->
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
		formid: 'anestpreop',
		pause: 1000 //<--no comma following last option!
    })
</script>

<!-- tinymce text editor-->
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  	//selector:'textarea#diagnosis', 
	mode : 'textareas',
	editor_selector : 'surgdata',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	min_height: 10,
	width: 500,
	autoresize_max_height: 400,
	autoresize_min_height: 10,
	autoresize_bottom_margin: 0,
	autoresize_top_margin: 0,
	menubar: false,
	statusbar: false,
	toolbar: false,
/*	toolbar_items_size : 'small',
	toolbar: 'bold italic underline | bullist  numlist | indent outdent | alignleft aligncenter alignright | superscript subscript | preview',
*/
    plugins: 'autoresize, autosave',     /*preview,*/
  	autosave_ask_before_unload: false,
		autosave_interval: "20s",
		autosave_restore_when_empty: true,
		autosave_retention: "30m"
	 });
</script>

<!-- for date & time picker-->
<link rel="stylesheet" href="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="../../js/jquery-ui-timepicker-addon.css">
<script src="../../js/jquery.js"></script>
<script src="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../../js/jquery-ui-timepicker-addon.js"></script>

<!-- preopdtt-->
<script>
$( function() {
$('#preopddt').datetimepicker({
	altField: "#preopddt_alt",
	controlType: 'select',
	oneLine: true,
	dateFormat: 'D M d, yy',
	timeFormat: 'hh:mm tt', 
	buttonImage: "../../js/jquery-ui-1.12.1.custom/images/calendar.gif",
	showOn: "both", 
  buttonImageOnly: false
});
});
//https://stackoverflow.com/questions/7183736/jquery-uidatepicker-buttonimage-not-working
// Add showOn: "both" and buttonImageOnly: true to stop the image looking like a button.
 //if showOn: "button" - user must click on button for calendar...both = input field and button
</script>
<!-- bascheddt-->
<script>
$( function() {
$('#loiddt').datetimepicker({
	altField: "#loiddt_alt",
	controlType: 'select',
	oneLine: true,
	dateFormat: 'D M d, yy',
	timeFormat: 'hh:mm tt',
	buttonImage: "../../js/jquery-ui-1.12.1.custom/images/calendar.gif",
	showOn: "both", 
  buttonImageOnly: false
});
});
</script>


</head>
<body>

<div align="center" class="BlueBold_24">Update Anesthesia PREOP Exam</div>

<table align="center" width="50%" border="0" style="border-collapse:collapse;">
  <tr>
   <td nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php" class="BlueBold_24" style="background-color:violet; border-color:blue; color:white; text-align:center; border-radius:5px;">Surg Update</a></td>&nbsp;
   <td nowrap="nowrap" title="Update Intraop"><a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&aid=<?php echo $row_anestpreop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestIntraopEdit.php" class="BlueBold_24" style="background-color:yellow; border-color:blue; color:black; text-align:center;border-radius:5px;">Anest Intraop</a></td>&nbsp;
    <td nowrap="nowrap" title="Update Postop"><a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&aid=<?php echo $row_anestpreop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestPostopEdit.php" class="BlueBold_24" style="background-color:orange; border-color:blue; color:white; text-align:center; border-radius:5px;">Anest Postop</a></td>
	</tr>
</table>
<table width="50%" align="center" border="1" style="border-collapse:collapse;"  bgcolor="#BCFACC">
 <form method="post" name="formAnestPreop" id="formAnestPreop" action="<?php echo $editFormAction; ?>" >
  <tr>
    <td>
      <table>
        <tr>
          <td>
            <table border="1" align="left" cellpadding="1" cellspacing="1"  style="border-collapse:collapse;">
              <tr>
         <!--Status selector --><!--display AnesthesiaID, Pat Medrecnum, Pat visitID, surgeryID In ToolTip-->
         
                <td nowrap title="MRN: <?php echo $row_anestpreop['medrecnum'] ?>&#10;VisitID: <?php echo $row_anestpreop['visitid'] ?>&#10;SurgID: <?php echo $row_anestpreop['surgid'] ?>&#10;AnestID: <?php echo $row_anestpreop['aid'] ?> ">Status:
              <select name="status" id="status">
                <option value="Ordered" <?php if (!(strcmp("Ordered", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
                <option value="Scheduled" <?php if (!(strcmp("Scheduled", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
                <option value="In-Progress" <?php if (!(strcmp("In-Progress", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
                <option value="Recovery" <?php if (!(strcmp("Recovery", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>Recovery</option>
                <option value="Complete" <?php if (!(strcmp("Complete", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>Complete</option>
        				<option value="Cancelled" <?php if (!(strcmp("Cancelled", $row_anestpreop['status']))) {echo "selected=\"selected\"";} ?>>Cancelled</option>
              </select>
                </td>
              
                <td height="18" align="right">Preopexamdt:</td>

            <!--isset($row_anestpreop['preopexamdt'])  && $row_anestpreop['preopexamdt'] != NULL && $row_anestpreop['preopexamdt'] !=0-->
              <?php if(!empty($row_anestpreop['preopexamdt']) ){  ?>
                <td  colspan="3" align="left" nowrap="nowrap">
                  <input id="preopddt" name="preopddt" type="text" size="12" maxlength="15" value="<?php echo date('D M d, Y', $row_anestpreop['preopexamdt']) ;?>" />
                  <input id="preopddt_alt" name="preopddt_alt" type="text" size="6" maxlength="15" value="<?php echo  date('h:i a', $row_anestpreop['preopexamdt']) ;?>" /></td>      
                <?php } else {?>
                <td align="left" nowrap="nowrap">
                  <input id="preopddt" name="preopddt" type="text" size="12" maxlength="15" value="" />  
                  <input id="preopddt_alt" name="preopddt_alt" type="text" size="6" maxlength="10" value="" /></td>
            
             <?php }?>
      <!--Select/display anesthesia Technique-->
              <tr>
      <!--Display Surgery name-->
                <td align="right">Surgery Name:</td>
                <td colspan="3" title ="SurgFeeid  <?php echo $row_anestpreop['surgfeeid'] ?>"><input type="text" name="surgname" id="surgname" size="50" readonly value="<?php echo $row_anestpreop['name'].': '.$row_anestpreop['descr']?>"></td>

                       
      <!--        <td align="center"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestOrd.php?mrn=<?php echo $row_anestpreop[	'medrecnum']; ?>&user=<?php echo $_SESSION['user'];?>&visitid=<?php echo $row_anestpreop['visitid']?>&sid=<?php echo $row_anestpreop['surgid'];?>','StatusView','scrollbars=yes,resizable=yes,width=1600,height=400')">Add Anesthesia Order</a></td>
      -->        
      
              </tr>
              <tr>
      <!-- Select/Display anesthetist -->
                <td align="right" >Anesthetist:</td>
                <td><select name="anesthetist">
          <?php
      do {  
      ?>
                        <option value="<?php echo $row_anesthetist['uid']?>"<?php if (!(strcmp($row_anesthetist['uid'], $row_anestpreop['anesthetist']))) {echo "selected=\"selected\"";} ?>><?php echo $row_anesthetist['userid']?></option>
          <?php
                } while ($row_anesthetist = mysql_fetch_assoc($anesthetist));
                  $rows = mysql_num_rows($anesthetist);
                  if($rows > 0) {
                      mysql_data_seek($anesthetist, 0);
                    $row_anesthetist = mysql_fetch_assoc($anesthetist);
                  }
      ?>
                      </select>
                </td>
              </tr>
      <!--enter/display preop diagnosis-->
              <tr>
                <td align="right" scope="row">Pre-Op Diagnosis</td>
                <td colspan="4"><textarea name="preopdiag" id="preopdiag" class="surgdata" ><?php echo $row_anestpreop['preopdiag']?></textarea></td>
              </tr>
            </table>																																
    </td>
    <td>  
		  <table border="1" align="left" cellpadding="1" cellspacing="1" style="border-collapse:collapse;">
				<tr>
          <td><input type="button" name = "button25" class="BlueBold_16" style="background-color:green; border-color:blue; color:white; text-align:center;border-radius:5px;" value="Add Anesthesia" onclick="parent.location='PatShow1.php?mrn=<?php echo $row_anestpreop[	'medrecnum']; ?>&user=<?php echo $_SESSION['user'];?>&visitid=<?php echo $row_anestpreop['visitid']?>&sid=<?php echo $row_anestpreop['surgid'];?>&visit=PatVisitView.php&act=lab&pge=PatAnestOrd.php'" />
          </td>
        </tr>
        <tr>
        	<td colspan="9" class="flagWhiteonGreen"><b>Anesthesia Orders</b></td>
				</tr>
        <tr>
                  <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                  <td nowrap="nowrap" class="BlackBold_11">Date/Time</td>
                  <td nowrap="nowrap" class="BlackBold_11">Ord#*</td>
                  <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>">Test*</td>
                  <td nowrap="nowrap" class="BlackBold_11">Urg</td>
                  <td nowrap="nowrap" class="BlackBold_11">Status</td>
                  <td nowrap="nowrap" class="BlackBold_11">Due</td>
                  <td nowrap="nowrap" class="BlackBold_11">Paid</td>
                </tr>
                <?php do { ?>
                <tr>
                  <?php if (!empty($row_ordered['id']) and empty($row_ordered['amtpaid']) ) {  // and allow(51,4) == 1 ?>
                  <td class="BlackBold_11" nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_GET['mrn']; ?>&vid=<?php echo $_GET['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['id'] ?>">Del</a></td>
                  <?php } else {?>
                  <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                  <?php } ?>
                  <td nowrap="nowrap" class="BlackBold_11" title="VID: <?php echo $row_ordered['visitid']; ?> &#10;EntryBy: <?php echo $row_ordered['entryby']; ?>"><?php echo $row_ordered['entrydt']; ?></td>
                  <td nowrap="nowrap" class="BlackBold_11" title="Doctor: <?php echo $row_ordered['doctor']; ?>"><div align="center"><?php echo $row_ordered['id']; ?></div></td>
                  <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>"><?php echo $row_ordered['name']; ?></td>
                  <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['urg']; ?></td>
                  <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['status']; ?></td>
                  <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtdue']; ?></div></td>
                  <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtpaid']; ?></div></td>
                </tr>
                <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
							</table>
            </td>
          </tr>
		    </table>
   		</td>
 		</tr>
  	<tr>  
    	<td colspan="1"> 
			<table>  <!--enter/display patient history-->      
				<tr>
					<td nowrap="nowrap"><div align="center" class="BlackBold_14">Patient History:</div></td>
          
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" Title="Hypertension" ><div align="left">
          <input type="hidden" name="pathisthtn" id="pathisthtn" value="N" />
          <input type="checkbox" name="pathisthtn" id="pathisthtn" <?php if ($row_anestpreop['pathisthtn'] == "Y") {echo "checked=\"checked\"";} ?> />HTN</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Diabetes"><div align="left">
          <input type="hidden" id="pathistdm" name="pathistdm" value="N" />
          <input type="checkbox" id="pathistdm" name="pathistdm" <?php if ($row_anestpreop['pathistdm'] == "Y") {echo "checked=\"checked\"";} ?> />DM</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Chest Disease"><div align="left">
          <input type="hidden" id="pathistchestdx" name="pathistchestdx" value="N" />
          <input type="checkbox" id="pathistchestdx" name="pathistchestdx" <?php if ($row_anestpreop['pathistchestdx'] == "Y") {echo "checked=\"checked\"";} ?> />CD</div>  </td>
        
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" Title="Heart Disease" ><div align="left">
          <input type="hidden" name="pathisthrtdx" value="N" />
          <input type="checkbox" name="pathisthrtdx" id="pathisthrtdx" <?php if ($row_anestpreop['pathisthrtdx'] == "Y") {echo "checked=\"checked\"";} ?> />HD</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Sickle Cell Disease"><div align="left">
          <input type="hidden" id="pathistscd" name="pathistscd" value="N" />
          <input type="checkbox" id="pathistscd" name="pathistscd" <?php if ($row_anestpreop['pathistscd'] == "Y") {echo "checked=\"checked\"";} ?> />SCD</div></td>        
	
        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" title="Tuberculosis"><div align="left">
          <input type="hidden" name="pathisttb" id="pathisttb" value="N" />
          <input type="checkbox" name="pathisttb" id="pathisttb"<?php if ($row_anestpreop['pathisttb'] == "Y") {echo "checked=\"checked\"";} ?> />TB</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Seizures"><div align="left">
          <input type="hidden" id="pathistseiz" name="pathistseiz" value="N" />
          <input type="checkbox" id="pathistseiz" name="pathistseiz" <?php if ($row_anestpreop['pathistseiz'] == "Y") {echo "checked=\"checked\"";} ?> />Seiz</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Kidney Disease"><div align="left">
          <input type="hidden" id="pathistkidneydx" name="pathistkidneydx" value="N">
          <input type="checkbox" id="pathistkidneydx" name="pathistkidneydx" <?php if ($row_anestpreop['pathistkidneydx'] == "Y") {echo "checked=\"checked\"";} ?> />KD</div></td>
          	
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Operations"><div align="left">
          <input type="hidden" id="pathistprevop" name="pathistprevop" value="N">
          <input type="checkbox" id="pathistprevop" name="pathistprevop" <?php if ($row_anestpreop['pathistprevop'] == "Y") {echo "checked=\"checked\"";} ?> />Ops</div></td>
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="">&nbsp;</td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Notes/Comments">Others:</td>
        	<td class="borderbottomthinblack" nowrap="nowrap" ><textarea name="pathistothers" cols="20" rows="1" id="pathistothers"><?php echo $row_anestpreop['pathistothers']; ?></textarea></td>
  
				</tr>
<!--enter/display family history-->

      	<tr>
        	<td class="borderbottomthinblackBold14" nowrap="nowrap" ><div align="center">Family History:</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Hypertension" ><div align="left">
          <input type="hidden" name="famhisthtn" id="famhisthtn" value="N" />
           <input type="checkbox" name="famhisthtn" id="famhisthtn" <?php if ($row_anestpreop['famhisthtn'] == "Y") {echo "checked=\"checked\"";} ?> />
HTN</div></td>
	
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Diabetes"><div align="left">
          <input type="hidden" id="famhistdm" name="famhistdm" value="N" />
          <input type="checkbox" id="famhistdm" name="famhistdm" <?php if ($row_anestpreop['famhistdm'] == "Y") {echo "checked=\"checked\"";} ?> />
DM</div></td>
          
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Chest Disease"><div align="left">
          <input type="hidden" id="famhistchestdx" name="famhistchestdx" value="N">
          <input type="checkbox" id="famhistchestdx" name="famhistchestdx" <?php if ($row_anestpreop['famhistchestdx'] == "Y") {echo "checked=\"checked\"";} ?> />
CD</div>  </td>
        
        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Heart Disease"><div align="left">
          <input type="hidden" name="famhisthrtdx" id="famhisthrtdx" value="N" />
          <input type="checkbox" name="famhisthrtdx" id="famhisthrtdx" <?php if ($row_anestpreop['famhisthrtdx'] == "Y") {echo "checked=\"checked\"";} ?> />
HD</div></td>

        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Sickle Cell Disease"><div align="left">
          <input type="hidden" id="famhistscd" name="famhistscd" value="N" />
          <input type="checkbox" id="famhistscd" name="famhistscd" <?php if ($row_anestpreop['famhistscd'] == "Y") {echo "checked=\"checked\"";} ?> />
SCD</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" title="Tuberculosis"><div align="left">
          <input type="hidden" name="famhisttb" id="famhisttb" value="N" />
          <input type="checkbox" name="famhisttb" id="famhisttb" <?php if ($row_anestpreop['famhisttb'] == "Y") {echo "checked=\"checked\"";} ?> />
TB</div></td>
       
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Seizures"><div align="left">
          <input type="hidden" id="famhistseiz" name="famhistseiz" value="N" />
          <input type="checkbox" id="famhistseiz" name="famhistseiz" <?php if ($row_anestpreop['famhistseiz'] == "Y") {echo "checked=\"checked\"";} ?> />
Seiz</div></td>
          
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Kidney Disease"><div align="left">
          <input type="hidden" id="famhistkidneydx" name="famhistkidneydx" value="N">
          <input type="checkbox" id="famhistkidneydx" name="famhistkidneydx" <?php if ($row_anestpreop['famhistkidneydx'] == "Y") {echo "checked=\"checked\"";} ?> />
KD</div>  </td>

        	<td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" >&nbsp;</td>
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Notes/Comments">Others:</td>
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" ><textarea name="famhistothers" cols="20" rows="1" id="famhistothers"><?php echo $row_anestpreop['famhistothers']; ?></textarea></td>
    		</tr>
 <!--enter/display Social history-->
      	<tr>
        	<td class="borderbottomthinblackBold14" nowrap="nowrap" ><div align="center">Social History:</div></td>
 
        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Education" ><div align="left">
          <input type="hidden" name="sociahistedu" id="sociahistedu" value="N" />
           <input type="checkbox" name="sociahistedu" id="sociahistedu" <?php if ($row_anestpreop['sociahistedu'] == "Y") {echo "checked=\"checked\"";} ?> />
EDU</div></td>
        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Financial Status" ><div align="left">
          <input type="hidden" name="sociahistfinstatus" id="sociahistfinstatus" value="N" />
           <input type="checkbox" name="sociahistfinstatus" id="sociahistfinstatus" <?php if ($row_anestpreop['sociahistfinstatus'] == "Y") {echo "checked=\"checked\"";} ?> />
FS</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Social Supports" ><div align="left">
          <input type="hidden" name="sociahistsupports" id="sociahistsupports" value="N" />
           <input type="checkbox" name="sociahistsupports" id="sociahistsupports" <?php if ($row_anestpreop['sociahistsupports'] == "Y") {echo "checked=\"checked\"";} ?> />
SS</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Social Home" ><div align="left">
          <input type="hidden" name="sociahisthome" id="sociahisthome" value="N" />
           <input type="checkbox" name="sociahisthome" id="sociahisthome" <?php if ($row_anestpreop['sociahisthome'] == "Y") {echo "checked=\"checked\"";} ?> />
SH</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Sexually Active" ><div align="left">
          <input type="hidden" name="sociahistsexual" id="sociahistsexual" value="N" />
           <input type="checkbox" name="sociahistsexual" id="sociahistsexual" <?php if ($row_anestpreop['sociahistsexual'] == "Y") {echo "checked=\"checked\"";} ?> />
SA</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Social Habit" ><div align="left">
          <input type="hidden" name="sociahisthabit" id="sociahisthabit" value="N" />
           <input type="checkbox" name="sociahisthabit" id="sociahisthabit" <?php if ($row_anestpreop['sociahisthabit'] == "Y") {echo "checked=\"checked\"";} ?> />
SHB</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Social Work" ><div align="left">
          <input type="hidden" name="sociahistwork" id="sociahistwork" value="N" />
           <input type="checkbox" name="sociahistwork" id="sociahistwork" <?php if ($row_anestpreop['sociahistwork'] == "Y") {echo "checked=\"checked\"";} ?> />
SW</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Social Travels" ><div align="left">
          <input type="hidden" name="socishisttravels" id="socishisttravels" value="N" />
           <input type="checkbox" name="socishisttravels" id="socishisttravels" <?php if ($row_anestpreop['socishisttravels'] == "Y") {echo "checked=\"checked\"";} ?> />
ST</div></td>

        	<td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Contact with Sick Persons" ><div align="left">
          <input type="hidden" name="sociahistsickcontact" id="sociahistsickcontact" value="N" />
           <input type="checkbox" name="sociahistsickcontact" id="sociahistsickcontact" <?php if ($row_anestpreop['sociahistsickcontact'] == "Y") {echo "checked=\"checked\"";} ?> />
SC</div></td>

        	<td colspan="1" nowrap="nowrap" bgcolor="#C0E8D5" >&nbsp;</td>
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Notes/Comments">Others:</td>
        	<td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" ><textarea name="sociahistothers" cols="20" rows="1" id="sociahistothers"><?php echo $row_anestpreop['famhistothers']; ?></textarea></td>

   <?php // do { ?>
       <?php //} while ($row_anestpreop = mysql_fetch_assoc($anestpreop)); ?>
        </tr>
			</table>
    </td>
	</tr>
  <tr> <!-- ********************** Begin Dental ********************************************-->
  	<td>Dental Status: N = Normal, O = Mobile,  X = Missing</td>
  </tr>
  <tr>
    <td>
      <table width="600px" border="0" cellspacing="1" cellpadding="1" style="border-collapse:collapse">
        <tr>
          <td width="50px" align="center">8</td>
          <td width="50px" align="center">7</td>
          <td width="50px" align="center">6</td>
          <td width="50px" align="center">5</td>
          <td width="50px" align="center">4</td>
          <td width="50px" align="center">3</td>
          <td width="50px" align="center">2</td>
          <td width="50px" align="center">1</td>
          <td>&nbsp;</td>
          <td width="50px" align="center">1</td>
          <td width="50px" align="center">2</td>
          <td width="50px" align="center">3</td>
          <td width="50px" align="center">4</td>
          <td width="50px" align="center">5</td>
          <td width="50px" align="center">6</td>
          <td width="50px" align="center">7</td>
          <td width="50px" align="center">8</td>
        </tr>
        <tr>
          <td>
            <select name="0" id="0">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],0,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],0,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],0,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="1" id="1">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],1,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],1,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],1,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="2" id="2">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],2,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],2,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],2,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="3" id="3">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],3,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],3,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],3,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="4" id="4">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],4,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],4,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],4,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="5" id="5">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],5,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],5,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],5,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="6" id="6">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],6,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],6,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],6,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="7" id="7">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],7,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],7,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],7,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>&nbsp;</td>
          <td>
            <select name="8" id="8">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],8,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],8,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],8,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="9" id="9">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],9,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],9,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],9,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="10" id="10">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],10,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],10,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],10,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="11" id="11">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],11,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],11,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],11,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="12" id="12">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],12,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],12,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],12,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="13" id="13">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],13,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],13,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],13,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="14" id="14">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],14,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],14,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],14,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="15" id="15">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],15,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],15,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],15,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      
        <tr>
          <td>
            <select name="16" id="16">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],16,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],16,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],16,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="17" id="17">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],17,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],17,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],17,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="18" id="18">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],18,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],18,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],18,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="19" id="19">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],19,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],19,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],19,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="20" id="20">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],20,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],20,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],20,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="21" id="21">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],21,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],21,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],21,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="22" id="22">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],22,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],22,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],22,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="23" id="23">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],23,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],23,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],23,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>&nbsp;</td>
          <td>
            <select name="24" id="24">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],24,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],24,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],24,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="25" id="25">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],25,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],25,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],25,1)))) {echo "selected=\"selected\"";} ?>>X</option>
             </select></td>
          <td>
            <select name="26" id="26">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],26,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],26,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],26,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="27" id="27">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],27,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],27,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],27,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="28" id="28">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],28,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],28,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],28,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="29" id="29">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],29,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],29,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],29,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="30" id="30">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],30,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],30,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],30,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>
            <select name="31" id="31">
              <option value="N" <?php if(!(strcmp("N", substr($row_anestpreop['dentalstatus'],31,1)))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if(!(strcmp("O", substr($row_anestpreop['dentalstatus'],31,1)))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="X" <?php if(!(strcmp("X", substr($row_anestpreop['dentalstatus'],31,1)))) {echo "selected=\"selected\"";} ?>>X</option>
            </select></td>
          <td>&nbsp;</td>
              <input type="hidden" name="MM_update" value="anestpreop" />
              <input type="hidden" name="medrecnum" value="<?php echo $colname_mrn; ?>" />
              <input type="hidden" name="visitid" value="<?php echo $colname_vid; ?>" />
              <input type="hidden" name="sid" value="<?php echo $colname_sid; ?>" />
              <input type="hidden" name="dentalstatus" value="dentalstatus" />
            <input type="hidden" name="entryby" Value = "<?php echo $_SESSION['user']; ?>"/>
              <input type="hidden" name="entrydt" Value = "<?php echo date("Y-m-d H:i"); ?>" />
        </tr>
      
        <tr>
          <td width="50px" align="center">8</td>
          <td width="50px" align="center">7</td>
          <td width="50px" align="center">6</td>
          <td width="50px" align="center">5</td>
          <td width="50px" align="center">4</td>
          <td width="50px" align="center">3</td>
          <td width="50px" align="center">2</td>
          <td width="50px" align="center">1</td>
          <td>&nbsp;</td>
          <td width="50px" align="center">1</td>
          <td width="50px" align="center">2</td>
          <td width="50px" align="center">3</td>
          <td width="50px" align="center">4</td>
          <td width="50px" align="center">5</td>
          <td width="50px" align="center">6</td>
          <td width="50px" align="center">7</td>
          <td width="50px" align="center">8</td>
        </tr>
      </table>
    </td>
   </tr>  <!-- end of dental-->
   <tr>
    <td>
      <table border="1 style="border-collapse:collapse;"">
        <tr>
   <!--Allergy selector -->         
					<td nowrap>Allergy:
            <select name="allergies" id="allergies">
             <option value="" <?php if (!(strcmp("Select", $row_anestpreop['allergies']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <option value="Yes" <?php if (!(strcmp("Yes", $row_anestpreop['allergies']))) {echo "selected=\"selected\"";} ?>>Y</option>
          <option value="No" <?php if (!(strcmp("No", $row_anestpreop['allergies']))) {echo "selected=\"selected\"";} ?>>N</option>
          </select>
					</td>
<!--Lab Status -->         
					<td>Lab Status:
            <select name="labstatus" id="labstatus">
              <option value="" <?php if (!(strcmp("Select", $row_anestpreop['labstatus']))) {echo "selected=\"selected\"";} ?>>Select</option>
          		<option value="Complete" <?php if (!(strcmp("Complete", $row_anestpreop['labstatus']))) {echo "selected=\"selected\"";} ?>>Complete</option>
          		<option value="Incomplete" <?php if (!(strcmp("Incomplete", $row_anestpreop['labstatus']))) {echo "selected=\"selected\"";} ?>>Incomplete</option>
          	</select>
					</td>
 
<!--Nutrition Status -->         
					<td>Nutrition Status:
        <select name="nutritionstatus" id="nutritionstatus">
          <option value="" <?php if (!(strcmp("Select", $row_anestpreop['nutritionstatus']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <option value="Good" <?php if (!(strcmp("Good", $row_anestpreop['nutritionstatus']))) {echo "selected=\"selected\"";} ?>>Good</option>
          <option value="Fair" <?php if (!(strcmp("Fair", $row_anestpreop['nutritionstatus']))) {echo "selected=\"selected\"";} ?>>Fair</option>
          <option value="Poor" <?php if (!(strcmp("Poor", $row_anestpreop['nutritionstatus']))) {echo "selected=\"selected\"";} ?>>Poor</option>
          </select></td>
         

<!--Physical Status -->         
					<td>Physical Status:
        <select name="physicalstatus" id="physicalstatus">
          <option value="" <?php if (!(strcmp("Select", $row_anestpreop['physicalstatus']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <option value="Good" <?php if (!(strcmp("Good", $row_anestpreop['physicalstatus']))) {echo "selected=\"selected\"";} ?>>Good</option>
          <option value="Fair" <?php if (!(strcmp("Fair", $row_anestpreop['physicalstatus']))) {echo "selected=\"selected\"";} ?>>Fair</option>
          <option value="Poor" <?php if (!(strcmp("Poor", $row_anestpreop['physicalstatus']))) {echo "selected=\"selected\"";} ?>>Poor</option>
        </select>
					</td>
<!--ASA Grading -->         
					<td>ASA Grading:
					<select name="asagrading" id="asagrading">
            <option value="" <?php if (!(strcmp("Select", $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>Select</option>
            <option value="1" <?php if (!(strcmp(1, $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>1</option>
            <option value="2" <?php if (!(strcmp(2, $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>2</option>
            <option value="3" <?php if (!(strcmp(3, $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>3</option>
            <option value="4" <?php if (!(strcmp(4, $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="1E" <?php if (!(strcmp("1E", $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>1E</option>
            <option value="2E" <?php if (!(strcmp("2E", $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>2E</option>
            <option value="3E" <?php if (!(strcmp("3E", $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>3E</option>
            <option value="4E" <?php if (!(strcmp("4E", $row_anestpreop['asagrading']))) {echo "selected=\"selected\"";} ?>>4E</option>
            </select>        
					</td>
				</tr>
     		<tr>
  
        	<td height="18" align="right" scope="row">Last Oral Intake:</td>
                  <?php if(!empty($row_anestpreop['lastoralintake'])){  ?>
                <td  colspan="3" align="left" nowrap="nowrap">
              <input id="loiddt" name="loiddt" type="text" size="12" maxlength="15" value="<?php echo  date('D M d, Y', $row_anestpreop['lastoralintake']) ;?>" />              <input id="loiddt_alt" name="loiddt_alt" type="text" size="6" maxlength="15" value="<?php echo  date('h:i a', $row_anestpreop['lastoralintake']) ;?>" /></td>       
             <?php } else {?>
                <td align="left" nowrap="nowrap">
              <input id="loiddt" name="loiddt" type="text" size="12" maxlength="15" value="" />  </td>     
              <td><input id="loiddt_alt" name="loiddt_alt" type="text" size="8" maxlength="10" value="" /></td>
            
             <?php }?>
<!-- PreOp Anesthetic Orders --->
        	<td nowrap align="right" scope="row">Pre-Anesthetic Order</td>
        	<td colspan="5"><textarea name="preanestorders" id="preanestorders" class="surgdata" ><?php echo $row_anestpreop['preanestorders']?></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
  <tr>
        <input type="hidden" name="MM_update" value="formAnestPreop" />
        <input type="hidden" name="medrecnum" value="<?php echo $colname_mrn; ?>" />
        <input type="hidden" name="visitid" value="<?php echo $colname_vid; ?>" />
        <input type="hidden" name="sid" value="<?php echo $colname_sid; ?>" />
        <input type="hidden" name="dentalstatus" value="dentalstatus" />
     	  <input type="hidden" name="entryby" Value = "<?php echo $_SESSION['user']; ?>"/>
      	<input type="hidden" name="entrydt" Value = "<?php echo date("Y-m-d H:i"); ?>" />
    	<td colspan ="1" align="right"><input name="submit" type="submit" class="BlueBold_16" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Update PREOP"></td>
  </tr>
  </form>
</table>

      
</body>
</html><?php
mysql_free_result($anestpreop);
?>
