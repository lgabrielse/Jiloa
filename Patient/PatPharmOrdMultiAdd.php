<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/functions.php'); ?>

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
  
   $billstatus = 'Due';  
if(isset($_POST['paylater'])&& isset($_POST['urgency']) && ($_POST['paylater'] == 'paylater' || $_POST['urgency'] == 'STAT')){
	$billstatus = 'paylater';
	} 

   $status = 'RxOrdered';  
//if(isset($_POST['paylater']) && $_POST['paylater'] == 'paylater'){
//	$status = 'RxPaylater';
//	} 

if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_1']) AND !empty($_POST['item_1'])) { 
echo "testing testing";
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_1'], "text"),
                       GetSQLValueString($_POST['nunits_1'], "int"),
                       GetSQLValueString($_POST['unit_1'], "text"),
                       GetSQLValueString($_POST['every_1'], "int"),
                       GetSQLValueString($_POST['evperiod_1'], "text"),
                       GetSQLValueString($_POST['fornum_1'], "int"),
                       GetSQLValueString($_POST['forperiod_1'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_1'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_1']) AND !empty($_POST['schedtime_1'])) {  
	$schedt = strtotime($_POST['scheddt_1'].' '.$_POST['schedtime_1']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_1'])) {  // calculate interval in seconds
	   if($_POST['evperiod_1'] == 'minutes') {
	   		$interval = $_POST['every_1'] * 60; }
	   if($_POST['evperiod_1'] == 'hour(s)') {
	   		$interval = $_POST['every_1'] * 60 * 60; }
	   if($_POST['evperiod_1'] == 'day(s)') {
	   		$interval = $_POST['every_1'] * 60 * 60 * 24; }
	   if($_POST['evperiod_1'] == 'week(s)') {
	   		$interval = $_POST['every_1'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_1'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_1'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_1'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_1'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_1'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_1'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_1'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_1'])) {
	   if($_POST['forperiod_1'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_1'] * 60); }
	   if($_POST['forperiod_1'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_1'] * 60 * 60); }
	   if($_POST['forperiod_1'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_1'] * 60 * 60 * 24); }
	   if($_POST['forperiod_1'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_1'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_1'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_1'], "text"),
                       GetSQLValueString($_POST['nunits_1'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_1
//} 
///  2  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_2']) AND !empty($_POST['item_2'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_2'], "text"),
                       GetSQLValueString($_POST['nunits_2'], "int"),
                       GetSQLValueString($_POST['unit_2'], "text"),
                       GetSQLValueString($_POST['every_2'], "int"),
                       GetSQLValueString($_POST['evperiod_2'], "text"),
                       GetSQLValueString($_POST['fornum_2'], "int"),
                       GetSQLValueString($_POST['forperiod_2'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_2'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_2']) AND !empty($_POST['schedtime_2'])) {  
	$schedt = strtotime($_POST['scheddt_2'].' '.$_POST['schedtime_2']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_2'])) {  // calculate interval in seconds
	   if($_POST['evperiod_2'] == 'minutes') {
	   		$interval = $_POST['every_2'] * 60; }
	   if($_POST['evperiod_2'] == 'hour(s)') {
	   		$interval = $_POST['every_2'] * 60 * 60; }
	   if($_POST['evperiod_2'] == 'day(s)') {
	   		$interval = $_POST['every_2'] * 60 * 60 * 24; }
	   if($_POST['evperiod_2'] == 'week(s)') {
	   		$interval = $_POST['every_2'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_2'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_2'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_2'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_2'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_2'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_2'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_2'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_2'])) {
	   if($_POST['forperiod_2'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_2'] * 60); }
	   if($_POST['forperiod_2'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_2'] * 60 * 60); }
	   if($_POST['forperiod_2'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_2'] * 60 * 60 * 24); }
	   if($_POST['forperiod_2'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_2'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_2'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_2'], "text"),
                       GetSQLValueString($_POST['nunits_2'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_2
//}
///  3  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_3']) AND !empty($_POST['item_3'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_3'], "text"),
                       GetSQLValueString($_POST['nunits_3'], "int"),
                       GetSQLValueString($_POST['unit_3'], "text"),
                       GetSQLValueString($_POST['every_3'], "int"),
                       GetSQLValueString($_POST['evperiod_3'], "text"),
                       GetSQLValueString($_POST['fornum_3'], "int"),
                       GetSQLValueString($_POST['forperiod_3'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_3'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_3']) AND !empty($_POST['schedtime_3'])) {  
	$schedt = strtotime($_POST['scheddt_3'].' '.$_POST['schedtime_3']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_3'])) {  // calculate interval in seconds
	   if($_POST['evperiod_3'] == 'minutes') {
	   		$interval = $_POST['every_3'] * 60; }
	   if($_POST['evperiod_3'] == 'hour(s)') {
	   		$interval = $_POST['every_3'] * 60 * 60; }
	   if($_POST['evperiod_3'] == 'day(s)') {
	   		$interval = $_POST['every_3'] * 60 * 60 * 24; }
	   if($_POST['evperiod_3'] == 'week(s)') {
	   		$interval = $_POST['every_3'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_3'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_3'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_3'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_3'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_3'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_3'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_3'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_3'])) {
	   if($_POST['forperiod_3'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_3'] * 60); }
	   if($_POST['forperiod_3'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_3'] * 60 * 60); }
	   if($_POST['forperiod_3'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_3'] * 60 * 60 * 24); }
	   if($_POST['forperiod_3'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_3'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_3'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_3'], "text"),
                       GetSQLValueString($_POST['nunits_3'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_3
//}
///  4  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_4']) AND !empty($_POST['item_4'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_4'], "text"),
                       GetSQLValueString($_POST['nunits_4'], "int"),
                       GetSQLValueString($_POST['unit_4'], "text"),
                       GetSQLValueString($_POST['every_4'], "int"),
                       GetSQLValueString($_POST['evperiod_4'], "text"),
                       GetSQLValueString($_POST['fornum_4'], "int"),
                       GetSQLValueString($_POST['forperiod_4'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_4'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_4']) AND !empty($_POST['schedtime_4'])) {  
	$schedt = strtotime($_POST['scheddt_4'].' '.$_POST['schedtime_4']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_4'])) {  // calculate interval in seconds
	   if($_POST['evperiod_4'] == 'minutes') {
	   		$interval = $_POST['every_4'] * 60; }
	   if($_POST['evperiod_4'] == 'hour(s)') {
	   		$interval = $_POST['every_4'] * 60 * 60; }
	   if($_POST['evperiod_4'] == 'day(s)') {
	   		$interval = $_POST['every_4'] * 60 * 60 * 24; }
	   if($_POST['evperiod_4'] == 'week(s)') {
	   		$interval = $_POST['every_4'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_4'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_4'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_4'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_4'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_4'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_4'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_4'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_4'])) {
	   if($_POST['forperiod_4'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_4'] * 60); }
	   if($_POST['forperiod_4'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_4'] * 60 * 60); }
	   if($_POST['forperiod_4'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_4'] * 60 * 60 * 24); }
	   if($_POST['forperiod_4'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_4'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_4'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_4'], "text"),
                       GetSQLValueString($_POST['nunits_4'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_4
//}
///  5  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_5']) AND !empty($_POST['item_5'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_5'], "text"),
                       GetSQLValueString($_POST['nunits_5'], "int"),
                       GetSQLValueString($_POST['unit_5'], "text"),
                       GetSQLValueString($_POST['every_5'], "int"),
                       GetSQLValueString($_POST['evperiod_5'], "text"),
                       GetSQLValueString($_POST['fornum_5'], "int"),
                       GetSQLValueString($_POST['forperiod_5'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_5'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_5']) AND !empty($_POST['schedtime_5'])) {  
	$schedt = strtotime($_POST['scheddt_5'].' '.$_POST['schedtime_5']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_5'])) {  // calculate interval in seconds
	   if($_POST['evperiod_5'] == 'minutes') {
	   		$interval = $_POST['every_5'] * 60; }
	   if($_POST['evperiod_5'] == 'hour(s)') {
	   		$interval = $_POST['every_5'] * 60 * 60; }
	   if($_POST['evperiod_5'] == 'day(s)') {
	   		$interval = $_POST['every_5'] * 60 * 60 * 24; }
	   if($_POST['evperiod_5'] == 'week(s)') {
	   		$interval = $_POST['every_5'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_5'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_5'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_5'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_5'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_5'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_5'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_5'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_5'])) {
	   if($_POST['forperiod_5'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_5'] * 60); }
	   if($_POST['forperiod_5'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_5'] * 60 * 60); }
	   if($_POST['forperiod_5'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_5'] * 60 * 60 * 24); }
	   if($_POST['forperiod_5'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_5'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_5'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_5'], "text"),
                       GetSQLValueString($_POST['nunits_5'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_5
//}
///  6  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_6']) AND !empty($_POST['item_6'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_6'], "text"),
                       GetSQLValueString($_POST['nunits_6'], "int"),
                       GetSQLValueString($_POST['unit_6'], "text"),
                       GetSQLValueString($_POST['every_6'], "int"),
                       GetSQLValueString($_POST['evperiod_6'], "text"),
                       GetSQLValueString($_POST['fornum_6'], "int"),
                       GetSQLValueString($_POST['forperiod_6'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_6'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_6']) AND !empty($_POST['schedtime_6'])) {  
	$schedt = strtotime($_POST['scheddt_6'].' '.$_POST['schedtime_6']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_6'])) {  // calculate interval in seconds
	   if($_POST['evperiod_6'] == 'minutes') {
	   		$interval = $_POST['every_6'] * 60; }
	   if($_POST['evperiod_6'] == 'hour(s)') {
	   		$interval = $_POST['every_6'] * 60 * 60; }
	   if($_POST['evperiod_6'] == 'day(s)') {
	   		$interval = $_POST['every_6'] * 60 * 60 * 24; }
	   if($_POST['evperiod_6'] == 'week(s)') {
	   		$interval = $_POST['every_6'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_6'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_6'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_6'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_6'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_6'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_6'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_6'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_6'])) {
	   if($_POST['forperiod_6'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_6'] * 60); }
	   if($_POST['forperiod_6'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_6'] * 60 * 60); }
	   if($_POST['forperiod_6'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_6'] * 60 * 60 * 24); }
	   if($_POST['forperiod_6'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_6'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_6'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_6'], "text"),
                       GetSQLValueString($_POST['nunits_6'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_6
//} 
///  7  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_7']) AND !empty($_POST['item_7'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_7'], "text"),
                       GetSQLValueString($_POST['nunits_7'], "int"),
                       GetSQLValueString($_POST['unit_7'], "text"),
                       GetSQLValueString($_POST['every_7'], "int"),
                       GetSQLValueString($_POST['evperiod_7'], "text"),
                       GetSQLValueString($_POST['fornum_7'], "int"),
                       GetSQLValueString($_POST['forperiod_7'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_7'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_7']) AND !empty($_POST['schedtime_7'])) {  
	$schedt = strtotime($_POST['scheddt_7'].' '.$_POST['schedtime_7']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_7'])) {  // calculate interval in seconds
	   if($_POST['evperiod_7'] == 'minutes') {
	   		$interval = $_POST['every_7'] * 60; }
	   if($_POST['evperiod_7'] == 'hour(s)') {
	   		$interval = $_POST['every_7'] * 60 * 60; }
	   if($_POST['evperiod_7'] == 'day(s)') {
	   		$interval = $_POST['every_7'] * 60 * 60 * 24; }
	   if($_POST['evperiod_7'] == 'week(s)') {
	   		$interval = $_POST['every_7'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_7'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_7'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_7'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_7'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_7'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_7'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_7'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_7'])) {
	   if($_POST['forperiod_7'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_7'] * 60); }
	   if($_POST['forperiod_7'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_7'] * 60 * 60); }
	   if($_POST['forperiod_7'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_7'] * 60 * 60 * 24); }
	   if($_POST['forperiod_7'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_7'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_7'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_7'], "text"),
                       GetSQLValueString($_POST['nunits_7'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_7
//} 
///  8  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_8']) AND !empty($_POST['item_8'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_8'], "text"),
                       GetSQLValueString($_POST['nunits_8'], "int"),
                       GetSQLValueString($_POST['unit_8'], "text"),
                       GetSQLValueString($_POST['every_8'], "int"),
                       GetSQLValueString($_POST['evperiod_8'], "text"),
                       GetSQLValueString($_POST['fornum_8'], "int"),
                       GetSQLValueString($_POST['forperiod_8'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_8'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_8']) AND !empty($_POST['schedtime_8'])) {  
	$schedt = strtotime($_POST['scheddt_8'].' '.$_POST['schedtime_8']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_8'])) {  // calculate interval in seconds
	   if($_POST['evperiod_8'] == 'minutes') {
	   		$interval = $_POST['every_8'] * 60; }
	   if($_POST['evperiod_8'] == 'hour(s)') {
	   		$interval = $_POST['every_8'] * 60 * 60; }
	   if($_POST['evperiod_8'] == 'day(s)') {
	   		$interval = $_POST['every_8'] * 60 * 60 * 24; }
	   if($_POST['evperiod_8'] == 'week(s)') {
	   		$interval = $_POST['every_8'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_8'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_8'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_8'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_8'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_8'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_8'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_8'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_8'])) {
	   if($_POST['forperiod_8'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_8'] * 60); }
	   if($_POST['forperiod_8'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_8'] * 60 * 60); }
	   if($_POST['forperiod_8'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_8'] * 60 * 60 * 24); }
	   if($_POST['forperiod_8'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_8'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_8'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_8'], "text"),
                       GetSQLValueString($_POST['nunits_8'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_8
//} 
///  9  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_9']) AND !empty($_POST['item_9'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_9'], "text"),
                       GetSQLValueString($_POST['nunits_9'], "int"),
                       GetSQLValueString($_POST['unit_9'], "text"),
                       GetSQLValueString($_POST['every_9'], "int"),
                       GetSQLValueString($_POST['evperiod_9'], "text"),
                       GetSQLValueString($_POST['fornum_9'], "int"),
                       GetSQLValueString($_POST['forperiod_9'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_9'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_9']) AND !empty($_POST['schedtime_9'])) {  
	$schedt = strtotime($_POST['scheddt_9'].' '.$_POST['schedtime_9']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_9'])) {  // calculate interval in seconds
	   if($_POST['evperiod_9'] == 'minutes') {
	   		$interval = $_POST['every_9'] * 60; }
	   if($_POST['evperiod_9'] == 'hour(s)') {
	   		$interval = $_POST['every_9'] * 60 * 60; }
	   if($_POST['evperiod_9'] == 'day(s)') {
	   		$interval = $_POST['every_9'] * 60 * 60 * 24; }
	   if($_POST['evperiod_9'] == 'week(s)') {
	   		$interval = $_POST['every_9'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_9'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_9'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_9'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_9'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_9'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_9'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_9'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_9'])) {
	   if($_POST['forperiod_9'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_9'] * 60); }
	   if($_POST['forperiod_9'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_9'] * 60 * 60); }
	   if($_POST['forperiod_9'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_9'] * 60 * 60 * 24); }
	   if($_POST['forperiod_9'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_9'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_9'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_9'], "text"),
                       GetSQLValueString($_POST['nunits_9'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_9
//} 
///  0  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Orders')  {
if (isset($_POST['item_0']) AND !empty($_POST['item_0'])) { 
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, status, rate, ratereason, billstatus, item, nunits, unit, every, evperiod, fornum, forperiod, ofee, amtdue, amtpaid, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($status, "text"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($billstatus, "text"),
                       GetSQLValueString($_POST['item_0'], "text"),
                       GetSQLValueString($_POST['nunits_0'], "int"),
                       GetSQLValueString($_POST['unit_0'], "text"),
                       GetSQLValueString($_POST['every_0'], "int"),
                       GetSQLValueString($_POST['evperiod_0'], "text"),
                       GetSQLValueString($_POST['fornum_0'], "int"),
                       GetSQLValueString($_POST['forperiod_0'], "text"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments_0'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

if(!empty($_POST['scheddt_0']) AND !empty($_POST['schedtime_0'])) {  
	$schedt = strtotime($_POST['scheddt_0'].' '.$_POST['schedtime_0']);

	mysql_select_db($database_swmisconn, $swmisconn);   // find thge receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders where visitid = ".$_POST['visitid']."";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
  
	$begin = 0;
	$interval = 0;
	$end = 0;
 	$begin = $schedt;
	//echo 'Begin: '.$begin. ' - '. date('Y-m-d h:i', $begin)."<br />";
    if(isset($_POST['evperiod_0'])) {  // calculate interval in seconds
	   if($_POST['evperiod_0'] == 'minutes') {
	   		$interval = $_POST['every_0'] * 60; }
	   if($_POST['evperiod_0'] == 'hour(s)') {
	   		$interval = $_POST['every_0'] * 60 * 60; }
	   if($_POST['evperiod_0'] == 'day(s)') {
	   		$interval = $_POST['every_0'] * 60 * 60 * 24; }
	   if($_POST['evperiod_0'] == 'week(s)') {
	   		$interval = $_POST['every_0'] * 60 * 60 * 24 * 7; }
	   if($_POST['evperiod_0'] == 'OD') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_0'] == 'Nocte') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_0'] == 'BD') {
	   		$interval = 60 * 60 * 12; }
	   if($_POST['evperiod_0'] == 'TDS') {
	   		$interval = 60 * 60 * 8; }
	   if($_POST['evperiod_0'] == 'QDS') {
	   		$interval = 60 * 60 * 6; }
	   if($_POST['evperiod_0'] == 'PRN') {
	   		$interval = 60 * 60 * 24; }
	   if($_POST['evperiod_0'] == 'STAT') {
	   		$interval = 60 * 60 * 24; }
    } 
	//echo 'interval: '.$interval. ' - '. date('i', $interval)."<br />";
    if(isset($_POST['forperiod_0'])) {
	   if($_POST['forperiod_0'] == 'minutes') {  // calculate end time in numeric format
	   		$end = $schedt + ($_POST['fornum_0'] * 60); }
	   if($_POST['forperiod_0'] == 'hour(s)') {
	   		$end = $schedt + ($_POST['fornum_0'] * 60 * 60); }
	   if($_POST['forperiod_0'] == 'day(s)') {
	   		$end = $schedt + ($_POST['fornum_0'] * 60 * 60 * 24); }
	   if($_POST['forperiod_0'] == 'week(s)') {
	   		$end = $schedt + ($_POST['fornum_0'] * 60 * 60 * 24 * 7); }
    } 
	//echo 'end: '.$end. ' - '. date('Y-m-d h:i', $end)."<br />"."<br />";
		for ($dt = $begin; $dt < $end; $dt+=($interval)) {
	//echo $dt . ' - '. date('Y-m-d h:i', $dt)."<br />";
	// add to ipmeds table   id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt
//  $insertSQL = sprintf("INSERT INTO ipmed_calc (begin_TS, myinterval, end_TS) VALUES (%s, %s, %s)",
//                       GetSQLValueString($begin, "int"),
//                       GetSQLValueString($interval, "int"),
//                       GetSQLValueString($end, "int"));
//  mysql_select_db($database_swmisconn, $swmisconn);
//  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO ipmeds (visitid, orderid, med, status, unit, nunits, schedt, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_maxid['mxid'], "int"),
                       GetSQLValueString($_POST['item_0'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['unit_0'], "text"),
                       GetSQLValueString($_POST['nunits_0'], "int"),
                       GetSQLValueString($dt, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
 } //dt loop
}   //date& time OK
} // item_0
?>
	<script>function out(){opener.location.reload(1); self.close()};</script>
    <script> out();</script>
<?php
} 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_patient = "SELECT lastName, firstName, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age, gender FROM patperm WHERE medrecnum = ".$_GET['mrn'];
	$patient = mysql_query($query_patient, $swmisconn) or die(mysql_error());
	$row_patient = mysql_fetch_assoc($patient);
	$totalRows_patient = mysql_num_rows($patient);
 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
	$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
	$row_doctor = mysql_fetch_assoc($doctor);
	$totalRows_doctor = mysql_num_rows($doctor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>RxMultiOrder</title>
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
//<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table align="center">
   <tr>
      <td>
	     <form id="formrx0" name="formrx0" method="post" action=""> 
         <table width="20%" align="center">
			<tr>
			   <td><div align="center"><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></div></td>
			   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			   <td colspan="3" nowrap="nowrap"><div align="center" class="BlueBold_20">ORDER DRUG </div></td>
  			   <td>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</td>
			   <td class="BlueBold_16"><?php echo $_GET['patype'] ?></td>
  			   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			   <td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patient['lastName'].', '.$row_patient['firstName'].' Age: '.$row_patient['age'].' Gender: '.$row_patient['gender']; ?></td>
 			   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		      <td><div align="center"><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></div></td>
			</tr>
         </table>










































<!-- 1111111  ##########################################################################################	-->
          <table border="1" align="center" bgcolor="#BCFACC">
            <tr>
              <td colspan="5" nowrap="nowrap"><div align="center"><span class="BlueBold_14">Tablet/Capsule</span> </div></td>
            </tr>
            <tr>
              <td><div align="right" class="Black_11">Drug:</div></td>
              <td><input name="item_1" type="text" class="BlackBold_11" id="item_1" size="30" maxlength="100" /></td>
              <td align="right" nowrap="nowrap"><input name="nunits_1" type="text" class="BlackBold_11" id="nunits_1" size="2" maxlength="5" autocomplete="off" />
                  <select name="unit_1" class="BlackBold_11" id="unit_1">
                    <option value="Tablet(s)">Tablet(s)</option>
                    <option value="Capsules(s)">Capsule(s)</option>
                    <option value="Drop(s)">Drop(s)</option>
                    <option value="CC(s)">CC(s)</option>
                    
                </select>              </td>
              <td nowrap="nowrap"><input name="every_1" type="hidden" value="" />
			  <select name="evperiod_1" class="BlackBold_11" id="evperiod_1">
                  <option value="OD">OD</option>
                  <option value="Nocte">Nocte</option>
                  <option value="BD">BD</option>
                  <option value="TDS">TDS</option>
                  <option value="QDS">QDS</option>
                  <option value="PRN">PRN</option>
                  <option value="STAT">STAT</option>
                </select>              </td>
              <td nowrap="nowrap" class="Black_11"> for
                <input name="fornum_1" type="text" class="BlackBold_11" id="fornum_1" size="2" maxlength="5" autocomplete="off" />
                  <select name="forperiod_1" class="BlackBold_11" id="forperiod_1">
                    <option value="minutes">minutes</option>
                    <option value="hour(s)">hour(s)</option>
                    <option value="day(s)" selected="selected">day(s)</option>
                    <option value="week(s)">week(s)</option>
              </select>              </td>
            </tr>
            <tr>
              <td align="right" class="Black_11"> Comments:</td>
              <td colspan="2" nowrap="nowrap"><textarea name="comments_1" cols="50" rows="1" class="BlackBold_11" id="comments_1"></textarea>              </td>

		<?php if($_GET['patype'] == 'InPatient'){?>
			  <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_1" type="text" class="BlackBold_11" id="scheddt_1" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_1" type="text" class="BlackBold_11" id="schedtime_1" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		<?php }?>
            </tr>
          </table>


<!--##########################################################################################	-->	 


<!-- 2  ##########################################################################################	-->
          <table border="1" align="center" bgcolor="#BCFACC">
            <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
            <tr>
              <td><div align="right" class="Black_11">Drug:</div></td>
              <td><input name="item_2" type="text" class="BlackBold_11" id="item_2" size="30" maxlength="100" /></td>
              <td align="right" nowrap="nowrap"><input name="nunits_2" type="text" class="BlackBold_11" id="nunits_2" size="2" maxlength="5" autocomplete="off" />
                  <select name="unit_2" class="BlackBold_11" id="unit_2">
                    <option value="Tablet(s)">Tablet(s)</option>
                    <option value="Capsules(s)">Capsule(s)</option>
                    <option value="Drop(s)">Drop(s)</option>
                    <option value="CC(s)">CC(s)</option>
                </select>              </td>
              <td nowrap="nowrap"><input name="every_2" type="hidden" value="" />
			  <select name="evperiod_2" class="BlackBold_11" id="evperiod_2">
                  <option value="OD">OD</option>
                  <option value="Nocte">Nocte</option>
                  <option value="BD">BD</option>
                  <option value="TDS">TDS</option>
                  <option value="QDS">QDS</option>
                  <option value="PRN">PRN</option>
                  <option value="STAT">STAT</option>
                </select>              </td>
              <td nowrap="nowrap" class="Black_11"> for
                <input name="fornum_2" type="text" class="BlackBold_11" id="fornum_2" size="2" maxlength="5" autocomplete="off" />
                <select name="forperiod_2" class="BlackBold_11" id="forperiod_2">
                    <option value="minutes">minutes</option>
                    <option value="hour(s)">hour(s)</option>
                    <option value="day(s)" selected="selected">day(s)</option>
                    <option value="week(s)">week(s)</option>
              </select>              </td>
            </tr>
            <tr>
              <td align="right" class="Black_11"> Comments:</td>
              <td colspan="2" nowrap="nowrap"><textarea name="comments_2" cols="50" rows="1" class="BlackBold_11" id="comments_2"></textarea>              </td>

		<?php if($_GET['patype'] == 'InPatient'){?>
			  <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_2" type="text" class="BlackBold_11" id="scheddt_2" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_2" type="text" class="BlackBold_11" id="schedtime_2" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="BlackBold_11">&nbsp;</td>
			  <td class="BlackBold_11">&nbsp;</td>
		<?php }?>
            </tr>
          </table>


<!--##########################################################################################	-->	 



<!-- 3  ##########################################################################################	-->
          <table border="1" align="center" bgcolor="#BCFACC">
            <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
            <tr>
              <td><div align="right" class="Black_11">Drug:</div></td>
              <td><input name="item_3" type="text" class="BlackBold_11" id="item_3" size="30" maxlength="100" /></td>
              <td align="right" nowrap="nowrap"><input name="nunits_3" type="text" class="BlackBold_11" id="nunits_3" size="2" maxlength="5" autocomplete="off" />
                  <select name="unit_3" class="BlackBold_11" id="unit_3">
                    <option value="Tablet(s)">Tablet(s)</option>
                    <option value="Capsules(s)">Capsule(s)</option>
                    <option value="Drop(s)">Drop(s)</option>
                    <option value="CC(s)">CC(s)</option>
                </select>              </td>
              <td nowrap="nowrap"><input name="every_3" type="hidden" value="" />
			  <select name="evperiod_3" class="BlackBold_11" id="evperiod_3">
                  <option value="OD">OD</option>
                  <option value="Nocte">Nocte</option>
                  <option value="BD">BD</option>
                  <option value="TDS">TDS</option>
                  <option value="QDS">QDS</option>
                  <option value="PRN">PRN</option>
                  <option value="STAT">STAT</option>
                </select>              </td>
              <td nowrap="nowrap" class="Black_11"> for
                <input name="fornum_3" type="text" class="BlackBold_11" id="fornum_3" size="2" maxlength="5" autocomplete="off" />
                <select name="forperiod_3" class="BlackBold_11" id="forperiod_3">
                    <option value="minutes">minutes</option>
                    <option value="hour(s)">hour(s)</option>
                    <option value="day(s)" selected="selected">day(s)</option>
                    <option value="week(s)">week(s)</option>
              </select>              </td>
            </tr>
            <tr>
              <td align="right" class="Black_11"> Comments:</td>
              <td colspan="2" nowrap="nowrap"><textarea name="comments_3" cols="50" rows="1" class="BlackBold_11" id="comments_3"></textarea>              </td>

		<?php if($_GET['patype'] == 'InPatient'){?>
			  <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_3" type="text" class="BlackBold_11" id="scheddt_3" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_3" type="text" class="BlackBold_11" id="schedtime_3" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="BlackBold_11">&nbsp;</td>
			  <td class="BlackBold_11">&nbsp;</td>
		<?php }?>
            </tr>
          </table>


<!--##########################################################################################	-->	 

<!-- 4  ##########################################################################################	-->
          <table border="1" align="center" bgcolor="#BCFACC">
            <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
            <tr>
              <td><div align="right" class="Black_11">Drug:</div></td>
              <td><input name="item_4" type="text" class="BlackBold_11" id="item_4" size="30" maxlength="100" /></td>
              <td align="right" nowrap="nowrap"><input name="nunits_4" type="text" class="BlackBold_11" id="nunits_4" size="2" maxlength="5" autocomplete="off" />
                  <select name="unit_4" class="BlackBold_11" id="unit_4">
                    <option value="Tablet(s)">Tablet(s)</option>
                    <option value="Capsules(s)">Capsule(s)</option>
                    <option value="Drop(s)">Drop(s)</option>
                    <option value="CC(s)">CC(s)</option>
                </select>              </td>
              <td nowrap="nowrap"><input name="every_4" type="hidden" value="" />
			  <select name="evperiod_4" class="BlackBold_11" id="evperiod_4">
                  <option value="OD">OD</option>
                  <option value="Nocte">Nocte</option>
                  <option value="BD">BD</option>
                  <option value="TDS">TDS</option>
                  <option value="QDS">QDS</option>
                  <option value="PRN">PRN</option>
                  <option value="STAT">STAT</option>
                </select>              </td>
              <td nowrap="nowrap" class="Black_11"> for
                <input name="fornum_4" type="text" class="BlackBold_11" id="fornum_4" size="2" maxlength="5" autocomplete="off" />
                <select name="forperiod_4" class="BlackBold_11" id="forperiod_4">
                    <option value="minutes">minutes</option>
                    <option value="hour(s)">hour(s)</option>
                    <option value="day(s)" selected="selected">day(s)</option>
                    <option value="week(s)">week(s)</option>
              </select>              </td>
            </tr>
            <tr>
              <td align="right" class="Black_11"> Comments:</td>
              <td colspan="2" nowrap="nowrap"><textarea name="comments_4" cols="50" rows="1" class="BlackBold_11" id="comments_4"></textarea>              </td>

		<?php if($_GET['patype'] == 'InPatient'){?>
			  <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_4" type="text" class="BlackBold_11" id="scheddt_4" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_4" type="text" class="BlackBold_11" id="schedtime_4" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="BlackBold_11">&nbsp;</td>
			  <td class="BlackBold_11">&nbsp;</td>
		<?php }?>
            </tr>
          </table>


<!--##########################################################################################	-->	 


<!-- 5  ##########################################################################################	-->
          <table border="1" align="center" bgcolor="#BCFACC">
            <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
            <tr>
              <td><div align="right" class="Black_11">Drug:</div></td>
              <td><input name="item_5" type="text" class="BlackBold_11" id="item_5" size="30" maxlength="100" /></td>
              <td align="right" nowrap="nowrap"><input name="nunits_5" type="text" class="BlackBold_11" id="nunits_5" size="2" maxlength="5" autocomplete="off" />
                  <select name="unit_5" class="BlackBold_11" id="unit_5">
                    <option value="Tablet(s)">Tablet(s)</option>
                    <option value="Capsules(s)">Capsule(s)</option>
                    <option value="Drop(s)">Drop(s)</option>
                    <option value="CC(s)">CC(s)</option>
                </select>              </td>
              <td nowrap="nowrap"><input name="every_5" type="hidden" value="" />
			  <select name="evperiod_5" class="BlackBold_11" id="evperiod_5">
                  <option value="OD">OD</option>
                  <option value="Nocte">Nocte</option>
                  <option value="BD">BD</option>
                  <option value="TDS">TDS</option>
                  <option value="QDS">QDS</option>
                  <option value="PRN">PRN</option>
                  <option value="STAT">STAT</option>
                </select>              </td>
              <td nowrap="nowrap" class="Black_11"> for
                <input name="fornum_5" type="text" class="BlackBold_11" id="fornum_5" size="2" maxlength="5" autocomplete="off" />
                <select name="forperiod_5" class="BlackBold_11" id="forperiod_5">
                    <option value="minutes">minutes</option>
                    <option value="hour(s)">hour(s)</option>
                    <option value="day(s)" selected="selected">day(s)</option>
                    <option value="week(s)">week(s)</option>
              </select>              </td>
            </tr>
            <tr>
              <td align="right" class="Black_11"> Comments:</td>
              <td colspan="2" nowrap="nowrap"><textarea name="comments_5" cols="50" rows="1" class="BlackBold_11" id="comments_5"></textarea>              </td>

		<?php if($_GET['patype'] == 'InPatient'){?>
			  <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_5" type="text" class="BlackBold_11" id="scheddt_5" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_5" type="text" class="BlackBold_11" id="schedtime_5" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="BlackBold_11">&nbsp;</td>
			  <td class="BlackBold_11">&nbsp;</td>
		<?php }?>
            </tr>
          </table>


<!--##########################################################################################	-->	 
		  <table><tr><td class="BlackBold_11">&nbsp;</td></tr></table>
        
<!--******** Beginning Of 6  ****************************************-->

        <table border="1" align="center" bgcolor="#BCFACC">
          <tr>
            <td colspan="5" nowrap="nowrap"><div align="center"><span class="BlueBold_14">Other Non Tablet/Capsule</span> </div></td>
          </tr>
          <tr>
            <td><div align="right" class="Black_11">Drug:</div></td>
            <td><input name="item_6" type="text" class="BlackBold_11" id="item_6" size="30" maxlength="100" /></td>
            <td align="right" nowrap="nowrap"><input name="nunits_6" type="text" class="BlackBold_11" id="nunits_6" size="2" maxlength="5" autocomplete="off" />
              <select name="unit_6" class="BlackBold_11" id="unit_6">
                <option value="ml">ml</option>
                <option value="ounces">ounces</option>
                <option value="mg">mg</option>
                <option value="iU">iU</option>
                <option value="mU">mU</option>
              </select></td>
            <td nowrap="nowrap" class="Black_11"> Every
              <input name="every_6" type="text" class="BlackBold_11" id="every_6" size="2" maxlength="5" autocomplete="off" />
              <select name="evperiod_6" class="BlackBold_11" id="evperiod_6">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)" selected="selected">hour(s)</option>
                  <option value="day(s)">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
            <td nowrap="nowrap" class="Black_11"> for
              <input name="fornum_6" type="text" class="BlackBold_11" id="fornum_6" size="2" maxlength="5" autocomplete="off" />
              <select name="forperiod_6" class="BlackBold_11" id="forperiod_6">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)">hour(s)</option>
                  <option value="day(s)" selected="selected">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="right" class="Black_11">            Comments:</td>
          <td colspan="2" nowrap="nowrap"><textarea name="comments_6" cols="50" rows="1" class="BlackBold_11" id="comments_6"></textarea></td>
            <?php if($_GET['patype'] == 'InPatient'){?>
          
          <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_6" type="text" class="BlackBold_11" id="scheddt_6" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_6" type="text" class="BlackBold_11" id="schedtime_6" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="Black_11">&nbsp;</td>
			  <td class="Black_11">&nbsp;</td>
		<?php }?>
          </tr>
		  </table>
		  
 <!--******** End Of 6 ****************************************-->

<!--******** Beginning Of 7  ****************************************-->
        <table border="1" align="center" bgcolor="#BCFACC">
             <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
          <tr>
            <td><div align="right" class="Black_11">Drug:</div></td>
            <td><input name="item_7" type="text" class="BlackBold_11" id="item_7" size="30" maxlength="100" /></td>
            <td align="right" nowrap="nowrap"><input name="nunits_7" type="text" class="BlackBold_11" id="nunits_7" size="2" maxlength="5" autocomplete="off" />
                <select name="unit_7" class="BlackBold_11" id="unit_7">
                  <option value="ml">ml</option>
                  <option value="ounces">ounces</option>
                  <option value="mg">mg</option>
                  <option value="iU">iU</option>
                  <option value="mU">mU</option>
              </select>			</td>
            <td nowrap="nowrap" class="Black_11"> Every
              <input name="every_7" type="text" class="BlackBold_11" id="every_7" size="2" maxlength="5" autocomplete="off" />
              <select name="evperiod_7" class="BlackBold_11" id="evperiod_7">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)" selected="selected">hour(s)</option>
                  <option value="day(s)">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
            <td nowrap="nowrap" class="Black_11"> for
              <input name="fornum_7" type="text" class="BlackBold_11" id="fornum_7" size="2" maxlength="5" autocomplete="off" />
              <select name="forperiod_7" class="BlackBold_11" id="forperiod_7">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)">hour(s)</option>
                  <option value="day(s)" selected="selected">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="right" class="Black_11">            Comments:</td>
          <td colspan="2" nowrap="nowrap"><textarea name="comments_7" cols="50" rows="1" class="BlackBold_11" id="comments_7"></textarea></td>
            <?php if($_GET['patype'] == 'InPatient'){?>
          
          <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_7" type="text" class="BlackBold_11" id="scheddt_7" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_7" type="text" class="BlackBold_11" id="schedtime_7" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="Black_11">&nbsp;</td>
			  <td class="Black_11">&nbsp;</td>
		<?php }?>
          </tr>
		  </table>
		  
 <!--******** End Of 7  ****************************************-->


<!--******** Beginning Of 8  ****************************************-->
        <table border="1" align="center" bgcolor="#BCFACC">
             <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
          <tr>
            <td><div align="right" class="Black_11">Drug:</div></td>
            <td><input name="item_8" type="text" class="BlackBold_11" id="item_8" size="30" maxlength="100" /></td>
            <td align="right" nowrap="nowrap"><input name="nunits_8" type="text" class="BlackBold_11" id="nunits_8" size="2" maxlength="5" autocomplete="off" />
                <select name="unit_8" class="BlackBold_11" id="unit_8">
                  <option value="ml">ml</option>
                  <option value="ounces">ounces</option>
                  <option value="mg">mg</option>
                  <option value="iU">iU</option>
                  <option value="mU">mU</option>
              </select>			</td>
            <td nowrap="nowrap" class="Black_11"> Every
              <input name="every_8" type="text" class="BlackBold_11" id="every_8" size="2" maxlength="5" autocomplete="off" />
              <select name="evperiod_8" class="BlackBold_11" id="evperiod_8">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)" selected="selected">hour(s)</option>
                  <option value="day(s)">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
            <td nowrap="nowrap" class="Black_11"> for
              <input name="fornum_8" type="text" class="BlackBold_11" id="fornum_8" size="2" maxlength="5" autocomplete="off" />
              <select name="forperiod_8" class="BlackBold_11" id="forperiod_8">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)">hour(s)</option>
                  <option value="day(s)" selected="selected">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="right" class="Black_11">            Comments:</td>
          <td colspan="2" nowrap="nowrap"><textarea name="comments_8" cols="50" rows="1" class="BlackBold_11" id="comments_8"></textarea></td>
            <?php if($_GET['patype'] == 'InPatient'){?>
          
          <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_8" type="text" class="BlackBold_11" id="scheddt_8" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_8" type="text" class="BlackBold_11" id="schedtime_8" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="Black_11">&nbsp;</td>
			  <td class="Black_11">&nbsp;</td>
		<?php }?>
          </tr>
		  </table>
		  
 <!--******** End Of 8  ****************************************-->


<!--******** Beginning Of 9  ****************************************-->
        <table border="1" align="center" bgcolor="#BCFACC">
             <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
          <tr>
            <td><div align="right" class="Black_11">Drug:</div></td>
            <td><input name="item_9" type="text" class="BlackBold_11" id="item_9" size="30" maxlength="100" /></td>
            <td align="right" nowrap="nowrap"><input name="nunits_9" type="text" class="BlackBold_11" id="nunits_9" size="2" maxlength="5" autocomplete="off" />
                <select name="unit_9" class="BlackBold_11" id="unit_9">
                  <option value="ml">ml</option>
                  <option value="ounces">ounces</option>
                  <option value="mg">mg</option>
                  <option value="iU">iU</option>
                  <option value="mU">mU</option>
              </select>			</td>
            <td nowrap="nowrap" class="Black_11"> Every
              <input name="every_9" type="text" class="BlackBold_11" id="every_9" size="2" maxlength="5" autocomplete="off" />
              <select name="evperiod_9" class="BlackBold_11" id="evperiod_9">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)" selected="selected">hour(s)</option>
                  <option value="day(s)">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
            <td nowrap="nowrap" class="Black_11"> for
              <input name="fornum_9" type="text" class="BlackBold_11" id="fornum_9" size="2" maxlength="5" autocomplete="off" />
              <select name="forperiod_9" class="BlackBold_11" id="forperiod_9">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)">hour(s)</option>
                  <option value="day(s)" selected="selected">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="right" class="Black_11">            Comments:</td>
          <td colspan="2" nowrap="nowrap"><textarea name="comments_9" cols="50" rows="1" class="BlackBold_11" id="comments_9"></textarea></td>
            <?php if($_GET['patype'] == 'InPatient'){?>
          
          <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_9" type="text" class="BlackBold_11" id="scheddt_9" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_9" type="text" class="BlackBold_11" id="schedtime_9" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="Black_11">&nbsp;</td>
			  <td class="Black_11">&nbsp;</td>
		<?php }?>
          </tr>
		  </table>
		  
 <!--******** End Of 9  ****************************************-->


<!--******** Beginning Of 0  ****************************************-->
        <table border="1" align="center" bgcolor="#BCFACC">
             <tr>
              <td bgcolor="#000000" colspan="5" nowrap="nowrap"><div class="borderbottomthinblackBold14"> </div></td>
            </tr>
          <tr>
            <td><div align="right" class="Black_11">Drug:</div></td>
            <td><input name="item_0" type="text" class="BlackBold_11" id="item_0" size="30" maxlength="100" /></td>
            <td align="right" nowrap="nowrap"><input name="nunits_0" type="text" class="BlackBold_11" id="nunits_0" size="2" maxlength="5" autocomplete="off" />
                <select name="unit_0" class="BlackBold_11" id="unit_0">
                  <option value="ml">ml</option>
                  <option value="ounces">ounces</option>
                  <option value="mg">mg</option>
                  <option value="iU">iU</option>
                  <option value="mU">mU</option>
              </select>			</td>
            <td nowrap="nowrap" class="Black_11"> Every
              <input name="every_0" type="text" class="BlackBold_11" id="every_0" size="2" maxlength="5" autocomplete="off" />
              <select name="evperiod_0" class="BlackBold_11" id="evperiod_0">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)" selected="selected">hour(s)</option>
                  <option value="day(s)">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
            <td nowrap="nowrap" class="Black_11"> for
              <input name="fornum_0" type="text" class="BlackBold_11" id="fornum_0" size="2" maxlength="5" autocomplete="off" />
              <select name="forperiod_0" class="BlackBold_11" id="forperiod_0">
                  <option value="minutes">minutes</option>
                  <option value="hour(s)">hour(s)</option>
                  <option value="day(s)" selected="selected">day(s)</option>
                  <option value="week(s)">week(s)</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="right" class="Black_11">            Comments:</td>
          <td colspan="2" nowrap="nowrap"><textarea name="comments_0" cols="50" rows="1" class="BlackBold_11" id="comments_0"></textarea></td>
            <?php if($_GET['patype'] == 'InPatient'){?>
          
          <td  align="right" nowrap="nowrap" title="Inpatient only!"><input name="scheddt_0" type="text" class="BlackBold_11" id="scheddt_0" size="12" maxlength="15"/></td>
			  <td  align="left" nowrap="nowrap"><input name="schedtime_0" type="text" class="BlackBold_11" id="schedtime_0" size="8" maxlength="10" /></td>
		<?php } else {?>
			  <td class="Black_11">&nbsp;</td>
			  <td class="Black_11">&nbsp;</td>
		<?php }?>
          </tr>
		  </table>
		  
 <!--******** End Of 0  ****************************************-->



		        <!--<input name="quant" type="hidden" id="quant" value=""/> --> 
				<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
				<input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
				<input name="feeid" type="hidden" id="feeid" value="30" />
				<input name="ratereason" type="hidden" id="ratereason" value="103" />
				<input name="rate" type="hidden" id="rate" value="100" />
				<input type="hidden" name="status" value="ordered"/>
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />

         <table width="20%" align="center">
			<tr>
			   <td><div align="center">
			     <input name="button2" style="background-color:#f81829" type="button" onClick="out()" value="Close" />
			   </div></td>      
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			   <td colspan="3" nowrap="nowrap"><div align="center" class="BlueBold_20">ORDER DRUG </div></td>

			   <td colspan="2" nowrap="nowrap">Urgency:
				 <select name="urgency" id="urgency">
				  <option value="Routine">Routine</option>
				  <option value="Scheduled">Scheduled</option>
				  <option value="ASAP">ASAP</option>
				  <option value="STAT">STAT</option>
				</select>
				Doctor:
				<select name="doctor">
				  <option value="NA">NA</option>
				  <?php do { ?>
 
				<option value="<?php echo $row_doctor['userid']?>"<?php if (!(strcmp($row_doctor['userid'], $_SESSION['user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doctor['userid']?></option>
				  <?php }
 while ($row_doctor = mysql_fetch_assoc($doctor));
  $rows = mysql_num_rows($doctor);
  if($rows > 0) {
      mysql_data_seek($doctor, 0);
	  $row_doctor = mysql_fetch_assoc($doctor);
  }
?>
				</select></td>
			  <td nowrap="nowrap">Pay Later: </td>
           <td>
           <?php if(allow(61,3) == 1) { ?>
              <label>
                 <select name="paylater" id="paylater">
                    <option value="Due" selected="Due">No</option>
                    <option value="paylater">Yes</option>
                 </select>
             </label>
 	   <?php }?>
</td>
		       <td  align="right" nowrap="nowrap"><input name="SubmitAll" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Orders" /></td>
			</tr>
         </table>


         </form>
      </td>
   </tr>
</table>


  <script  type="text/javascript">
   var frmvalidator = new Validator("formrx0");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>

<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">
ng.ready( function() {
    var my_cal = new ng.Calendar({
        input:'scheddt_1'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_1',
    }); 
	
    var my_cal = new ng.Calendar({
        input:'scheddt_2'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_2',
    }); 
	
	    var my_cal = new ng.Calendar({
        input:'scheddt_3'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_3',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_4'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_4',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_5'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_5',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_6'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_6',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_7'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_7',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_8'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_8',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_9'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_9',
    });   
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_0'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime_0',
    });   
  

});

</script>

</body>
</html>
