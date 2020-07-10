<?php require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
  $scheddt = 0;
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<!--@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@  ADD  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@-->
<?php
if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Submit' AND isset($_POST["MM_insert"]) AND $_POST["MM_insert"] == "formvital") {

if($_POST['schedt'] == ''){ $scheddt = NULL;} else { $scheddt = strtotime($_POST['schedt'].' '.$_POST['schedt_alt']);}

 if (isset($_POST['anesttemp']) AND $_POST['anesttemp'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (anestid, schedt, vital, value, comment, entryby, entrydt, entrynum) VALUES (%s, %s, 'temp', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['anestid'], "int"),
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString($_POST['anesttemp']*10, "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }
  
 if (isset($_POST['anestpulse']) AND $_POST['anestpulse'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (anestid, schedt, vital, value, comment, entryby, entrydt, entrynum) VALUES (%s, %s, 'pulse', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['anestid'], "int"),
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString($_POST['anestpulse'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['anestresp']) AND $_POST['anestresp'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (anestid, schedt, vital, value, comment, entryby, entrydt, entrynum) VALUES (%s, %s, 'resp', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['anestid'], "int"),
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString($_POST['anestresp'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['anestbps']) AND $_POST['anestbps'] > 0 AND isset($_POST['anestbpd']) AND $_POST['anestbpd'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (anestid, schedt, vital, value, value2, comment, entryby, entrydt, entrynum) VALUES (%s, %s, 'bpsd', %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['anestid'], "int"),
                       GetSQLValueString($scheddt, "int"),
	                     GetSQLValueString($_POST['anestbps'], "int"),
                       GetSQLValueString($_POST['anestbpd'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }



 if (isset($_POST['oxysaturation']) AND $_POST['oxysaturation'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (anestid, schedt, vital, value, comment, entryby, entrydt, entrynum) VALUES (%s, %s, 'oxysat', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['anestid'], "int"),
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString($_POST['oxysaturation'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

	$saved = "true";
}
?>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@  END ADD @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$  EDIT UPDATE $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->

<?php
if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Update' AND isset($_POST["MM_Update"]) AND $_POST["MM_Update"] == "formvitalupdate") {

if($_POST['schedt'] == ''){ $scheddt = NULL;} else { $scheddt = strtotime($_POST['schedt'].' '.$_POST['schedt_alt']);}

 if (isset($_POST['anesttemp']) AND $_POST['anesttemp'] > 0) {
  $UpdateSQL = sprintf("Update ipvitals SET  schedt=%s, vital=%s, value=%s, comment=%s, entryby=%s, entrydt=%s WHERE vital = 'temp' and anestid=%s and entrynum=%s",
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString('temp', "text"),
                       GetSQLValueString($_POST['anesttemp']*10, "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['anestid'], "int"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  }
  
 if (isset($_POST['anestpulse']) AND $_POST['anestpulse'] > 0) {
  $UpdateSQL = sprintf("Update ipvitals SET  schedt=%s, vital=%s, value=%s, comment=%s, entryby=%s, entrydt=%s WHERE vital = 'pulse' and  anestid=%s and entrynum=%s",
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString('pulse', "text"),
                       GetSQLValueString($_POST['anestpulse'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['anestid'], "int"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['anestresp']) AND $_POST['anestresp'] > 0) {
  $UpdateSQL = sprintf("Update ipvitals SET  schedt=%s, vital=%s, value=%s, comment=%s, entryby=%s, entrydt=%s WHERE vital = 'resp' and  anestid=%s and entrynum=%s",
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString('resp', "text"),
                       GetSQLValueString($_POST['anestresp'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['anestid'], "int"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['anestbps']) AND $_POST['anestbps'] > 0 AND isset($_POST['anestbpd']) AND $_POST['anestbpd'] > 0) {
  $UpdateSQL = sprintf("Update ipvitals SET  schedt=%s, vital=%s, value=%s, value2=%s, comment=%s, entryby=%s, entrydt=%s WHERE vital = 'bpsd' and  anestid=%s and entrynum=%s",
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString('bpsd', "text"),
                       GetSQLValueString($_POST['anestbps'], "int"),
                       GetSQLValueString($_POST['anestbpd'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['anestid'], "int"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['oxysaturation']) AND $_POST['oxysaturation'] > 0) {
  $UpdateSQL = sprintf("Update ipvitals SET  schedt=%s, vital=%s, value=%s, comment=%s, entryby=%s, entrydt=%s WHERE vital = 'oxysat' and anestid=%s and entrynum=%s",
                       GetSQLValueString($scheddt, "int"),
                       GetSQLValueString('oxysat', "text"),
                       GetSQLValueString($_POST['oxysaturation'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
									   	 GetSQLValueString($_POST['entryby'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['anestid'], "int"),
										   GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  }

	$saved = "true";
}
?>


<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$  END EDIT UPDATE $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->

<!--####################################################  DELETE  #############################################-->
<!--####################################################  DELETE  #############################################-->
<?php if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Delete' AND isset($_POST["MM_Delete"]) AND $_POST["MM_Delete"] == "formvitaldelete") { 

  $DeleteSQL = sprintf("DELETE FROM ipvitals WHERE anestid=%s && entrynum=%s",
                       GetSQLValueString($_POST['anestid'], "int"),
											 GetSQLValueString($_POST['entrynum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($DeleteSQL, $swmisconn) or die(mysql_error());

 $saved = 'true'; // triggers <body onload="out()"  below to close the edit window and refresh the calling page with the new data


 }?>  

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<!-- for date & time picker-->
<link rel="stylesheet" href="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="../../js/jquery-ui-timepicker-addon.css">
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<script src="../../js/jquery.js"></script>
<script src="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../../js/jquery-ui-timepicker-addon.js"></script>

<!-- exscheddt-->
<script>
$( function() {
	$('#schedt').datetimepicker({
		altField: "#schedt_alt",
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
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>

<?php 		$colname_vitals = "-1";
if (isset($_GET['anestid'])) {
  $colname_vitals = $_GET['anestid'];
}
?>
<!--********************Begin ADD *************************************************************************************************-->

<?php if($_GET['act'] == 'add') {
mysql_select_db($database_swmisconn, $swmisconn);
$query_entrynum = sprintf("SELECT Max(entrynum) entnum FROM ipvitals WHERE anestid = '".$_GET['anestid']."'");
$entrynum = mysql_query($query_entrynum, $swmisconn) or die(mysql_error());
$row_entrynum = mysql_fetch_assoc($entrynum);
$totalRows_entrynum = mysql_num_rows($entrynum);

if($totalRows_entrynum == 0){ $entrynum = 1; } else {$newentrynum = $row_entrynum['entnum'] + 1;}

//$newentrynum is entrynum value for next entry
?>
<div align="center" class="BlueBold_20">Add Vitals</div>
<table width="700" border="1" align="center" style="border-collapse: collapse;" bgcolor="#99FF66">
<form name="formvital" method="post" action="">  
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
      </td>
    <td>&nbsp;</td>
    <td colspan="3" align="center">IntraOP Vitals</td>
    <td>&nbsp;<?php //echo strtotime("now") ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>Time</p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>Temp <br>
        35-41</p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>Pulse<br>
        20-160</p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>Resp<br>
        10-40      <br>
      </p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>Blood Pressure<br>
        50-300 / 20-150</p>
    </td>
    <td align="center">
      <p>Oxygen<br>
        Saturation</p>
      <p>20 - 100 </p>
    </td>
    <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" tabindex="0" /></td>
  </tr>
  <tr>
<!-- I want the new datetime picker we used for surg/anest project
-->  	
    <td nowrap="nowrap"><div align="center">
    <input id="schedt" name="schedt" type="text" size="12" maxlength="15" autocomplete="off" value="" />
    <input id="schedt_alt" name="schedt_alt" type="text" size="8" maxlength="10" autocomplete="off" value=""  />
    
    </div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anesttemp" name="anesttemp" type="text" size="2" maxlength="4" autocomplete="off" value="" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestpulse" name="anestpulse" type="text" size="2" maxlength="4" autocomplete="off" value="" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestresp" name="anestresp" type="text" size="2" maxlength="4" autocomplete="off" value="" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestbps" name="anestbps" type="text" size="2" maxlength="3" autocomplete="off" value="" />
    Over
    <input id="anestbpd" name="anestbpd" type="text" size="2" maxlength="3" autocomplete="off" value="" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="oxysaturation" name="oxysaturation" type="text" size="2" maxlength="3" autocomplete="off" value="" /></div></td>
    <td>&nbsp;</td>
  </tr>
 						<input name="entrynum" type="hidden"	value="<?php echo $newentrynum ?>"/>-->
    <input name="anestid" type="hidden" value="<?php echo $_GET['anestid']; ?>"/>
            <input name="comment" type="hidden" value="" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
            <input name="MM_insert" type="hidden" value="formvital" />
  
</form>
</table>
<?php }?>
<p>&nbsp;</p>
<!--********************END OF ADD*************************************************************************************************-->
<!--********************Begin EDIT *************************************************************************************************-->

<?php if($_GET['act'] == 'edit'){?>
<div align="center" class="BlueBold_20">Edit Vitals</div>

<table width="700" border="1" align="center" style="border-collapse: collapse;" bgcolor="#FFFFCC">
<form name="formvitalupdate" method="post" action="PatAnestIntraopVitals.php?anestid=<?php echo $colname_vitals ?>&act=add">  
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
      </td>
    <td>&nbsp;</td>
    <td colspan="3" align="center">IntraOP Vitals</td>
    <td>&nbsp;<?php //echo strtotime("now") ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Time</td>
    <td align="center">Temp <br>
    35-41</td>
    <td align="center">Pulse<br>
    20-200</td>
    <td align="center">Resp<br>
      10-120      <br></td>
    <td align="center">Blood Pressure<br>
    50-300 / 20-150</td>
    <td align="center">Oxygen<br>
      Saturation</td>
    <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Update"  /></td>
  </tr>
  <tr>
<?php $user="";
$temp = 0;
$time = 0;
$pulse = 0;
$resp = 0;
$bps = 0;
$bpd = 0;
$oxysat = 0;
$dated = '';
$timed = '';
$entrynum = 0;

mysql_select_db($database_swmisconn, $swmisconn);
$query_vitals4entry = sprintf("SELECT schedt, vital, value, value2, comment, entryby, entrydt, entrynum FROM ipvitals WHERE entrynum = '".$_GET['entrynum']."' ORDER BY entrydt ASC", GetSQLValueString($colname_vitals, "int"));
$vitals4entry = mysql_query($query_vitals4entry, $swmisconn) or die(mysql_error());
$row_vitals4entry = mysql_fetch_assoc($vitals4entry);
$totalRows_vitals4entry = mysql_num_rows($vitals4entry);

do {
	$user=$row_vitals4entry['entryby'];
	$dated=date('D M d, yy',$row_vitals4entry['schedt']);
	$timed=date('h:i a',$row_vitals4entry['schedt']);
	if($row_vitals4entry['vital'] == 'temp') {$temp = number_format($row_vitals4entry['value']/10,1); }
	if($row_vitals4entry['vital'] == 'pulse') {$pulse = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'resp') {$resp = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'bpsd') {$bps = $row_vitals4entry['value'] and $bpd = $row_vitals4entry['value2']; }
	if($row_vitals4entry['vital'] == 'oxysat') {$oxysat = $row_vitals4entry['value']; }
	$entrynum = $row_vitals4entry['entrynum'];
	} while ($row_vitals4entry = mysql_fetch_assoc($vitals4entry));

?>
    <td nowrap="nowrap"><div align="center">
    <input id="schedt" name="schedt" type="text" size="12" maxlength="15" value="<?php echo $dated ?>" />
    <input id="schedt_alt" name="schedt_alt" type="text" size="8" maxlength="10" value="<?php echo $timed ?>"  />
    
    </div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anesttemp" name="anesttemp" type="text" size="2" maxlength="4"value="<?php echo $temp ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestpulse" name="anestpulse" type="text" size="2" maxlength="4"value="<?php echo $pulse ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestresp" name="anestresp" type="text" size="2" maxlength="4"value="<?php echo $resp ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestbps" name="anestbps" type="text" size="2" maxlength="3"value="<?php echo $bps ?>" />
    Over
    <input id="anestbpd" name="anestbpd" type="text" size="2" maxlength="3"value="<?php echo $bpd ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="oxysaturation" name="oxysaturation" type="text" size="2" maxlength="3"value="<?php echo $oxysat ?>" /></div></td>
    <td>&nbsp;</td>
  </tr>
 						<!--<input name="schedt" type="hidden"	value="<?php //echo strtotime("now") ?>"/>-->
            <input name="anestid" type="hidden" value="<?php echo $_GET['anestid']; ?>"/>
            <input name="entrynum" type="hidden" value="<?php echo $_GET['entrynum']; ?>"/>
            <input name="comment" type="hidden" value="" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
            <input name="MM_Update" type="hidden" value="formvitalupdate" />
  
</form>
</table>
<?php }?>
<p>&nbsp;</p>
<!--********************END OF EDIT*************************************************************************************************-->
<!--********************Begin Delete *************************************************************************************************-->
<?php if($_GET['act'] == 'delete'){?>
<div align="center" class="BlueBold_20">Delete Vitals</div>

<table width="700" border="1" align="center" style="border-collapse: collapse;" bgcolor="#FFCCFF">
<form name="formvitaldelete" method="post" action="PatAnestIntraopVitals.php?anestid=<?php echo $colname_vitals ?>&act=add">  
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
      </td>
    <td>&nbsp;</td>
    <td colspan="3" align="center">IntraOP Vitals</td>
    <td>&nbsp;<?php //echo strtotime("now") ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Time</td>
    <td align="center">Temp <br>
    35-41</td>
    <td align="center">Pulse<br>
    20-200</td>
    <td align="center">Resp<br>
      10-120      <br></td>
    <td align="center">Blood Pressure<br>
    50-300 / 20-150</td>
    <td align="center">Oxygen<br>
      Saturation</td>
    <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" tabindex="0" /></td>
  </tr>
  <tr>
<?php $user="";
$temp = 0;
$time = 0;
$pulse = 0;
$resp = 0;
$bps = 0;
$bpd = 0;
$oxysat = 0;

mysql_select_db($database_swmisconn, $swmisconn);
$query_vitals4entry = sprintf("SELECT schedt, vital, value value, value2, comment, entryby, entrydt, entrynum FROM ipvitals WHERE entrynum = '".$_GET['entrynum']."' ORDER BY entrydt ASC", GetSQLValueString($colname_vitals, "int"));
$vitals4entry = mysql_query($query_vitals4entry, $swmisconn) or die(mysql_error());
$row_vitals4entry = mysql_fetch_assoc($vitals4entry);
$totalRows_vitals4entry = mysql_num_rows($vitals4entry);

do {
	$user=$row_vitals4entry['entryby'];
	$dated=date('D M d, yy',$row_vitals4entry['schedt']);
	$timed=date('h:i a',$row_vitals4entry['schedt']);
	if($row_vitals4entry['vital'] == 'temp') {$temp = number_format($row_vitals4entry['value']/10,1); }
	if($row_vitals4entry['vital'] == 'pulse') {$pulse = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'resp') {$resp = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'bpsd') {$bps = $row_vitals4entry['value'] and $bpd = $row_vitals4entry['value2']; }
	if($row_vitals4entry['vital'] == 'oxysat') {$oxysat = $row_vitals4entry['value']; }
	$entrynum = $row_vitals4entry['entrynum'];
	} while ($row_vitals4entry = mysql_fetch_assoc($vitals4entry));

?>
    <td nowrap="nowrap"><div align="center">
    <input id="schedt" name="schedt" type="text" size="12" maxlength="15" value="<?php echo $dated ?>" />
    <input id="schedt_alt" name="schedt_alt" type="text" size="8" maxlength="10" value="<?php echo $timed ?>"  />
    
    </div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anesttemp" name="anesttemp" type="text" size="2" maxlength="4"value="<?php echo $temp ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestpulse" name="anestpulse" type="text" size="2" maxlength="4"value="<?php echo $pulse ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestresp" name="anestresp" type="text" size="2" maxlength="4"value="<?php echo $resp ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="anestbps" name="anestbps" type="text" size="2" maxlength="3"value="<?php echo $bps ?>" />
    Over
    <input id="anestbpd" name="anestbpd" type="text" size="2" maxlength="3"value="<?php echo $bpd ?>" /></div></td>
    <td nowrap="nowrap"><div align="center">
    <input id="oxysaturation" name="oxysaturation" type="text" size="2" maxlength="3"value="<?php echo $oxysat ?>" /></div></td>
    <td>&nbsp;</td>
  </tr>
 						<!--<input name="schedt" type="hidden"	value="<?php //echo strtotime("now") ?>"/>-->
            <input name="anestid" type="hidden" value="<?php echo $_GET['anestid']; ?>"/>
            <input name="entrynum" type="hidden" value="<?php echo $_GET['entrynum']; ?>"/>
            <input name="comment" type="hidden" value="" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo $row_vitals4entry['entrydt']; ?>" />
            <input name="MM_Delete" type="hidden" value="formvitaldelete" />
  
</form>
</table>
<?php }?>
<p>&nbsp;</p>
<!--********************END OF DELETE************************************************************************-->
<!--********************Begin VIEW ***************************************************************************-->

<div align="center" class="BlueBold_20">Previous Vitals</div>

<table align="center" border="1" style="border-collapse: collapse;">
	<tr>
		<td colspan="2"><a href="PatAnestIntraopVitals.php?anestid=<?php echo $colname_vitals ?>&act=add">Add</a></td>
		<td align="center"><strong>User</strong></td>
  	<td align="center"><strong>Time</strong></td>
  	<td align="center"><strong>Temp</strong></td>
  	<td align="center"><strong>Pulse</strong></td>
  	<td align="center"><strong>Resp</strong></td>
  	<td align="center"><strong>Bld Press</strong></td>
  	<td align="center"><strong>OxySat</strong></td>
  	<td align="center"><strong>EntryDt</strong></td>
  	<td align="center"><strong>Num</strong></td>
  </tr>
  	
<?php

// get list of vitals entry dates for this anestid
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_entrydt = sprintf("SELECT distinct entrynum FROM ipvitals WHERE anestid = %s ORDER BY schedt ASC", GetSQLValueString($colname_vitals, "int"));
		$entrydt = mysql_query($query_entrydt, $swmisconn) or die(mysql_error());
		$row_entrydt = mysql_fetch_assoc($entrydt);
		$totalRows_entrydt = mysql_num_rows($entrydt);
$user="";
$temp = 0;
$time = 0;
$pulse = 0;
$resp = 0;
$bps = 0;
$bpd = 0;
$oxysat = 0;
$entrydate='';
$entrynum = 0;
do {
mysql_select_db($database_swmisconn, $swmisconn);
$query_vitals4entry = sprintf("SELECT schedt, vital, value, value2, comment, entryby, entrydt, entrynum FROM ipvitals WHERE entrynum = '".$row_entrydt['entrynum']."' and anestid = '".$colname_vitals."' ORDER BY schedt ASC");
$vitals4entry = mysql_query($query_vitals4entry, $swmisconn) or die(mysql_error());
$row_vitals4entry = mysql_fetch_assoc($vitals4entry);
$totalRows_vitals4entry = mysql_num_rows($vitals4entry);

do {
	$user=$row_vitals4entry['entryby'];
	$time=date('Y-m-d h:i a',$row_vitals4entry['schedt']);
	if($row_vitals4entry['vital'] == 'temp') {$temp = number_format($row_vitals4entry['value']/10,1); }
	if($row_vitals4entry['vital'] == 'pulse') {$pulse = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'resp') {$resp = $row_vitals4entry['value']; }
	if($row_vitals4entry['vital'] == 'bpsd') {$bps = $row_vitals4entry['value'] and $bpd = $row_vitals4entry['value2']; }
	if($row_vitals4entry['vital'] == 'oxysat') {$oxysat = $row_vitals4entry['value']; }
	$entrydate =$row_vitals4entry['entrydt']; 
	$entrynum =$row_vitals4entry['entrynum']; 
  } while ($row_vitals4entry = mysql_fetch_assoc($vitals4entry));
?>
	<tr>
  	<td><a href="PatAnestIntraopVitals.php?anestid=<?php echo $colname_vitals ?>&act=edit&entrynum=<?php echo $entrynum ?>">E</a></td>
  	<td><a href="PatAnestIntraopVitals.php?anestid=<?php echo $colname_vitals ?>&act=delete&entrynum=<?php echo $entrynum ?>">D</a></td>
		<td align="center"><?php echo $user ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $time ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $temp ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $pulse ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $resp ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $bps ?>/<?php echo $bpd ?></td>
		<td align="center" bgcolor="#FFFFFF"><?php echo $oxysat ?></td>
		<td align="center"><?php echo $entrydate ?></td>
		<td align="center"><?php echo $entrynum ?></td>
	</tr>
<?php 	} while ($row_entrydt = mysql_fetch_assoc($entrydt)); ?>
</table>
<!--********************END OF VIEW*************************************************************************************************-->

</body>
</html>
