<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/swmisconn.php'); ?>

<?php
$colname_begin = date('Y-m-d');
if (isset($_POST['begin'])) {
  $colname_begin = (get_magic_quotes_gpc()) ? $_POST['begin'] : addslashes($_POST['begin']);
}
$colname_end = date('Y-m-d');
if (isset($_POST['end'])) {
  $colname_end = (get_magic_quotes_gpc()) ? $_POST['end'] : addslashes($_POST['end']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_BankByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'Bank' group by f.dept order by f.dept";
$BankByDept = mysql_query($query_BankByDept, $swmisconn) or die(mysql_error());
$row_BankByDept = mysql_fetch_assoc($BankByDept);
$totalRows_BankByDept = mysql_num_rows($BankByDept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_POSByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'POS' group by f.dept order by f.dept";
$POSByDept = mysql_query($query_POSByDept, $swmisconn) or die(mysql_error());
$row_POSByDept = mysql_fetch_assoc($POSByDept);
$totalRows_POSByDept = mysql_num_rows($POSByDept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_CashByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'Cash' group by f.dept order by f.dept";
$CashByDept = mysql_query($query_CashByDept, $swmisconn) or die(mysql_error());
$row_CashByDept = mysql_fetch_assoc($CashByDept);
$totalRows_CashByDept = mysql_num_rows($CashByDept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_DepositByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'Deposit' group by f.dept order by f.dept";
$DepositByDept = mysql_query($query_DepositByDept, $swmisconn) or die(mysql_error());
$row_DepositByDept = mysql_fetch_assoc($DepositByDept);
$totalRows_DepositByDept = mysql_num_rows($DepositByDept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_NonCredByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc IN ('Bank','POS','Cash','Deposit') group by f.dept order by f.dept";
$NonCredByDept = mysql_query($query_NonCredByDept, $swmisconn) or die(mysql_error());
$row_NonCredByDept = mysql_fetch_assoc($NonCredByDept);
$totalRows_NonCredByDept = mysql_num_rows($NonCredByDept);


$query_BMCByDeptCred = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'BMCAdmin' group by f.dept order by f.dept";
$BMCByDeptCred = mysql_query($query_BMCByDeptCred, $swmisconn) or die(mysql_error());
$row_BMCByDeptCred = mysql_fetch_assoc($BMCByDeptCred);
$totalRows_BMCByDeptCred = mysql_num_rows($BMCByDeptCred);

$query_PHByDeptCred = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'PHAdmin' group by f.dept order by f.dept";
$PHByDeptCred = mysql_query($query_PHByDeptCred, $swmisconn) or die(mysql_error());
$row_PHByDeptCred = mysql_fetch_assoc($PHByDeptCred);
$totalRows_PHByDeptCred = mysql_num_rows($PHByDeptCred);

$query_CACByDeptCred = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'CAC' group by f.dept order by f.dept";
$CACByDeptCred = mysql_query($query_CACByDeptCred, $swmisconn) or die(mysql_error());
$row_CACByDeptCred = mysql_fetch_assoc($CACByDeptCred);
$totalRows_CACByDeptCred = mysql_num_rows($CACByDeptCred);

$query_DestByDeptCred = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc = 'Destitute' group by f.dept order by f.dept";
$DestByDeptCred = mysql_query($query_DestByDeptCred, $swmisconn) or die(mysql_error());
$row_DestByDeptCred = mysql_fetch_assoc($DestByDeptCred);
$totalRows_DestByDeptCred = mysql_num_rows($DestByDeptCred);

$query_CreditByDeptCred = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc IN('BMCAdmin','PHAdmin','CAC','Destitute') group by f.dept order by f.dept";
$CreditByDeptCred = mysql_query($query_CreditByDeptCred, $swmisconn) or die(mysql_error());
$row_CreditByDeptCred = mysql_fetch_assoc($CreditByDeptCred);
$totalRows_CreditByDeptCred = mysql_num_rows($CreditByDeptCred);

mysql_select_db($database_swmisconn, $swmisconn);
$query_AllByDept = "Select f.dept, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc IN('Bank','POS','Cash','Deposit','BMCAdmin','PHAdmin','CAC','Destitute') group by f.dept order by f.dept";
$AllByDept = mysql_query($query_AllByDept, $swmisconn) or die(mysql_error());
$row_AllByDept = mysql_fetch_assoc($AllByDept);
$totalRows_AllByDept = mysql_num_rows($AllByDept);



mysql_select_db($database_swmisconn, $swmisconn);
$query_BySection = "Select f.dept, f.section, SUM(ro.amtdue) amtdue, SUM(ro.amtpaid) amtpaid, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc IN ('Bank', 'POS', 'Cash') group by f.dept, f.section order by f.dept";
$BySection = mysql_query($query_BySection, $swmisconn) or die(mysql_error());
$row_BySection = mysql_fetch_assoc($BySection);
$totalRows_BySection = mysql_num_rows($BySection);

mysql_select_db($database_swmisconn, $swmisconn);
$query_BySectionCred = "Select f.dept, f.section, SUM(ro.amtdue) amtdue, SUM(ro.amtpaid) amtpaid, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded from orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' and r.nbc IN ('BMCAdmin', 'PHAdmin', 'CAC', 'Destitute') group by f.dept, f.section order by f.dept";
$BySectionCred = mysql_query($query_BySectionCred, $swmisconn) or die(mysql_error());
$row_BySectionCred = mysql_fetch_assoc($BySectionCred);
$totalRows_BySectionCred = mysql_num_rows($BySectionCred);

mysql_select_db($database_swmisconn, $swmisconn);
$query_nbc = "SELECT r.nbc, SUM(ro.amtdue) amtdue, SUM(Case When ro.amtpaid > 0 then ro.amtpaid ELSE 0 End) amtpaid, SUM(CASE WHEN ro.amtpaid < 0 THEN  ro.amtpaid Else 0 END) Refunded FROM orders o join fee f on o.feeid = f.id join rcptord ro on o.id = ro.ordid join receipts r on ro.rcptid = r.id WHERE DATE_FORMAT(r.entrydt,'%Y-%m-%d') >= '".$colname_begin."' and DATE_FORMAT(r.entrydt,'%Y-%m-%d') <= '".$colname_end."' GROUP BY r.nbc ORDER BY r.nbc";
$nbc = mysql_query($query_nbc, $swmisconn) or die(mysql_error());
$row_nbc = mysql_fetch_assoc($nbc);
$totalRows_nbc = mysql_num_rows($nbc);

//echo $colname_begin;
//echo $colname_end;
//exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cashier Summary</title>
	<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" />
	<script src="../../jquery-1.11.1.js"></script>
    <script src="../../jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
   	<script>
	$(document).ready(function(){
     //  $(function() {           // this and previous line work OK... don't know the difference
    $.datepicker.setDefaults({    //solution to 0000-00-00 http://stackoverflow.com/questions/9888000/jquery-datepicker-format-date-not-working
     dateFormat: 'yy-mm-dd'
    });
	 dateFormat: "yy-mm-dd";
          $( "#begin" ).datepicker();
          $( "#end" ).datepicker();
//          $( "#ussedd" ).datepicker();
//          $( "#firstvisit" ).datepicker();
       });
   </script>
</head>

<body>
<table width="50%" border="1" align="center" class="tablebc">
<form id="form1" name="form1" method="post" action="CashierReport4.php">
  <tr>
    <td><a href="CashierReptMenu.php">Menu</a></td>
    <td align="center" nowrap="nowrap"><span class="BlackBold_36">Cashier Department Report</span><span class="BlackBold_16">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date: <?php echo date('Y-m-d'); ?></span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center">&nbsp;&nbsp;Begin:
      <input type="text" id="begin"name="begin" value="<?php echo $colname_begin; ?>" size="10" maxlength="12" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; End:
      <input type="text" id="end" name="end" value="<?php echo $colname_end; ?>" size="10" maxlength="12"  />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      <input type="submit" name="SelDate" value="Select Date Range" />
      </div></td>
    </tr>
  </form>
</table>
<p>&nbsp;</p>
<p align="center" class="BlueBold_16">By DEPARTMENT</p>
<p align="center" class="BlueBold_16">
  <!--By DEPARTMENT --------By DEPARTMENT --------By DEPARTMENT --------By DEPARTMENT ---------->
</p>
<table align="center">
  <tr>
    <td valign="top"><table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit - Bank</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_BankByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BankByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BankByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BankByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BankByDept['amtpaid'] + $row_BankByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_BankByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_BankByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_BankByDept['Refunded'];	
?>
      <?php } while ($row_BankByDept = mysql_fetch_assoc($BankByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table></td>

    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

    <td valign="top">
	  <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Credit - BMCAdmin</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduerocr = 0;
		$totamtpaidrocr = 0;
		$totrefundrocr = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_BMCByDeptCred['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BMCByDeptCred['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BMCByDeptCred['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BMCByDeptCred['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_BMCByDeptCred['amtpaid'] + $row_BMCByDeptCred['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduerocr = $totamtduerocr + $row_BMCByDeptCred['amtdue'];
		$totamtpaidrocr = $totamtpaidrocr + $row_BMCByDeptCred['amtpaid'];
		$totrefundrocr = $totrefundrocr + $row_BMCByDeptCred['Refunded'];	
?>
      <?php } while ($row_BMCByDeptCred = mysql_fetch_assoc($BMCByDeptCred)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduerocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr + $totrefundrocr; ?></td>
      </tr>
    </table>
<p>&nbsp;</p></tr>	

  <tr>
    <td valign="top"><br />
	 <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit - POS</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_POSByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_POSByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_POSByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_POSByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_POSByDept['amtpaid'] + $row_POSByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_POSByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_POSByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_POSByDept['Refunded'];	
?>
      <?php } while ($row_POSByDept = mysql_fetch_assoc($POSByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td valign="top"><br />
	  <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Credit - PHAdmin</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduerocr = 0;
		$totamtpaidrocr = 0;
		$totrefundrocr = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_PHByDeptCred['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_PHByDeptCred['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_PHByDeptCred['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_PHByDeptCred['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_PHByDeptCred['amtpaid'] + $row_PHByDeptCred['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduerocr = $totamtduerocr + $row_PHByDeptCred['amtdue'];
		$totamtpaidrocr = $totamtpaidrocr + $row_PHByDeptCred['amtpaid'];
		$totrefundrocr = $totrefundrocr + $row_PHByDeptCred['Refunded'];	
?>
      <?php } while ($row_PHByDeptCred = mysql_fetch_assoc($PHByDeptCred)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduerocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr + $totrefundrocr; ?></td>
      </tr>
    </table>
<p>&nbsp;</p> </tr>	

  <tr>
    <td valign="top"><br />
	 <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit - Cash </div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_CashByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CashByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CashByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CashByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CashByDept['amtpaid'] + $row_CashByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_CashByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_CashByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_CashByDept['Refunded'];	
?>
      <?php } while ($row_CashByDept = mysql_fetch_assoc($CashByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td valign="top"><br />
	  <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Credit - CAC</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduerocr = 0;
		$totamtpaidrocr = 0;
		$totrefundrocr = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_CACByDeptCred['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CACByDeptCred['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CACByDeptCred['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CACByDeptCred['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CACByDeptCred['amtpaid'] + $row_CACByDeptCred['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduerocr = $totamtduerocr + $row_CACByDeptCred['amtdue'];
		$totamtpaidrocr = $totamtpaidrocr + $row_CACByDeptCred['amtpaid'];
		$totrefundrocr = $totrefundrocr + $row_CACByDeptCred['Refunded'];	
?>
      <?php } while ($row_CACByDeptCred = mysql_fetch_assoc($CACByDeptCred)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduerocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr + $totrefundrocr; ?></td>
      </tr>
  </table> </tr>	

  <tr>
    <td valign="top"><br />
	 <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit - Deposit </div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_DepositByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DepositByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DepositByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DepositByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DepositByDept['amtpaid'] + $row_DepositByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_DepositByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_DepositByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_DepositByDept['Refunded'];	
?>
      <?php } while ($row_DepositByDept = mysql_fetch_assoc($DepositByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table>   </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td valign="top"><br />
	  <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Credit - Destitute</div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduerocr = 0;
		$totamtpaidrocr = 0;
		$totrefundrocr = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_DestByDeptCred['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DestByDeptCred['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DestByDeptCred['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DestByDeptCred['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_DestByDeptCred['amtpaid'] + $row_DestByDeptCred['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduerocr = $totamtduerocr + $row_DestByDeptCred['amtdue'];
		$totamtpaidrocr = $totamtpaidrocr + $row_DestByDeptCred['amtpaid'];
		$totrefundrocr = $totrefundrocr + $row_DestByDeptCred['Refunded'];	
?>
      <?php } while ($row_DestByDeptCred = mysql_fetch_assoc($DestByDeptCred)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduerocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr + $totrefundrocr; ?></td>
      </tr>
    </table>
<p>&nbsp;</p> </tr>	

  <tr>
    <td valign="top"><br />
	 <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit - Total </div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_NonCredByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_NonCredByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_NonCredByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_NonCredByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_NonCredByDept['amtpaid'] + $row_NonCredByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_NonCredByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_NonCredByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_NonCredByDept['Refunded'];	
?>
      <?php } while ($row_NonCredByDept = mysql_fetch_assoc($NonCredByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table>   </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td valign="top"><br />
	  <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Credit - Total </div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduerocr = 0;
		$totamtpaidrocr = 0;
		$totrefundrocr = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_CreditByDeptCred['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CreditByDeptCred['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CreditByDeptCred['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CreditByDeptCred['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_CreditByDeptCred['amtpaid'] + $row_CreditByDeptCred['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduerocr = $totamtduerocr + $row_CreditByDeptCred['amtdue'];
		$totamtpaidrocr = $totamtpaidrocr + $row_CreditByDeptCred['amtpaid'];
		$totrefundrocr = $totrefundrocr + $row_CreditByDeptCred['Refunded'];	
?>
      <?php } while ($row_CreditByDeptCred = mysql_fetch_assoc($CreditByDeptCred)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduerocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundrocr; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidrocr + $totrefundrocr; ?></td>
      </tr>
    </table>
<p>&nbsp;</p> </tr>	

  <tr>
    <td colspan="3" align="center" valign="top"><br />
	 <table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
      <tr>
        <td colspan="5" class="BlueBold_14"><div align="center">Non Credit and Credit - Total </div></td>
        </tr>
      <tr>
        <td class="BlackBold_12">Department</td>
        <td class="BlackBold_12">Amount Due </td>
        <td class="BlackBold_12">Amount Paid</td>
        <td class="BlackBold_12">Refunds</td>
        <td class="BlackBold_12">Received</td>
      </tr>
      <?php
		$totamtduero = 0;
		$totamtpaidro = 0;
		$totrefundro = 0;	
 		do {   ?>
      <tr>
        <td><?php echo $row_AllByDept['dept']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_AllByDept['amtdue']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_AllByDept['amtpaid']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_AllByDept['Refunded']; ?></td>
        <td align="right" bgcolor="#FFFFFF"><?php echo $row_AllByDept['amtpaid'] + $row_AllByDept['Refunded']; ?></td>
      </tr>
      <?php 	$totamtduero = $totamtduero + $row_AllByDept['amtdue'];
		$totamtpaidro = $totamtpaidro + $row_AllByDept['amtpaid'];
		$totrefundro = $totrefundro + $row_AllByDept['Refunded'];	
?>
      <?php } while ($row_AllByDept = mysql_fetch_assoc($AllByDept)); ?>
      <tr>
        <td align="right" bgcolor="#CCCCFF">Total:</td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduero; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundro; ?></td>
        <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidro + $totrefundro; ?></td>
      </tr>
    </table>   </td>
 </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
 <p align="center" class="BlueBold_16">By SECTION  </p>
   <!--By Section ------By Section ------By Section ------By Section ------By Section ------By Section ------By Section -------->
</p>
 <table width="40%" border="0" align="center">
   <tr>
     <td valign="top"><table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
       <tr>
         <td colspan="5" class="BlueBold_14"><div align="center">Non Credit (Bank, POS, Cash)</div></td>
        </tr>
       <tr>
         <td class="BlackBold_14">Department</td>
         <td class="BlackBold_14">Section</td>
         <td class="BlackBold_14">Amount Due </td>
         <td class="BlackBold_14">Amount Paid</td>
         <td class="BlackBold_14">Refunds</td>
       </tr>
       <?php
		$totamtduesro = 0;
		$totamtpaidsro = 0;
		$totrefundsro = 0;	
 		do {   
?>
       <tr>
         <td><?php echo $row_BySection['dept']; ?></td>
         <td><?php echo $row_BySection['section']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySection['amtdue']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySection['amtpaid']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySection['Refunded']; ?></td>
       </tr>
       <?php 	$totamtduesro = $totamtduesro + $row_BySection['amtdue'];
		$totamtpaidsro = $totamtpaidsro + $row_BySection['amtpaid'];
		$totrefundsro = $totrefundsro + $row_BySection['Refunded'];	
?>
       <?php } while ($row_BySection = mysql_fetch_assoc($BySection)); ?>
       <tr>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF">Total:</td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduesro; ?></td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidsro; ?></td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundsro; ?></td>
       </tr>
       <tr>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF">Received:</td>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidsro + $totrefundsro; ?></td>
         <td bgcolor="#CCCCFF">&nbsp;</td>
       </tr>
     </table></td>
     <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td valign="top"><table border="1" align="center" cellpadding="1" cellspacing="1" class="tablebc">
       <tr>
         <td colspan="5" class="BlueBold_14"><div align="center">Credit (BMCAdmin, PHAdmin, CAC, Destitute)</div></td>
        </tr>
       <tr>
         <td class="BlackBold_14">Department</td>
         <td class="BlackBold_14">Section</td>
         <td class="BlackBold_14">Amount Due </td>
         <td class="BlackBold_14">Amount Paid</td>
         <td class="BlackBold_14">Refunds</td>
       </tr>
       <?php
		$totamtduesrocr = 0;
		$totamtpaidsrocr = 0;
		$totrefundsrocr = 0;	
 		do {   
?>
       <tr>
         <td><?php echo $row_BySectionCred['dept']; ?></td>
         <td><?php echo $row_BySectionCred['section']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySectionCred['amtdue']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySectionCred['amtpaid']; ?></td>
         <td align="right" bgcolor="#FFFFFF"><?php echo $row_BySectionCred['Refunded']; ?></td>
       </tr>
       <?php 	$totamtduesrocr = $totamtduesrocr+ $row_BySectionCred['amtdue'];
		$totamtpaidsrocr = $totamtpaidsrocr + $row_BySectionCred['amtpaid'];
		$totrefundsrocr = $totrefundsrocr + $row_BySectionCred['Refunded'];	
?>
       <?php } while ($row_BySectionCred = mysql_fetch_assoc($BySectionCred)); ?>
       <tr>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF">Total:</td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtduesrocr; ?></td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidsrocr; ?></td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totrefundsrocr; ?></td>
       </tr>
       <tr>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF">Received:</td>
         <td bgcolor="#CCCCFF">&nbsp;</td>
         <td align="right" bgcolor="#CCCCFF"><?php echo $totamtpaidsrocr + $totrefundsrocr; ?></td>
         <td bgcolor="#CCCCFF">&nbsp;</td>
       </tr>
     </table></td>
   </tr>
 </table>
 <p>&nbsp;</p>
 <p align="center" class="BlueBold_16">By Payment Type</p>
 <p align="center" class="BlueBold_16">&nbsp;</p>

 <table border="1" align="center">
   <tr>
     <td>&nbsp; </td>
     <td class="BlueBold_14">amtdue</td>
     <td class="BlueBold_14">amtpaid</td>
     <td class="BlueBold_14">Refunded</td>
   </tr>
   <?php
   	 $nbc_amtdue = 0;
	 $nbc_amtpaid = 0;
	 $nbc_amtrefunded = 0;
    do { ?>
     <tr>
       <td bgcolor="#FFFFFF"><?php echo $row_nbc['nbc']; ?></td>
       <td align="right" bgcolor="#FFFFFF"><?php echo $row_nbc['amtdue']; ?></td>
       <td align="right" bgcolor="#FFFFFF"><?php echo $row_nbc['amtpaid']; ?></td>
       <td align="right" bgcolor="#FFFFFF"><?php echo $row_nbc['Refunded']; ?></td>
     </tr>
<?php
 	 $nbc_amtdue = $nbc_amtdue + $row_nbc['amtdue'];
	 $nbc_amtpaid = $nbc_amtpaid + $row_nbc['amtpaid'];
	 $nbc_amtrefunded = $nbc_amtrefunded + $row_nbc['Refunded']; 
?>
     <?php } while ($row_nbc = mysql_fetch_assoc($nbc)); ?>
	 <tr>
       <td bgcolor="#CCCCFF">Total</td>
       <td align="right" bgcolor="#CCCCFF"><?php echo $nbc_amtdue; ?></td>
       <td align="right" bgcolor="#CCCCFF"><?php echo $nbc_amtpaid; ?></td>
       <td align="right" bgcolor="#CCCCFF"><?php echo $nbc_amtrefunded; ?></td>
	 </tr>
	 <tr>
       <td bgcolor="#CCCCFF">Received</td>
         <td bgcolor="#CCCCFF">&nbsp;</td>
       <td align="right" bgcolor="#CCCCFF"><?php echo $nbc_amtpaid + $nbc_amtrefunded; ?></td>
	 </tr>
 </table>
</body>
</html>
<?php
mysql_free_result($BySection);
mysql_free_result($BySectionCred);

mysql_free_result($nbc);
?>
<?php
mysql_free_result($ByDept);
mysql_free_result($ByDeptCred);
?>
