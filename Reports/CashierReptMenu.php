<?php  $pt = "Receipts Report"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="1" align="center" class="tablebc">
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">Cashier Report Menu </td>
    <td>Note:</td>
  </tr>
  <tr>
    <td>1</td>
    <td nowrap="nowrap"><a href="CashReceipts.php">Receipt Transactions Report</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>2</td>
    <td nowrap="nowrap"><a href="CashReceipts2.php">ReceiptTransactions Report2</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>3</td>
    <td><a href="CashierReport3.php">Billing Summary </a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>4</td>
    <td><a href="CashierReport4.php">BillingSummary2</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>5</td>
    <td><!--<a href="AcctBal.php">Deposits</a>--></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>6</td>
    <td><a href="CashReceiptsOld.php">Old Receipts Report</a> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>7</td>
    <td><a href="CashierReport1a.php">Billling Data  Validation</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>7</td>
    <td><a href="CashPaylater.php">Pay Later</a></td>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>
