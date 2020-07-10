<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select DATE_FORMAT(o.entrydt,'%Y-%m-%d') entrydt, o.medrecnum, o.visitid, o.id ordid, o. ofee, o.amtdue oamtdue, o.amtpaid oamtpaid, o.billstatus, r.status, r.amtdue roamtdue, r.amtpaid roamtpaid from orders o join rcptord r on o.id = r.ordid where o.amtdue > 0 and o.amtPaid > 0 and instr(o.billstatus,'Paid') = 0 and o.billstatus NOT LIKE 'Refund%' and o.amtdue = o.amtpaid ";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Val paid = due</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<p align="center"><a href="ValidationMenu.php">Menu</a> - Orders where amtdue &gt; 0 and amtpaid &gt; 0 andand status not Refund and status does not contain 'Paid' </p>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
  <tr>
    <td class="BlueBold_1212">Order Date</td>
    <td class="BlueBold_1212">medrecnum</td>
    <td class="BlueBold_1212">visitid</td>
    <td class="BlueBold_1212">ordid</td>
    <td class="BlueBold_1212">ofee</td>
    <td class="BlueBold_1212">amtdue</td>
    <td class="BlueBold_1212">amtpaid</td>
    <td class="BlueBold_1212">billstatus</td>
    <td class="BlueBold_1212">ro-status</td>
    <td class="BlueBold_1212">ro-amtdue</td>
    <td class="BlueBold_1212">ro-amtpaid</td>
  </tr>
  <?php do { ?>
    <tr>
      <td class="BlueBold_1212"><?php echo $row_orders['entrydt']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['medrecnum']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['visitid']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['ordid']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['ofee']; ?></td>
      <td bgcolor="#FFFDDA" class="BlueBold_1212"><?php echo $row_orders['oamtdue']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['oamtpaid']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['billstatus']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['status']; ?></td>
      <td bgcolor="#FFFDDA" class="BlueBold_1212"><?php echo $row_orders['roamtdue']; ?></td>
      <td class="BlueBold_1212"><?php echo $row_orders['roamtpaid']; ?></td>
    </tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
</table>

</body>
</html>
<?php
mysql_free_result($orders);
?>
