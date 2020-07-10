<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
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
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['SubmitAll2']) AND isset($_POST['feeid']) AND $_POST['SubmitAll2']  == 'Add Order')  {

  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, evperiod, fornum, forperiod, quant, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString('RxOrdered', "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString('Ordered', "text"),
                       GetSQLValueString($_POST['item'], "text"),
                       GetSQLValueString($_POST['nunits'], "int"),
                       GetSQLValueString($_POST['unit'], "text"),
                       GetSQLValueString($_POST['evperiod'], "text"),
                       GetSQLValueString($_POST['fornum'], "int"),
                       GetSQLValueString($_POST['forperiod'], "text"),
                       GetSQLValueString($_POST['quant'], "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
//$message = "wrong answer";
//echo " 
/*<script type='text/javascript'>alert('$message');</script>*/
//";  
if(!empty($_POST['scheddt']) AND !empty($_POST['schedtime'])) {  
	$schedt = strtotime($_POST['scheddt'].' '.$_POST['schedtime']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
    if(isset($_POST['evperiod'])) {  // calculate interval in seconds
	   if($_POST['evperiod'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod'])) {
	   if($_POST['forperiod'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum'] * 60 * 60 * 24); }
	   if($_POST['forperiod'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt

  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit'], "text"),
                       GetSQLValueString($_POST['nunits'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
//                       GetSQLValueString(date('Y-m-d h:i', $dt), "date")); //used to view $dt values
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
     }
   }  
 } 
 
 
 $_SESSION['CurrDateTime'] =  date("Y-m-d H:i:s");

mysql_select_db($database_swmisconn, $swmisconn);
$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
$totalRows_doctor = mysql_num_rows($doctor);

mysql_select_db($database_swmisconn, $swmisconn);
$query_other = "Select id, dept, section, name, descr from fee where dept in ('pharm', 'admin') Order By id";
$other = mysql_query($query_other, $swmisconn) or die(mysql_error());
$row_other = mysql_fetch_assoc($other);
$totalRows_other = mysql_num_rows($other);
?>

<?php  //define 15 minute intervals from current time for dropdown selection
  $colname_schedate = date("Y-m-d H:i"); // current time
  $mkmin = date('i',strtotime($colname_schedate)) ;  // current minute
  if($mkmin >=0 && $mkmin <=15 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(15*60));  //show 15 minutes past hour 
  }
  elseif($mkmin >15 && $mkmin <=30 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(30*60));
  }
  elseif($mkmin >30 && $mkmin <=45 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(45*60));
  }
  elseif($mkmin >45 && $mkmin <=59 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(60*60));
  } 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
//<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>

<?php if($saved == "true") {?>
	<body onload="out()">
<?php  }?>

<body>

<table align="center">
  <tr>
   <td>
<form id="formrx2" name="formrx2" method="post" action="">  <!--//PatPharmOrderAdd.php?patype=<?php  //echo $_GET['patype']; ?>-->
  <table border="1" align="center" bgcolor="#BCFACC">
    <tr>
		<td><div align="center"><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></div></td>      <td colspan="3" nowrap="nowrap"><div align="center" class="BlueBold_20">ORDER DRUG <span class="BlueBold_14">Tablet/Capsule</span> </div></td>
    </tr>
    <tr>
      <td colspan="2" nowrap="nowrap">Drug</td>
      <td colspan="2" nowrap="nowrap">Urg:
        <select name="urgency" id="urgency">
            <option value="Routine">Routine</option>
            <option value="Scheduled">Scheduled</option>
            <option value="ASAP">ASAP</option>
            <option value="STAT">STAT</option>
          </select>
        Doctor:
        <select name="doctor">
          <option value="NA">NA</option>
          <?php
	do {  
	?>
          <option value="<?php echo $row_doctor['userid']?>"><?php echo $row_doctor['userid']?></option>
          <?php
	} while ($row_doctor = mysql_fetch_assoc($doctor));
	  $rows = mysql_num_rows($doctor);
	  if($rows > 0) {
		  mysql_data_seek($doctor, 0);
		  $row_doctor = mysql_fetch_assoc($doctor);
	  }
	?>
        </select>
        <!--Naira:            
            <input name="ofee" type="text" value="0" size="8" />-->      </td>
    </tr>
    <tr>
      <td colspan="4"><div align="center" class="BlueBold_1414">Prescription</div></td>
    </tr>
    <tr>
      <td colspan="2"><input name="item" type="text" id="item" size="30" maxlength="100" /></td>
      <td colspan="2" nowrap="nowrap"><input name="nunits" type="text" id="nunits" size="5" maxlength="5" />
          <select name="unit" id="unit"> 
            <option value="Tablet(s)">Tablet(s)</option>
            <option value="Capsules(s)">Capsule(s)</option>
          </select>
<!--        Every
        <input name="every" type="text" id="every" size="3" />
-->        <select name="evperiod" id="evperiod">
  <option value="OD">OD</option>
  <option value="NOCTE">Nocte</option>
  <option value="BD">BD</option>
  <option value="TDS">TDS</option>
  <option value="QDS">QDS</option>
  <option value="PRN">PRN</option>
  <option value="STAT">STAT</option>
</select>
        for
        <input name="fornum" type="text" id="fornum" size="3" />
        <select name="forperiod" id="forperiod">
          <option value="day(s)" selected="selected">day(s)</option>
          <option value="week(s)">week(s)</option>
        </select>      </td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Order Comments:</td>
      <td colspan="3" nowrap="nowrap"><textarea name="comments" cols="60" rows="2" id="comments"></textarea></td>
    </tr>
    <tr>
<?php if($_GET['patype'] == 'InPatient'){?>
      <td nowrap="nowrap">Selecting date will create schedule.</td>
      <td  align="right" nowrap="nowrap"><input id="scheddt" name="scheddt" type="text" size="12" maxlength="15"/></td>
      <td  align="left" nowrap="nowrap"><input id="schedtime" name="schedtime" type="text" size="8" maxlength="10">
        Inpatient only!</td>
<?php } else {?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
<?php }?>
      <td  align="right" nowrap="nowrap"><input name="SubmitAll2" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Drug" /></td>
   </tr>
    <input name="quant" type="hidden" id="quant" value=""/>
    <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
    <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
    <input name="feeid" type="hidden" id="feeid" value="30" />
    <input name="ratereason" type="hidden" id="ratereason" value="103" />
    <input name="rate" type="hidden" id="rate" value="100" />
    <input type="hidden" name="status" value="Ordered"/>
    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
    <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
    <input type="hidden" name="MM_insert" value="formrx2" />
  </table>
</form>    </td>
  </tr>
</table>
  <script  type="text/javascript">
   var frmvalidator = new Validator("formrx2");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>

<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">
ng.ready( function() {
    var my_cal = new ng.Calendar({
        input:'scheddt'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime',
    });   
});

</script>

	</body>
</html>
