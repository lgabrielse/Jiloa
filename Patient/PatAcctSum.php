<?php require_once('../../Connections/swmisconn.php'); ?>
<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$mm_mrn = "1376";
if (isset($_GET['mrn'])) {
  $mm_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
//$query_AcctBal = "SELECT p.lastname, p.firstname, p.othername, DATE_FORMAT(p.DOB,'%d-%c-%Y') DOB, p.gender, o.id, o.medrecnum, SUM(CASE WHEN r.amtdue IS NULL THEN o.amtdue ELSE r.amtdue End) as amtdue, IFNULL(SUM(r.amtpaid),0) amtpaid, SUM(IFNULL(r.amtpaid,0))-SUM(CASE WHEN r.amtdue IS NULL THEN o.amtdue ELSE IFNULL(r.amtdue,0) End) bal FROM patperm p join `orders` o on o.medrecnum = p.medrecnum left outer join`rcptord` r on o.id = r.ordid WHERE o.billstatus <> 'Refunded' and o.medrecnum = '".$mm_mrn."' GROUP BY medrecnum";
 //(Select SUM(IFNULL(o.amtpaid,0)) sumamtpaid FROM `orders` o  where o.feeid <> 279 GROUP BY medrecnum)
//r.nbc <> 'Deposit' and 

$query_AcctPaid = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE status = 'Paid' and r.medrecnum = '".$mm_mrn."' GROUP BY r.medrecnum";
$AcctPaid = mysql_query($query_AcctPaid, $swmisconn) or die(mysql_error());
$row_AcctPaid = mysql_fetch_assoc($AcctPaid);
$totalRows_AcctPaid = mysql_num_rows($AcctPaid);


$AcctBal = 0;

//ok
$query_AcctDeposits = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE status = 'Deposited' and r.medrecnum = '".$mm_mrn."' GROUP BY r.medrecnum";
$AcctDeposits = mysql_query($query_AcctDeposits, $swmisconn) or die(mysql_error());
$row_AcctDeposits = mysql_fetch_assoc($AcctDeposits);
$totalRows_AcctDeposits = mysql_num_rows($AcctDeposits);

//ok
$query_AcctPdByDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE (ro.status = 'Paid' OR ro.status = 'PartPaid') and r.nbc = 'Deposit' and r.medrecnum = '".$mm_mrn."' GROUP BY r.medrecnum";
$AcctPdByDep = mysql_query($query_AcctPdByDep, $swmisconn) or die(mysql_error());
$row_AcctPdByDep = mysql_fetch_assoc($AcctPdByDep);
$totalRows_AcctPdByDep = mysql_num_rows($AcctPdByDep);


//ok  and r.nbc = 'Deposit' added
$query_AcctRefToDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'Refunded' and r.nbc = 'Deposit' and r.medrecnum = '".$mm_mrn."' GROUP BY r.medrecnum";
$AcctRefToDep = mysql_query($query_AcctRefToDep, $swmisconn) or die(mysql_error());
$row_AcctRefToDep = mysql_fetch_assoc($AcctRefToDep);
$totalRows_AcctRefToDep = mysql_num_rows($AcctRefToDep);

//copied
$query_AcctDepRefund = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'DepRefund' and r.medrecnum = '".$mm_mrn."' GROUP BY r.medrecnum";
$AcctDepRefund = mysql_query($query_AcctDepRefund, $swmisconn) or die(mysql_error());
$row_AcctDepRefund = mysql_fetch_assoc($AcctDepRefund);
$totalRows_AcctDepRefund = mysql_num_rows($AcctDepRefund);

$AcctBal = $row_AcctDeposits['sumamtpaid'] + $row_AcctDepRefund['sumamtpaid'] + (0 - $row_AcctRefToDep['sumamtpaid']) - $row_AcctPdByDep['sumamtpaid'];

$query_OrdBal = "SELECT p.lastname, p.firstname, p.othername, DATE_FORMAT(p.DOB,'%d-%c-%Y') DOB, p.gender, o.id, o.medrecnum, SUM(o.amtdue) sumamtdue, SUM(o.amtpaid) sumamtpaid, SUM(IFNULL(o.amtpaid,0)) - SUM(IFNULL(o.amtdue,0)) bal FROM patperm p join `orders` o on o.medrecnum = p.medrecnum WHERE o.billstatus <> 'Refunded' and o.feeid <> 279 and o.medrecnum = '".$mm_mrn."' GROUP BY medrecnum";
$OrdBal = mysql_query($query_OrdBal, $swmisconn) or die(mysql_error());
$row_OrdBal = mysql_fetch_assoc($OrdBal);
$totalRows_OrdBal = mysql_num_rows($OrdBal);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Regist = "SELECT o.id ordid, DATE_FORMAT(o.entrydt,'%d-%c-%Y') entrydt, o.status, o.billstatus, o.amtdue, o.amtpaid, f.dept, f.name, ro.amtdue, ro.amtpaid, ro.status rostatus, r.nbc from orders o join fee f on o.feeid =f.id join rcptord ro on ro.ordid = o.id join receipts r on r.id = ro.rcptid where ro.status <> 'Refunded' AND o.medrecnum = ".$mm_mrn." and o.visitid = 0";
$Regist = mysql_query($query_Regist, $swmisconn) or die(mysql_error());
$row_Regist = mysql_fetch_assoc($Regist);
$totalRows_Regist = mysql_num_rows($Regist);



mysql_select_db($database_swmisconn, $swmisconn);
$query_Visit = "SELECT id, DATE_FORMAT(visitdate,'%d-%c-%Y') visitdate, pat_type, location, visitreason FROM patvisit WHERE medrecnum = '".$mm_mrn."'";
$Visit = mysql_query($query_Visit, $swmisconn) or die(mysql_error());
$row_Visit = mysql_fetch_assoc($Visit);
$totalRows_Visit = mysql_num_rows($Visit);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>
</head>

<body>
<p>&nbsp;</p>
<table width="50%" border="0" align="center" class="tablebc">
  <tr>
    <td height="28" class="BlackBold_16">Account Information </td>
    <td>&nbsp;</td>
    <td>&nbsp;<input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></td>
    <td><!--Paid: <?php //echo $row_AcctPaid['sumamtpaid'] ?>--></td>
    <td align="center">Order <br />
    Balance </td>
    <td>Amt Due</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>Amt Paid</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td align="center">Account<br /> 
    Balance </td>
  </tr>
<?php do { ?>
  <tr>
    <td nowrap="nowrap" bgcolor="#C1ECD6" title="MRN:<?php echo $row_OrdBal['medrecnum']; ?>">Patient:<strong>  <?php echo $row_OrdBal['lastname']; ?>,<?php echo $row_OrdBal['firstname']; ?>(<?php echo $row_OrdBal['othername']; ?>)</strong></td>
    <td nowrap="nowrap" bgcolor="#C1ECD6">DOB: <strong><?php echo $row_OrdBal['DOB']; ?></strong></td>
    <td nowrap="nowrap" bgcolor="#C1ECD6">Gender: <strong><?php echo $row_OrdBal['gender']; ?></strong></td>
    <td nowrap="nowrap" bgcolor="#C1ECD6">&nbsp;</td>
  <?php if($row_OrdBal['bal'] < 0) { ?>
    <td bgcolor="#FFFFFF" align="center" nowrap="nowrap"><span class="flagWhiteonRed"><strong><?php echo $row_OrdBal['bal']; ?></strong></span></td>
	<?php } else {?>
    <td bgcolor="#FFFFFF" align="center" nowrap="nowrap"> <span class="flagWhiteonGreen"><strong><?php echo $row_OrdBal['bal']; ?></strong></span></td>
<?php }?>
    <td bgcolor="#D5FFD5"><strong><?php echo $row_OrdBal['sumamtdue']; ?></strong></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#D5FFD5"><strong><?php echo $row_OrdBal['sumamtpaid']; ?></strong></td>
    <td>&nbsp;</td>

  <?php if($AcctBal['bal'] < 0) { ?>
    <td bgcolor="#FFFFFF" align="center" nowrap="nowrap"><span class="flagWhiteonRed"><strong><?php echo $AcctBal; ?></strong></td>
	<?php } else {?>
    <td bgcolor="#FFFFFF" align="center" nowrap="nowrap"> <span class="flagWhiteonGreen"><strong><?php echo $AcctBal; ?></strong></span></td>
<?php }?>
    </tr>
<?php } while ($row_OrdBal = mysql_fetch_assoc($OrdBal)); ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php do { ?>
  <tr>
    <td nowrap="nowrap" bgcolor="#FFFFFF" title="Ordid:<?php echo $row_Regist['ordid']; ?>"><?php echo $row_Regist['dept']; ?>-<?php echo $row_Regist['name']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_Regist['entrydt']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_Regist['status']; ?></td>
    <td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_Regist['billstatus'].' By ('.$row_Regist['nbc'].')'; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#D5FFD5"><?php echo $row_Regist['amtdue']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    
<?php if($row_Regist['nbc'] == 'Deposit') {  ?>
    <td bgcolor="#D5FFD5"><?php echo $row_Regist['amtpaid']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#D5FFD5">-<?php echo $row_Regist['amtpaid']; ?></td>
<?php 		} else { ?>
    <td bgcolor="#D5FFD5"><?php echo $row_Regist['amtpaid']; ?></td>
  
<?php   } ?>

  </tr>
<?php } while ($row_Regist = mysql_fetch_assoc($Regist)); ?>
<?php if(isset($row_Visit['id'])){?>
<?php do { ?>
  <tr>
    <td bgcolor="#CCFFFF" title="VISIT: <?php echo $row_Visit['id']; ?>">Visit Reason: <strong><?php echo $row_Visit['visitreason']; ?></strong></td>
    <td nowrap="nowrap" bgcolor="#CCFFFF">Date: <strong><?php echo $row_Visit['visitdate']; ?></strong></td>
    <td align="right" bgcolor="#CCFFFF">Location:</td>
    <td colspan="3" nowrap="nowrap" bgcolor="#CCFFFF"><strong> <strong><?php echo $row_Visit['pat_type']; ?></strong><?php echo $row_Visit['location']; ?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Order: Dept - Order Name </td>
    <td>Receipt Date</td>
    <td>Order Status</td>
    <td>Receipt<br />
    Billing Status </td>
    <td>&nbsp;</td>
    <td>Amt Due</td>
    <td>&nbsp;</td>
    <td>Amt Paid</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>Deposit</td>
  </tr>

<?php  // orders processed by cashier
mysql_select_db($database_swmisconn, $swmisconn);
$query_Order = "Select o.id ordid, DATE_FORMAT(o.entrydt, '%d-%m-%Y') entrydt, o.status, o.billstatus, o.feeid, substr(dept,1,3), f.dept, f.name, ro.amtdue, ro.amtpaid, ro.status rostatus, r.nbc, DATE_FORMAT(r.entrydt, '%d-%m-%Y') rcptdt from orders o join fee f on o.feeid =f.id join rcptord ro on ro.ordid = o.id join receipts r on r.id = ro.rcptid where ro.status <> 'Refunded' and o.visitid = ".$row_Visit['id']."";
$Order = mysql_query($query_Order, $swmisconn) or die(mysql_error());
$row_Order = mysql_fetch_assoc($Order);
$totalRows_Order = mysql_num_rows($Order);
?>
<?php do { ?>
  <tr>
    <td nowrap="nowrap" bgcolor="#FFFFFF" title="Ordid:<?php echo $row_Order['ordid']; ?>"><?php echo $row_Order['dept']; ?>-<?php echo $row_Order['name']; ?></td>
    <td bgcolor="#FFFFFF" title="ORDER DATE: <?php echo $row_Order['entrydt']; ?>"><?php echo $row_Order['rcptdt']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_Order['status']; ?></td>
    <td colspan="2" nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_Order['rostatus'].' - ('.$row_Order['nbc'].')'; //. '('.$row_Order['billstatus'].')' ?></td>
    <td bgcolor="#D5FFD5"><?php echo $row_Order['amtdue']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
<?php if($row_Order['feeid'] == 279 or $row_Order['rostatus'] == 'DepRefund'){ ?> 
    <td bgcolor="#D5FFD5">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#D5FFD5"><?php echo $row_Order['amtpaid']; ?></td>
<?php   } else {  
			if($row_Order['nbc'] == 'Deposit') {  ?>
    <td bgcolor="#D5FFD5"><?php echo $row_Order['amtpaid']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#D5FFD5">-<?php echo $row_Order['amtpaid']; ?></td>
<?php 		} else { ?>
    <td bgcolor="#D5FFD5"><?php echo $row_Order['amtpaid']; ?></td>
  
<?php   } 
	}     ?> 
    </tr>
<?php } while ($row_Order = mysql_fetch_assoc($Order));
mysql_free_result($Order);
?>

<?php  // orders Not processed by cashier
mysql_select_db($database_swmisconn, $swmisconn);
$query_Order2 = "Select o.id ordid, DATE_FORMAT(o.entrydt, '%d-%m-%Y') entrydt, o.status, o.billstatus, o.feeid, substr(dept,1,3), f.dept, f.name, o.amtdue from orders o join fee f on o.feeid =f.id where o.status <> 'Refunded' and o.visitid = ".$row_Visit['id']." and o.id not in (Select o.id from orders o join fee f on o.feeid =f.id join rcptord ro on ro.ordid = o.id join receipts r on r.id = ro.rcptid where ro.status <> 'Refunded' and o.visitid = ".$row_Visit['id'].")";
$Order2 = mysql_query($query_Order2, $swmisconn) or die(mysql_error());
$row_Order2 = mysql_fetch_assoc($Order2);
$totalRows_Order2 = mysql_num_rows($Order2);
?>
<?php if($totalRows_Order2 >0 ){
	 do { ?>
  <tr>
    <td nowrap="nowrap" bgcolor="#FFFFFF" title="Ordid:<?php echo $row_Order2['ordid']; ?>"><?php echo $row_Order2['dept']; ?>-<?php echo $row_Order2['name']; ?></td>
    <td bgcolor="#FFFFFF" Title="ORDER DATE: <?php echo $row_Order2['entrydt']; ?>"><?php echo $row_Order2['entrydt']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_Order2['status']; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row_Order2['billstatus']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#D5FFD5"><?php echo $row_Order2['amtdue']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
<?php } while ($row_Order2 = mysql_fetch_assoc($Order2));
mysql_free_result($Order2);
} // if > 0 records
 ?>




<?php } while ($row_Visit = mysql_fetch_assoc($Visit)); ?>
<?php // refunded orders
mysql_select_db($database_swmisconn, $swmisconn);  // , ro.amtdue roamtdue, ro.amtpaid roamtpaid, ro.status rostatus
$query_Refunded = "Select o.id ordid, DATE_FORMAT(o.entrydt, '%d-%m-%Y') entrydt, o.status, o.billstatus, substr(f.dept,1,3), f.dept, f.name, o.amtdue, o.amtpaid, ro.amtdue roamtdue, ro.amtpaid roamtpaid, ro.status rostatus, r.nbc, DATE_FORMAT(r.entrydt, '%d-%m-%Y') rcptdt from orders o join fee f on o.feeid = f.id join rcptord ro on ro.ordid = o.id join receipts r on  ro.rcptid = r.id where ro.status = 'Refunded' and o.medrecnum = ".$mm_mrn."";
$Refunded = mysql_query($query_Refunded, $swmisconn) or die(mysql_error());
$row_Refunded = mysql_fetch_assoc($Refunded);
$totalRows_Refunded = mysql_num_rows($Refunded);
?>
<?php   if($totalRows_Refunded > 0){?>
  <tr>
    <td>Refunded</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>

<?php do { ?>
  <tr>
    <td bgcolor="#FFFDDA" title="Ordid: <?php echo $row_Refunded['ordid']; ?>"> <?php echo $row_Refunded['dept']; ?>-<?php echo $row_Refunded['name']; ?></td>
    <td bgcolor="#FFFDDA"title="<?php echo $row_Refunded['entrydt']; ?>"><?php echo $row_Refunded['rcptdt']; ?></td>
    <td bgcolor="#FFFDDA"><?php echo $row_Refunded['status']; ?></td>
    <td colspan="2" nowrap="nowrap" bgcolor="#FFFDDA"><?php echo $row_Refunded['rostatus'].' to ('.$row_Refunded['nbc'].')'; ?></td>
    <td bgcolor="#FFFDDA"><?php echo 0 - $row_Refunded['roamtdue']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFDDA"><?php echo $row_Refunded['roamtpaid']; ?></td>
<?php if($row_Refunded['rostatus'] == 'Refunded' and $row_Refunded['nbc'] == 'Deposit') { ?>
    <td bgcolor="#FFFFFF">&nbsp;</td>
	<td align="right" bgcolor="#D5FFD5"><?php echo $row_Refunded['roamtdue']; ?></td>
<?php }?>
    </tr>
<?php } while ($row_Refunded = mysql_fetch_assoc($Refunded)); 
mysql_free_result($Refunded);?>
<?php   } //refunded?>
<?php  } //visitid?>
</table>
<p>If Deposits then:</p>
<table width="20%" border="0">
  <tr>
    <td>AcctDeposits:</td>
    <td><?php echo $row_AcctDeposits['sumamtpaid'] ?></td>
  </tr>
  <tr>
    <td>AcctRefToDep:</td>
    <td><?php echo $row_AcctRefToDep['sumamtpaid'] ?></td>
  </tr>
  <tr>
    <td>AcctPdByDep:</td>
    <td><?php echo $row_AcctPdByDep['sumamtpaid'] ?></td>
  </tr>
</table>

<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($AcctPaid);
mysql_free_result($OrdBal);

mysql_free_result($Visit);

mysql_free_result($AcctDeposits);
mysql_free_result($AcctPdByDep);
mysql_free_result($AcctRefToDep);
mysql_free_result($Regist);
?>
