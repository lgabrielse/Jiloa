<?php  $pt = "Cashier-Paid"; ?>
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

mysql_select_db($database_swmisconn, $swmisconn);  //CASE WHEN o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END as amtdue
$query_OrdRecpt = "SELECT f.id feeid, left(f.dept,3) dept, f.section, f.name, o.id ordid, o.amtdue, o.amtpaid from orders o join fee f on o.feeid = f.id join receipts r on (instr(r.ordlist,o.id)> 0) WHERE o.medrecnum = '" . $row_PatRecpt['medrecnum'] . "' AND r.id = '" . $colname_rid."'";
$OrdRecpt = mysql_query($query_OrdRecpt, $swmisconn) or die(mysql_error());
$row_OrdRecpt = mysql_fetch_assoc($OrdRecpt);
$totalRows_OrdRecpt = mysql_num_rows($OrdRecpt);
} 
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
    <td colspan="2" align="center" >
      <a href="javascript:window.print()">Printed Patient Receipt</a></td> <!-- <a href="#" onclick="Print()">Printed Patient Receipt </a>-->
  </tr>
<?php 	if($_GET['Status'] == 'Refund' OR $_GET['Status'] == 'DepRefund'){?>
  <tr>
    <td colspan="2" align="center" class="Black_18">MRN: <?php echo $row_PatRecpt['medrecnum'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REFUND # <?php echo $colname_rid ?></td>
  </tr>
<?php 	} else { ?>
  <tr>
    <td colspan="2" align="center" class="Black_18">MRN: <?php echo $row_PatRecpt['medrecnum'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RECEIPT # <?php echo $colname_rid ?></td>
  </tr>
<?php  }?>
  <tr>
    <td colspan="2" align="center" nowrap="nowrap" class="Black_30"><?php echo $row_PatRecpt['hospital'] ?> Medical Center</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Black_24"><a href="CashShowAll.php?mrn=<?php echo $row_PatRecpt['medrecnum'] ?>"><?php echo $row_PatRecpt['lastname'] ?>,<?php echo $row_PatRecpt['firstname'] ?> (<?php echo $row_PatRecpt['othername'] ?>)</a></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Black_24"><?php echo $row_PatRecpt['entrydt'] ?></td>
  </tr>
  <tr><td colspan="2"></tr>
  <?php do { ?>
  <tr>
    <td align="left" nowrap="nowrap" class="Black_18"><?php echo $row_OrdRecpt['ordid']; ?>&nbsp;&nbsp;
    <?php echo $row_OrdRecpt['dept']; ?>&nbsp;&nbsp;
    <?php echo $row_OrdRecpt['name']; ?>&nbsp;&nbsp;</td>
	<?php if($row_OrdRecpt['feeid'] == 279){ ?>
	    <td align="right" nowrap="nowrap" class="Black_18"><?php echo number_format($row_OrdRecpt['amtpaid']) ?></td>	
	<?php } else { ?>
   	 <td align="right" nowrap="nowrap" class="Black_18"><?php echo number_format($row_OrdRecpt['amtdue']) ?></td>
    <?php } ?>
  </tr>
  <?php } while ($row_OrdRecpt = mysql_fetch_assoc($OrdRecpt)); ?>

  <?php 	if($_GET['Status'] == 'Refund' OR $_GET['Status'] == 'DepRefund' ){?>
  <tr>
    <td colspan="2" align="center" class="Black_24">Refunded: N <?php echo (0 - $row_PatRecpt['amt']); ?></td>
  </tr>
<?php 	} else { ?>
  <tr>
    <td colspan="2" align="center" class="Black_24">Received: N <?php echo $row_PatRecpt['amt'] ?></td>
  </tr>
<?php  }?>
  <tr>
    <td colspan="2" align="center" class="BlackBold_30"><a href="CashSearch.php">THANK YOU</a></td>
  </tr>
  <tr>
<?php 	if($_GET['Status'] == 'Refund' or $_GET['Status'] == 'DepRefund'){?>
  <tr>
    <td colspan="2" align="center" nowrap="nowrap" class="Black_18"> Refunded By: <?php echo $row_PatRecpt['entryby'] ?> </td>
  </tr>
  <tr>
   <td colspan="2" align="center" nowrap="nowrap" class="Black_18"> Refunded To: <?php echo $row_PatRecpt['nbc'] ?> </td>
  </tr>
<?php 	} else { ?>
  <tr>
    <td colspan="2" align="center" nowrap="nowrap" class="Black_18"> Received By: <?php echo $row_PatRecpt['entryby'] ?></td>
   </tr>
  <tr>
   <td colspan="2" align="center" nowrap="nowrap" class="Black_18"> Received From: <?php echo $row_PatRecpt['nbc'] ?> </td>
  </tr>
<?php  }?>
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
