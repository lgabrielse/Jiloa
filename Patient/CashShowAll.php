<?php ob_start(); ?>
<?php  $pt = "Cashier Orders List"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formdep")) {

$Comments = $_POST['comments'].$_POST['amtpaid'];

  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, rate, ratereason, amtdue, amtpaid, billstatus, status, urgency, doctor, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($_POST['amtdue'], "int"),
                       GetSQLValueString(0, "int"),  
                       GetSQLValueString($_POST['billstatus'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($Comments, "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  

	mysql_select_db($database_swmisconn, $swmisconn);   // find the receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
 
$insertGoTo = "CashPaid.php?Status=".$_POST['getstatus']."&selorder=".$row_maxid['mxid']."&nbc=".$_POST['nbc']."&amtpaid=".$_POST['amtpaid']."&mrn=".$_POST['medrecnum'];  
  header(sprintf("Location: %s", $insertGoTo));
  //$saved = "true";
}

?>





<?php $colname_billstatus = "Due";
if (isset($_GET['billstatus'])) {
  $colname_billstatus = (get_magic_quotes_gpc()) ? $_GET['billstatus'] : addslashes($_GET['billstatus']);
} ?>

<?php 
	$colname_mrn = "-1";
if (isset($_GET['mrn']) and $_GET['mrn'] <> "") {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
 
mysql_select_db($database_swmisconn, $swmisconn);  //sdded join to patperm and patvisit so deleted vists that had orders would not be listed
$query_orders = "SELECT distinct o.id ordid, o.medrecnum, o.visitid, o.feeid, o.item, o.rate, o.ratereason, o.billstatus, o.status, o.urgency,substr(o.comments,1,20) comm20, o.comments, o.entryby, o.amtdue, o.amtpaid,  DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, f.dept, f.section, f.name, f.unit, f.descr, f.fee, amtdue, ddl.name ddlname FROM orders o join patperm p on o.medrecnum = p.medrecnum join patvisit v on (o.visitid = v.id or o.visitid = 0) join fee f on o.feeid = f.id join dropdownlist ddl on o.ratereason = ddl.id WHERE o.medrecnum = ".$colname_mrn." Order by o.visitid, o.id";  // o.billstatus <> 'Refunded' AND
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

}
else {
  $updateGoTo = "CashSearch.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if($totalRows_orders == 0){
  $updateGoTo = "CashSearch.php";
  header(sprintf("Location: %s", $updateGoTo));
} ?>

<?php  // go to CashShow.php if Refund
  if($colname_billstatus == 'Refund') {
     $insertGoTo = "CashShow.php?mrn=".$colname_mrn."&billstatus=".$colname_billstatus;  
  header(sprintf("Location: %s", $insertGoTo));
  }
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_PayBy = "SELECT name FROM dropdownlist WHERE list = 'PayBy' AND name not in ('PHAdmin','BMCAdmin', 'CAC', 'Destitute', 'Cash', 'test', 'Deposit') ORDER BY seq ASC";
$PayBy = mysql_query($query_PayBy, $swmisconn) or die(mysql_error());
$row_PayBy = mysql_fetch_assoc($PayBy);
$totalRows_PayBy = mysql_num_rows($PayBy);
 ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=400,screenX=300,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
<style type="text/css">
     .right { text-align: right; }
</style>
</head>

<body>

<!-- Display PATIENT PERMANENT Data  -->
<?php $patview = "PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>
<?php if(isset($_GET['status']) and ($_GET['status'] == 'Deposit' OR $_GET['status'] == 'DepRefund')){ ?>
<?php
$colname_mrn2 = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn2 = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
$colname_visitid2 = "-1";
if (isset($_GET['visitid'])) {
  $colname_visitid2 = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
}
  if($colname_visitid2 == 0){
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_PatVisit = "Select p.medrecnum, lastName, firstName, otherName, gender, dob, est from patPerm p where p.medrecnum = '".$colname_mrn2."'";
	$PatVisit = mysql_query($query_PatVisit, $swmisconn) or die(mysql_error());
	$row_PatVisit = mysql_fetch_assoc($PatVisit);
	$totalRows_PatVisit = mysql_num_rows($PatVisit);
} else {
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_PatVisit = "Select p.medrecnum, lastName, firstName, otherName, gender, dob, est, pat_Type, location from patPerm p join patVisit v on p.medrecnum = v.medrecnum where v.id <> 0 and  p.medrecnum = '".$colname_mrn2."' and v.id = '".$colname_visitid2."'";
	$PatVisit = mysql_query($query_PatVisit, $swmisconn) or die(mysql_error());
	$row_PatVisit = mysql_fetch_assoc($PatVisit);
	$totalRows_PatVisit = mysql_num_rows($PatVisit);
  }
?>


<?php if($_GET['status'] == 'DepRefund'){?>
<div align="center" class="GreenBold_18">ACCOUNT REFUND Bal=<?php echo $AcctBal ?></div>
<?php } else {?>
	<div align="center" class="GreenBold_18">ACCOUNT DEPOSIT</div>
<?php }?>
<table width="40%" border="1" align="center" bgcolor="#BCFACC">
<form id="formdep" name="formdep" method="POST" action="<?php echo $editFormAction; ?>">
  <tr>
    <td align="right" nowrap="nowrap" class="BlackBold_14">MedRecNum = </td>
    <td><span class="BlackBold_14"><?php echo $row_PatVisit['medrecnum']; ?></span></td>
  </tr>
  <tr>
    <td class="BlackBold_14">Patient Name </td>
    <td nowrap="nowrap" bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $row_PatVisit['lastName']; ?>, <?php echo $row_PatVisit['firstName']; ?> (<?php echo $row_PatVisit['otherName']; ?>)</td>
  </tr>
  <tr>
    <td class="BlackBold_14">Age, (Gender) </td>
    <td bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $patage ?>, (<?php echo $row_PatVisit['gender']; ?>)</td>
  </tr>
<?php if(isset($_GET['visitid']) and $_GET['visitid'] > 0){ ?>
  <tr>
    <td class="BlackBold_14">Location</td>
    <td nowrap="nowrap" bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $row_PatVisit['pat_Type']; ?>-<?php echo $row_PatVisit['location']; ?></td>
  </tr>
<?php  } else { ?>
  <tr>
    <td class="BlackBold_14">Location</td>
    <td bgcolor="#FAFAF0" class="GreenBold_14">No Visit Yet Registration Only</td>
  </tr>
<?php  } ?>

  <tr>
<?php if($_GET['status'] == 'DepRefund'){?>
    <td class="BlackBold_14">Refund Amount </td>
<?php } else {?>
    <td class="BlackBold_14">Deposit Amount </td>
<?php }?>
    <td><input type="text" name="amtpaid" autocomplete="off"/>
      <select name="nbc" id="nbc">
	<?php do { ?>
		<option value="<?php echo $row_PayBy['name']?>"><?php echo $row_PayBy['name']?></option>
	<?php } while ($row_PayBy = mysql_fetch_assoc($PayBy));
		  $rows = mysql_num_rows($PayBy);
		  if($rows > 0) {
			  mysql_data_seek($PayBy, 0);
			  $row_PayBy = mysql_fetch_assoc($PayBy);
		  } ?>
  		</select>	  </td>
  </tr>
  <tr>
    <td><a href="CashShowAll.php?mrn=<?php echo $colname_mrn ?>">CLOSE</a>
		<input name="medrecnum" type="hidden" value="<?php echo $colname_mrn2 ?>" />
		<input name="visitid" type="hidden" value="<?php echo $colname_visitid2 ?>" />
<?php if($_GET['status'] == 'DepRefund'){?>
		<input name="getstatus" type="hidden" value="DepRefund" />
		<input name="feeid" type="hidden" value="280" />
		<input name="amtdue" type="hidden" value="0" />
		<input name="billstatus" type="hidden" value="DepRefund" />
		<input name="status" type="hidden" value="Ordered" />
		<input name="comments" type="hidden" value="Deposit Refund: " />
<?php } else {?>
		<input name="getstatus" type="hidden" value="Deposit" />
		<input name="feeid" type="hidden" value="279" />
		<input name="amtdue" type="hidden" value="0" />
		<input name="billstatus" type="hidden" value="Paid" />
		<input name="status" type="hidden" value="Ordered" />
		<input name="comments" type="hidden" value="Deposit: " />
<?php }?>
		<input name="rate" type="hidden" value="100" />
		<input name="ratereason" type="hidden" value="103" />	
		<input name="urgency" type="hidden" value="Routine" />
		<input name="doctor" type="hidden" value="NA" />
		<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
		<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />	</td>
    <td align="right">

	
	<input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
  </tr>
  <input type="hidden" name="MM_insert" value="formdep"; />
  </form>
</table>
	
	
<?php }  else {?>


<?php $bkgd="#f5f5f5"?>
<?php if (isset($_GET['mrn']) AND $totalRows_orders > 0) {
	$i = 0; ?>
<table align="center">
  <form id="form1" name="form1" method="post" action="CashShowTot.php" >
    <tr>
      <td class="BlueBold_14"><div align="center"></div></td>
      <td nowrap="nowrap" class="BlueBold_14">Visit</td>
      <td nowrap="nowrap" class="BlueBold_14"><div align="center">Entrydt*(<?php echo $totalRows_orders ?>)</div></td>
      <td class="BlueBold_14"><div align="center">Dept</div></td>
      <td class="BlueBold_14"><div align="center">Section</div></td>
      <td class="BlueBold_14"><div align="center">Name*</div></td>
      <td class="BlueBold_14"><div align="center">Unit</div></td>
      <td class="BlueBold_14"><div align="center">Rate*</div></td>
      <td class="BlueBold_14"><div align="center">Status</div></td>
      <td class="BlueBold_14"><div align="center">Urgency</div></td>
      <td class="BlueBold_14"><div align="center">Amt Due</div></td>
      <td class="BlueBold_14"><div align="center">Paid</div></td>
      <td class="BlueBold_14"><div align="center">Unpaid</div></td>
      <td class="BlueBold_14"><div align="center">Select</div></td>
      <td class="BlueBold_14"><div align="center">Rcpt(Pd From)</div></td>
      <td class="BlueBold_14"><div align="center">Comments*</div></td>
      <td class="BlueBold_14"><div align="center"><a href="CashShowHist.php?mrn=<?php echo $row_orders['medrecnum']; ?>">Hist</a> <a href="CashShowHist2.php?mrn=<?php echo $row_orders['medrecnum']; ?>">Hist2</a></div></td>
    </tr>
   <?php $lastVid = -1 ?>
   <?php do { 
	  $amtunpaid = 0;
	  $bkgd="#F5F5F5"; //grey
	  if ($row_orders['amtpaid'] < $row_orders['amtdue'] and $row_orders['billstatus'] <> 'Refunded') {
		$bkgd="#F8FDCE"; //yellow
		$amtunpaid = $row_orders['amtdue'] - $row_orders['amtpaid'];
 	} ?>
    <tr>
<?php if($row_orders['visitid'] <> 0 and $lastVid <> $row_orders['visitid']){  ?>
      <td class="BlueBold_14"><div align="center">
  <?php if($AcctBal > 0 AND allow(53,4) == 1){?>
  <a href="CashShowAll.php?status=DepRefund&mrn=<?php echo $row_orders['medrecnum']; ?>&visitid=<?php echo $row_orders['visitid']; ?>" class="flagBlackonOrange">REF</a>
          <?php }?>
          <a href="CashShowAll.php?status=Deposit&mrn=<?php echo $row_orders['medrecnum']; ?>&visitid=<?php echo $row_orders['visitid']; ?>">DEP</a>
          <?php } else {  ?>
        </div></td>
        <td class="BlueBold_14"><div align="center"></div></td>
        <?php }?>
      <td bgcolor=<?php echo $bkgd ?> title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;Ord ID: <?php echo $row_orders['ordid']; ?>&#10;Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['visitid']; ?></td>
      <td bgcolor=<?php echo $bkgd ?> title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?>&#10;Ord ID: <?php echo $row_orders['ordid']; ?>&#10;Entry BY: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
      <td bgcolor=<?php echo $bkgd ?>><?php echo $row_orders['dept']; ?></td>
      <td bgcolor=<?php echo $bkgd ?>><?php echo $row_orders['section']; ?></td>
      <td bgcolor=<?php echo $bkgd ?> title="Description: : <?php echo $row_orders['descr']; ?>"><?php echo $row_orders['name']; ?> <?php echo $row_orders['item']; ?></td>
      <td bgcolor=<?php echo $bkgd ?>><?php echo $row_orders['unit']; ?></td>
      <td bgcolor=<?php echo $bkgd ?> title="Rate Reason: <?php echo $row_orders['ddlname']; ?>"><?php echo $row_orders['rate']; ?></td>

<?php if($row_orders['billstatus'] == 'paylater'){?>
      <td bgcolor=<?php echo $bkgd ?> title="billstatus:<?php echo $row_orders['billstatus']; ?>" ><?php echo $row_orders['status']; ?><span class="flagBlackonOrange">PL</span></td>
<?php } else {?>
      <td bgcolor=<?php echo $bkgd ?> title="billstatus:<?php echo $row_orders['billstatus']; ?>" ><?php echo $row_orders['status']; ?></td>
<?php }?>

      <td bgcolor=<?php echo $bkgd ?>><?php echo $row_orders['urgency']; ?></td>
      <td bgcolor=<?php echo $bkgd ?>><div align="center">
        <input class="right" name="amtdue2" readonly="readonly" type="text" value="<?php echo number_format($row_orders['amtdue'],0); ?>" size="5" />
      </div></td>
      <td bgcolor=<?php echo $bkgd ?> nowrap="nowrap"><div align="center"><?php echo number_format($row_orders['amtpaid'],0); ?></div></td>
      <td bgcolor=<?php echo $bkgd ?> nowrap="nowrap"><div align="center"><?php echo number_format($amtunpaid,0); ?></div></td>
      <?php if (($row_orders['billstatus'] == 'Due' or $row_orders['billstatus'] == 'paylater') and $row_orders['status'] != 'RxOrdered' and $row_orders['status'] != 'RxPaylater') {?>
      <td align="center" bgcolor=<?php echo $bkgd ?>><input type="checkbox" name="anyorder[]" value="<?php echo $row_orders['ordid']; ?>" /></td>
      <?php
	$i = $i + 1; }
	else { ?>
      <td align="center" bgcolor="#F5F5F5">&nbsp;-&nbsp;</td>
      <?php } 
    mysql_select_db($database_swmisconn, $swmisconn);
	$query_recpt = "SELECT r.id, status, nbc from receipts r join rcptord ro on r.id = ro.rcptid where r.medrecnum = ".$row_orders['medrecnum']." and ro.ordid = ".$row_orders['ordid']."";
	$recpt = mysql_query($query_recpt, $swmisconn) or die(mysql_error());
	$row_recpt = mysql_fetch_assoc($recpt);
	$totalRows_recpt = mysql_num_rows($recpt);

   if($totalRows_recpt > 0) { 
      
	 do { ?>
      <td nowrap="nowrap" bgcolor=<?php echo $bkgd ?> class="BlackBold_10"><a href="CashReceiptPP.php?rid=<?php echo $row_recpt['id']?>&Status=<?php echo $row_recpt['status'] ?>"><?php echo $row_recpt['id']?>(<?php echo $row_recpt['nbc']?>)</a></td>
          <?php   }  while ($row_recpt = mysql_fetch_assoc($recpt));
 		} else { ?>
      
      <td bgcolor=<?php echo $bkgd ?> >&nbsp;</td>
      <?php }?>
      <td bgcolor=<?php echo $bkgd ?> title="<?php echo $row_orders['comments']; ?>"><?php echo $row_orders['comm20']; ?></td>
      <?php if($row_orders['amtpaid'] > 0 and $row_orders['status'] != "Refund" and $row_orders['status'] != "Refunded" and $row_orders['billstatus'] != "PartPaid" and allow(30,4) == 1) { ?>
      <td title="REFUND"><a href="javascript:void(0)" onclick="MM_openBrWindow('../Lab/LabPopRefund.php?ordid=<?php echo $row_orders['ordid'] ?>&name=<?php echo $row_orders['name'] ?>&amtpaid=<?php echo $row_orders['amtpaid'] ?>&dept=<?php echo $row_orders['dept'] ?>&section=<?php echo $row_orders['section'] ?>&status=<?php echo $row_orders['status'] ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=450')">$</a></td>
      <?php   }?>
      <?php if($amtunpaid > 0 and ($row_orders['billstatus'] == 'Due' or $row_orders['billstatus'] == 'PartPaid')) { ?>
      <td bgcolor = "ff9900" title="Partial Payment"><a href="CashShowPP.php?ordid=<?php echo $row_orders['ordid'] ?>">PP</a></td>
        <?php 	}?>
      </tr>
      <?php $lastVid = $row_orders['visitid'] ?>
      <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
      <?php if ($i > 0) { ?>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td colspan="9" align="center"><a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=order">Re-Select</a></td>
        <td colspan="2" align="right"><input type="submit" name="Submit2" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Summarize"/></td>
      </tr>
      <?php 	}?>
    </form></table>      <a href="CashShow.php?mrn=<?php echo $_SESSION['mrn']; ?>&Status=order"></a></td>
  </tr>
</form>
</table>
<?php $chkcash = "checkbox-cash.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php ///require_once($chkcash); ?></td>
	</tr>
</table>
<?php
mysql_free_result($orders);
?>
<?php }  //if mrn
	else {
?>
 <table align="center">
	<tr>
		<td nowrap="nowrap" class="GreenBold_36">No orders</td>
		<td nowrap="nowrap"><a href="CashSearch.php" class="nav">Re-Select</a></td>
	</tr>
</table>
<?php   } ?>

<?php } ?>
<?php ob_end_flush();?>

</body>
</html>

<!--
find orders:

SELECT o.id, o.status, o.feeid, r.id from orders o join receipts r on instr(r.ordlist,o.id)> 0 where r.id = 111


find receipt
SELECT r.id, r.ordlist, r.medrecnum, o.id FROM receipts r join orders o on instr(r.ordlist,o.id)>0 where o.id = '2126'
-->