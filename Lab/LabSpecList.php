<?php  $pt = "Lab Specimen List"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
	$colname_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
 $_SESSION['mrn'] = $colname_mrn;
 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, p.dob, p.gender, p.ethnicgroup, o.id ordid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, o.urgency, o.doctor, o.status, o.billstatus, o.rate, o.comments, o.amtdue, o.amtpaid, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, o.feeid, o.visitid, v.id, v.pat_type, v.location, f.dept, f.section, f.name, f.descr, s.name spname FROM `patperm` p join orders o on p.medrecnum = o.medrecnum join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id join specimens s on f.specid = s.id WHERE f.dept = 'Laboratory' AND  (o.status like '%ordered%' or o.status like '%Recollect%') AND o.medrecnum = $colname_mrn";
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
<?php $patview = "../Patient/PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>


<?php if (isset($_GET['mrn']) AND $totalRows_orders > 0) {?>

<table align="center" bgcolor="#F8FDCE">
  <form id="form1" name="form1" method="post" action="LabSpecConfirmed.php" >
  <tr>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Ord #</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order Date</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Status</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Doctor</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Paid</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Pat. Type</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Location</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Dept</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Section</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Specimen</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11">
       <div align="center">Urg</div>
    </td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order*</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Chk</div></td>
    <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Comment</div></td>
  </tr>

    <?php do { 
	  $bkg = "#F8FDCE";
	if ($row_orders['paid'] != 'Y') {
	  $bkg = "FFCC00";
	} ?>
	
    <tr>
      <td class="BlackBold_12" title="VisitID: <?php echo $row_orders['visitid']; ?>
Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['ordid']; ?></td>
      <td bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?>&#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
      <td><?php echo $row_orders['status']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>

	<?php if($row_orders['rate'] == '0') { ?>
      <td nowrap="nowrap" class="BlackBold_11" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="right">Rt:0</div></td>

	<?php } elseif($row_orders['paid'] == 'N' && $row_orders['billstatus'] == 'paylater') {?>
      <td bgcolor="<?php echo $bkg ?>" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="center"><span class="flagBlackonYellow">PL</span></div></td>

	<?php } else {?>
      <td bgcolor="<?php echo $bkg ?>" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="center"><?php echo $row_orders['paid']; ?></div></td>
	<?php } ?>
      <td title="<?php echo $row_orders['visitid']; ?>"><?php echo $row_orders['pat_type']; ?></td>
      <td><?php echo $row_orders['location']; ?></td>
      <td><?php echo $row_orders['dept']; ?></td>
      <td><?php echo $row_orders['section']; ?></td>
      <!--<td><?php echo $row_orders['urgency']; ?></td>-->

            <td class="BlackBold_11"><?php echo $patage ; ?></td>
<?php if ($row_orders['urgency'] == 'STAT') {   ?>
            <td class="flagWhiteonRed"><?php echo $row_orders['urgency']; ?></td>
<?php } ELSE if ($row_orders['urgency'] == 'ASAP') {   ?>
            <td class="flagBlackonOrange"><?php echo $row_orders['urgency']; ?></td>
<?php } ELSE { ?>
            <td class="BlackBold_11"><?php echo $row_orders['urgency']; ?></td>
<?php } ?>

      <td bgcolor="#FFFFFF" title="Order ID: <?php echo $row_orders['id']; ?>
<?php echo $row_orders['descr']; ?>
Fee ID: <?php echo $row_orders['feeid']; ?>
Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['spname'] ?></td>
      <td  bgcolor="#f5f5f5" class="BlueBold_1414" title="Order ID: <?php echo $row_orders['id']; ?>&#10;<?php echo $row_orders['descr']; ?>&#10;Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['name'] ?></td>

<!--Remove checkbox if not paid-->
<?php if($row_orders['paid'] =='Y' || $row_orders['billstatus'] == 'paylater' || $row_orders['rate'] =='0')  { ?>	
      <td><input type="checkbox" name="anyorder[]" value="<?php echo $row_orders['ordid']; ?>" /></td>
<?php } else {?>
      <td>&nbsp;</td>
<?php } ?>

      <td><?php echo $row_orders['comments']; ?></td>
    </tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
	
<?php if(allow(24,3) == 1) { ?>
	<tr>
		<td colspan="13" align="right"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Verify Selection"/></td>
	</tr>
<?php }?>
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
<?php }
	else {
?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders</td>
		<td nowrap="nowrap"><a href="LabSearchPat.php" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php } ?>

</body>
</html>
