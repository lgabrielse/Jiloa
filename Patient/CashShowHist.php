<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/functions.php'); ?>

<?php
$colname_patperm = "503"; //"-1";
if (isset($_GET['mrn'])) {
  $colname_patperm = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $colname_patperm."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

mysql_select_db($database_swmisconn, $swmisconn);
$query_visits = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, height, weight, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE medrecnum = %s", $colname_patperm);
$visits = mysql_query($query_visits, $swmisconn) or die(mysql_error());
$row_visits = mysql_fetch_assoc($visits);
$totalRows_visits = mysql_num_rows($visits);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient Billing History</title>
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

</head>

<body>

<!-- Begin PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT -   -->
 
  <table width="700px"  border="0" align="center" bordercolor="#000000" border-collapse="collapse">
	  <tr>
	  	<td colspan="7" class ="BlueBold_18"><div align="center"><a href="CashShowAll.php?mrn=<?php echo $row_patperm['medrecnum']; ?>">Back</a> &nbsp;&nbsp;&nbsp;&nbsp;Patient Payment History</div></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?></td>
		<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_16"><?php echo $row_patperm['lastname']; ?></span>, <span class="BlueBold_16"><?php echo $row_patperm['firstname']; ?></span> (<span class="BlueBold_16"><?php echo $row_patperm['othername']; ?></span>) </td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
		<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_patperm['dob']; ?></span>:<?php echo $row_patperm['est']; ?></td>
	  </tr>
</table>

<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_order19 = "Select o.id ordid, o.medrecnum mrn, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.visitid, o.rate, o.status ostatus, o.billstatus, o.doctor, o.amtdue, o.amtpaid, o.entryby, o.comments, f.dept, f.section, f.name, DATE_FORMAT(r.entrydt,'%d-%b-%Y') rentrydt, DATE_FORMAT(r.entrydt,'%H:%i') rentrytime, r.medrecnum, r.id rid, r.amt, r.nbc, ro.status rostatus, ro.amtdue roamtdue, ro.amtpaid roamtpaid, ro.unpaid rounpaid  from orders o join fee f on o.feeid = f.id left outer join rcptord ro on o.id = ro.ordid left outer join receipts r on r.id = ro.rcptid where (o.medrecnum = '".$colname_patperm."' and o.visitid = '0') ORDER BY visitid, ordid, r.id";
$order19 = mysql_query($query_order19, $swmisconn) or die(mysql_error());
$row_order19 = mysql_fetch_assoc($order19);
$totalRows_order19 = mysql_num_rows($order19);
?>
	<table  border="0" align="center" bordercolor="#000000" border-collapse="collapse">
	  <tr>
		<td bgcolor="#dff1ff" ><div align="center">Order<br /> 
		  ID</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Fee<br /> 
		  Dept</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Fee<br /> 
		  Section</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br /> 
		  Name </div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br />
		  Rate</div></td>
		<td bgcolor="#dff1ff"><div align="center">Current<br />
		  Order<br />
		  Status</div></td>
		<td bgcolor="#dff1ff"><div align="center">Current<br />
		  Order<br />
		  AmtDue</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Current<br />
		  Order<br />
		  AmtPaid</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Current<br />
		  Order<br />
		  BillStatus</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br /> 
		  Status</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br /> 
		  AmtDue</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br />
		  AmtPaid</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br />
		  UnPaid<br />
		  Partial</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Receipt<br />
		  Amt</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Receipt<br />
		  Num</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Pmnt<br />
		  Type</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Receipt<br />
		  Date</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br />
		  Comments</div></td>
	  </tr>


<?php  $lastorder = 0; ?>
<?php do { ?>
	  <tr>
<?php   if($row_order19['ordid']<> $lastorder ){ ?> 
	<?php if(allow(53,4) == 1) { ?>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><a href="javascript:void(0)" onclick="MM_openBrWindow('CashCorrOrd.php?visitid=<?php echo $row_order19['visitid']; ?>&ordid=<?php echo $row_order19['ordid']; ?>&mrn=<?php echo $row_order19['mrn']; ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=400')"><?php echo $row_order19['ordid']; ?></a></td>
	<?php 	} else {?>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_order19['ordid']; ?></td>
	<?php  }  ?>			
		<td bgcolor="#FFFFDA" class="BlackBold_11" ><?php echo $row_order19['dept']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" ><?php echo $row_order19['section']; ?></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_order19['name']; ?></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_order19['rate']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_order19['ostatus']; ?>"><?php echo $row_order19['ostatus']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_order19['ostatus']; ?>"><div align="center"><?php echo $row_order19['amtdue']; ?></div></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_order19['ostatus']; ?>"><div align="center"><?php echo $row_order19['amtpaid']; ?></div></td>
		<td bgcolor="#fffdda" class="BlackBold_11" title="current order status: <?php echo $row_order19['ostatus']; ?>"><?php echo $row_order19['billstatus']; ?></td>
<?php  }  else { ?>	
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>

<?php  }  ?>	
		
		<td bgcolor="#fafafa" class="BlackBold_11" title="current order status: <?php echo $row_order19['ostatus']; ?>"><?php echo $row_order19['rostatus']; ?></td>
		<td bgcolor="#fafafa" class="BlackBold_11" title="curent order amt due: <?php echo number_format($row_order19['amtdue']); ?>"><div align="center"><?php echo $row_order19['roamtdue']; ?></div></td>
		<td bgcolor="#fafafa" class="BlackBold_11" title="current order amtpaid:<?php echo $row_order19['amtpaid']; ?>"><div align="center"><?php echo $row_order19['roamtpaid']; ?></div></td>
		<td bgcolor="#fafafa" class="BlackBold_11" ><div align="center"><?php echo $row_order19['rounpaid']; ?></div></td>
		<td bgcolor="#fffdda" class="BlackBold_11" ><div align="center"><?php echo $row_order19['amt']; ?></div></td>

	<?php if(allow(53,4) == 1) { ?>
		<td bgcolor="#fffdda" class="Black_10" ><div align="center">#<a href="javascript:void(0)" onclick="MM_openBrWindow('CashCorrRcpt.php?mrn=<?php echo $row_order19['medrecnum']; ?>&rid=<?php echo $row_order19['rid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=400')"><?php echo $row_order19['rid']; ?></a></div></td>
	<?php 	} else {?>
		<td bgcolor="#fffdda" class="Black_10" ><div align="center">#<?php echo $row_order19['rid']; ?></div></td>
	<?php  }  ?>			

		<td bgcolor="#fffdda" class="Black_10" ><div align="center"><?php echo $row_order19['nbc']; ?></div></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11" title="Entry time: <?php echo $row_order19['rentrytime']; ?>&#10;Entry By: <?php echo $row_order19['entryby']; ?>"><?php echo $row_order19['rentrydt']; ?></td>
		<td bgcolor="#fffdda" class="Black_10" title="<?php echo $row_order19['comments']; ?>"><?php echo $row_order19['comments']; ?></td>
	  </tr>
<?php $lastorder = $row_order19['ordid'];	?>

  <?php } while ($row_order19 = mysql_fetch_assoc($order19)); ?>
  </table>


<table width="700px" align="center">
<tr>
<td>
<!--Begin VISIT - VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT - -->  
  <?php do { ?>
<p>&nbsp;</p>
<table width="900px" align="center" bgcolor="#FFEEDD">
    <tr>
<!--      <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
-->      <td nowrap="nowrap" Title="Entry Date: <?php echo $row_visits['entrydt']; ?>&#10; Entry By: <?php echo $row_visits['entryby']; ?>"><span class="BlueBold_16">Visit</span> #: <span class="BlueBold_16"><?php echo $row_visits['id']; ?></span></td>
      <td nowrap="nowrap">Date:</td>
      <td nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['visitdate']; ?></span></td>
      <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type-Location:<span class="BlueBold_16"><?php echo $row_visits['location']; ?>-<?php echo $row_visits['pat_type']; ?></span></td>
      <td colspan="1"nowrap="nowrap"><div align="right">Urgency:</div></td>
      <td colspan="1" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['urgency']; ?></span></td>
      <td><div align="right">Discharged:</div></td>
      <td colspan="1" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['discharge']; ?></span></td>
    </tr>
    </table>
<!--Begin ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS -
MAY NEED A SEPARATE QUERY FOR REGISTRATION !! if added to  every visit -->




<!-- >>>>>>>Orders>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, o.medrecnum mrn, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.visitid, o.rate, o.status ostatus, o.billstatus, o.doctor, o.amtdue, o.amtpaid, o.entryby, o.comments, f.dept, f.section, f.name, DATE_FORMAT(r.entrydt,'%d-%b-%Y') rentrydt, DATE_FORMAT(r.entrydt,'%H:%i') rentrytime, r.medrecnum, r.id rid, r.amt, r.nbc, ro.status rostatus, ro.amtdue roamtdue, ro.amtpaid roamtpaid, ro.unpaid rounpaid  from orders o join fee f on o.feeid = f.id left outer join rcptord ro on o.id = ro.ordid left outer join receipts r on r.id = ro.rcptid where o.visitid <> '0' and o.visitid = '".$row_visits['id']."' ORDER BY visitid, ordid, r.id";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
	<table  border="0" align="center" bordercolor="#000000" border-collapse="collapse">
	  <tr>
		<td bgcolor="#dff1ff" ><div align="center">Order<br /> 
		  ID</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Fee<br /> 
		  Dept</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Fee<br /> 
		  Section</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br /> 
		  Name</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br />
		  Rate</div></td>
		<td bgcolor="#dff1ff"><div align="center">Current<br />
		  Order<br />
		  Status</div></td>
		<td bgcolor="#dff1ff"><div align="center">Current<br />
		  Order<br />
		  AmtDue</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Current<br />
		  Order<br />
		  AmtPaid</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Current<br />
		  Order<br />
		  BillStatus</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br /> 
		  Status</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br /> 
		  AmtDue</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br />
		  AmtPaid</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Recpt<br />Order<br />
		  UnPaid<br />
		  Partial</div></td>
		<td bgcolor="#dff1ff" ><div align="center">		  Receipt<br />
		  Amt</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Receipt<br />
		  Num</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Pmnt<br />
		  Type</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Receipt<br />
		  Date</div></td>
		<td bgcolor="#dff1ff" ><div align="center">Order<br />
		  Comments</div></td>
	  </tr>

<?php  $lastorder = 0; ?>
<?php do { ?>
	  <tr>
<?php   if($row_orders['ordid']<> $lastorder ){ ?>
	<?php if(allow(53,4) == 1) { ?>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><a href="javascript:void(0)" onclick="MM_openBrWindow('CashCorrOrd.php?visitid=<?php echo $row_orders['visitid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&mrn=<?php echo $row_orders['mrn']; ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=400')"><?php echo $row_orders['ordid']; ?></a></td>
	<?php 	} else {?>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_orders['ordid']; ?></td>
	<?php 	}?>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_orders['dept']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_orders['section']; ?></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_orders['name']; ?></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11"><?php echo $row_orders['rate']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_orders['ostatus']; ?>"><?php echo $row_orders['ostatus']; ?></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_orders['ostatus']; ?>"><div align="center"><?php echo $row_orders['amtdue']; ?></div></td>
		<td bgcolor="#FFFFDA" class="BlackBold_11" title="current order status: <?php echo $row_orders['ostatus']; ?>"><div align="center"><?php echo $row_orders['amtpaid']; ?></div></td>
		<td bgcolor="#fffdda" class="BlackBold_11" title="current order status: <?php echo $row_orders['ostatus']; ?>"><?php echo $row_orders['billstatus']; ?></td>
<?php  }  else { ?>	
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>

<?php  }  ?>	
		
		<td bgcolor="#fafafa" class="BlackBold_11" title="current order status: <?php echo $row_orders['ostatus']; ?>"><?php echo $row_orders['rostatus']; ?></td>
		<td bgcolor="#fafafa" class="BlackBold_11" title="curent order amt due: <?php echo number_format($row_orders['amtdue']); ?>"><div align="center"><?php echo $row_orders['roamtdue']; ?></div></td>
		<td bgcolor="#fafafa" class="BlackBold_11" title="current order amtpaid:<?php echo $row_orders['amtpaid']; ?>"><div align="center"><?php echo $row_orders['roamtpaid']; ?></div></td>
		<td bgcolor="#fafafa" class="BlackBold_11" ><div align="center"><?php echo $row_orders['rounpaid']; ?></div></td>
		<td bgcolor="#fffdda" class="BlackBold_11" ><div align="center"><?php echo $row_orders['amt']; ?></div></td>

	<?php if(allow(53,4) == 1) { ?>
		<td bgcolor="#fffdda" class="Black_10" ><div align="center">#<a href="javascript:void(0)" onclick="MM_openBrWindow('CashCorrRcpt.php?mrn=<?php echo $row_orders['medrecnum']; ?>&rid=<?php echo $row_orders['rid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=400')"><?php echo $row_orders['rid']; ?></a></div></td>
	<?php 	} else {?>
		<td bgcolor="#fffdda" class="Black_10" ><div align="center">#<?php echo $row_orders['rid']; ?></div></td>
	<?php  }  ?>	
		
		<td bgcolor="#fffdda" class="Black_10" ><div align="center"><?php echo $row_orders['nbc']; ?></div></td>
		<td nowrap="nowrap" bgcolor="#FFFFDA" class="BlackBold_11" title="Receipt time:  <?php echo $row_orders['rentrytime']; ?>&#10;Entry By: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['rentrydt']; ?></td>
		<td bgcolor="#fffdda" class="Black_10" title="<?php echo $row_orders['comments']; ?>"><?php echo $row_orders['comments']; ?></td>
	  </tr>
<?php $lastorder = $row_orders['ordid'];	?>

  <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
  </table>
  
  

<?php } while ($row_visits = mysql_fetch_assoc($visits)); ?>
</td></tr></table>    
