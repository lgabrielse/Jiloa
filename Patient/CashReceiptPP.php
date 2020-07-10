<?php  $pt = "CashReceiptPP"; ?>
<?php session_start(); // 'Start new or resume existing session'  $_SESSION['sysconn'] seems to unavailable default?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
	$colname_rid = "70";
if (isset($_GET['rid'])) {
  $colname_rid = (get_magic_quotes_gpc()) ? $_GET['rid'] : addslashes($_GET['rid']);
  
mysql_select_db($database_swmisconn, $swmisconn);
$query_PatRecpt = "SELECT r.id, r.amt, r.nbc, DATE_FORMAT(r.entrydt,'%d-%b-%Y') entrydt, r.entryby, pp.medrecnum, pp.lastname, pp.firstname, pp.othername, pp.hospital FROM `receipts` r join `patperm` pp on r.medrecnum = pp.medrecnum WHERE r.id = '" . $colname_rid."'";
$PatRecpt = mysql_query($query_PatRecpt, $swmisconn) or die(mysql_error());
$row_PatRecpt = mysql_fetch_assoc($PatRecpt);
$totalRows_PatRecpt = mysql_num_rows($PatRecpt);

//mysql_select_db($database_swmisconn, $swmisconn);
//$query_OrdRecpt = "SELECT f.id, left(f.dept,3) dept, f.section, f.name, o.id ordid, CASE WHEN o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as amtdue, o.amtpaid from orders o join fee f on o.feeid = f.id join receipts r on (instr(r.ordlist,o.id)> 0) WHERE r.id = '" . $colname_rid."'";
//$OrdRecpt = mysql_query($query_OrdRecpt, $swmisconn) or die(mysql_error());
//$row_OrdRecpt = mysql_fetch_assoc($OrdRecpt);
//$totalRows_OrdRecpt = mysql_num_rows($OrdRecpt);

mysql_select_db($database_swmisconn, $swmisconn);  
$query_OrdRecpt = "SELECT ro.amtdue roamtdue, o.amtdue oamtdue, o.amtpaid oamtpaid, ro.amtpaid roamtpaid, f.id feeid, left(f.dept,3) dept, f.section, f.name, o.id orderid from receipts r join rcptord ro on r.id = ro.rcptid join orders o on ro.ordid = o.id join fee f on o.feeid = f.id WHERE r.id = '" . $colname_rid."'";
$OrdRecpt = mysql_query($query_OrdRecpt, $swmisconn) or die(mysql_error());
$row_OrdRecpt = mysql_fetch_assoc($OrdRecpt);
$totalRows_OrdRecpt = mysql_num_rows($OrdRecpt);
}
  $paid = 0;
  $unpaid = 0;
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Printed Receipt onload</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body onload="Print()">
 <table width="320" border="0" align="center">
  <tr>
    <td colspan="3" align="center" >
      <a href="javascript:window.print()">Printed Patient Receipt</a></td> <!-- <a href="#" onclick="Print()">Printed Patient Receipt </a>-->
  </tr>
<?php 	if($_GET['Status'] == 'Refund' or $_GET['Status'] == 'Refunded'){?>
  <tr>
    <td colspan="3" align="center" class="Black_18">MRN: <?php echo $row_PatRecpt['medrecnum'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REFUND # <?php echo $colname_rid ?></td>
  </tr>
<?php 	} else { ?>
  <tr>
    <td colspan="3" align="center" class="Black_18">MRN: <?php echo $row_PatRecpt['medrecnum'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RECEIPT # <?php echo $colname_rid ?></td>
  </tr>
<?php  }?>
  <tr>
    <td colspan="3" align="center" nowrap="nowrap" class="Black_30"><?php echo $row_PatRecpt['hospital'] ?> Medical Center</td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="Black_24"><?php echo $row_PatRecpt['lastname'] ?>,<?php echo $row_PatRecpt['firstname'] ?> (<?php echo $row_PatRecpt['othername'] ?>)</td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="Black_24"><?php echo $row_PatRecpt['entrydt'] ?></td>
  </tr>
  <tr>
     <td colspan="3"></td></tr>
  <?php do { 
  $paid = $row_OrdRecpt['oamtpaid'];
  $unpaid = $row_OrdRecpt['oamtdue'] - $row_OrdRecpt['oamtpaid'];
  
  ?>
  <tr>
    <td  colspan="2" align="left" nowrap="nowrap" class="BlackBold_16">
    <?php echo $row_OrdRecpt['dept']; ?>&nbsp;&nbsp;
    <?php echo $row_OrdRecpt['name']; ?>&nbsp;&nbsp; </td>
	<td class="BlackBold_16">N<?php echo number_format($row_OrdRecpt['oamtdue']) ?></td>
  </tr>
  <tr>
    <td align="left" class="Black_14"><span class="Black_14">&nbsp;&nbsp;Ord#&nbsp;<?php echo $row_OrdRecpt['orderid']; ?>&nbsp; Unpaid:</span> <?php echo number_format($unpaid) ?></td>
    <td align="left" nowrap="nowrap" class="Black_14">Paid: </td>
    <td align="left" nowrap="nowrap" class="Black_14"><?php echo number_format($paid); ?></td>
  </tr>
  <?php } while ($row_OrdRecpt = mysql_fetch_assoc($OrdRecpt)); ?>

  <?php 	if($_GET['Status'] == 'Refund' or $_GET['Status'] == 'Refunded'){?>
  <tr>
    <td colspan="3" align="center" class="Black_24">Refunded: N <?php echo (0 - $row_PatRecpt['amt']); ?></td>
  </tr>
<?php 	} else { ?>
  <tr>
    <td colspan="3" align="center" class="Black_24">Received: N <?php echo $row_PatRecpt['amt'] ?></td>
  </tr>
<?php  }?>
   <tr>
     <td colspan="3" align="center" class="BlackBold_30"><a href="CashSearch.php">THANK YOU</a></td>
   </tr>
<?php 	if($_GET['Status'] == 'Refund' or $_GET['Status'] == 'Refunded') { ?>
   <tr>
     <td colspan="3" nowrap="nowrap" class="Black_18"> <div align="center">Refunded By: <?php echo $row_PatRecpt['entryby']; ?> </div></td>
   </tr>
<?php 	} else { ?>
   <tr>
     <td colspan="3" nowrap="nowrap" class="Black_18"> <div align="center">Received By: <?php echo $row_PatRecpt['entryby']; ?> </div></td>
   </tr>
<?php  }?>
  <tr>
   <td colspan="2" align="center" nowrap="nowrap" class="Black_18"> Received From: <?php echo $row_PatRecpt['nbc'] ?> </td>
  </tr>

  </table>
<object id="WebBrowser1" width="0" height="0" 
    classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2">
</object>
<script>
      function Print()
         {
          if (document.all) 
             {
               WebBrowser1.ExecWB(6, 6) //use 6, 1 to prompt the print dialog or 6, 6 to omit it; 
                WebBrowser1.outerHTML = "";
             }
         else
            {
             window.print();
             }
         }
</script>


</body>
</html>
