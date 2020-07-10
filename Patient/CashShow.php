<?php ob_start(); ?>
<?php  $pt = "Cashier Orders List"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 
	$colname_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
	$colname_billstatus = "Due";
if (isset($_GET['billstatus'])) {
  $colname_billstatus = (get_magic_quotes_gpc()) ? $_GET['billstatus'] : addslashes($_GET['billstatus']);
}
if($colname_billstatus == 'Refund') { 
mysql_select_db($database_swmisconn, $swmisconn);  
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.feeid, o.rate, o.ratereason, o.billstatus, o.status, o.urgency, o.doctor, o.comments, o.entryby, o.amtpaid, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename, f.unit, f.descr, f.fee, ddl.name, amtdue FROM orders o join fee f on o.feeid = f.id join dropdownlist ddl on o.ratereason = ddl.id WHERE billstatus = 'Refund' and o.rate <> 0  AND o.amtpaid > 0  AND o.medrecnum = ".$colname_mrn."";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
}
else {
/*
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.rate, o.ratereason, o.status, o.urgency, o.doctor,o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt,
f.dept, f.section, 
CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename,
CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as amtdue,
f.unit, f.descr, f.fee
FROM orders o 
join fee f on o.feeid = f.id 
WHERE
o.medrecnum = $colname_mrn";	*/
mysql_select_db($database_swmisconn, $swmisconn);  //CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as o.amtdue,
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.feeid, o.rate, o.ratereason, o.billstatus, o.status, o.urgency, o.doctor, o.amtpaid, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename, o.amtdue, f.unit, f.descr, f.fee, ddl.name FROM orders o join fee f on o.feeid = f.id join dropdownlist ddl on o.ratereason = ddl.id WHERE billstatus IN ('Due', 'paylater', 'PartPaid') AND o.medrecnum = $colname_mrn";	
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
	
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
} ?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
     .right { text-align: right; }
</style>
</head>

<body>

<!-- Display PATIENT PERMANENT Data  -->
<?php $patview = "PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>
<?php
  if($colname_billstatus == 'PartPaid') {
     $insertGoTo = "CashshowAll.php?mrn=".$row_orders['medrecnum']."&billstatus=".$row_orders['billstatus'];  
  header(sprintf("Location: %s", $insertGoTo));
  }
?>

