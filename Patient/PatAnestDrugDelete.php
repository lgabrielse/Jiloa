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

// set variables to store merged date and time in timestamp format
	  //$exschedt = strtotime($_POST['exscheddt'].' '.$_POST['exschedtime']);

    $begindrugdatetime = strtotime($_POST['begindrugdate'].' '.$_POST['begindrugtime']);
    $enddrugdatetime = strtotime($_POST['enddrugdate'].' '.$_POST['enddrugtime']); 
//echo 'begindt '.$begindrugdatetime;
//echo 'begind '.$_POST['begindrugdate'];
//echo 'begint '.$_POST['begindrugtime'];
//echo 'enddt '.$enddrugdatetime;
//echo 'endd '.$_POST['enddrugdate'];
//echo 'endt '.$_POST['enddrugtime'];
//exit;
  $updateSQL = sprintf("DELETE FROM anestdrug WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
 $saved = 'true'; // triggers <body onload="out()"  below to close the edit window and refresh the calling page with the new data
}
?>


<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_druglist = "SELECT id, drug FROM anestdruglist ORDER BY drug ASC";
$druglist = mysql_query($query_druglist, $swmisconn) or die(mysql_error());
$row_druglist = mysql_fetch_assoc($druglist);
$totalRows_druglist = mysql_num_rows($druglist);

// get the id of the entered anestesia drug from the URL
 if(isset($_GET['anestdrugid'])) {
   $col_did = $_GET['anestdrugid'];}

// query to et data about the entered drug t be edited
mysql_select_db($database_swmisconn, $swmisconn);
$query_drug = "SELECT ad.id adid, ad.druglistid, ad.begindrug, ad.enddrug, al.drug FROM anestdrug ad join anestdruglist al on ad.druglistid = al.id WHERE ad.id = ".$_GET['anestdrugid']." ORDER BY ad.druglistid ASC";
$drug = mysql_query($query_drug, $swmisconn) or die(mysql_error());
$row_drug = mysql_fetch_assoc($drug);
$totalRows_drug = mysql_num_rows($drug);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Anesthesi Drug</title>
</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>


<body>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1"  bgcolor="#FBD0D7">
 <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"> <tr>
    <td><input name="id" type="text" value="<?php echo $row_drug['adid']?>"   /></td>
    <td>Delete Drug</td>
      <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
  </tr>
  <tr>
    <td align="right">Select:</td>
    <td>
      <input name="druglistid" id="druglistid" value="<?php echo $row_drug['drug'] ?>">
     </td>
  </tr>
  <tr>
    <td align="right">Begin Time:</td>
    <td><label for="begindrugtime"></label>
      <input name="begindrugtime" type="text" id="begindrugtime" value="<?php echo date('h:i_A', $row_drug['begindrug']); ?>" /></td>
    <td nowrap="nowrap"><?php echo date('Y/m/d', $row_drug['begindrug']).'  '.date('h:i_A', $row_drug['begindrug']) ?></td>
    <td nowrap="nowrap"><?php echo date('Y/m/d', $row_drug['enddrug']).'  '.date('h:i_A', $row_drug['enddrug']) ?></td>

  </tr>
  <tr>
    <td align="right">End Time:</td>
    <td><label for="enddrugtime"></label>
      <input name="enddrugtime" type="text" id="enddrugtime" value="<?php echo date('h:i_A', $row_drug['enddrug']); ?>" /></td>
    <td>
    <td>      <input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Drug" /></td>
  </tr>
      <input type="hidden" name="begindrugdate" id="begindrugdate" value="<?php echo date('d-m-Y', $row_drug['begindrug']) ?>"/>
      <input type="hidden" name="enddrugdate" id="endrugdate" value="<?php echo date('d-m-Y', $row_drug['enddrug']) ?>"/>
   	 <input type="hidden" name="entryby" id="entryby"value = "<?php echo $_SESSION['user']; ?>"/>
      <input type="hidden" name="entrydt" id="entrydt" Value = "<?php echo date("Y-m-d"); ?>" />
      <input type="hidden" name="anestid" id="anestid" value="<?php echo $col_did ?>" />
  <input type="hidden" name="MM_update" value="form1" />
 </form>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>
  <!--Javascript for calendar date & time-->
  <script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
  <script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
  <script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
  <script type="text/javascript">
ng.ready( function() {

    var my_timepicker = new ng.TimePicker({
        input:'begindrugtime',   //beginsurgery

    });

    var my_timepicker = new ng.TimePicker({
        input:'enddrugtime',   //beginsurgery
    });   
});

  </script>
</p>
<script language="JavaScript" type="text/JavaScript">
<!--

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

//-->
  </script>
</body>
</html>
<?php
mysql_free_result($druglist);
?>
