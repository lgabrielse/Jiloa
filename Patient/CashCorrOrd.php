<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php $pt = "Exam Setup Menu"; ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php require_once('../../Connections/swmisconn.php'); ?>
<?php

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
  $saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orders SET amtdue=%s, billstatus=%s, amtpaid=%s, ofee=%s WHERE id=%s",
                       GetSQLValueString($_POST['amtdue'], "int"),
                       GetSQLValueString($_POST['billstatus'], "text"),
                       GetSQLValueString($_POST['amtpaid'], "int"),
                       GetSQLValueString($_POST['ofee'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

if (isset($_POST["roid"]) && ($_POST["rcptCnt"] == 1) &&  ($_POST["roid"] > 1)){
  $updateSQL = sprintf("UPDATE rcptord SET amtdue=%s, status=%s, amtpaid=%s WHERE id=%s",
                       GetSQLValueString($_POST['amtdue'], "int"),
                       GetSQLValueString($_POST['billstatus'], "text"),
                       GetSQLValueString($_POST['amtpaid'], "int"),
                       GetSQLValueString($_POST['roid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
}


  $saved = "true";
}

$colname_visitid = "-1";
if (isset($_GET['visitid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
}
$colname_ordid = "-1";
if (isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
}


mysql_select_db($database_swmisconn, $swmisconn);
if($colname_visitid == 0){
$query_order = "Select p.lastname, p.firstname, p.gender, o.id ordid, o.medrecnum, o.visitid, o.feeid, o.rate, o.amtdue, o.amtpaid, o.status, o.billstatus, f.name from orders o join patperm p on o.medrecnum = p.medrecnum join fee f on f.id = o.feeid where o.id = '".$colname_ordid."'";
} else {
$query_order = "Select p.lastname, p.firstname, p.gender, v.pat_type, v.location, o.id ordid, o.item, ofee, o.medrecnum, visitid, feeid, rate, amtdue, amtpaid, o.status, billstatus, f.name from orders o join patperm p on o.medrecnum = p.medrecnum join patvisit v on v.id = o.visitid join fee f on f.id = o.feeid where o.id = '".$colname_ordid."'";
}
$order = mysql_query($query_order, $swmisconn) or die(mysql_error());
$row_order = mysql_fetch_assoc($order);
$totalRows_order = mysql_num_rows($order);

mysql_select_db($database_swmisconn, $swmisconn);  // find the rcptord record for this transaction so it can be updated
$query_rctpord2 = "Select id, ordid, status from rcptord where ordid = '".$colname_ordid."' and amtdue = '".$row_order['amtdue']."' and amtpaid = '".$row_order['amtpaid']."' and status = '".$row_order['billstatus']."'";
$rctpord2 = mysql_query($query_rctpord2, $swmisconn) or die(mysql_error());
$row_rctpord2 = mysql_fetch_assoc($rctpord2);
$totalRows_rctpord2 = mysql_num_rows($rctpord2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Correct Order Billing</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<?php if($saved == "true") { ?>
	<body onload="out()">
	<?php }?>

<p align="center" class="BlueBold_24">Correct Billing in ORDER <?php echo $colname_ordid ?></p>
    <table width="40%" border="1"  bgcolor="#dff1ff" align="center">
      <?php do { ?>
      <tr>
        <td>MedRecNum</td>
        <td colspan="2"><?php echo $row_order['medrecnum']; ?></td>
      </tr>
      <tr>
        <td>Name</td>
        <td colspan="2"><?php echo $row_order['lastname']; ?>, <?php echo $row_order['firstname']; ?> ( <?php echo $row_order['gender']; ?> ) </td>
      </tr>
<?php if($colname_visitid <> 0){?>
      <tr>
        <td>Location</td>
        <td colspan="2"><?php echo $row_order['pat_type']; ?>-<?php echo $row_order['location']; ?></td>
      </tr>
<?php  } ?> 
      <tr>
        <td>OrdStatus</td>
        <td colspan="2"><?php echo $row_order['status']; ?></td>
      </tr>
      <tr>
        <td>Rate </td>
        <td colspan="2"><?php echo $row_order['rate']; ?></td>
      </tr>
      <tr>
        <td>Fee ID - Name </td>
        <td colspan="2"><?php echo $row_order['feeid']; ?>-<?php echo $row_order['name']; ?></td>
      </tr>
<?php if($colname_visitid <> 0){?>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td>Drug-RxCost</td>
        <td><?php echo $row_order['item']; ?>-<?php echo $row_order['ofee']; ?></td>
        <td><input type="text" name="ofee" autocomplete="off" value="<?php echo $row_order['ofee']; ?>" /></td>
      </tr>
<?php } else { ?> 
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
<?php } ?>
        <tr>
          <td>AmtDue</td>
          <td><?php echo $row_order['amtdue']; ?></td>
          <td><input type="text" name="amtdue" autocomplete="off" value="<?php echo $row_order['amtdue']; ?>" />          </td>
        </tr>
        <tr>
          <td>AmtPaid</td>
          <td><?php echo $row_order['amtpaid']; ?></td>
          <td><input type="text" name="amtpaid" autocomplete="off" value="<?php echo $row_order['amtpaid']; ?>"/>          </td>
        </tr>
        <tr>
          <td>BillStatus </td>
          <td><?php echo $row_order['billstatus']; ?></td>
		  <td><select name="billstatus" id="billstatus" >
				  <option value="Due" <?php if (!(strcmp("Due", $row_order['billstatus']))) {echo "selected=\"selected\"";} ?>>Due</option>
				  <option value="Paid" <?php if (!(strcmp("Paid", $row_order['billstatus']))) {echo "selected=\"selected\"";} ?>>Paid</option>
				  <option value="PartPaid" <?php if (!(strcmp("PartPaid", $row_order['billstatus']))) {echo "selected=\"selected\"";} ?>>PartPaid</option>
				  <option value="Refund" <?php if (!(strcmp("Refund", $row_order['billstatus']))) {echo "selected=\"selected\"";} ?>>Refund</option>
				  <option value="Refunded" <?php if (!(strcmp("Refunded", $row_order['billstatus']))) {echo "selected=\"selected\"";} ?>>Refunded</option>
			  </select>
		  </td>

        </tr>
        <tr>
          <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
          <input type="hidden" name="id" value="<?php echo $row_order['ordid']; ?>" />
          <input type="hidden" name="roid" value="<?php echo $row_rctpord2['id']; ?>" /></td>
          <td><input type="hidden" name="rcptCnt" value="<?php echo $totalRows_rctpord2 ?>" /></td>
          <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
        </tr>
        <tr>
          <td colspan="2">rcptord recs = <?php echo $totalRows_rctpord2 ?></td>
          <td>rcptord.id = <?php echo $row_rctpord2['id']; ?></td>
        </tr>
        <?php } while ($row_order = mysql_fetch_assoc($order)); ?>
        <input type="hidden" name="MM_update" value="form1" />
      </form>
    </table>
	</body>
</html>
<?php
mysql_free_result($rctpord2);

mysql_free_result($order);
?>