<?php if($colname_billstatus == 'Refund') { ?>

<!-- //status Refund  ****************************************************************************** -->
<div>
  <div align="center" class="BlueBold_18"> REFUND </div>
</div>
<?php if (isset($_GET['mrn']) AND $totalRows_orders > 0) {?>

<table align="center" bgcolor="#DDEEFF">
  <form id="form1" name="form1" method="post" action="CashShowTot.php?Status=Refund" >
    <tr>
      <td class="BlueBold_14"><div align="center">Order#*</div></td>
      <td class="BlueBold_14"><div align="center">entrydt*</div></td>
      <td class="BlueBold_14"><div align="center">rate*</div></td>
      <td class="BlueBold_14"><div align="center">status</div></td>
      <td class="BlueBold_14"><div align="center">urgency</div></td>
      <td class="BlueBold_14"><div align="center">doctor</div></td>
      <td class="BlueBold_14"><div align="center">dept</div></td>
      <td class="BlueBold_14"><div align="center">order*</div></td>
      <td class="BlueBold_14"><div align="center">fee</div></td>
      <td class="BlueBold_14"><div align="center">chk</div></td>
      <td class="BlueBold_14"><div align="center">comments</div></td>
    </tr>
    <?php do { ?>
    <tr>
      <td class="BlueBold_16" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;FeeID: <?php echo $row_orders['feeid']; ?>"><?php echo $row_orders['ordid']; ?></td>
      <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
      <td title="Rate = % of normal fee.&#10;Rate Reason: <?php echo $row_orders['name']; ?>"><div align="center"><?php echo $row_orders['rate']; ?>%</div></td>
      <td title="billstatus: <?php echo $row_orders['billstatus']; ?>"><?php echo $row_orders['status']; ?>(<?php echo $row_orders['billstatus']; ?>)</td>
      <td><?php echo $row_orders['urgency']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
      <td><?php echo $row_orders['dept']; ?></td>
      <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit: <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['feename']; ?></td>
      <td><input class="right" name="amtdue" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtpaid'],0); ?>" size="10" /></td>
      <td><input type="checkbox" name="anyorder[]" value="<?php echo $row_orders['ordid']; ?>" /></td>
      <td><?php echo $row_orders['comments']; ?></td>
    </tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
	<tr>
		<td align="right">Orders:</td>
	    <td align="right"><div align="left"><?php echo $totalRows_orders ?></div></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
		<td align="center" nowrap="nowrap"><a href="CashSearch.php" class="btngradblu">Re-Select</a></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td colspan="2" align="right">
		<?php if(allow(10,3) == 1) { ?>
			<input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="summarize"/>
		<?php } ?>	  </td>
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
		<td nowrap="nowrap"><a href="CashSearch.php" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php } //if No order ?>
<!--   *******************************************End of Refund********************************************************************* -->
<?php }
 else { ?> <!-- get status = order-->
<div>
  <div align="center"><span class="BlueBold_18">Items to pay at Cashier </span>&nbsp;&nbsp;&nbsp;<span class="nav"> <a href="CashShowAll.php?mrn=<?php echo $colname_mrn ?> ">Show All</a></span> </div>
</div>
<?php if (isset($_GET['mrn']) AND $totalRows_orders > 0) {?>

<table align="center" bgcolor="#F8FDCE">
  <form id="form1" name="form1" method="post" action="CashShowTot.php?Status=other" >
    <tr>
      <td class="BlueBold_14"><div align="center">Order#*</div></td>
      <td class="BlueBold_14"><div align="center">entrydt*</div></td>
      <td class="BlueBold_14"><div align="center">rate*</div></td>
      <td class="BlueBold_14"><div align="center">status</div></td>
      <td class="BlueBold_14"><div align="center">urgency</div></td>
      <td class="BlueBold_14"><div align="center">doctor</div></td>
      <td class="BlueBold_14"><div align="center">dept</div></td>
      <td class="BlueBold_14"><div align="center">order*</div></td>
      <td class="BlueBold_14"><div align="center">fee</div></td>
      <td class="BlueBold_14"><div align="center">chk</div></td>
      <td class="BlueBold_14"><div align="center">comments</div></td>
    </tr>
    <?php do { ?>
    <tr>
      <td class="BlueBold_16" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;FeeID: <?php echo $row_orders['feeid']; ?>"><?php echo $row_orders['ordid']; ?></td>
      <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
      <td title="Rate = % of normal fee.&#10;Rate Reason: <?php echo $row_orders['name']; ?>"><div align="center"><?php echo $row_orders['rate']; ?>%</div></td>
      <td title="billstatus: <?php echo $row_orders['billstatus']; ?>"><?php echo $row_orders['status']; ?>(<?php echo $row_orders['billstatus']; ?>)</td>
      <td><?php echo $row_orders['urgency']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
      <td><?php echo $row_orders['dept']; ?></td>
      <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit: <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['feename']; ?></td>
      <td><input class="right" name="amtdue" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtdue'],0); ?>" size="10" /></td>
      <td><input type="checkbox" name="anyorder[]" value="<?php echo $row_orders['ordid']; ?>" /></td>
      <td><?php echo $row_orders['comments']; ?></td>
    </tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
	<tr>
		<td align="right">Orders:</td>
	    <td align="right"><div align="left"><?php echo $totalRows_orders ?></div></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
		<td nowrap="nowrap"><div align="center"><a href="CashSearch.php" class="btngradblu">Re-Select</a></div></td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td colspan="2" align="right">
		<?php if(allow(10,3) == 1) { ?>
			<input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="summarize"/>
		<?php } ?>	
	  </td>
	    <td align="right">&nbsp;</td>
	</tr>
  </form>
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
		<td nowrap="nowrap"><a href="CashSearch.php" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php ob_end_flush();?>
<?php } //if No order ?>
<?php } //if Not status ?>

</body>
</html>
