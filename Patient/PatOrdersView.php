<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
	}
else {
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
 $_SESSION['mrn'] = $_GET['mrn'];
	}}
	
if (isset($_SESSION['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']);
	}
else {
if (isset($_GET['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
 $_SESSION['vid'] = $_GET['vid'];
	}}

mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id ordid, o.medrecnum, o.doctor, o.visitid, o.rate, o.feeid, o.status, o.item, o.ofee, o.billstatus,  substr(o.urgency,1,1) urg, o.comments, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.entryby, o.amtdue, o.amtpaid, o.receiptnum, f.dept, f.section, f.name, f.descr FROM orders o join fee f on o.feeid = f.id WHERE o.medrecnum ='". $colname_mrn."' and (o.visitid ='". $colname_vid."' or o.visitid = 0) ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);
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
</head>

<body>

<table width="60%">
  <tr>
    <td valign="top">
	 <form action="" method="get">
	   <table>
         <tr>
           <td class="GreenBold_12">&nbsp;</td>
           <td class="GreenBold_12">&nbsp;</td>
           <td class="GreenBold_12">&nbsp;</td>
           <td colspan="5" class="GreenBold_12"><div align="center">View Patient Orders </div></td>
           <td class="GreenBold_16">&nbsp;</td>
           <td class="GreenBold_16">&nbsp;</td>
           <td class="GreenBold_16">&nbsp;</td>
         </tr>
         <tr>
           <td class="GreenBold_12"><div align="center">&nbsp;</div></td>
           <td class="GreenBold_12"><div align="center">&nbsp;</div></td>
           <td nowrap="nowrap" class="GreenBold_12"><div align="center">entry date*</div></td>
           <td class="GreenBold_12"><div align="center">section</div></td>
           <td class="GreenBold_12">order</td>
           <td class="GreenBold_12"><div align="center">status</div></td>
           <td class="GreenBold_12"><div align="center">urg</div></td>
           <!--		<td class="GreenBold_12"><div align="center">doctor</div></td>
-->
           <td class="GreenBold_12"><div align="center">entryby</div></td>
           <td class="GreenBold_12"><div align="center">amtdue</div></td>
           <td class="GreenBold_12"><div align="center">amtpaid</div></td>
           <?php $statusview = 'Y';
	  if($statusview = 'Y') {?>
           <td class="GreenBold_12"><div align="center">Rxfee</div></td>
           <td class="GreenBold_12"><div align="center">billstatus</div></td>
           <td class="GreenBold_12"><div align="center">rate</div></td>
           <td class="GreenBold_12"><div align="center">feeid</div></td>
           <?php } ?>
         </tr>
         <?php do { ?>
         <tr>
           <?php if(!empty($row_ordered['amtpaid']) and $row_ordered['amtpaid'] > 0 and $row_ordered['billstatus'] != 'Refund' and $row_ordered['billstatus'] != 'Due' and allow(30,4) == 1) {?>
           <td title="REFUND"><a href="javascript:void(0)" onclick="MM_openBrWindow('../Lab/LabPopRefund.php?ordid=<?php echo $row_ordered['ordid'] ?>&name=<?php echo $row_ordered['name'] ?>&amtpaid=<?php echo $row_ordered['amtpaid'] ?>&dept=<?php echo $row_ordered['dept'] ?>&section=<?php echo $row_ordered['section'] ?>&status=<?php echo $row_ordered['status'] ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">$</a></td>
           <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
           <?php } else { ?>
           <?php if (empty($row_ordered['amtpaid']) and $row_ordered['billstatus'] != 'Refund' and $row_ordered['billstatus'] != 'Refunded') {?>
           <td nowrap="nowrap"><?php if(allow(30,4) == 1) { ?>
             <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['ordid']; ?>" class="nav11">Del</a></td>
           <?php } ?>
           <td nowrap="nowrap"><?php if(allow(30,2) == 1) { ?>
               <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersEdit.php&id=<?php echo $row_ordered['ordid']; ?>" class="nav11">Edit</a>
               <?php } ?>           </td>
           <?php } else {?>
           <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
           <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
           <?php } ?>
           <?php } ?>
           <td nowrap="nowrap" class="BlackBold_11" title="MRN:<?php echo $row_ordered['medrecnum']; ?>&#10;VISITID:<?php echo $row_ordered['visitid']; ?>&#10;ORDERID:<?php echo $row_ordered['ordid']; ?>&#10;FEEID:<?php echo $row_ordered['feeid']; ?>"><?php echo $row_ordered['entrydt']; ?></td>
           <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['section']; ?></td>
           <td nowrap="nowrap" class="BlackBold_11" title="Description: <?php echo $row_ordered['descr']; ?>&#10;Item: <?php echo $row_ordered['item']; ?> &#10;Comments: <?php echo $row_ordered['comments']; ?>"><?php echo $row_ordered['name']; ?>: <?php echo $row_ordered['item']; ?></td>
           <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['status']; ?></td>
           <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['urg']; ?></td>
           <!--				  <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['doctor']; ?></td>
-->
           <td nowrap="nowrap" class="BlackBold_10"><?php echo $row_ordered['entryby']; ?></td>
           <?php if($row_ordered['rate'] == '0') { ?>
           <td nowrap="nowrap" class="BlackBold_11"><div align="right">Rate:0</div></td>
           <?php } else {?>
           <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtdue']; ?></div></td>
           <?php } ?>
           <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtpaid']; ?></div></td>
           <?php 	if($statusview = 'Y') {?>
           <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['ofee']; ?></div></td>
           <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['billstatus']; ?></div></td>
           <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['rate']; ?></div></td>
           <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['feeid']; ?></div></td>
           <?php 	} ?>
         </tr>
         <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
       </table>
	 </form>
<?php
mysql_free_result($ordered);
?>	</td>
    <td valign="top">

<?php
$ordchgapp="";
if (isset($_GET["ordchg"])) {
	$ordchgapp = $_GET['ordchg'];
?>
		<table align="center">
			<tr>
				<td valign="top">
					<?php require_once($ordchgapp); ?></td>
			</tr>
		</table>
<?php } ?>	</td>
  </tr>
</table>
</body>
</html>
