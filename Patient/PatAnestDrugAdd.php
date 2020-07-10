<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
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
$saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && isset($_POST['begindrug'])) {

		// if posted, set posted variables to timestamp 
	if(!empty($_POST['begindrug']) && !empty($_POST['begindrug_alt'])){
	  $begindrug = strtotime($_POST['begindrug'].' '.$_POST['begindrug_alt']);
   } else {   //if $_POST['begindrug'] is not set, set begin date/time to null
		$begindrug = 0; 
	 }

	if(isset($_POST["enddrugX"]) && $_POST['enddrugX'] == 'Y')	{
		  $autoend = 'Y';
			$enddrug = $_POST['ba'] + 3600*8;
			$insertSQL = sprintf("INSERT INTO anestdrug (anestid, druglistid, autoend, begindrug, enddrug) VALUES (%s, %s, %s, %s, %s)",
												 GetSQLValueString($_POST['anestid'], "int"),
												 GetSQLValueString($_POST['druglistid'], "text"),
												 GetSQLValueString($autoend, "text"),
												 GetSQLValueString($begindrug, "int"),
												 GetSQLValueString($enddrug, "int"));
		 mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
		$saved = "true";
	
	} else {
		  $autoend = 'N';
			$insertSQL = sprintf("INSERT INTO anestdrug (anestid, druglistid, autoend, begindrug) VALUES (%s, %s, %s, %s)",
												 GetSQLValueString($_POST['anestid'], "int"),
												 GetSQLValueString($_POST['druglistid'], "text"),
												 GetSQLValueString($autoend, "text"),
												 GetSQLValueString($begindrug, "int"));
		 mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	
		$saved = "true";
	}
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_druglist = "SELECT id, drug FROM anestdruglist WHERE active ='Y' ORDER BY drug ASC";
$druglist = mysql_query($query_druglist, $swmisconn) or die(mysql_error());
$row_druglist = mysql_fetch_assoc($druglist);
$totalRows_druglist = mysql_num_rows($druglist);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Anesthesi Drug</title>
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

</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>

<?php if(isset($_GET['anestid'])) {
   $col_aid = $_GET['anestid'];}
?>

<body>
<p>&nbsp;</p>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
 <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"> <tr>
    <td>&nbsp;</td>
    <td>Add Drug</td>
      <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div>
  </tr>
  <tr>
    <td align="right">Select:</td>
    <td>
      <select name="druglistid" id="druglistid">
        <?php
do {  
?>
        <option value="<?php echo $row_druglist['id']?>"><?php echo $row_druglist['drug']?></option>
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
<?php if(isset($_GET['ba']) && $_GET['ba'] > 100000) {?>
    <td>Auto End <input name="enddrugX" id="enddrugX" type="checkbox" value="Y"></td>
<?php } else {?>
		<td>Auto End**
<?php }?>
  </tr>
  <tr>
    <td align="right">Begin Time:</td>
    <td nowrap><label for="begindrug"></label>  <!--set value to null here so datepicker does not save current date as default-->
       <input type="text" id="begindrug" name="begindrug" size="12" maxlength="15"  />
       <input type="text" id="begindrug_alt" name="begindrug_alt" size="8" maxlength="10" />
</td>
<!--    <td>&nbsp;</td>

  </tr>
  <tr>
    <td align="right">End Time:</td>
      <input type="text" name="enddrug" id="enddrug" /></td>
		<td nowrap>
       <input type="text" id="enddrug" name="enddrug" size="12" maxlength="15" value="" />
       <input type="text" id="enddrug_alt" name="enddrug_alt" size="8" maxlength="10" value="" />
--> <td>
     	<input type="hidden" name="ba" id="ba"value="<?php echo $_GET['ba']; ?>"/>
     	<input type="hidden" name="entryby" id="entryby"value = "<?php echo $_SESSION['user']; ?>"/>
      <input type="hidden" name="entrydt" id="entrydt" Value = "<?php echo date("Y-m-d"); ?>" />
      <input type="hidden" name="anestid" id="anestid" value="<?php echo $col_aid ?>" />
      <input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Drug" /></td>

  </tr>
  <input type="hidden" name="MM_insert" value="form1" />
 </form>
</table>
<div align="center"> ** Saved Anesthesia begining date & time required to use Auto End.<br> Auto end time will be 8 hours after Anesthesia begining date & time. </div>


<p>&nbsp;</p>
<p>&nbsp; </p>
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
</body>
</html>