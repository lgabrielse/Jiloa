<?php  $pt = "Cashier Partial Payment"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //include('PatPermView.php'); ?>

<?php $ordnum = '';
	if (!isset($_GET['ordid'])) {
 		//echo("You didn't select any orders.");
		$ordnum = '0';
	  }
	else {
		$ordnum = $_GET['ordid'];
	}
?>
<?php
if (isset($ordnum) and $ordnum != '0') { 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.rate, o.ratereason, o.amtpaid, o.status, o.urgency, o.doctor, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename, f.unit, f.descr, o.amtdue FROM orders o join fee f on o.feeid = f.id Where o.id = ($ordnum) ORDER BY entrydt ASC";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
$totaldue = 0;
}
?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_PayBy = "SELECT name FROM dropdownlist WHERE list = 'PayBy' ORDER BY seq ASC";
$PayBy = mysql_query($query_PayBy, $swmisconn) or die(mysql_error());
$row_PayBy = mysql_fetch_assoc($PayBy);
$totalRows_PayBy = mysql_num_rows($PayBy);
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
     .right { text-align: right;
	          background-color: #FFFDDA }
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

<?php if(!isset($totalRows_orders )) {?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders selected </td>
		<td nowrap="nowrap"><a href="CashShowAll.php?mrn=<?php echo $_SESSION['mrn']; ?>" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php }
	else {
?>




<div>
  <div align="center" class="BlueBold_18"><!--AnyOrder: <?php //echo $ordnum ?> -->   Cashier confirms receipt of payment </div>
</div>
<?php if (isset($ordnum) and $ordnum != '0') { ?>

<table align="center">
  <tr>
    <td>
 	 <form id="form1" name="form1" method="post" action="CashPaidPP.php">
		<table align="center" bgcolor="#BCFACC">
			<tr>
			  <td class="BlueBold_14"><div align="center">Order#*</div></td>
			  <td class="BlueBold_14"><div align="center">Entrydt*</div></td>
			  <td class="BlueBold_14"><div align="center">Rate*</div></td>
			  <td class="BlueBold_14"><div align="center">Status</div></td>
			  <td class="BlueBold_14"><div align="center">Urgency</div></td>
			  <td class="BlueBold_14"><div align="center">Doctor</div></td>
			  <td class="BlueBold_14"><div align="center">Dept</div></td>
			  <td class="BlueBold_14"><div align="center">Order*</div></td>
			  <td class="BlueBold_14"><div align="center">Amt Due</div></td>
			  <td class="BlueBold_14">Amt Paid </td>
			  <td class="BlueBold_14"><div align="center">Unpaid</div></td>
			  <td class="BlueBold_14"><div align="center">Paid Now </div></td>
			  <td class="BlueBold_14"><div align="center">Comments</div></td>
			</tr>
			<?php do { ?>
			<tr>
			  <td title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?> &#10;FeeID: <?php echo $row_orders['feeid']; ?> "><?php echo $row_orders['ordid']; ?></td>
			  <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
			  <td title="Rate = % of normal fee.&#10;Rate Reason: <?php echo $row_orders['ratereason']; ?>"><div align="center"><?php echo $row_orders['rate']; ?>%</div></td>
			  <td><?php echo $row_orders['status']; ?></td>
			  <td><?php echo $row_orders['urgency']; ?></td>
			  <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
			  <td><?php echo $row_orders['dept']; ?></td>
			  <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit = <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['feename']; ?></td>
			  
			  <td><input class="right" name="amtdue" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtdue']); ?>" size="6" /></td>
			  <td><input class="right" name="amtpaid" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtpaid']); ?>" size="6" /></td>
			  <td><input class="right" name="unpaid" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtdue'] - $row_orders['amtpaid']); ?>" size="6" /></td>
			  <td><input name="paid" type="text" id="paid" size="6" /></td>
			  <td><?php echo $row_orders['comments']; ?></td>
			</tr>
			<?php
			  
			 } while ($row_orders = mysql_fetch_assoc($orders)); ?>
			<tr>
				<td align="right">&nbsp;</td>
			    <td align="right"><a href="CashShowAll.php?mrn=<?php echo $_SESSION['mrn']; ?>">Re-Select</a></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><input name="ordnum" type="hidden" id="ordnum" value="<?php echo $ordnum; ?>" /></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><?php echo $_SESSION['user']; ?></td>
			    <td align="right">&nbsp;</td>
			    <td colspan="3" align="right">Enter Amount Received: </td>
			    <td colspan="2" align="right"><select name="nbc" id="nbc">
        <?php do { ?>
	        <option value="<?php echo $row_PayBy['name']?>"><?php echo $row_PayBy['name']?></option>
        <?php } while ($row_PayBy = mysql_fetch_assoc($PayBy));
			  $rows = mysql_num_rows($PayBy);
			  if($rows > 0) {
				  mysql_data_seek($PayBy, 0);
				  $row_PayBy = mysql_fetch_assoc($PayBy);
			  } ?>
                </select></td>
			    <td align="right"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Partial Payment Received"/></td>
			</tr>
			<tr>
				<td colspan="15" bgcolor="#FFFFFF"><div align="right">On next page (Print Receipt), click on THANK YOU to exit to Cashier page.</div></td>
			</tr>
		</table>
  	  </form>
	</td>
  </tr>
</table>
<?php
mysql_free_result($orders);
?>

<?php }
}  ?>

</body>
</html>
