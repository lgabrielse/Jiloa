<?php  $pt = "Cashier Payment Total"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //include('PatPermView.php'); ?>

<?php $ordlist = '';
	if (!isset($_POST['anyorder'])) {
 		//echo("You didn't select any orders.");
		$ordlist = '0';
	  }

	else {
	   $order = $_POST['anyorder'];
    $N = count($order);
		for($i=0; $i < $N; $i++) {
		$ordlist = $ordlist . $order[$i].', ';
		//  echo($order[$i])."    ";
		}
	$ordlist = substr($ordlist, 0, -2);
	$_SESSION['OrderList'] = $order;
	//echo $ordlist;
	}
?>
<?php
if (isset($ordlist) and $ordlist != '0') { 
mysql_select_db($database_swmisconn, $swmisconn);
//$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.rate, o.ratereason, o.status, o.billstatus, o.urgency, o.doctor, o.comments, o.amtpaid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename, f.unit, f.descr, CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as amtdue FROM orders o join fee f on o.feeid = f.id Where o.id in ($ordlist) ORDER BY entrydt ASC";
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.item, o.quant, o.ofee, o.feeid, o.rate, o.ratereason, o.status, o.billstatus, o.urgency, o.doctor, o.comments, o.amtpaid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, CASE WHEN o.item is NULL THEN f.name ELSE o.item END as feename, f.unit, f.descr, o.amtdue FROM orders o join fee f on o.feeid = f.id Where o.id in ($ordlist) ORDER BY entrydt ASC";

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
 
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_NoDepPayBy = "SELECT name FROM dropdownlist WHERE list = 'PayBy'and name != 'deposit' ORDER BY seq ASC";
$NoDepPayBy = mysql_query($query_NoDepPayBy, $swmisconn) or die(mysql_error());
$row_NoDepPayBy = mysql_fetch_assoc($NoDepPayBy);
$totalRows_NoDepPayBy = mysql_num_rows($NoDepPayBy);
 ?>


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
	<?php //exit("ordlist: ".$ordlist) ;?>
 <?php $_SESSION['totaldue'] = 0;?>
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
	<?php 	if($_GET['Status'] == 'Refund'){?>
		<td nowrap="nowrap"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=Refund" class="nav">Re-Select</a></td>
	<?php 	} else { ?>
		<td nowrap="nowrap"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=order" class="nav">Re-Select</a></td>
	<?php  }?>
	</tr>
</table>
<?php }
	else {
?>



<?php if(isset($_GET['Status']) and $_GET['Status'] == 'Refund') {?>
<div>
  <div align="center" class="BlueBold_18"><!--AnyOrder: <?php // echo $ordlist ?> -->   Confirm REFUND</div>
</div>
<?php if (isset($ordlist) and $ordlist != '0') { ?>

<table align="center">
 	 <form id="form1" name="form1" method="post" action="CashPaid.php?Status=Refund">
  <tr>
    <td>
		<table align="center" bgcolor="#DDEEFF">
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
			  <td class="BlueBold_14"><div align="center">Comments</div></td>
			</tr>
			<?php do { ?>
			<tr>
			  <td title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?> &#10;FeeID: <?php echo $row_orders['feeid']; ?> "><?php echo $row_orders['ordid']; ?></td>
			  <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
			  <td title="Rate = % of normal fee.&#10;Rate Reason: <?php echo $row_orders['ratereason']; ?>"><div align="center"><?php echo $row_orders['rate']; ?>%</div></td>
			  <td title="BillStatus: <?php echo $row_orders['billstatus']; ?>"><?php echo $row_orders['status']; ?></td>
			  <td><?php echo $row_orders['urgency']; ?></td>
			  <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
			  <td><?php echo $row_orders['dept']; ?></td>
			  <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit = <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['feename']; ?>   <?php echo $row_orders['item']; ?></td>
			  
			  <td><input class="right" name="amtdue2" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtpaid']); ?>" size="10" /></td>

			  <td><?php echo $row_orders['comments']; ?></td>
			  <td><input name="selorder[]" type="hidden" value="<?php echo $row_orders['ordid'] ?>" />
  				<input name="medrecnum" type="hidden" value="<?php echo $row_orders['medrecnum']?>" />
				<input name="visitid" type="hidden" value="<?php echo $row_orders['visitid'] ?>" />		
				<input name="amtpaid" type="hidden" value="<?php echo number_format($row_orders['amtpaid']) ?>" />			  </td>
			</tr>
			<?php
			  $totaldue = $totaldue + $row_orders['amtpaid'];
			 } while ($row_orders = mysql_fetch_assoc($orders)); ?>
          
			<tr>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">TOTAL:</td>
			    <td ><input class="right" name="totdue2" readonly="readonly" type="text" value="<?php echo number_format($totaldue); ?>" size="10" /></td>
			    <td align="right"></td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
			    <td align="right"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=Refund">Re-Select</a></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><input name="ordlist" type="text" id="ordlist" value="<?php echo $ordlist; ?>" /></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><?php echo $_SESSION['user']; ?></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><select name="nbc" id="nbc">
                <?php do { ?>
                <option value="<?php echo $row_PayBy['name']?>"><?php echo $row_PayBy['name']?></option>
                <?php } while ($row_PayBy = mysql_fetch_assoc($PayBy));
			  $rows = mysql_num_rows($PayBy);
			  if($rows > 0) {
				  mysql_data_seek($PayBy, 0);
				  $row_PayBy = mysql_fetch_assoc($PayBy);
			  } ?>
              </select></td>
	    <td align="right"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Payment Refunded"/></td>
			    <td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="12" bgcolor="#FFFFFF"><div align="right">On next page (Print Receipt), click on THANK YOU to exit to Cashier page.</div></td>
			</tr>
		</table>
	</td>
  </tr>
 </form>
</table>
<?php
mysql_free_result($orders);
?>
<?php }
	else {
?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders selected </td>
		<td nowrap="nowrap"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=order" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php } ?>

<!--end of if Refund-->
<?php } 
	else{ // //////////////////////////////////////////////////////////////////////////////////////////////////////// ?>  
<div>
  <div align="center" class="BlueBold_18"><!--AnyOrder: <?php //echo $ordlist ?> -->   Cashier confirms receipt of payment </div>
</div>
<?php if (isset($ordlist) and $ordlist != '0') { ?>
<table align="center">
  <tr>
    <td>
 	 <form id="form1" name="form1" method="post" action="CashPaid.php?Status=other">
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
			  <td class="BlueBold_14"><div align="center">Comments</div></td>
			</tr>
			<?php do { ?>
			<tr>
			  <td title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?> &#10;FeeID: <?php echo $row_orders['feeid']; ?> "><?php echo $row_orders['ordid']; ?></td>
			  <td bgcolor="#FFFFFF" title="Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
			  <td title="Rate = % of normal fee.&#10;Rate Reason: <?php echo $row_orders['ratereason']; ?>"><div align="center"><?php echo $row_orders['rate']; ?>%</div></td>
			  <td title="BillStatus: <?php echo $row_orders['billstatus']; ?>"><?php echo $row_orders['status']; ?></td>
			  <td><?php echo $row_orders['urgency']; ?></td>
			  <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
			  <td><?php echo $row_orders['dept']; ?></td>
			  <td class="BlackBold_12" title="Description: : <?php echo $row_orders['descr']; ?>&#10;Unit = <?php echo $row_orders['unit']; ?>"><?php echo $row_orders['feename']; ?></td>
			  
			  <td><input class="right" name="amtdue" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtdue']); ?>" size="10" /></td>
			  <td><?php echo $row_orders['comments']; ?>
			  <input name="selorder[]" type="hidden" value="<?php echo $row_orders['ordid'] ?>" /></td>
			</tr>
			<?php
			  $totaldue = $totaldue + $row_orders['amtdue'];
			 } while ($row_orders = mysql_fetch_assoc($orders)); ?>
		   <?php $_SESSION['totaldue'] = $totaldue;?>
			<tr>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
             
<!--Display a message that deposit can be used if AcctBal >= TotalDue else isplay a message that deposit cannot be used.  -->           
<?php if($_SESSION['AcctBal'] >= $totaldue ) { ?>
			    <td colspan="3" align="right" bgcolor="#FFFFFF">DEPOSIT CAN BE USED.</td>
	
<?php } else {  ?>
	
			    <td colspan="3" align="right" bgcolor="#FFFF66">DEPOSIT CAN NOT BE USED.</td>
<?php }?>
			    <td align="right">TOTAL:</td>
			  <td><input class="right" name="totdue" readonly="readonly" type="text" value="<?php echo number_format($totaldue); ?>" size="10" /></td>
			    <td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
			    <td align="right"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=order">Re-Select</a></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><input name="ordlist" type="hidden" id="ordlist" value="<?php echo $ordlist; ?>" /></td>
			    <td align="right">&nbsp;</td>
			    <td align="right"><?php echo $_SESSION['user']; ?></td>
			    <td align="right">&nbsp;</td>
             
<!--Display a DDL that includes 'Deposit' if AcctBal >= totaldue, else  display a DDL that does not include 'Deposit' --> 
          
<?php if($_SESSION['AcctBal'] >= $totaldue ) { ?>
			    <td align="right"><select name="nbc" id="nbc">
        <?php do { ?>
	        <option value="<?php echo $row_PayBy['name']?>"><?php echo $row_PayBy['name']?></option>
        <?php } while ($row_PayBy = mysql_fetch_assoc($PayBy));
			  $rows = mysql_num_rows($PayBy);
			  if($rows > 0) {
				  mysql_data_seek($PayBy, 0);
				  $row_PayBy = mysql_fetch_assoc($PayBy);
			  } ?>
              </select></td>
<?php } else { ?>
			    <td align="right"><select name="nbc" id="nbc">
        <?php do { ?>
	        <option value="<?php echo $row_NoDepPayBy['name']?>"><?php echo $row_NoDepPayBy['name']?></option>
        <?php } while ($row_NoDepPayBy = mysql_fetch_assoc($NoDepPayBy));
			  $rows = mysql_num_rows($NoDepPayBy);
			  if($rows > 0) {
				  mysql_data_seek($NoDepPayBy, 0);
				  $row_NoDepPayBy = mysql_fetch_assoc($NoDepPayBy);
			  } ?>
              </select></td>
<?php }?>
			    <td align="right"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Payment Received"/></td>
			    <td align="right"><!--<?php echo $totaldue; ?><?php echo $_SESSION['totaldue']; ?>--></td>
			</tr>
			<tr>
				<td colspan="12" bgcolor="#FFFFFF"><div align="right">On next page (Print Receipt), click on THANK YOU to exit to Cashier page.</div></td>
			</tr>
		</table>
  	  </form>
	</td>
  </tr>
</table>
<?php
mysql_free_result($orders);
?>
<?php } ?>
<?php }
}  // end else not Refund?>

</body>
</html>
