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

// make sure all posted items are OK
if (isset($_POST['scheddt']) and isset($_POST['schedtime']) and isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Re-Schedule' and $_POST['numrecs'] >=2 and isset($_POST['MM_Update']) and $_POST['MM_Update'] == 'formrxrs')  {

//	echo 'newsched '.$_POST['scheddt'].'_'.$_POST['schedtime']."<br/>"; 
//	echo 'submit '.$_POST['SubmitAll']."<br/>"; 
//	echo 'numrecs '.$_POST['numrecs']."<br/>"; 
//	echo 'interval '.$_POST['ordinterval']."<br/>"; 
//	echo 'form: '.$_POST['MM_Update']."<br/>";
//	echo 'ordid: '.$_POST['ordid']."<br/>" ;
//	 exit;

// Find the current orders with status 'ordered' to be updated
 	mysql_select_db($database_swmisconn, $swmisconn);
	$query_currsched = "SELECT id, orderid, status, schedt from ipmeds where orderid = ".$_POST['ordid']." and status = 'ordered' ORDER BY schedt ASC";
	$currsched = mysql_query($query_currsched, $swmisconn) or die(mysql_error());
	$row_currsched = mysql_fetch_assoc($currsched);
	$totalRows_currsched = mysql_num_rows($currsched);
	$j = 0;
  do { 
	 $newdate = 0;
// set the new start date/time in timestamp format
	 $newschedt =  strtotime($_POST['scheddt'].' '.$_POST['schedtime']);
// if the imed record has the timestamp of the first and subsequent record to be updated (first one j = 0 so nothing is added)
     if($row_currsched['schedt'] == strtotime($_POST['firstordered'])  + ($j * $_POST['ordinterval']) ){
// set variable $newdate to date-time to repace the current one
	 	$newdate = $newschedt + ($j * $_POST['ordinterval']) ;
	 } 
  $UpdateSQL = sprintf("Update ipmeds set schedt = %s, entryby = %s, entrydt = %s where id = %s",
                       GetSQLValueString($newdate, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($row_currsched['id'], "int"));
     $j = $j + 1;
//	  echo 'newdate '.$newdate. '  -- ' .date('Y-m-d H:i',$newdate). "<br/>" ;
//	  echo 'id '.$row_currsched['id']."<br/>"."<br/>" ;
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());
  } while ($row_currsched = mysql_fetch_assoc($currsched));

  $saved = "true";  
} //if form post date is correct

// if this is the initial opening of the page ...  i.e. not after the update
if($saved == ""){
 ?>

<?php
if(isset($_GET['ordid'])) {  // get order information
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
	} else {
		if(isset($_POST['ordid'])) {
  		$colname_ordid = (get_magic_quotes_gpc()) ? $_POST['ordid'] : addslashes($_POST['ordid']);
		}
	}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.id ordid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtpaid, o.comments FROM orders o WHERE o.id ='".$colname_ordid."'";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);

?>
<?php  //find 1st available starting date/time to be updated
 	mysql_select_db($database_swmisconn, $swmisconn);
	$query_medsched = "SELECT id, orderid, status, schedt from ipmeds where orderid = '".$colname_ordid."' and status = 'ordered' ORDER BY schedt ASC";
	$medsched = mysql_query($query_medsched, $swmisconn) or die(mysql_error());
	$row_medsched = mysql_fetch_assoc($medsched);
	$totalRows_medsched = mysql_num_rows($medsched);
    $firstordered = 0;
	$numrecs = 0;
	$i = 0;
	$ordinterval = 0;
	  do { // set value of first med date/time (timestamp) to be modified and the order interval (timestamp)
		 $i = $i +1;
		 if($i == 1) { $firstordered = $row_medsched['schedt'];}
		 if($i == 2) { $ordinterval = ($row_medsched['schedt'] - $firstordered);} 
	  } while ($row_medsched = mysql_fetch_assoc($medsched));
		 $numrecs = $totalRows_medsched;
   }  // end of if saved == '' ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
<style type="text/css">
 input.center{
 text-align:center;
 }
</style>

</head>

