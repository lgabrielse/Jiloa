<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p align="center" class="BlackBold_16"><span class="nav"><a href="../Reports/ReportsMenu.php">Reports Menu</a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="BlackBold_24">Validation Menu</span> </p>
<table width="60%" border="1" align="center" class="tablebc">
  <tr>
    <td><a href="Val1_paid=due.php">VAL_1</a></td>
    <td>Orders where amtdue &gt; 0 and amtpaid &gt; 0 and status not Refund and status does not contain 'Paid' - should be 0</td>
  </tr>
  <tr>
    <td><a href="Val2_PartPaid.php">Val_2 -</a> </td>
    <td>Orders where amtdue &gt; 0 and amtpaid &gt; 0 and amtdue &lt;&gt; amt paid (should be partial payment)</td>
  </tr>
  <tr>
    <td><a href="Val3_PaidMoreThanDue.php">Val_3 -</a> </td>
    <td>Orders where amtdue = 0 and amtpaid &gt; 0  - should be 0</td>
  </tr>
  <tr>
    <td><a href="Val4_ShouldBeDue.php">Val_4 -</a></td>
    <td> Orders where amtpaid = 0 and amtdue  &gt; 0 and status &lt;&gt; 'Due' - should be 0</td>
  </tr>
  <tr>
    <td><a href="Val5_Refunded.php">Val_5 -</a></td>
    <td> Refunded - should have amtdue = 0 (???)</td>
  </tr>
  <tr>
    <td><a href="Val6_RefundStatus.php">Val_6 - </a></td>
    <td>Orders where Status = Refund - should be 0 </td>
  </tr>
  <tr>
    <td><a href="Val7_OfeeNot=Due.php">Val_7 - </a></td>
    <td>Orders where amtdue &lt;&gt; ofee  and ofee &gt; 0   - should be 0</td>
  </tr>
  <tr>
    <td><a href="Val8_OfeeStatusOK.php">Val_8 - </a></td>
    <td>Orders where ofee &gt; 0 and amtpaid = 0 and bill status not Rx Costed or RxReferred</td>
  </tr>
  <tr>
    <td><a href="Val9_UnPaid.php">Val_9 - </a></td>
    <td>orders where amtdue &gt; 0 and amtpaid = 0 and process status &lt;&gt; ordered  - how to capture unpaid???</td>
  </tr>
  <tr>
    <td>Val_0 - </td>
    <td>RcptOrder amtpaid that need to be set = to receipt amt <br />
Only in case of partial payment<br />
Receipt for partial payment only has one order on it.<br />
Amt Paid in RcptOrd record needs to show amt of transaction not cumulative paid</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
