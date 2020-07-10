<?php  $pt = "Rx Orders List"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 
	$colname_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}

if(isset($_GET['status']) && $_GET['status'] == "'RxPending'") { 
mysql_select_db($database_swmisconn, $swmisconn);//o.status, o.amtdue, amtpaid, billstatus, 
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.status, o.billstatus, o.urgency, o.doctor, o.amtdue, o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.item as feename, o.ofee,  f.dept, f.section, f.unit, f.descr, f.fee, left(v.pat_type,1) pt FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept = 'Pharm' and  
o.item IS NOT NULL and (o.status in ('RxOrdered', 'RxPaid') || (o.status in ('RxCosted') && o.billstatus = 'paylater')) AND o.medrecnum = '". $colname_mrn ."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

} elseif(isset($_GET['status']) && $_GET['status'] == "'RxPaylater'")  {
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.status, o.billstatus, o.urgency, o.doctor, o.amtdue, o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.item as feename, o.ofee,  f.dept, f.section, f.unit, f.descr, f.fee, left(v.pat_type,1) pt FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept = 'Pharm' and  
o.item IS NOT NULL and o.billstatus = 'paylater' AND o.medrecnum = '". $colname_mrn ."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

}
	
else {

mysql_select_db($database_swmisconn, $swmisconn);  //CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as o.amtdue,
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.status, o.billstatus, o.urgency, o.doctor, o.amtdue, o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.item as feename, o.ofee,  f.dept, f.section, f.unit, f.descr, f.fee, left(v.pat_type,1) pt FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept = 'Pharm' and o.item IS NOT NULL and o.status in (". $_GET['status'].") AND o.billstatus in ('Due', 'Paid', 'paylater') AND o.medrecnum = '". $colname_mrn ."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
}
/*mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.rate, o.ratereason, o.status, o.urgency, o.doctor,o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt,
f.dept, f.section, 
CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename,
CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as amtdue,
f.unit, f.descr, f.fee
FROM orders o 
join fee f on o.feeid = f.id 
WHERE
o.medrecnum = $colname_mrn";	
	
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
} ?>*/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Rx Orders List</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=400,screenX=300,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<style type="text/css">
     .right { text-align: right; }
</style>
</head>

<body>

<!-- Display PATIENT PERMANENT Data  -->
<?php $patview = "../Patient/PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>

<div>
  <div align="center" class="BlueBold_18">Pharmacy Items <?php echo $_GET['status']; ?></div>
