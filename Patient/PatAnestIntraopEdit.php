<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php //date_default_timezone_set('Africa/Lagos');?>
<?php //date_default_timezone_set('America/New_York');?>
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

$editFormAction = $_SERVER['PHP_SELF'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  }
  if ((isset($_POST["MM_UPDATE"])) && ($_POST["MM_UPDATE"] == "anestintraop")) {
// if posted value = '', then set value in database to NULL, otherwise current date will be saved by default
if($_POST['exscheddt'] == ''){	   $exschedt = NULL;} else {$exschedt = strtotime($_POST['exscheddt'].' '.$_POST['exscheddt_alt']);}
if($_POST['bascheddt'] == ''){	   $baschedt = NULL;} else {$baschedt = strtotime($_POST['bascheddt'].' '.$_POST['bascheddt_alt']);}
if($_POST['bsscheddt'] == ''){	   $bsschedt = NULL;} else {$bsschedt = strtotime($_POST['bsscheddt'].' '.$_POST['bsscheddt_alt']);}
if($_POST['esscheddt'] == ''){	   $esschedt = NULL;} else {$esschedt = strtotime($_POST['esscheddt'].' '.$_POST['esscheddt_alt']);}
if($_POST['eascheddt'] == ''){	   $easchedt = NULL;} else {$easchedt = strtotime($_POST['eascheddt'].' '.$_POST['eascheddt_alt']);}
//echo 'ex'.$_POST['exscheddt'].'..'.$_POST['exscheddt_alt'].'..'.$exschedt.'<br>'; 
//echo 'ba'.$_POST['bascheddt'].'..'.$_POST['bascheddt_alt'].'..'.$baschedt.'<br>';
//echo 'bs'.$_POST['bsscheddt'].'..'.$_POST['bsscheddt_alt'].'..'.$bsschedt.'<br>';
//echo 'es'.$_POST['esscheddt'].'..'.$_POST['esscheddt_alt'].'..'.$esschedt.'<br>';
//echo 'ea'.$_POST['eascheddt'].'..'.$_POST['eascheddt_alt'].'..'.$easchedt.'<br>';
//exit;

  $UPDATESQL = sprintf("UPDATE anesthesia SET intraopdt=%s, aneststarttime=%s, surgstarttime=%s, hblevel=%s, ebv=%s, mabl=%s, bldgp=%s, PtCondition=%s, cannularsite=%s, intubation=%s, circuit=%s, resp=%s, localanest=%s, generalanest=%s, localanestposition=%s, totalbldloss=%s, totalfluidgiven=%s, anestcomplications=%s, surgendtime=%s, anestendtime=%s, status=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($exschedt, "int"),
                       GetSQLValueString($baschedt, "int"),
                       GetSQLValueString($bsschedt, "int"),
                       GetSQLValueString($_POST['hblevel'], "text"),
                       GetSQLValueString($_POST['ebv'], "text"),
                       GetSQLValueString($_POST['mabl'], "text"),
                       GetSQLValueString($_POST['bldgp'], "text"),
                       GetSQLValueString($_POST['PtCondition'], "text"),
                       GetSQLValueString($_POST['cannularsite'], "text"),
                       GetSQLValueString($_POST['intubation'], "text"),
                       GetSQLValueString($_POST['circuit'], "text"),
                       GetSQLValueString($_POST['resp'], "text"),
                       GetSQLValueString($_POST['localanest'], "text"),
                       GetSQLValueString($_POST['generalanest'], "text"),
                       GetSQLValueString($_POST['localanestposition'], "text"),
                       GetSQLValueString($_POST['totalbldloss'], "text"),
                       GetSQLValueString($_POST['totalfluidgiven'], "text"),
                       GetSQLValueString($_POST['anestcomplications'], "text"),
                       GetSQLValueString($esschedt, "int"),
                       GetSQLValueString($easchedt, "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['aid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UPDATESQL, $swmisconn) or die(mysql_error());

	// keep surgery status same as ansthesia
  $updateSQL = sprintf("UPDATE surgery SET status=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['sid'], "int"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $UPDATEGoTo = "PatShow1.php";
    if (isset($_SERVER['QUERY_STRING'])) {
      $UPDATEGoTo .= (strpos($UPDATEGoTo, '?')) ? "&" : "?";
      $UPDATEGoTo .= $_SERVER['QUERY_STRING'];
    }
  header(sprintf("Location: %s", $UPDATEGoTo));
  }

	if(isset($_GET['mrn'])) {
		 $col_mrn = $_GET['mrn'];
	}
	if(isset($_GET['vid'])) {
		 $col_vid = $_GET['vid'];
	}
	if(isset($_GET['sid'])) {
		 $col_sid = $_GET['sid'];
	}

mysql_select_db($database_swmisconn, $swmisconn);
$query_anestintraop = "SELECT id aid, medrecnum, visitid, surgid, intraopdt, aneststarttime, surgstarttime, surgeon, surgeonassist, anesthetist, hblevel, ebv, mabl, bldgp, PtCondition, cannularsite, intubation, circuit, resp, localanest, generalanest, localanestposition, drugchart, vitalchart, fluidchart, totalbldloss, totalfluidgiven, anestcomplications, surgendtime, anestendtime, status, entrydt, entryby FROM anesthesia WHERE surgid='". $col_sid."'";
$anestintraop = mysql_query($query_anestintraop, $swmisconn) or die(mysql_error());
$row_anestintraop = mysql_fetch_assoc($anestintraop);
$totalRows_anestintraop = mysql_num_rows($anestintraop);

//  query for anesthetist ddl
mysql_select_db($database_swmisconn, $swmisconn);
$query_anesthetist = "SELECT id uid, userid FROM users WHERE active = 'Y' and anflag = 'Y' Order BY userid";
$anesthetist = mysql_query($query_anesthetist, $swmisconn) or die(mysql_error());
$row_anesthetist = mysql_fetch_assoc($anesthetist);
$totalRows_anesthetist = mysql_num_rows($anesthetist);

// query for surgeon and assisting surgeon ddl
mysql_select_db($database_swmisconn, $swmisconn);
$query_surgeon = "SELECT id uid, userid FROM users WHERE docflag = 'Y' and active = 'Y' ORDER BY userid";
$surgeon = mysql_query($query_surgeon, $swmisconn) or die(mysql_error());
$row_surgeon = mysql_fetch_assoc($surgeon);
$totalRows_surgeon = mysql_num_rows($surgeon);

//find drugs for this anesthesia procedure
$colname_anestdrugs = "-1";
if (isset($row_anestintraop['aid'])) {
  $colname_anestdrugs = $row_anestintraop['aid'];
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_anestdrugs = sprintf("SELECT ad.id, ad.druglistid, ad.anestid, ad.begindrug, ad.enddrug, ad.autoend, al.drug FROM anestdrug ad join anestdruglist al on ad.druglistid = al.id WHERE anestid = %s ORDER BY ad.begindrug ASC", GetSQLValueString($colname_anestdrugs, "int"));
$anestdrugs = mysql_query($query_anestdrugs, $swmisconn) or die(mysql_error());
$row_anestdrugs = mysql_fetch_assoc($anestdrugs);
$totalRows_anestdrugs = mysql_num_rows($anestdrugs);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Intraop Anesthesia</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
  function MM_openBrWindow(theURL,winName,features) { //v2.0
    var win_position = ',left=400,top=5,screenX=400,screenY=5';
    var newWindow = window.open(theURL,winName,features+win_position);
    newWindow.focus();
  }
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

<!-- for date & time picker-->
<link rel="stylesheet" href="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="../../js/jquery-ui-timepicker-addon.css">
<script src="../../js/jquery.js"></script>
<script src="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../../js/jquery-ui-timepicker-addon.js"></script>

<!-- exscheddt-->
<script>
$( function() {
$('#exscheddt').datetimepicker({
	altField: "#exscheddt_alt",
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
$('#bascheddt').datetimepicker({
	altField: "#bascheddt_alt",
	controlType: 'select',
	oneLine: true,
	dateFormat: 'D M d, yy',
	timeFormat: 'hh:mm tt',
	buttonImage: "../../js/jquery-ui-1.12.1.custom/images/calendar.gif",
	showOn: "button", 
  buttonImageOnly: false
});
});
</script>
<!-- bsscheddt-->
<script>
$( function() {
$('#bsscheddt').datetimepicker({
	altField: "#bsscheddt_alt",
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
<!-- eascheddt-->
<script>
$( function() {
$('#eascheddt').datetimepicker({
	altField: "#eascheddt_alt",
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
<!-- esscheddt-->
<script>
$( function() {
$('#esscheddt').datetimepicker({
	altField: "#esscheddt_alt",
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
<!-- style below and scrolling table are from from https://www.geeksforgeeks.org/how-to-create-table-with-100-width-with-vertical-scroll-inside-table-body-in-html/-->
</head>

<body>
<table align="center" width="50%">
	<tr>
		<td nowrap="nowrap">
			<div align="center" class="BlueBold_20">Anesthesia INTRAOP Update</div>
		</td>
	</tr>
</table>
<table align="center" width="50%" border="0" style="border-collapse:collapse;">
<tr>
   <td nowrap="nowrap" align="left"><a href="PatShow1.php?mrn=<?php echo $col_mrn ?>&vid=<?php echo $col_vid ?>&sid=<?php echo $col_sid ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php" class="BlueBold_24" style="background-color:violet; border-color:blue; color:white; text-align:center; border-radius:5px;">Surg Update</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td nowrap="nowrap"><div><a href="Patshow1.php?mrn=<?php echo $col_mrn ?>&vid=<?php echo $col_vid ?>&sid=<?php echo $col_sid ?>&aid=<?php echo $row_anestintraop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestPreopEdit.php" title="Update Preop" class="BlueBold_24"  style="background-color:green; border-color:blue; color:white; text-align:center; border-radius:5px;">Anest Preop</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
   <td nowrap="nowrap" align="right"><div><a href="Patshow1.php?mrn=<?php echo $col_mrn ?>&vid=<?php echo $col_vid ?>&sid=<?php echo $col_sid ?>&aid=<?php echo $row_anestintraop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestPostopEdit.php" title="Update Postop" class="BlueBold_24"  style="background-color:orange; border-color:blue; color:white; text-align:center; border-radius:5px;">Anest Postop</a></div></td>
   </td>
</tr><p>&nbsp;</p>
</table>
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="0" >
	<tr>
		<td>
			<table border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#F8FDCE">
				<tr>
					<td align="left" nowrap="nowrap">
						<table border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#F8FDCE">
 					 <form method="POST" name="anestintraop" action="<?php echo $editFormAction; ?>" >
							<tr>
								<td nowrap>Presenting Patient condition:
                  <select name="PtCondition" id="PtCondition">
                    <option value="" <?php if (!(strcmp("Select", $row_anestintraop['PtCondition']))) {echo "selected=\"selected\"";} ?>>Select</option>
                    <option value="Good" <?php if (!(strcmp("Good", $row_anestintraop['PtCondition']))) {echo "selected=\"selected\"";} ?>>Good</option>
                    <option value="Fair" <?php if (!(strcmp("Fair", $row_anestintraop['PtCondition']))) {echo "selected=\"selected\"";} ?>>Fair</option>
                    <option value="Poor" <?php if (!(strcmp("Poor", $row_anestintraop['PtCondition']))) {echo "selected=\"selected\"";} ?>>Poor</option>
                  </select></td>
            		<td nowrap="nowrap">Local Anest:
              		<select name="localanest" id="localanest">
                    <option value="" <?php if (!(strcmp("Select", $row_anestintraop['localanest']))) {echo "selected=\"selected\"";} ?>>Select</option>
                    <option value="Infiltrated" <?php if (!(strcmp("Infiltrated", $row_anestintraop['localanest']))) {echo "selected=\"selected\"";} ?>>Infiltrated</option>
                    <option value="Blocked" <?php if (!(strcmp("Blocked", $row_anestintraop['localanest']))) {echo "selected=\"selected\"";} ?>>Blocked</option>
                    <option value="Spinal" <?php if (!(strcmp("Spinal", $row_anestintraop['localanest']))) {echo "selected=\"selected\"";} ?>>Spinal</option>
                    <option value="Epidural" <?php if (!(strcmp("Epidural", $row_anestintraop['localanest']))) {echo "selected=\"selected\"";} ?>>Epidural</option>
              		</select></td>
								<td title="Estimated Blood Volume">EBV:
              		<input type="text" name="ebv" id="ebv" size='2' max='15' value="<?php echo $row_anestintraop['ebv'] ?>"/>ml </td>
            		<td align="right" scope="row">aneststarttime:</td>
            		<td align="left" nowrap="nowrap">
             <?php  if(isset($row_anestintraop['aneststarttime']) && $row_anestintraop['aneststarttime'] != NULL &&  $row_anestintraop['aneststarttime'] != 0) {?>
									<input id="bascheddt" name="bascheddt" type="text" size="12" maxlength="15"  value="<?php echo  date('D M d, Y', $row_anestintraop['aneststarttime']) ;?>" />
              		<input id="bascheddt_alt" name="bascheddt_alt" type="text" size="8" maxlength="10" value="<?php echo  date('h:i a', $row_anestintraop['aneststarttime']) ;?>" />
              <!-- to delete date/time-->
            		<td title="aid: <?php echo $row_anestintraop['aid']?>&#10; Date: <?php echo date('D M d, Y', $row_anestintraop['intraopdt']) ;?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestSchedDelete.php?aid=<?php echo $row_anestintraop['aid']?>&intradate=aneststarttime','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Del</a></td>
						<?php } else {?>
            			<input id="bascheddt" name="bascheddt" type="text" size="12" maxlength="15"  value="" />
              		<input id="bascheddt_alt" name="bascheddt_alt" type="text" size="8" maxlength="10" value="" />
                <td></td>
						<?php }?>
            	</tr>
          		<tr>
            		<td title="Hemoglobin Level">HB Level:
              		<input type="text" name="hblevel" id="hblevel"  size='2' max='15' value="<?php echo $row_anestintraop['hblevel'] ?>"/></td>
            		<td nowrap="nowrap">General Anest:
                  <select name="generalanest" id="generalanest">
                    <option value="" <?php if (!(strcmp("Select", $row_anestintraop['generalanest']))) {echo "selected=\"selected\"";} ?>>Select</option>
                    <option title="Total Intra Venous Anesthesia" value="TIVA" <?php if (!(strcmp("TIVA", $row_anestintraop['generalanest']))) {echo "selected=\"selected\"";} ?>>TIVA</option>
                    <option value="Intubation" <?php if (!(strcmp("Intubation", $row_anestintraop['generalanest']))) {echo "selected=\"selected\"";} ?>>Intubation</option>
                    <option title="Laryngeal Mask Airway" value="LMA" <?php if (!(strcmp("LMA", $row_anestintraop['generalanest']))) {echo "selected=\"selected\"";} ?>>LMA</option>
                  </select>
            		</td>
            		<td title="Maximum Allowable Blood Loss">MABL
 		              <input type="text" name="mabl" id="mabl"  size='2' max='15' value="<?php echo $row_anestintraop['mabl'] ?>"/>ml </td>
            <td align="right" scope="row">surgstarttime:</td>
            <td align="left" nowrap="nowrap">
         <?php  if(isset($row_anestintraop['surgstarttime']) && $row_anestintraop['surgstarttime'] != NULL ) {?>
            	<input id="bsscheddt" name="bsscheddt" type="text" size="12" maxlength="15"  value="<?php echo  date('D M d, Y', $row_anestintraop['surgstarttime']) ;?>" />
              <input id="bsscheddt_alt" name="bsscheddt_alt" type="text" size="8" maxlength="10" value="<?php echo  date('h:i a', $row_anestintraop['surgstarttime']) ;?>" />
             <!-- to delete date/time-->
            		<td title="aid: <?php echo $row_anestintraop['aid']?>&#10; Date: <?php echo date('D M d, Y', $row_anestintraop['intraopdt']) ;?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestSchedDelete.php?aid=<?php echo $row_anestintraop['aid']?>&intradate=surgstarttime','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Del</a></td>
         <?php } else {?>
            	<input id="bsscheddt" name="bsscheddt" type="text" size="12" maxlength="15"  value="" />
              <input id="bsscheddt_alt" name="bsscheddt_alt" type="text" size="8" maxlength="10" value="" />
                <td></td>
         <?php }?>
            	</tr>

          		<tr>
            		<td title="Blood Group">Bld Gp:
                  <select name="bldgp" id="bldgp">
                    <option value="" <?php if (!(strcmp("Select", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>Select</option>
                    <option value="O POS" <?php if (!(strcmp("O POS", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>O POS</option>
                    <option value="A POS" <?php if (!(strcmp("A POS", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>A POS</option>
                    <option value="B POS" <?php if (!(strcmp("B POS", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>B POS</option>
                    <option value="AB POS" <?php if (!(strcmp("AB POS", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>AB POS</option>
                    <option value="O NEG" <?php if (!(strcmp("O NEG", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>O NEG</option>
                    <option value="A NEG" <?php if (!(strcmp("A NEG", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>A NEG</option>
                    <option value="B NEG" <?php if (!(strcmp("B NEG", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>B NEG</option>
                    <option value="AB NEG" <?php if (!(strcmp("AB NEG", $row_anestintraop['bldgp']))) {echo "selected=\"selected\"";} ?>>AB NEG</option>
                  </select></td>
            		<td title="Level of Block" nowrap="nowrap">Local Anest Position:
                  <select name="localanestposition" id="localanestposition">
                    <option value="" <?php if (!(strcmp("Select", $row_anestintraop['localanestposition']))) {echo "selected=\"selected\"";} ?>>Select</option>
                    <option value="L1-L2" <?php if (!(strcmp("L1-L2", $row_anestintraop['localanestposition']))) {echo "selected=\"selected\"";} ?>>L1-L2</option>
                    <option value="L2-L3" <?php if (!(strcmp("L2-L3", $row_anestintraop['localanestposition']))) {echo "selected=\"selected\"";} ?>>L2-L3</option>
                    <option value="L3-L4" <?php if (!(strcmp("L3-L4", $row_anestintraop['localanestposition']))) {echo "selected=\"selected\"";} ?>>L3-L4</option>
                    <option value="L4-L5" <?php if (!(strcmp("L4-L5", $row_anestintraop['localanestposition']))) {echo "selected=\"selected\"";} ?>>L4-L5</option>
                  </select>
            		</td>
            <td title="Total Blood Loss">TBL:
              <input type="text" name="totalbldloss" id="totalbldloss" size='2' max='5' value="<?php echo $row_anestintraop['totalbldloss'] ?>"/>
              ml </td>
            <td height="18" align="right" scope="row">surgendtime:</td>
            <td align="left" nowrap="nowrap">
         <?php  if(isset($row_anestintraop['surgendtime']) && $row_anestintraop['surgendtime'] != NULL ) {?>
              <input id="esscheddt" name="esscheddt" type="text" size="12" maxlength="15" value="<?php echo  date('D M d, Y', $row_anestintraop['surgendtime']) ;?>" />
              <input id="esscheddt_alt" name="esscheddt_alt" type="text" size="8" maxlength="10" value="<?php echo  date('h:i a', $row_anestintraop['surgendtime']) ;?>" />
             <!-- to delete date/time-->
            		<td title="aid: <?php echo $row_anestintraop['aid']?>&#10; Date: <?php echo date('D M d, Y', $row_anestintraop['intraopdt']) ;?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestSchedDelete.php?aid=<?php echo $row_anestintraop['aid']?>&intradate=surgendtime','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Del</a></td>
				    <?php } else {?>
            	<input id="esscheddt" name="esscheddt" type="text" size="12" maxlength="15"  value="" />
              <input id="esscheddt_alt" name="esscheddt_alt" type="text" size="8" maxlength="10" value="" />
                <td></td>
         <?php }?>
          </tr>

          <tr>
            <td nowrap="nowrap">Respiration:
              <select name="resp" id="resp">
                <option value="" <?php if (!(strcmp("Select", $row_anestintraop['resp']))) {echo "selected=\"selected\"";} ?>>Select</option>
                <option value="Circle" <?php if (!(strcmp("Circle", $row_anestintraop['resp']))) {echo "selected=\"selected\"";} ?>>Circle</option>
                <option value="Controlled" <?php if (!(strcmp("Controlled", $row_anestintraop['resp']))) {echo "selected=\"selected\"";} ?>>Controlled</option>
                <option value="Spontaneous" <?php if (!(strcmp("Spontaneous", $row_anestintraop['resp']))) {echo "selected=\"selected\"";} ?>>Spontaneous</option>
              </select></td>
            <td nowrap="nowrap">Intubation:
              <select name="intubation" id="intubation">
                <option value="" <?php if (!(strcmp("Select", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Select</option>
                <option value="Oral" <?php if (!(strcmp("Oral", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Oral</option>
                <option value="Nasal" <?php if (!(strcmp("Nasal", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Nasal</option>
                <option value="Cuffed" <?php if (!(strcmp("Cuffed", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Cuffed</option>
                <option value="Packed" <?php if (!(strcmp("Packed", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Packed</option>
                <option value="Spray" <?php if (!(strcmp("Spray", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>Spray</option>
                <option value="difficulty" <?php if (!(strcmp("difficulty", $row_anestintraop['intubation']))) {echo "selected=\"selected\"";} ?>>difficulty</option>
              </select>
            </td>
            <td title="Total Fluid Given">TFG:
              <input type="text" name="totalfluidgiven" id="totalfluidgiven"  size='2' max='5' value="<?php echo $row_anestintraop['totalfluidgiven'] ?>"/> ml </td>

            <td height="18" align="right" scope="row">anestendtime:</td>
            <td align="left" nowrap="nowrap">
         <?php  if(isset($row_anestintraop['anestendtime']) && $row_anestintraop['anestendtime'] != NULL ) {?>
              <input id="eascheddt" name="eascheddt" type="text" size="12" maxlength="15" value="<?php echo  date('D M d, Y', $row_anestintraop['anestendtime']) ;?>" />
              <input id="eascheddt_alt" name="eascheddt_alt" type="text" size="8" maxlength="10" value="<?php echo  date('h:i a', $row_anestintraop['anestendtime']) ;?>" />
             <!-- to delete date/time-->
            		<td title="aid: <?php echo $row_anestintraop['aid']?>&#10; Date: <?php echo date('D M d, Y', $row_anestintraop['anestendtime']) ;?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestSchedDelete.php?aid=<?php echo $row_anestintraop['aid']?>&intradate=anestendtime','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Del</a></td>
				<?php } else {?>
            	<input id="eascheddt" name="eascheddt" type="text" size="12" maxlength="15"  value="" />
              <input id="eascheddt_alt" name="eascheddt_alt" type="text" size="8" maxlength="10" value="" />
                <td></td>
				<?php }?>

          </tr>
          <tr>
            <td>Cannular Site:<input type="text" name="cannularsite" id="cannularsite"  max='5' value="<?php echo $row_anestintraop['cannularsite'] ?>"/>
            <td nowrap>circuit:
              <select name="circuit" id="circuit">
                <option value="" <?php if (!(strcmp("Select", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>Select</option>
                <option value="Circle" <?php if (!(strcmp("Circle", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>Circle</option>
                <option value="Semicircle" <?php if (!(strcmp("Semicircle", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>Semicircle</option>
                <option value="Non-rebreth" <?php if (!(strcmp("Non-rebreth", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>Non-rebreth</option>
                <option value="Draw-over" <?php if (!(strcmp("Draw-over", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>Draw-over</option>
                <option value="T-piece" <?php if (!(strcmp("T-piece", $row_anestintraop['circuit']))) {echo "selected=\"selected\"";} ?>>T-piece</option>
              </select>
            </td>
            <td colspan='5'><textarea name="anestcomplications" id="anestcomplications" cols="60" placeholder="Add Anest Complications Here" ><?php echo $row_anestintraop['anestcomplications']?></textarea></td>
          </tr>
				  <tr>
  					<td>&nbsp;</td>
            		<td align="center"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestIntraopVitals.php?anestid=<?php echo $row_anestintraop['aid'] ?>&act=add','StatusView','scrollbars=yes,resizable=yes,width=800,height=400')" class="BlueBold_24" style="background-color:yellow; border-color:blue; color:black; text-align:center;border-radius:5px;">Add Vitals</a> </td>
<!--  					<td align='center'><a href="PatAnestIntraopVitals.php?anestid=<?php echo $row_anestintraop['aid'] ?>">Add Vitals</a></td>
-->
  	<td>Status:</td>
    <!--Status selector -->
    <td align="right" nowrap title="Anest Id: <?php echo $row_anestintraop['aid'] ?>&#10;Surg Id: <?php echo $row_anestintraop['surgid'] ?>&#10;MRN: <?php echo $row_anestintraop['medrecnum'] ?>&#10;Visit Id: <?php echo $row_anestintraop['visitid'] ?>">
      <select name="status" id="status">
        <option value="Ordered" <?php if (!(strcmp("Ordered", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
        <option value="Scheduled" <?php if (!(strcmp("Scheduled", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
        <option value="In-Progress" <?php if (!(strcmp("In-Progress", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
        <option value="Recovery" <?php if (!(strcmp("Recovery", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>Recovery</option>
        <option value="Complete" <?php if (!(strcmp("Complete", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>Complete</option>
        <option value="Cancelled" <?php if (!(strcmp("Cancelled", $row_anestintraop['status']))) {echo "selected=\"selected\"";} ?>>Cancelled</option>
			</select></td>
      <!--Submit form-->
  					<td align="right"><input type="submit" name="Add" id="Add" class="BlueBold_16" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Update INTRAOP" /></td>
          </tr>
        <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="sid" id="sid" value="<?php echo $row_anestintraop['surgid'] ?>" />
        <input type="hidden" name="aid" id="aid" value="<?php echo $row_anestintraop['aid'] ?>" />
        <input type="hidden" name="MM_UPDATE" value="anestintraop" />
        </form>    
        </table>
      </td>
       
<!--  section to add, edit, delete drugs for surgery/anesthesia   section to add, edit, delete drugs for surgery/anesthesia   -->       
      <td valign="top">
        <table style="width:100%; border-spacing:0; border:2px solid black;"> 
				<thead style="display:block;">
          <tr>
            <td style="border:none;"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestDrugAdd.php?anestid=<?php echo $row_anestintraop['aid'];?>&ba=<?php echo $row_anestintraop['aneststarttime'];?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Add</a>
            </td>
            <td nowrap="nowrap" style="border:none;">&nbsp;<a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestDrugAdd.php?anestid=<?php echo $row_anestintraop['aid'];?>&ba=<?php echo $row_anestintraop['aneststarttime'];?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Drug</a>
            <span  class="Black_11" style="border:none;">** = autoadd enddate</span></td>
            <td colspan="3" nowrap="nowrap" class="subtitlebk" style="border:none;"><?php echo date("D, d-M-Y"); ?></td>
          </tr>
          <tr>
            <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" class="BlackBold_12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;drug&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="center" class="BlackBold_12">&nbsp;begindrug&nbsp;</td>
            <td align="center" class="BlackBold_12">&nbsp;&nbsp;&nbsp;enddrug&nbsp;&nbsp;&nbsp;</td>
            <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          </tr>
         </thead>
         <tbody style="display:block; overflow-y:auto; overflow-x:hidden; height:150px;">
    <?php if($totalRows_anestdrugs > 0) { ?>
          <?php do { ?>
            <tr>
        <!--    <td style="padding:0px;">
        <div style='width:75% !important;overflow-x:scroll;position:relative;'>This DIV should have a large width</div>-->

              <td title="ID: <?php echo $row_anestdrugs['id']; ?>&#10;anest ID: <?php echo $row_anestdrugs['anestid']; ?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestDrugEdit.php?anestid=<?php echo $row_anestintraop['aid']?>&anestdrugid=<?php echo $row_anestdrugs['id'];?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Edit</a></td>
          <?php if($row_anestdrugs['autoend'] == 'Y'){?>
              <td nowrap="nowrap"><input name="drug" size = "16" readonly value="<?php echo $row_anestdrugs['drug'].'**'; ?>" /></td>
					<?php } else {?>
              <td nowrap="nowrap"><input name="drug" size = "16" readonly value="<?php echo $row_anestdrugs['drug']; ?>" /></td>
					<?php }?>
          
          <?php  if(isset($row_anestdrugs['begindrug']) && $row_anestdrugs['begindrug'] != 0 ) {?>
             <td nowrap="nowrap" title="Date: <?php echo date('D, M d, Y',$row_anestdrugs['begindrug']); ?>"><input name="begindrug" size = "4" readonly value="<?php echo date('h:i A',$row_anestdrugs['begindrug']); ?>"  /></td>
          <?php } else {  ?>
             <td nowrap="nowrap"></td>
          <?php }  ?>
          
          <?php if(isset($row_anestdrugs['enddrug']) && !empty($row_anestdrugs['enddrug']  )) {?>
        <!--  check for begin date > end date error-->
							<?php if(!empty($row_anestdrugs['enddrug']) && $row_anestdrugs['begindrug'] > $row_anestdrugs['enddrug']){ ?>
                      <td nowrap="nowrap"><input name="enddrug" size = "15" readonly class="flagWhiteonRed" value="Begin date > End Date"/></td>	
              <?php } else {  ?>          
                <td nowrap="nowrap"><input name="enddrug" size = "4" readonly value="<?php echo date('h:i A',$row_anestdrugs['enddrug']); ?>"/></td>
              <?php }  ?> 
          <?php } else {  ?>          
                  <td nowrap="nowrap"></td>
          <?php }  ?> 
          
             <td title="ID: <?php echo $row_anestdrugs['id']; ?>&#10;anest ID: <?php echo $row_anestdrugs['anestid']; ?>"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAnestDrugDelete.php?anestid=<?php echo $row_anestintraop['aid']?>&anestdrugid=<?php echo $row_anestdrugs['id'];?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Delete</a></td>
            </tr>
            <?php } while ($row_anestdrugs = mysql_fetch_assoc($anestdrugs)); ?>
        <?php }  ?>  <!--if > 0 drugs-->
						</tbody>
          </table>
        </td>
      </tr>
    </table>
	</tr>
</table> 

<!-- Surgery/Anesthesia/Drug Chart   Surgery/Anesthesia/Drug Chart   Surgery/Anesthesia/Drug Chart   Surgery/Anesthesia/Drug Chart  -->
<?php if($row_anestintraop['aneststarttime'] == null) {
		    $aneststarttime = time(); 
     } else {
		    $aneststarttime = $row_anestintraop['aneststarttime'];	 
		 }?>
<?php if($row_anestintraop['surgstarttime'] == null) {
		    $surgstarttime = time(); 
     } else {
		    $surgstarttime = $row_anestintraop['surgstarttime'];	 
		 }?>

<?php if($row_anestintraop['anestendtime'] == null) {
		    $anestendtime = time(); 
     } else {
		    $anestendtime = $row_anestintraop['anestendtime'];	 
		 }?>
<?php if($row_anestintraop['surgendtime'] == null) {
		    $surgendtime = time(); 
     } else {
		    $surgendtime = $row_anestintraop['surgendtime'];	 
		 }?>

<table align="center">
  <tr>
  	<td><div>
			<img src="../../Gantt.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartAnestTime_Meds">
		</div></td>
	</tr>
</table>
<table align="center">
	<tr>
		<td>
			<table>
      	<tr>
          <td><div>
            <img src="PatAnestChartPulse.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartPulse">
          </div></td>
        </tr>
        <tr>
          <td><div>
            <img src="PatAnestChartBPsys.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="BPsys">
          </div></td>
        </tr>
        <tr>
  	<td><div>
			<img src="PatAnestChartBPdia.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartBPdia">
		</div></td>
        </tr>
      </table>
    </td>
		<td>
			<table>
      	<tr>
          <td><div>
            <img src="PatAnestChartTemp.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartTemp">
          </div></td>
        </tr>
          <td><div>
            <img src="PatAnestChartResp.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartResp">
          </div></td>
        </tr>
        <tr>
          <td><div>
            <img src="PatAnestChartOxSat.php? aid=<?php echo $row_anestintraop['aid'] ?>&ba=<?php echo $aneststarttime ?>&ea=<?php echo $anestendtime ?>&bs=<?php echo $surgstarttime ?>&es=<?php echo $surgendtime ?>" alt="ChartOxSat">
          </div></td>
        </tr>
      </table>
    </td>
	</tr>
	</div>
	</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($anestintraop);


mysql_free_result($anestdrugs);

?>

