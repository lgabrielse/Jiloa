<?php ob_start(); ?>
<?php  $pt = "Cashier-Paid"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php  $entrydate = date("Y-m-d  H:i:s");?>

<?php // IF DEPOSIT  ?>
<?php if(isset($_GET['selorder']) and isset($_GET['Status']) and isset($_GET['nbc']) and ($_GET['Status'] == 'Deposit' OR $_GET['Status'] == 'DepRefund')){  // and isset($_GET['mrn'])
// insert receipt info
	mysql_select_db($database_swmisconn, $swmisconn); 
		if($_GET['Status'] == 'DepRefund'){
			$insertSQL = "INSERT INTO receipts (medrecnum, ordlist, amt, nbc, entrydt, entryby) VALUES ('" . $_GET['mrn'] . "', '" . $_GET['selorder'] . "', '" .(0 - $_GET['amtpaid']). "', '" . $_GET['nbc'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";
 		} else {
	$insertSQL = "INSERT INTO receipts (medrecnum, ordlist, amt, nbc, entrydt, entryby) VALUES ('" . $_GET['mrn'] . "', '" . $_GET['selorder'] . "', '" . $_GET['amtpaid'] . "', '" . $_GET['nbc'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";
	 	}
	mysql_select_db($database_swmisconn, $swmisconn);
  	$Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	
// find new Receipt number
mysql_select_db($database_swmisconn, $swmisconn);   // find the receipt number
$query_maxid = "SELECT MAX(id) mxid from receipts";  
$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
$row_maxid = mysql_fetch_assoc($maxid);
$totalRows_maxid = mysql_num_rows($maxid);

// insert rcptord info
	if($_GET['Status'] == 'DepRefund'){
		$insertSQL = "INSERT INTO rcptord (rcptid, ordid, status, amtdue, amtpaid, unpaid) VALUES (" . $row_maxid['mxid'] . ", '".$_GET['selorder']."', 'DepRefund', '0', '".(0 -$_GET['amtpaid'])."', '0')";

 		} else {
		$insertSQL = "INSERT INTO rcptord (rcptid, ordid, status, amtdue, amtpaid, unpaid) VALUES (" . $row_maxid['mxid'] . ", '".$_GET['selorder']."', 'Deposited', '0',  '". $_GET['amtpaid'] ."', '0')";
		}	
		mysql_select_db($database_swmisconn, $swmisconn);
  		$Result2 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
//echo $_GET['selorder'].'amt: '.$_GET['amtpaid'];	
//exit;

} else { //status not = deposit or deprefund
?>

<?php
    $ordlist = '';
	if (!isset($_POST['selorder'])) {
 		//echo("You didn't select any orders.");
		$ordlist = '0';
	  }

	else {
	   $order = $_POST['selorder'];
    $N = count($order);
		for($i=0; $i < $N; $i++) {
		$ordlist = $ordlist . $order[$i].', ';
		//  echo($order[$i])."    ";
		}
	$ordlist = substr($ordlist, 0, -2);
	//echo $ordlist;
	}
//exit("ordlist: ".$ordlist) ;
 
$colname_ordlist_orders = "-1";
if (isset($ordlist) and $ordlist <> '0') {
  $colname_ordlist_orders = $ordlist;
}

// only one order is processed when refunding
mysql_select_db($database_swmisconn, $swmisconn);   ///calc amt due sum from order records 
$query_orders = "SELECT o.medrecnum, o.visitid, SUM(o.amtpaid) totamtpaid, SUM(amtdue) totamtdue FROM orders o WHERE o.id in ($colname_ordlist_orders) GROUP BY o.medrecnum ORDER BY o.entrydt ASC";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
$totaldue = 0;

?>

 <?php
 $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);   // Save a receipt record from amt paid above
	if($_GET['Status'] == 'Refund'){
	  $insertSQL = "INSERT INTO receipts (medrecnum, ordlist, amt, nbc, entrydt, entryby) VALUES ('" . $row_orders['medrecnum'] . "', '" . $colname_ordlist_orders . "', '" . (0 - $row_orders['totamtpaid']) . "', '" . $_POST['nbc'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";  

	} 
	else {
	  $insertSQL = "INSERT INTO receipts (medrecnum, ordlist, amt, nbc, entrydt, entryby) VALUES ('" . $row_orders['medrecnum'] . "', '" . $colname_ordlist_orders . "', '" . $row_orders['totamtdue'] . "', '" . $_POST['nbc'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";
	}

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
?>
 <?php $rordlist = "-1";
if (isset($order)) {
  $rordlist = $order ;
}
     $N = count($rordlist);
		for($i=0; $i < $N; $i++) {  // begin loop
	//	echo "***".$rordlist[$i];
	
mysql_select_db($database_swmisconn, $swmisconn);  // get amtdue for each order in rordlist
$query_orders2 = "SELECT billstatus, status, amtdue, comments FROM orders WHERE id = '" . $rordlist[$i] . "'";
$orders2 = mysql_query($query_orders2, $swmisconn) or die(mysql_error());
$row_orders2 = mysql_fetch_assoc($orders2);
$totalRows_orders2 = mysql_num_rows($orders2);



 $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);  //update each record amtpaid, receipt #

	if($_GET['Status'] == 'Refund'){
		$refComments = $row_orders2['comments'].' Refund: '.$row_orders2['amtdue'];
		if ($row_orders2['billstatus'] == 'Refund' and ($row_orders2['status'] == 'RxRefund')) { //or $row_orders2['status'] == 'RxDispensed'
			$updateSQL = "UPDATE orders SET status = 'RxRefunded', billstatus = 'Refunded', amtpaid = 0, amtdue = 0, comments = '".$refComments."' WHERE id = '" . $rordlist[$i] . "' ";
		} else {
			$updateSQL = "UPDATE orders SET status = 'Refunded', billstatus = 'Refunded', amtpaid = 0, amtdue = 0, comments = '".$refComments."' WHERE id = '" . $rordlist[$i] . "' ";
		}
	} else {
		if(($row_orders2['billstatus'] == 'Due' || $row_orders2['billstatus'] == 'paylater') and $row_orders2['status'] == 'RxCosted') {  //case where RxCosted order is paid
			$updateSQL = "UPDATE orders SET status = 'RxPaid', billstatus = 'Paid', amtpaid = '".$row_orders2['amtdue']."' WHERE id = '" . $rordlist[$i] . "' ";
		} else{
		$updateSQL = "UPDATE orders SET billstatus = 'Paid', amtpaid = '".$row_orders2['amtdue']."' WHERE id = '" . $rordlist[$i] . "' ";
		}
	}
 mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

	if($_GET['Status'] == 'Refund'){
		$insertSQL = "INSERT INTO rcptord (rcptid, ordid, status, amtdue, amtpaid, unpaid) VALUES (" . $row_maxid['mxid'] . ", '".$rordlist[$i]."', 'Refunded', '".$row_orders2['amtdue']."',  '".(0 - $row_orders2['amtdue'])."', '0')";
	} else {
		$insertSQL = "INSERT INTO rcptord (rcptid, ordid, status, amtdue, amtpaid, unpaid) VALUES (" . $row_maxid['mxid'] . ", '".$rordlist[$i]."', 'Paid', '".$row_orders2['amtdue']."', '".$row_orders2['amtdue']."', '0')";
		}

}
 mysql_select_db($database_swmisconn, $swmisconn);
  $Result2 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  }
?>



<?php
mysql_free_result($orders);

?>

<?php
	mysql_free_result($orders2);
?>
<?php } // status not deposit?>

<?php
	echo 'maxid: '.$row_maxid['mxid']. ' -- status: '.$row_orders2['status'] ;
?>
<?php   $insertGoTo = "CashReceipt.php?Status=".$_GET['Status']."&rid=".$row_maxid['mxid'];  
  header(sprintf("Location: %s", $insertGoTo));
?>
<?php ob_end_flush();?>
</body>
</html>
