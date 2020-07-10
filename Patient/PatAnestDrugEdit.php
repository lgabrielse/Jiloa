<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
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

 $saved = '';

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}  

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

//echo 'begindrugX   '.$_POST['begindrugX'];  
//exit;
// set variables to store merged date and time in timestamp format
	  //$exschedt = strtotime($_POST['exscheddt'].' '.$_POST['exschedtime']);
		if((isset($_POST['begindrugX']) && $_POST['begindrugX'] == 'Y') || empty($_POST['begindrug']) || empty($_POST['begindrug_alt']) ){
		    $begindrugdatetime = null;					
		} else {
		    $begindrugdatetime = strtotime($_POST['begindrug'].' '.$_POST['begindrug_alt'] );
		}
		if((isset($_POST['enddrugX']) && $_POST['enddrugX'] == 'Y') || empty($_POST['enddrug']) || empty($_POST['enddrug_alt']) || empty($_POST['begindrug']) ){ //$_POST['enddrugX'] = 'Y' || 
		    $enddrugdatetime = null;					
		} else {
   		  $enddrugdatetime = strtotime($_POST['enddrug'].' '.$_POST['enddrug_alt']); 
		}
////echo  'begX '.$_POST['begindrugX'].'<br>';		
////echo  'endX '.$_POST['enddrugX'].'<br>';		
//echo 'begindt '.$begindrugdatetime.'<br>';
//echo 'begind '.$_POST['begindrug'].'<br>';
//echo 'begint '.$_POST['begindrug_alt'].'<br>';
//echo 'enddt '.$enddrugdatetime.'<br>';
//echo 'endd '.$_POST['enddrug'].'<br>';
//echo 'endt '.$_POST['enddrug_alt'].'<br>';
//exit;
  $updateSQL = sprintf("UPDATE anestdrug SET druglistid=%s, begindrug=%s, enddrug=%s WHERE id=%s",
                       GetSQLValueString($_POST['druglistid'], "int"),
                       GetSQLValueString($begindrugdatetime, "int"),
                       GetSQLValueString($enddrugdatetime, "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
 $saved = 'true'; // triggers <body onload="out()"  below to close the edit window and refresh the calling page with the new data
}
?>


<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_druglist = "SELECT id, drug FROM anestdruglist WHERE active= 'Y' ORDER BY drug ASC";
$druglist = mysql_query($query_druglist, $swmisconn) or die(mysql_error());
$row_druglist = mysql_fetch_assoc($druglist);
$totalRows_druglist = mysql_num_rows($druglist);

// bet the id of the entered anestesia drug from the URL
 if(isset($_GET['anestdrugid'])) {
   $col_did = $_GET['anestdrugid'];}

// query to et data about the entered drug t be edited
mysql_select_db($database_swmisconn, $swmisconn);
$query_drug = "SELECT ad.id adid, ad.druglistid, ad.begindrug, ad.enddrug, al.drug FROM anestdrug ad join anestdruglist al on ad.druglistid = al.id WHERE ad.id = ".$_GET['anestdrugid']." ORDER BY ad.druglistid ASC";
$drug = mysql_query($query_drug, $swmisconn) or die(mysql_error());
$row_drug = mysql_fetch_assoc($drug);
$totalRows_drug = mysql_num_rows($drug);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Anesthesi Drug</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
		function openBrWindow(theURL,winName,features) { //v2.0
			window.open(theURL,winName,features);
		}
		function out(){
			opener.location.reload(1); //This updates the data on the calling page
				self.close();
		}
		function MM_closeBrWindow() { // this works too
			window.close(); 
		}
</script>
<!-- for date & time picker-->
<link rel="stylesheet" href="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="../../js/jquery-ui-timepicker-addon.css">
<script src="../../js/jquery.js"></script>
<script src="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../../js/jquery-ui-timepicker-addon.js"></script>

<!-- begindate-->
<script>
$( function() {
$('#begindrug').datetimepicker({
	altField: "#begindrug_alt",
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
<!-- enddate-->
<script>
$( function() {
$('#enddrug').datetimepicker({
	altField: "#enddrug_alt",
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

<?php if($saved == "true") {?>
<body onload="out()">
<?php } else {?>
<body>
<?php }?>

<p>&nbsp;</p>
<table width="20%" border="1" align="center" cellpadding="1" cellspacing="1">
 <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"> 
   <tr>
    <td><input name="id" type="text" value="<?php echo $row_drug['adid']?>"   /></td>
    <td>Edit Drug</td>
      <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
  </tr>
  <tr>
    <td align="right">Select:</td>
    <td>
      <select name="druglistid" id="druglistid">
        <?php
do {  
?>
        <option value="<?php echo $row_druglist['id']?>"<?php if (!(strcmp($row_druglist['id'], $row_drug['druglistid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_druglist['drug']?></option>
        <?php
} while ($row_druglist = mysql_fetch_assoc($druglist));
  $rows = mysql_num_rows($druglist);
  if($rows > 0) {
      mysql_data_seek($druglist, 0);
	  $row_druglist = mysql_fetch_assoc($druglist);
  }
?>
      </select>
    </td>
    <td></td>
  </tr>
  <tr>
    <td align="right">Begin Time:</td>
    <td nowrap>
      <label for="begindrugtime"></label>
    <?php   if(!empty($row_drug['begindrug'])){?>
      <input type="text" id="begindrug" name="begindrug" size="12" maxlength="15" value="<?php echo date('D M d, Y', $row_drug['begindrug'])?>" />
      <input type="text" id="begindrug_alt" name="begindrug_alt" size="8" maxlength="10" value="<?php echo date('h:i a', $row_drug['begindrug'])?>" />
			<!--<input name="begindrugX" id="begindrugX" type="checkbox" value="Y">-->
		<?php } else {?>
      <input type="text" id="begindrug" name="begindrug" size="12" maxlength="15" value="" />
      <input type="text" id="begindrug_alt" name="begindrug_alt" size="8" maxlength="10" value="" />  
			<!--<input name="begindrugX" id="begindrugX" type="checkbox" value="Y">-->
    <?php }?>
    </td>
    <td></td>
  </tr>
  <tr>
    <td align="right">End Time:</td>
    <td nowrap>
    <?php   if(!empty($row_drug['enddrug'])){?>
      <input type="text" id="enddrug" name="enddrug" size="12" maxlength="15" value="<?php echo date('D M d, Y', $row_drug['enddrug'])?>" />
      <input type="text" id="enddrug_alt" name="enddrug_alt" size="8" maxlength="10" value="<?php echo date('h:i a', $row_drug['enddrug'])?>" />
			<input name="enddrugX" id="enddrugX" type="checkbox" value="Y">
		<?php } else {?>
      <input type="text" id="enddrug" name="enddrug" size="12" maxlength="15" value="" />
      <input type="text" id="enddrug_alt" name="enddrug_alt" size="8" maxlength="10" value="" />  
			<input name="enddrugX" id="enddrugX" type="checkbox" value="Y">
    <?php }?>
    </td>

    <td>      <input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Drug" /></td>
  </tr>
  <tr>
    <td colspan="3" align="right">Check box to remove date/time entry.</td>
	</tr>   

<!--      <input type="hidden" name="begindrugdate" id="begindrugdate" value="<?php //echo date('d-m-Y', $row_drug['begindrug'])?>"/>
      <input type="hidden" name="enddrugdate" id="endrugdate" value="<?php //echo date('d-m-Y', $row_drug['enddrug'])?>"/>
-->   	 <input type="hidden" name="entryby" id="entryby"value = "<?php echo $_SESSION['user']; ?>"/>
      <input type="hidden" name="entrydt" id="entrydt" Value = "<?php echo date("Y-m-d"); ?>" />
      <input type="hidden" name="anestid" id="anestid" value="<?php echo $col_did ?>" />
    <input type="hidden" name="MM_update" value="form1" />
 </form>
</table>
</body>
</html>
<?php
mysql_free_result($druglist);
?>
