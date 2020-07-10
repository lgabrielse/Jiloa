<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //session_start()?>

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
  if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "formrxd")) {

mysql_select_db($database_swmisconn, $swmisconn);
$query_myfee = "Select fee from fee where id = ".$_POST['feeid']."";
$myfee = mysql_query($query_myfee, $swmisconn) or die(mysql_error());
$row_myfee = mysql_fetch_assoc($myfee);
$totalRows_myfee = mysql_num_rows($myfee);

			//	item, nunits, unit, every, evperiod, fornum, forperiod
  $updateSQL = sprintf("UPDATE orders SET status=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
}

 ?>

<?php

mysql_select_db($database_swmisconn, $swmisconn);
$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
$totalRows_doctor = mysql_num_rows($doctor);

?><!--query for an order record-->
<?php if(isset($_GET['ordid'])) {  
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_editdel = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.quant, o.ofee, o.rate, o.doctor, substr(o.status,1,7) status, o.comments, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee FROM orders o join fee f on o.feeid = f.id WHERE f.dept in ('pharm', 'admin') and o.id = '". $colname_ordid ."'";
$editdel = mysql_query($query_editdel, $swmisconn) or die(mysql_error());
$row_editdel = mysql_fetch_assoc($editdel);
$totalRows_editdel = mysql_num_rows($editdel);
	}
?>
<?php
	$colname_ordid = "-1";
if (isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
 
mysql_select_db($database_swmisconn, $swmisconn);
$query_medsched = "SELECT i.id, i.visitid, i.orderid, i.med, i.status, i.unit, i.nunits, i.schedt, i.comments, CASE WHEN i.givendt is NULL THEN 0 ELSE i.givendt END as givendt, i.givenby, i.entryby, i.entrydt, o.evperiod, o.status ostatus, o.billstatus,  p.lastname, p.firstname, v.pat_type from ipmeds i join orders o on i.orderid = o.id join patperm p on o.medrecnum = p.medrecnum join patvisit v on i.visitid = v.id where i.orderid like '".$colname_ordid."%' ORDER BY schedt ASC";
$medsched = mysql_query($query_medsched, $swmisconn) or die(mysql_error());
$row_medsched = mysql_fetch_assoc($medsched);
$totalRows_medsched = mysql_num_rows($medsched);
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
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>

<body>

<?php if($row_medsched['pat_type'] == 'InPatient'){ ?>

	 <table border = "1" align="center" bgcolor="#F5F5F5">
      <tr bgcolor="#DCDCDC">
	  	<td>Order Status: :<span><?php echo $row_medsched['ostatus'].', <br />Billing Status:'.$row_medsched['billstatus'] ?></span></td>
		<td colspan="9"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="BlueBold_1414"><?php echo $row_medsched['lastname'].', '.$row_medsched['firstname'] ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="BlueBold_16">&nbsp;VIEW INPATIENT MED SCHEDULE</span></div></td>
		<td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
	  </tr>
      <tr bgcolor="#DCDCDC">
        <td><div align="center">Order# <?php echo $colname_ordid ?></div></td>
        <td><div align="center">Date:Time</div></td>
        <td><div align="center">Med</div></td>
        <td><div align="center">#</div></td>
        <td><div align="center">Status</div></td>
        <td><div align="center">GivenDt</div></td>
        <td><div align="center">GivenBy</div></td>
        <td><div align="center">Comment</div></td>
      </tr>
	  <tr>
        <?php if($totalRows_medsched > 0){
	    do { ?>
    <?php //if(allow(41,2) == 1) { ?>	
        <!--<td><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatMedStatus.php?mmid=<?php echo $row_medsched['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=500')">Edit</a></td>-->
      <?php	// }?>
	    <!--id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt-->
        <?php 		if(date('A',$row_medsched['schedt']) == 'AM') { // set color of time display
				$dtbkgd = '#d5effc';
			} else {
				$dtbkgd = '#DFDFDF';
			}
 		   if($row_medsched['status'] == 'ordered') { // set color of time display
				$stbkgd = '#F5F5F5';
			} else {
				$stbkgd = '#DCDCDC"';
			}
?>
<?php if(strpos("OD Nocte BD TDS QDS PRN STAT", $row_medsched['evperiod'])>0) {?>
        <td bgcolor="<?php echo $dtbkgd ?>" nowrap="nowrap"><?php echo date('D M-d',$row_medsched['schedt']) .'  '.$row_medsched['evperiod']; ?></td>
<?php } else {?>
        <td bgcolor="<?php echo $dtbkgd ?>" nowrap="nowrap"><?php echo date('D M-d H:i',$row_medsched['schedt']); ?>
<?php }?>
	    <td nowrap="nowrap" bgcolor="<?php echo $stbkgd ?>" title="ID: <?php echo $row_medsched['id']; ?>&#10;VisitID: <?php echo $row_medsched['visitid']; ?>&#10;OrderID: <?php echo $row_medsched['orderid']; ?>&#10;EntryDt: <?php echo $row_medsched['entrydt']; ?>&#10;EntryBy: <?php echo $row_medsched['entryby']; ?>&#10;Medication Comments: <?php echo $row_medsched['comments']; ?>"><?php echo $row_medsched['med']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['nunits']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['unit']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['status']; ?></td>
        <td bgcolor="<?php echo $stbkgd ?>" nowrap="nowrap"><?php if($row_medsched['givendt'] == 0) { echo ' --- ';} else { echo date('D h:i_A' ,$row_medsched['givendt']); } ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>" title="<?php if($row_medsched['givendt'] == 0) { echo ' --- ';} else { echo date('D M-d_h:i_A' ,$row_medsched['givendt']); } ?>" nowrap="nowrap"><?php echo $row_medsched['givenby']; ?></td>
<?php if(strlen($row_medsched['comments'])>0){  ?>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['comments']; ?></td>  
<?php   } 		?>
       </tr>
      <?php } while ($row_medsched = mysql_fetch_assoc($medsched)); 
	 } else { ?>
      <?php	 }?>
</table>
<p></p>
<p></p>
<?php }?>

<table align="center">
  <tr>
   <td>
<form id="formrxd" name="formrxd" method="post" action="<?php echo $editFormAction; ?>"> 
    <table border="1"  bgcolor="#F8FDCE">
   
     <tr>
       <td colspan="2" nowrap="nowrap" title="Order status will become RxDispensed &#10;Billing status will remain Paid"><div align="center" class="BlueBold_20">DISPENSE DRUG </div></td>
     </tr>
     <tr>
		<td  nowrap="nowrap">Drugs</td>
		<td colspan="2" nowrap="nowrap"><p>Urg:
		    <input type="text" name="urgency" id="urgency" disabled="disabled" value = "<?php echo $row_editdel['urg'] ?>" size="3" maxlength="5"/>
		    Dr:
		    <input type="text" name="doctor" id="doctor" disabled="disabled" value = "<?php echo $row_editdel['doctor'] ?>" size="15" maxlength="30"/>
		    Quant:
		    <input name="quant" type="text" id="quant"  disabled="disabled" value="<?php echo $row_editdel['quant']; ?>" size="4" />
		    Naira:            
		    <input name="ofee" type="text" disabled="disabled" value="<?php echo $row_editdel['ofee']; ?>" size="6" />
		    </p></td>
		</tr>
	    <tr>
	      <td colspan="2"><div align="center" class="BlueBold_1414">Prescription</div></td>
	    </tr>
	    <tr>
		  <td><input name="item" type="text" id="item" disabled="disabled" value="<?php echo $row_editdel['item']; ?>" size="30" maxlength="100" /></td>
		  <td nowrap="nowrap"><input name="nunits" type="text" id="nunits" disabled="disabled" value="<?php echo $row_editdel['nunits']; ?>" size="2" maxlength="2" />
		   <input name="unit" type="text" id="unit" disabled="disabled" value="<?php echo $row_editdel['unit']; ?>" size="8" maxlength="10" />
		    Every
  <input name="every" type="text" id="every" disabled="disabled" value="<?php echo $row_editdel['every']; ?>" size="2" />
		   <input name="evperiod" type="text" id="evperiod" disabled="disabled" value="<?php echo $row_editdel['evperiod']; ?>" size="8" maxlength="10" />
		    for
            <input name="fornum" type="text" id="fornum" disabled="disabled" value="<?php echo $row_editdel['fornum']; ?>" size="2" />
			<input name="forperiod" type="text" id="forperiod" disabled="disabled" value="<?php echo $row_editdel['forperiod']; ?>" size="8" maxlength="10" /></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap">Order Comments:<br /></td>
		<td nowrap="nowrap"><textarea name="comments" cols="60" rows="3" id="comments"><?php echo $row_editdel['comments']; ?></textarea></td>
	  </tr>
	  <tr>
	    <td nowrap="nowrap">Order #: <?php echo $row_editdel['id']; ?></td>
	    <td nowrap="nowrap"><div align="right">
	      Only comments can be edited.  
	      <input name="Submit" type="submit" id="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Dispense" />
	      </div></td>
	    </tr>
	  
				<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
				<input name="status" type="hidden" id="status" value="RxDispensed" />
				<input name="feeid" type="hidden" id="feeid" value="30" />
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				<input name="ordid" type="hidden" id="ordid" value="<?php echo $_GET['ordid']; ?>" />
				<input type="hidden" name="MM_Update" value="formrxd">
	</table>
   </form>
   </td>
  </tr>
</table>

</body>
</html>