</div>
<?php if (isset($_GET['mrn']) AND $totalRows_orders > 0) {?>

<table align="center" bgcolor="#F8FDCE">
  <form id="form1" name="form1" method="post" action="CashShowTot.php?Status=other" >
    <tr>
      <td title="Ward Stock Given"><div align="center" class="RedBold_14">WS</div></td>
      <td class="BlueBold_14"><div align="center">Ord#-PT*</div></td>
      <td class="BlueBold_14"><div align="center">entrydt*</div></td>
      <td class="BlueBold_14"><div align="center">status</div></td>
      <td class="BlueBold_14"><div align="center">urgency</div></td>
      <td class="BlueBold_14"><div align="center">doctor</div></td>
      <td class="BlueBold_14"><div align="center">dept</div></td>
      <td class="BlueBold_14"><div align="center">order*</div></td>
      <td class="BlueBold_14"><div align="center">cost</div></td>
      <td class="BlueBold_14"><div align="center">Amt Paid</div></td>
      <td class="BlueBold_14"><div align="center">action</div></td>
      <td class="BlueBold_14"><div align="center">comments</div></td>
    </tr>
    <?php do { ?>
    <tr>
<?php 	if($row_orders['pt'] == 'I'){  

			mysql_select_db($database_swmisconn, $swmisconn);
			$query_medsched = "SELECT i.id, i.visitid, i.orderid, i.med, i.status, i.unit, i.nunits, i.schedt, i.comments from ipmeds i where i.orderid = '".$row_orders['ordid']."' and (i.status = 'given' || length(i.comments) >0)";
			$medsched = mysql_query($query_medsched, $swmisconn) or die(mysql_error());
			$row_medsched = mysql_fetch_assoc($medsched);
			$totalRows_medsched = mysql_num_rows($medsched);
?>
      <td class="BlueBold_16" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;FeeID: <?php echo $row_orders['feeid']; ?>&#10;PatientType: <?php echo $row_orders['pt']; ?>&#10; Red Number = number of Med Sched comments"><span class="flagWhiteonRed"><?php echo $totalRows_medsched ?></span></td>
      <td class="BlueBold_16"><a href="javascript:void(0)" onclick="MM_openBrWindow('RxShowInPatMedOrd.php?vid=<?php echo $row_orders['visitid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=950,height=500')"> <?php echo $row_orders['ordid']; ?>-<?php echo $row_orders['pt']; ?></a></td>

	<?php } else { ?>
      <td>&nbsp;&nbsp;</td>  
      <td class="BlueBold_16" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;FeeID: <?php echo $row_orders['feeid']; ?>&#10;PatientType: <?php echo $row_orders['pt']; ?>"><?php echo $row_orders['ordid']; ?>-<?php echo $row_orders['pt']; ?></a></td>
	
	<?php }?>
  
     <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>

<?php if($row_orders['billstatus'] == 'paylater') { ?>
      <td title="BillStatus: <?php echo $row_orders['billstatus']; ?>"><span class="flagBlackonYellow">PL</span><?php echo $row_orders['status']; ?></td>
<?php } else { ?>	
      <td title="BillStatus: <?php echo $row_orders['billstatus']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_orders['status']; ?></td>
<?php  } ?>	  

      <td><?php echo $row_orders['urgency']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
      <td><?php echo $row_orders['dept']; ?></td>
      <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit: <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['item']; ?></td>
      <td title="ofee displayed&#10;AmtDue: <?php echo number_format($row_orders['amtdue']); ?>&#10; AmtPaid: <?php echo number_format($row_orders['amtpaid']); ?>"><div align="center"><?php echo number_format($row_orders['ofee']); ?></div></td>
      <td><div align="center"><strong><?php echo number_format($row_orders['amtpaid']); ?></strong></div></td>
	  
<?php if($row_orders['status'] == 'RxOrdered'){ ?>
      <td><div align="center">
          <?php  if( allow(72,2) == 1){ ?>
      			<a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmCost.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Cost</a>
<?php }?>
          <?php  if( allow(72,3) == 1){ ?>
	          <a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmEdit.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Edit</a>
						<a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmRefer.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Refer</a>
        </div></td>
<?php }?>
<?php } elseif($row_orders['status'] == 'RxCosted' && allow(72,2) == 1) { ?>
              <td><div align="center">
      <?php  if($row_orders['billstatus'] == 'paylater' && allow(72,2) == 1 ) {?>
              <a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmDispense.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Disp</a>
      <?php  } 
						if(allow(72,3) == 1 ) {?>      
	         	 <a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmEdit.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Edit</a>
					 <a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmRefer.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Refer</a>
      </div></td>
<?php } ?>		
<?php } elseif($row_orders['status'] == 'RxPaid'){ ?>
      <td><div align="center"><a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmDispense.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Disp</a>
	  </div></td>

<?php	} elseif($row_orders['status'] == 'RxDispensed'){ ?>
	  <td><div align="center">&nbsp;
	  </div></td>

<?php } elseif($row_orders['status'] == 'RxReferred'){ // go back to ordered?>
      <td><div align="center"><a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmOrd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=400')">ReOrder</a>&nbsp;&nbsp;&nbsp;
     </div></td>

<?php 	//		} elseif($row_orders['status'] == 'RxCancelled'){ // go back to ordered?>


<?php 			} else { ?>
		<td>&nbsp;</td>
<?php  } ?>	
<!--Optional actions
--><!--<a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmCancel.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Canc</a>-->
<!--<a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmRefund.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=350')">Refund</a>-->
 <!--      <td><div align="center"><a href="javascript:void(0)" onclick="MM_openBrWindow('RxPharmOrd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&ordid=<?php echo $row_orders['ordid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=400')">ReOrder</a>&nbsp;&nbsp;&nbsp;</td>-->
 
      <td><?php echo $row_orders['comments']; ?></td>
    </tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
	<tr>
	    <td align="right">&nbsp;</td>
		  <td>Orders: <?php echo $totalRows_orders ?></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
		<td nowrap="nowrap"><div align="center"><a href="RxOrdersList.php" class="btngradblu">Re-Select</a></div></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td colspan="2" align="right">
		  <div align="right">
		    <?php //if(allow(10,3) == 1) { ?>
		    <?php  //} ?>	  
          </div></td>
	    <td align="right">&nbsp;</td>
	</tr>
  </form>
</table>
<?php $chkcash = "checkbox-cash.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php ///require_once($chkcash); ?></td>
	</tr>
</table>
<?php
mysql_free_result($orders);
?>
<?php } //if order
	else {
?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders</td>
		<td nowrap="nowrap"><a href="RxOrdersList.php" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php } //if No order ?>

</body>
</html>
