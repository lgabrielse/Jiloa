<?php  $pt = "Specimen Collection"; ?>
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
$query_orders = "SELECT o.id ordid, o.medrecnum, o.visitid, o.feeid, o.rate, o.ratereason, o.status, o.urgency, o.doctor, o.comments, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, f.name, f.unit, f.descr, f.fee, f.fee*(o.rate/100) as amtdue, s.name spname FROM orders o join fee f on o.feeid = f.id join specimens s on f.specid = s.id Where o.id in ($ordlist) ORDER BY entrydt ASC";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
$totaldue = 0;
}
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
<?php $patview = "../Patient/PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>


<?php if (isset($ordlist) and $ordlist != '0') { 
	$total = 0; ?>

<div class="BlueBold_16">
  <div align="center">Ordered Collection(s) for patient</div>
</div>
<table align="center">
  <tr>
    <td>
 	 <form id="form1" name="form1" method="post" action="LabSpecRecd.php">
		<table align="center" bgcolor="#BCFACC">
			<tr>
			  <td class="BlueBold_14"><div align="center">Ord # *</div></td>
			  <td class="BlueBold_14"><div align="center">Entrydt*</div></td>
			  <td class="BlueBold_14"><div align="center">Status</div></td>
			  <td class="BlueBold_14"><div align="center">Urgency</div></td>
			  <td class="BlueBold_14"><div align="center">Doctor</div></td>
			  <td class="BlueBold_14"><div align="center">Dept</div></td>
			  <td class="BlueBold_14"><div align="center">section</div></td>
			  <td class="BlueBold_14"><div align="center">Specimen</div></td>
			  <td class="BlueBold_14"><div align="center">Order*</div></td>
			  <td class="BlueBold_14"><div align="center">Comments</div></td>
			</tr>
			<?php do { ?>
			<tr>
			  <td title="MRN: <?php echo $row_orders['medrecnum']; ?>
VisitID: <?php echo $row_orders['visitid']; ?>
OrderNum: <?php echo $row_orders['ordid']; ?>
Order By: <?php echo $row_orders['entryby']; ?>
FeeID:<?php echo $row_orders['feeid']; ?>"><?php echo $row_orders['ordid']; ?></td>
			  <td bgcolor="#FFFFFF" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;OrderNum: <?php echo $row_orders['ordid']; ?>&#10;Order By: <?php echo $row_orders['entryby']; ?>&#10;FeeID:<?php echo $row_orders['feeid']; ?>"><?php echo $row_orders['entrydt']; ?></td>
			  <td><?php echo $row_orders['status']; ?></td>
			  <td><?php echo $row_orders['urgency']; ?></td>
			  <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
			  <td><?php echo $row_orders['dept']; ?></td>
			  <td><?php echo $row_orders['section']; ?></td>
			  <td bgcolor="#FFFFFF"><?php echo $row_orders['spname']; ?></td>
			  <td bgcolor="#F5F5F5" title="Description: : <?php echo $row_orders['descr']; ?>"><?php echo $row_orders['name']; ?></td>
			  <td><?php echo $row_orders['comments']; ?></td>
			</tr>
			<?php
			  $total = $total + 1;
			 } while ($row_orders = mysql_fetch_assoc($orders)); ?>
			<tr>
			  <td align="right">&nbsp;</td>
			  <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td colspan="2" align="right">NUMBER OF ORDERS:</td>
			    <td><input name="text" type="text" value="<?php echo number_format($total); ?>" size="3" readonly="readonly" /></td>
			</tr>
			<tr>
			  <td align="right">&nbsp;</td>
			  <td align="right"><a href="LabSpecList.php?mrn=<?php echo $_SESSION['mrn']; ?>">Re-Select</a></td>
			    <td align="right"><input name="ordlist" type="hidden" id="ordlist" value="<?php echo $ordlist; ?>" /></td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td>
			    <td align="right">&nbsp;</td> <?php //echo $ordlist; ?>
			    <td align="right"><input name="mrn" type="hidden" value="<?php echo $_SESSION['mrn'];?>" /></td>
			    <td align="right"><?php echo $_SESSION['user']; ?></td>
				<td align="right"><input name="user" type="hidden" value="<?php echo $_SESSION['user'];?>" /></td>
			    <td align="right"><input type="submit" name="Submit" value="Specimen Received"/></td>
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
	else {
?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders selected </td>
		<td nowrap="nowrap"><a href="LabSpecList.php?mrn=<?php echo $_SESSION['mrn']; ?>" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php } ?>
</body>
</html>