<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>
<?php
//	echo 'submit '.$_POST['scheddt']."<br/>"; 
//	echo 'submit '.$_POST['schedtime']."<br/>"; 
//	echo '$firstordered '.$firstordered.'___'.date('Y-M-d D h:i A',$firstordered)."<br/>"; 
// 	echo 'newsched '.$_POST['newschedt']."<br/>"; 
//	echo 'submit '.$_POST['SubmitAll']."<br/>"; 
//	echo 'numrecs '.$_POST['numrecs']."<br/>"; 
//	echo 'interval '.$_POST['ordinterval']."<br/>"; 
//	echo 'form: '.$_POST['MM_Update']."<br/>";
//	echo 'ordid: '.$_POST['ordid']."<br/>" ;
?>
<body>
<table align="center">
  <tr>
    <td><form id="formrxrs" name="formrxrs" method="post" action="PatInPatMedReShed.php">
      <table border="1" align="center" bgcolor="#BCFACC">
        <tr>
          <td colspan="5" nowrap="nowrap"><div align="center" class="BlueBold_20">Re-Schedule Medication </div></td>
        </tr>
        <tr>
          <td colspan="5"><div align="center" class="BlueBold_1414">Prescription</div></td>
        </tr>
        <tr>
          <td><input name="item" type="text" id="item" size="30" maxlength="100" disabled="disabled" value="<?php echo $row_ordered['item']; ?>" /></td>
          <td colspan="4" nowrap="nowrap"><input name="nunits" type="text" id="nunits" class="center" size="3" maxlength="3" disabled="disabled" value="<?php echo $row_ordered['nunits']; ?>" />
           <input name="unit" type="text" size="6" disabled="disabled" value="<?php echo $row_ordered['unit']; ?>" />
<?php if(is_numeric($row_ordered['every'])){?>
            Every
            <input name="every" type="text" id="every" size="3" class="center" disabled="disabled" value="<?php echo $row_ordered['every']; ?>" />
<?php }?> 
            <input name="evperiod" type="text" id="every" size="6" disabled="disabled" value="<?php echo $row_ordered['evperiod']; ?>" />
            for
            <input name="fornum" type="text" id="fornum" size="3" class="center" disabled="disabled" value="<?php echo $row_ordered['fornum']; ?>" />
            <input name="forperiod" type="text" id="forperiod" size="6" disabled="disabled" value="<?php echo $row_ordered['forperiod']; ?>" />          </td>
        </tr>
        <tr>
          <td colspan="4" nowrap="nowrap">Order Comments: 
            <textarea name="comments" cols="60" rows="2" id="comments" disabled="disabled"> <?php echo $row_ordered['comments']; ?></textarea></td>
        </tr>
        <tr>
          <td nowrap="nowrap">Re-schedule starting From:</td>
          <td colspan="2"  align="center" nowrap="nowrap">
          <input name="firstordered" type="text" Value="<?php echo  date('Y-M-d D h:i A', $firstordered) ;?>" size="32"  disabled="disabled" /></td>
          <td  align="left" nowrap="nowrap">To:</td>
        </tr>
        <tr>
      <td nowrap="nowrap">Select date &amp; time to create schedule.</td>
      <td  align="right" nowrap="nowrap">
	  <input id="scheddt" name="scheddt" type="text" size="12" maxlength="15" Value="<?php echo  date('D, Y-M-d', $firstordered) ;?>" ></td>

      <td  align="right" nowrap="nowrap">Begin        
        <input id="schedtime" name="schedtime" type="text" size="8" maxlength="10" /></td>
          <td align="right" nowrap="nowrap">.
  <input name="SubmitAll" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Re-Schedule" /></td>
  </tr>
        <input name="ordinterval" type="hidden" id="ordinterval" value="<?php echo $ordinterval; ?>"/>
        <input name="numrecs" type="hidden" id="numrecs" value="<?php echo $numrecs; ?>"/>
        <input name="ordid" type="hidden" id="ordid" value="<?php echo $row_ordered['ordid']; ?>" />
        <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
        <input type="hidden" name="MM_Update" value="formrxrs" />
      </table>
    </form></td>
  </tr>
</table>
<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">
ng.ready( function() {
    var my_cal = new ng.Calendar({
        input:'scheddt'
    });
    var my_timepicker = new ng.TimePicker({
        input:'schedtime'
    });   
});

</script>
</body>
</html>
