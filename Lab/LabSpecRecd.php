<?php  $pt = "Collected"; ?>
<?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
 $_SESSION['CurrDateTime'] =  date("Y-m-d  H:i:s");
 $_SESSION['user']= $_POST['user'];
 $_SESSION['mrn']= $_POST['mrn'];
 // could use $_SESSION['OrderList'] here to be consistent
$colname_ordlist_orders = "-1";
if (isset($_POST['ordlist'])) {
   $chkord = $_POST['ordlist'];
  $colname_ordlist_orders = (get_magic_quotes_gpc()) ? $_POST['ordlist'] : addslashes($_POST['ordlist']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "SELECT o.id ordid, f.specid FROM orders o join fee f on o.feeid = f.id WHERE o.id in ($colname_ordlist_orders) ORDER BY o.entrydt ASC";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
$totaldue = 0;
echo '--'.$_POST['ordlist'].'--';

do { 
echo '--'.$row_orders['ordid'].'--   '.$_SERVER['QUERY_STRING'].'++';
	 $entrydate = date("Y-m-d  h:i:s");
	 $editFormAction = $_SERVER['PHP_SELF'];
	 if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	
		$insertSQL = "INSERT INTO speccollected (ordnum, specid, colldt, collby) VALUES ('" . $row_orders['ordid'] . "', '" . $row_orders['specid'] . "', '" . $entrydate . "', '" . $_SESSION['user'] . "')";
	
	 mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	}
	
	 $editFormAction = $_SERVER['PHP_SELF'];
	 if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	  $insertSQL = "UPDATE orders SET status = 'InLab' WHERE id = '" . $row_orders['ordid'] . "'";
	
	 mysql_select_db($database_swmisconn, $swmisconn);
	  $Result2 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	
	}
} while ($row_orders = mysql_fetch_assoc($orders));	

mysql_free_result($orders);
$_SESSION['OrderList'] = "";
?>
<?php   $insertGoTo = "LabSpecRecdPrtOnLoad.php?ordlist=" . $_POST['ordlist'] ."&user=". $_SESSION['user']."&mrn=". $_SESSION['mrn']."";  
  header(sprintf("Location: %s", $insertGoTo));
?>
</body>
</html>
