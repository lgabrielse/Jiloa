<?php ob_start(); ?>
<?php  $pt = "Cashier-PaidPP"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php

$colname_ordnum_order = "-1";
if (isset($_POST['ordnum'])) {  // only one order is sent here
  $colname_ordnum_order = (get_magic_quotes_gpc()) ? $_POST['ordnum'] : addslashes($_POST['ordnum']);

mysql_select_db($database_swmisconn, $swmisconn);   ///calc amt due sum from order records 
$query_orders = "SELECT o.medrecnum, SUM(CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END) as amtdue FROM orders o join fee f on o.feeid = f.id WHERE o.id in ($colname_ordnum_order) GROUP BY o.medrecnum ORDER BY o.entrydt ASC";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
$totaldue = 0;
}
?>

 <?php
 $entrydate = date("Y-m-d  H:i:s");
 $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);   // Save a receipt record from amt paid above
	  $insertSQL = "INSERT INTO receipts (medrecnum, ordlist, amt, nbc, entrydt, entryby) VALUES ('" . $row_orders['medrecnum'] . "', '" . $colname_ordnum_order . "', '" . $_POST['paid'] . "', '" . $_POST['nbc'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";


 mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
}
 ?>
<?php 
mysql_select_db($database_swmisconn, $swmisconn);   // find the receipt number
$query_maxid = "SELECT MAX(id) mxid from receipts";  
$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
$row_maxid = mysql_fetch_assoc($maxid);
$totalRows_maxid = mysql_num_rows($maxid);

if (isset($colname_ordnum_order)) {
	
mysql_select_db($database_swmisconn, $swmisconn);  // get amtdue for each order in rordlist
$query_orders2 = "SELECT status, billstatus, IFNULL(amtpaid,0) amtpaid, CASE WHEN o.item IS NULL THEN o.amtdue ELSE o.ofee END as amtdue FROM orders o join fee f on o.feeid = f.id WHERE o.id = '" . $colname_ordnum_order . "'";
$orders2 = mysql_query($query_orders2, $swmisconn) or die(mysql_error());
$row_orders2 = mysql_fetch_assoc($orders2);
$totalRows_orders2 = mysql_num_rows($orders2);



 $editFormAction = $_SERVER['PHP_SELF'];
 $mystatus = '';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);  
		if($row_orders2['status'] == 'Refunded'){   //case where a refunded order is paid again (possibly partial)
		     $mystatus = 'ordered'; 
		}else {
		     $mystatus = $row_orders2['status'];
		}
		// calc billstatus
		$billstatus = $row_orders2['billstatus'];
  		if($row_orders2['amtpaid'] + $_POST['paid'] < $row_orders2['amtdue']) {
			$billstatus = 'PartPaid';
		}
  		if($row_orders2['amtpaid'] + $_POST['paid'] == $row_orders2['amtdue']) {
			$billstatus = 'Paid';
			if($mystatus == 'RxCosted'){
				$mystatus = 'RxPaid';
			} else {
		    $mystatus = $row_orders2['status'];
		}
		}
		$updateSQL = "UPDATE orders SET status = '".$mystatus."', billstatus = '".$billstatus."', amtpaid = '".($row_orders2['amtpaid'] + $_POST['paid'])."' WHERE id = '" . $colname_ordnum_order . "' ";
		

 mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  		$unpaid = '0';
  		$paid = $_POST['paid'];
        $unpaid = $row_orders2['amtdue'] - ($paid + $row_orders2['amtpaid']);
		$insertSQL = "INSERT INTO rcptord (rcptid, ordid, status, amtdue, amtpaid, unpaid) VALUES(" . $row_maxid['mxid'] . ", '".$colname_ordnum_order."', '".$billstatus."', '".$row_orders2['amtdue']."',  '" . $paid. "', '".$unpaid."')";

 mysql_select_db($database_swmisconn, $swmisconn);
  $Result2 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

	}
}
 ?>
<?php
mysql_free_result($orders);

?>

<?php
mysql_free_result($orders2);
$_SESSION['OrderList'] = "";
?>
<?php   $insertGoTo = "CashReceiptPP.php?Status=" . $_GET['Status'] . "&rid=" . $row_maxid['mxid']. "&ordid=" . $colname_ordnum_order . "";  
  header(sprintf("Location: %s", $insertGoTo));
?>
<?php ob_end_flush();?>
</body>
</html>

