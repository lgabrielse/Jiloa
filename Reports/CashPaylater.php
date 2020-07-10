<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_paylater = "SELECT o.id oid, o.medrecnum, o.visitid, o.feeid, o.item, o.ofee, o.rate, o.amtdue, o.status, o.doctor, o.comments, o.entryby, o.entrydt, o.amtpaid, v.pat_type, v.location, v.discharge, p.lastname, p.firstname, p.othername, p.gender, p.dob, f.section, f.name FROM orders o join patvisit v on o.visitid = v.id join patperm p on o.medrecnum = p.medrecnum join fee f on o.feeid = f.id WHERE billstatus = 'paylater' ORDER BY v.id, o.entrydt DESC";
$paylater = mysql_query($query_paylater, $swmisconn) or die(mysql_error());
$row_paylater = mysql_fetch_assoc($paylater);
$totalRows_paylater = mysql_num_rows($paylater);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<link href="CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<title>Pay Later</title>
</head>

<body>
<table width="50%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td><a href="CashierReptMenu.php">Cahier Reports Menu</a></td>
    <td>&nbsp;</td>
    <td colspan="2" align="center" class="BlueBold_24">PAY LATER REPORT</td>
    <td>&nbsp;</td>
    <td>Date: <?php echo Date("Y-m-d H:i"); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>* = more info</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center" class="BlueBold_14">Patient*</td>
    <td align="center" class="BlueBold_14">item</td>
    <td align="center" class="BlueBold_14">amtdue*</td>
    <td align="center" class="BlueBold_14">doctor</td>
    <td align="center" class="BlueBold_14">comments</td>
  </tr>
  <?php  $_SESSION['payMRN'] = '';
				 $total = 0; ?>
  <?php do { 
		if($row_paylater['medrecnum'] != $_SESSION['payMRN'] && $_SESSION['payMRN'] != '') {  ?>
		 <tr>
   		 	<td>&nbsp;</td>
   		 	<td>&nbsp;</td>
     		<td class="flagWhiteonRed"><?php echo $total; ?></td>
        <td><a href="CashPaylaterCalls.php?mrn=<?php echo $_SESSION['payMRN']; ?>">Call</a></td>

     </tr>			
    <?php			
  $total = 0;			}
	?>
    <tr>
      <td title="MRN: <?php echo $row_paylater['medrecnum']; ?>&#10;VisitID: <?php echo $row_paylater['visitid']; ?>&#10;Order ID: <?php echo $row_paylater['oid']; ?>&#10;FeeID: <?php echo $row_paylater['feeid']; ?>"><?php echo $row_paylater['lastname'].', '.$row_paylater['firstname'].', '.$row_paylater['othername']; ?></td>
			<td><?php echo $row_paylater['section']; ?>: <?php echo $row_paylater['name']; ?></td>      
      <td title="Rate: <?php echo $row_paylater['rate']; ?>&#10;AmtPaid: <?php echo $row_paylater['amtpaid']; ?>&#10;EntryBy: <?php echo $row_paylater['entryby']; ?>&#10;EntryBy: <?php echo $row_paylater['entrydt']; ?>"&#10;Order Status: <?php echo $row_paylater['status']; ?>><?php echo $row_paylater['amtdue']; ?></td>
      <td><?php echo $row_paylater['doctor']; ?></td>
      <td><?php echo $row_paylater['comments']; ?></td>
    </tr>
    <?php $_SESSION['payMRN'] = $row_paylater['medrecnum']; 
					$total = $total + $row_paylater['amtdue'] ;?>
    <?php } while ($row_paylater = mysql_fetch_assoc($paylater)); ?>
		 <tr>
     		<td>&nbsp;</td>
  	  	<td>&nbsp;</td>
     		<td class="flagWhiteonRed"><?php echo $total; ?></td>
	      <td><a href="CashPaylaterCalls.php?mrn=<?php echo $_SESSION['payMRN']; ?>">Call</a></td>
        
     </tr>			

</table>
</body>
</html>
<?php
mysql_free_result($paylater);
?>
