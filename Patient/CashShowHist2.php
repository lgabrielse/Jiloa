<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/swmisconn.php'); ?><?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/functions.php'); ?>

<?php
$colname_patperm = "3433"; //"-1";
if (isset($_GET['mrn'])) {
  $colname_patperm = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $colname_patperm."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

mysql_select_db($database_swmisconn, $swmisconn);
$query_transx = "SELECT r.id rid, r.medrecnum mrn, r.amt, r.nbc, r.entryby, r.entrydt, ro.id roid, ro.ordid, ro.rcptid, ro.amtdue roamtdue, ro.amtpaid roamtpaid, ro.status rostatus, o.id oid, o.medrecnum, o.visitid, o.feeid, o.rate, o.item, o.amtdue, o.amtpaid, o.billstatus, f.name, f.dept, f.section, f.fee FROM orders o join fee f on o.feeid = f.id left outer join rcptord ro on ro.ordid = o.id left outer join receipts r on r.id = ro.rcptid WHERE o.medrecnum = '". $colname_patperm."' ORDER BY visitid, IFNULL(ro.id,o.id)";  //'99999'
$transx = mysql_query($query_transx, $swmisconn) or die(mysql_error());
$row_transx = mysql_fetch_assoc($transx);
$totalRows_transx = mysql_num_rows($transx);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Deposited = "Select nbc, SUM(ro.amtpaid) as dep from receipts r join rcptord ro on r.id = ro.rcptid where r.medrecnum = '". $colname_patperm."' and ro.status = 'Deposited' group by nbc order by nbc";
$Deposited = mysql_query($query_Deposited, $swmisconn) or die(mysql_error());
$row_Deposited = mysql_fetch_assoc($Deposited);
$totalRows_Deposited = mysql_num_rows($Deposited);

mysql_select_db($database_swmisconn, $swmisconn);
$query_PdByAcct = "Select r.nbc, Sum(ro.amtpaid) paid from receipts r join rcptord ro on r.id = ro. rcptid where r.medrecnum = '". $colname_patperm."' and ro.status in ('Paid', 'PartPaid') group by r.nbc";
$PdByAcct = mysql_query($query_PdByAcct, $swmisconn) or die(mysql_error());
$row_PdByAcct = mysql_fetch_assoc($PdByAcct);
$totalRows_PdByAcct = mysql_num_rows($PdByAcct);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Refunded = "Select r.nbc, SUM(ro.amtpaid) refund from receipts r join rcptord ro on r.id = ro.rcptid where (ro.status = 'Refunded' or ro.status = 'DepRefund') and r.medrecnum = '". $colname_patperm."' group by nbc order by nbc";
$Refunded = mysql_query($query_Refunded, $swmisconn) or die(mysql_error());
$row_Refunded = mysql_fetch_assoc($Refunded);
$totalRows_Refunded = mysql_num_rows($Refunded);

mysql_select_db($database_swmisconn, $swmisconn);
$query_PdByDept = "Select f.dept, SUM(ro.amtpaid) ordpaid from rcptord ro join orders o on ro.ordid = o.id join fee f on o.feeid = f.id where ro.status in ('Paid', 'PartPaid') and o.medrecnum = '". $colname_patperm."' group by f.dept order by f.dept";
$PdByDept = mysql_query($query_PdByDept, $swmisconn) or die(mysql_error());
$row_PdByDept = mysql_fetch_assoc($PdByDept);
$totalRows_PdByDept = mysql_num_rows($PdByDept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_UnPdByDept = "SELECT f.dept, SUM(o.amtdue - o.amtpaid) unpaid FROM orders o join fee f on o.feeid = f.id WHERE o.amtdue - IFNULL(o.amtpaid,0) > 0 and medrecnum = '". $colname_patperm."' GROUP BY f.dept ORDER BY f.dept ";
$UnPdByDept = mysql_query($query_UnPdByDept, $swmisconn) or die(mysql_error());
$row_UnPdByDept = mysql_fetch_assoc($UnPdByDept);
$totalRows_UnPdByDept = mysql_num_rows($UnPdByDept);


$AcctBal = 0;
$query_AcctDeposits = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'Deposited' and r.medrecnum = '".$colname_patperm."' GROUP BY r.medrecnum";
$AcctDeposits = mysql_query($query_AcctDeposits, $swmisconn) or die(mysql_error());
$row_AcctDeposits = mysql_fetch_assoc($AcctDeposits);
$totalRows_AcctDeposits = mysql_num_rows($AcctDeposits);

$query_AcctPdByDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE (ro.status = 'Paid' OR ro.status = 'PartPaid') and r.nbc = 'Deposit' and r.medrecnum = '".$colname_patperm."' GROUP BY r.medrecnum";
$AcctPdByDep = mysql_query($query_AcctPdByDep, $swmisconn) or die(mysql_error());
$row_AcctPdByDep = mysql_fetch_assoc($AcctPdByDep);
$totalRows_AcctPdByDep = mysql_num_rows($AcctPdByDep);

$query_AcctRefToDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'Refunded' and r.nbc = 'Deposit' and r.medrecnum = '".$colname_patperm."' GROUP BY r.medrecnum";
$AcctRefToDep = mysql_query($query_AcctRefToDep, $swmisconn) or die(mysql_error());
$row_AcctRefToDep = mysql_fetch_assoc($AcctRefToDep);
$totalRows_AcctRefToDep = mysql_num_rows($AcctRefToDep);

$query_AcctDepRefund = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'DepRefund' and r.medrecnum = '".$colname_patperm."' GROUP BY r.medrecnum";
$AcctDepRefund = mysql_query($query_AcctDepRefund, $swmisconn) or die(mysql_error());
$row_AcctDepRefund = mysql_fetch_assoc($AcctDepRefund);
$totalRows_AcctDepRefund = mysql_num_rows($AcctDepRefund);


$AcctBal = $row_AcctDeposits['sumamtpaid'] + $row_AcctDepRefund['sumamtpaid'] + (0 - $row_AcctRefToDep['sumamtpaid']) - $row_AcctPdByDep['sumamtpaid'];

$query_OrdBal = "SELECT p.lastname, p.firstname, p.othername, DATE_FORMAT(p.DOB,'%d-%c-%Y') DOB, p.gender, o.id, o.medrecnum, SUM(o.amtdue) sumamtdue, SUM(o.amtpaid) sumamtpaid, SUM(IFNULL(o.amtpaid,0)) - SUM(IFNULL(o.amtdue,0)) bal FROM patperm p join `orders` o on o.medrecnum = p.medrecnum WHERE o.billstatus <> 'Refunded' and o.feeid NOT IN (279, 280) and o.medrecnum = '".$colname_patperm."' GROUP BY medrecnum";
$OrdBal = mysql_query($query_OrdBal, $swmisconn) or die(mysql_error());
$row_OrdBal = mysql_fetch_assoc($OrdBal);
$totalRows_OrdBal = mysql_num_rows($OrdBal);

$query_Rcvdlist = "SELECT r.nbc, SUM(ro.amtpaid) rcvdlist FROM `rcptord` ro join receipts r on ro.rcptid = r.id and r.medrecnum = '".$colname_patperm."' WHERE ro.status = 'Deposited' or (ro.status in ('Paid', 'PartPaid') and r.nbc <> 'Deposit') Group by r.nbc";
$Rcvdlist = mysql_query($query_Rcvdlist, $swmisconn) or die(mysql_error());
$row_Rcvdlist = mysql_fetch_assoc($Rcvdlist);
$totalRows_Rcvdlist = mysql_num_rows($Rcvdlist);

$query_Rcvd = "SELECT SUM(ro.amtpaid) rcvd FROM `rcptord` ro join receipts r on ro.rcptid = r.id and r.medrecnum = '".$colname_patperm."' WHERE ro.status = 'Deposited' or (ro.status in ('Paid', 'PartPaid') and r.nbc <> 'Deposit')";
$Rcvd = mysql_query($query_Rcvd, $swmisconn) or die(mysql_error());
$row_Rcvd = mysql_fetch_assoc($Rcvd);
$totalRows_Rcvd = mysql_num_rows($Rcvd);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CashShowTransx</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=400,screenX=300,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<!-- Begin PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT -   -->
 
  <table width="700px"  border="0" align="center" bordercolor="#000000" border-collapse="collapse">
	  <tr>
	  	<td colspan="7" class ="BlueBold_18"><div align="center"><a href="CashShowAll.php?mrn=<?php echo $row_patperm['medrecnum']; ?>">Back</a> &nbsp;&nbsp;&nbsp;&nbsp;Patient Payment History</div></td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?></td>
		<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_16"><?php echo $row_patperm['lastname']; ?></span>, <span class="BlueBold_16"><?php echo $row_patperm['firstname']; ?></span> (<span class="BlueBold_16"><?php echo $row_patperm['othername']; ?></span>) </td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
		<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_patperm['dob']; ?></span>:<?php echo $row_patperm['est']; ?></td>
	  </tr>
</table>

 
  <table width="20%" border="0" align="center" bgcolor="#FFFFFF">
    <tr>
  <?php if($row_OrdBal['bal'] < 0) { ?>
    <td align="center" nowrap="nowrap">Order Balance: <span class="flagWhiteonRed"><strong><?php echo $row_OrdBal['bal']; ?></strong></span></td>
	<?php } else {?>
    <td nowrap="nowrap">Order Balance: <span class="flagWhiteonGreen"><strong><?php echo $row_OrdBal['bal']; ?></strong></span></td>
<?php }?>
  <?php if($AcctBal< 0) { ?>
    <td align="right" nowrap="nowrap">Account Balance: <span class="flagWhiteonRed"><strong><?php echo $AcctBal; ?></strong></span></td>
	<?php } else {?>
    <td align="right" nowrap="nowrap">Account Balance: <span class="flagWhiteonGreen"><strong><?php echo $AcctBal; ?></strong></span></td>
<?php }?>
    </tr>
  </table>
  <p align="center" class="Black_12">Billing Transaction Receipts and Link records are not updated, but order record fields like amtpaid, status, change during processing. </p>
  <p>&nbsp;</p>
  <table border="1" align="center">
    <tr>
      <td colspan="6" align="center" class="BlackBold_14">Receipts</td>
      <td colspan="6" align="center" class="BlackBold_14">Receipts link to orders </td>
      <td colspan="11" align="center" class="BlackBold_14">Orders </td>
    </tr>
    <tr>
      <td align="center" class="BlackBold_12">entrydate</td>
      <td align="center" class="BlackBold_12">entryby</td>
      <td align="center" class="BlackBold_12">medrecnum</td>
      <td align="center" class="BlackBold_12">rcpt id</td>
      <td align="center" class="BlackBold_12">amt</td>
      <td align="center" class="BlackBold_12">acct</td>
      <td align="center" class="BlackBold_12">ro id</td>
      <td align="center" class="BlackBold_12">amtdue</td>
      <td align="center" class="BlackBold_12">amtpaid</td>
      <td align="center" class="BlackBold_12">status</td>
      <td align="center" class="BlackBold_12">OrderBal</td>
      <td align="center" class="BlackBold_12">AcctBal</td>
      <td align="center" class="BlackBold_12">order id</td>
      <td align="center" class="BlackBold_12">visitid</td>
      <td align="center" class="BlackBold_12">feeid</td>
      <td align="center" class="BlackBold_12">amtdue</td>
      <td align="center" class="BlackBold_12">amtpaid</td>
      <td align="center" class="BlackBold_12">billstatus</td>
      <td align="center" class="BlackBold_12">name</td>
      <td align="center" class="BlackBold_12">dept</td>
      <td align="center" class="BlackBold_12">section</td>
      <td align="center" class="BlackBold_12">rate</td>
      <td align="center" class="BlackBold_12">fee</td>
      <td align="center" class="BlackBold_12">AcctBal</td>
    </tr>

<?php 
?>    

	<?php $HAbal = 0; $HObal = 0;?>
    <?php do {
		$baldue = 0;
		$unpaid = 0;
		// find amt paid before this transaction... baldue = $row_transx['roamtdue'] - amount paid before transaction
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_rotrx = "SELECT SUM(ro.amtpaid) sumpaid FROM rcptord ro  WHERE ro.ordid = '".$row_transx['oid']."' and ro.id < '".$row_transx['roid']."'";
		$rotrx = mysql_query($query_rotrx, $swmisconn) or die(mysql_error());
		$row_rotrx = mysql_fetch_assoc($rotrx);
		$totalRows_rotrx = mysql_num_rows($rotrx);
		
		if($row_rotrx['sumpaid']>0){   //only if there is a previous balance due 
		$baldue = -($row_transx['roamtdue']- $row_rotrx['sumpaid']) ;
		} 
		$unpaid = $row_transx['roamtdue'] - ($row_transx['roamtpaid'] + $row_rotrx['sumpaid']);  //tranmsaction amtdue - tranmsaction amountpaid + previous balance

	  	if($row_transx['rostatus'] == "Deposited") {
			$HAbal = $HAbal + $row_transx['roamtpaid'];
		} elseif(($row_transx['rostatus'] == "Paid" or $row_transx['rostatus'] == "PartPaid") and $row_transx['nbc'] == 'Deposit')  {
			$HAbal = $HAbal - $row_transx['roamtpaid'];
		} elseif($row_transx['rostatus'] == "DepRefund")  {
			$HAbal = $HAbal + $row_transx['roamtpaid']; // add negative number decreases balance
		} elseif($row_transx['rostatus'] == "Refunded" and $row_transx['nbc'] == 'Deposit')  {
			$HAbal = $HAbal - $row_transx['roamtpaid']; // subtract negative number increases balance
		}

//	  	if($row_transx['rostatus'] == "Paid" and $row_transx['nbc'] == 'Deposit')  {
//			$HObal = $HObal +  $row_transx['roamtdue']- $row_transx['roamtpaid'];
// } else
		if($row_transx['rostatus'] == "Paid" OR $row_transx['rostatus'] == "PartPaid")  {
			$HObal = $HObal + $baldue + $unpaid; 
		} elseif($row_transx['rostatus'] == "Refunded")  {
			$HObal = $HObal - $row_transx['roamtdue'] - $row_transx['roamtpaid']; // subtract negative number increases balance
		} elseif(is_null($row_transx['rostatus']))  {
			$HObal = $HObal + $row_transx['amtdue'] - $row_transx['amtpaid']; // subtract negative number increases balance
		}
	
		
	   ?>	
      <tr>
        <td><?php echo $row_transx['entrydt']; ?></td>
        <td><?php echo $row_transx['entryby']; ?></td>
        <td><?php echo $row_transx['mrn']; ?><!--::<?php //echo $row_rotrx['sumpaid'] ?>::baldue=<?php //echo $baldue ?>:unpaid=<?php //echo $unpaid ?>--></td>
        <td><?php echo $row_transx['rid']; ?></td>
        <td bgcolor="#D5FFD5"><?php echo $row_transx['amt']; ?></td>
        <td bgcolor="#D5FFD5"><?php echo $row_transx['nbc']; ?></td>
        <td><?php echo $row_transx['roid']; ?></td>
        <td bgcolor="#fffdda"><?php echo $row_transx['roamtdue']; ?></td>
        <td bgcolor="#fffdda"><?php echo $row_transx['roamtpaid']; ?></td>
        <td bgcolor="#fffdda"><?php echo $row_transx['rostatus']; ?></td>
        <td align="right" bgcolor="#ffffff"><?php echo $HObal; ?></td>
        <td align="right" bgcolor="#ffffff"><?php echo $HAbal; ?></td>
        <td><?php echo $row_transx['oid']; ?></td>
        <td><?php echo $row_transx['visitid']; ?></td>
        <td><?php echo $row_transx['feeid']; ?></td>
<?php 	if(isset($row_transx['rostatus'])) {?>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['roamtdue']; //at time of transaction ?></td>  
        <td bgcolor="#e6f3ff"><?php echo $row_transx['roamtpaid'];  //at time of transaction ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['rostatus'];  //at time of transaction; ?></td>
<?php 	} else {?>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['amtdue']; //at time of transaction ?></td>  
        <td bgcolor="#e6f3ff"><?php echo $row_transx['amtpaid'];  //at time of transaction ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['billstatus'];  //at time of transaction; ?></td>
<?php 	}?>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['name']; ?>   <?php echo $row_transx['item']; ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['dept']; ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['section']; ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['rate']; ?></td>
        <td bgcolor="#e6f3ff"><?php echo $row_transx['fee']; ?></td>
      </tr>	  
      <?php } while ($row_transx = mysql_fetch_assoc($transx)); ?>
  </table>
  
  
  
<p>&nbsp;</p>
  <table width="30%" border="1" align="center" class="tablebc">
    <tr>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Rcvd by acct</div></td>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Deposited</div></td>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Paid by account </div></td>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Refunded by acct </div></td>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Paid by Dept </div></td>
      <td colspan="2" nowrap="nowrap" class="BlackBold_14"><div align="center">Unpaid by dept </div></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top">
          <table border="1" class="tablebc">
            <tr>
              <td nowrap="nowrap" class="BlackBold_12">From Acct</td>
              <td class="BlackBold_12">Recvd</td>
            </tr>
			<?php $t1=0; $t2=0; $t3=0;	$t4=0;	$t5=0; $t6=0; ?>
            <?php do { ?>
              <tr>
                <td><?php echo $row_Rcvdlist['nbc']; 
					$t6 = $t6 + $row_Rcvdlist['rcvdlist'];  ?></td>
                <td bgcolor="#e6f3ff"><?php echo $row_Rcvdlist['rcvdlist']; ?></td>
              </tr>
              <?php } while ($row_Rcvdlist = mysql_fetch_assoc($Rcvdlist)); ?>
        </table>      </td>
      <td colspan="2" valign="top">
        <div align="center">
          <table border="1" class="tablebc">
            <tr>
              <td nowrap="nowrap" class="BlackBold_12">From Acct</td>
              <td class="BlackBold_12">Deposit</td>
            </tr>
            <?php do { ?>
              <tr>
                <td align="right"><?php echo $row_Deposited['nbc']; 
					$t1 = $t1 + $row_Deposited['dep']; ?></td>
                <td bgcolor="#FFFDDA"><?php echo $row_Deposited['dep']; ?></td>
              </tr>
              <?php } while ($row_Deposited = mysql_fetch_assoc($Deposited)); ?>
                  </table>
      </div></td>
      <td colspan="2" align="right" valign="top">
        <table border="1" class="tablebc">
          <tr>
            <td class="BlackBold_12">acct</td>
            <td class="BlackBold_12">amtpaid</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_PdByAcct['nbc']; 
					$t2 = $t2 + $row_PdByAcct['paid'];  ?></td>
              <td bgcolor="#FFFDDA"><?php echo $row_PdByAcct['paid']; ?></td>
            </tr>
            <?php } while ($row_PdByAcct = mysql_fetch_assoc($PdByAcct)); ?>
        </table>
      </div></td>
      <td colspan="2" align="right" valign="top">
          <table border="1" class="tablebc">
            <tr>
              <td nowrap="nowrap" class="BlackBold_12">To Acct</td>
              <td class="BlackBold_12">Refund</td>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_Refunded['nbc']; 
					$t3 = $t3 + $row_Refunded['refund'];  ?></td>
                <td bgcolor="#FFFDDA"><?php echo $row_Refunded['refund']; ?></td>
              </tr>
              <?php } while ($row_Refunded = mysql_fetch_assoc($Refunded)); ?>
        </table>      </td>
      <td colspan="2" align="right" valign="top">
        
          <table border="1" class="tablebc">
            <tr>
              <td class="BlackBold_12">dept</td>
              <td class="BlackBold_12">Paid</td>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_PdByDept['dept']; 
					$t4 = $t4 + $row_PdByDept['ordpaid'];  ?></td>
                <td bgcolor="#e6f3ff"><?php echo $row_PdByDept['ordpaid']; ?></td>
              </tr>
              <?php } while ($row_PdByDept = mysql_fetch_assoc($PdByDept)); ?>
        </table>	  </td>
      <td colspan="2" align="right" valign="top">
          <table border="1">
            <tr>
              <td class="BlackBold_12">Dept</td>
              <td class="BlackBold_12">UnPaid</td>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_UnPdByDept['dept']; 
					$t5 = $t5 + $row_UnPdByDept['unpaid'];  ?></td>
                <td bgcolor="#e6f3ff"><?php echo $row_UnPdByDept['unpaid']; ?></td>
              </tr>
              <?php } while ($row_UnPdByDept = mysql_fetch_assoc($UnPdByDept)); ?>
        </table>      </td>
    </tr>
    <tr>
      <td align="right">Total:</td>
      <td align="right" bgcolor="#e6f3ff"><?php echo $row_Rcvd['rcvd'] ?></td>
      <td align="right">Total: </td>
      <td align="right" bgcolor="#FFFDDA"><?php echo $t1 ?></td>
      <td align="right">Total: </td>
      <td align="right" bgcolor="#FFFDDA"><?php echo $t2 ?></td>
      <td align="right">Total: </td>
      <td align="right" bgcolor="#FFFDDA"><?php echo $t3 ?></td>
      <td align="right">Total: </td>
      <td align="right" bgcolor="#e6f3ff"><?php echo $t4 ?></td>
      <td align="right">Total: </td>
      <td align="right" bgcolor="#e6f3ff"><?php echo $t5 ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($transx);

mysql_free_result($Deposited);

mysql_free_result($PdByAcct);

mysql_free_result($Refunded);

mysql_free_result($PdByDept);

mysql_free_result($UnPdByDept);
?>