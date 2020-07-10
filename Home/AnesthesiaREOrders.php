<?php  $pt = "SURG RE Orders"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
//	$_SESSION['status'] = "InLab";  //set default selection
//	if (isset($_GET['status'])) {
//	  $colname_status = (get_magic_quotes_gpc()) ? $_GET['status'] : addslashes($_GET['status']);
//	 $_SESSION['status'] = $colname_status;
//	}
	if(!isset($_SESSION['ostatus'])) {	 //check for session already set ... so when reselect form LabRETests.php is selected, the selected section will be used
	$_SESSION['ostatus'] = "ordered";
	 }
	if(isset($_POST['ostatus'])) { // if a new section is selected
	  $colname_ostatus = (get_magic_quotes_gpc()) ? $_POST['ostatus'] : addslashes($_POST['ostatus']);
	 $_SESSION['ostatus'] = $colname_ostatus;
	}	 
	 $colname_daysback = "20";
    if(isset($_POST['daysback']) && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
     $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
	 
mysql_select_db($database_swmisconn, $swmisconn);  //select list of Surgery orders by date and status
$query_orders = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, p.dob, p.gender, p.ethnicgroup, o.id ordid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, o.urgency, o.doctor, o.status, o.billstatus, o.rate, o.comments, o.amtdue, o.amtpaid, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, o.feeid, o.visitid, v.id vid, v.pat_type, v.location, v.visitdate, f.dept, f.section, f.name, f.descr, s.name spname, b.bed FROM `patperm` p join orders o on p.medrecnum = o.medrecnum join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id join specimens s on f.specid = s.id LEFT OUTER JOIN patbed b ON p.medrecnum = b.medrecnum WHERE f.dept = 'Surgery' AND f.section = 'Anesthesia' AND o.status like '".$_SESSION['ostatus']."%' and o.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback)." DAY"; 
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
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
<h1 align="center">ANESTHESIA RESULTS ENTRY </h1>
	<table width="80%" align="center">
	  <tr>
	    <td>
		 <table width="40%" align="center">
          <form id="form1" name="form1" method="post" action="AnesthesiaREOrders.php" > 
          <tr>
            <td width="5%">&nbsp;</td>
            <td><div align="right">Order Status:</div></td>
            <td><select name="ostatus" id="ostatus" onChange="document.form1.submit();">
                <option value="%" <?php if (!(strcmp("%", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>All</option>
                <option value="ordered" <?php if (!(strcmp("ordered", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>ordered</option>
                <option value="Resulted" <?php if (!(strcmp("Resulted", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Resulted</option>
                <option value="Reviewed" <?php if (!(strcmp("Reviewed", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Reviewed</option>
                <option value="Refunded" <?php if (!(strcmp("Refunded", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Refunded</option>
              </select>
			  <?php // echo $_SESSION['ostatus'] ?></td>
        <td>Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.form1.submit();">    <!--onChange="document.form3.submit();"-->
            <option value="1" <?php if (!(strcmp(1, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1</option>
            <option value="2" <?php if (!(strcmp(2, $colname_daysback))) {echo "selected=\"selected\"";} ?>>2</option>
            <option value="3" <?php if (!(strcmp(3, $colname_daysback))) {echo "selected=\"selected\"";} ?>>3</option>
            <option value="4" <?php if (!(strcmp(4, $colname_daysback))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="5" <?php if (!(strcmp(5, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5</option>
            <option value="6" <?php if (!(strcmp(6, $colname_daysback))) {echo "selected=\"selected\"";} ?>>6</option>
            <option value="7" <?php if (!(strcmp(7, $colname_daysback))) {echo "selected=\"selected\"";} ?>>7</option>
            <option value="10" <?php if (!(strcmp(10, $colname_daysback))) {echo "selected=\"selected\"";} ?>>10</option>
            <option value="15" <?php if (!(strcmp(15, $colname_daysback))) {echo "selected=\"selected\"";} ?>>15</option>
            <option value="20" <?php if (!(strcmp(20, $colname_daysback))) {echo "selected=\"selected\"";} ?>>20</option>
            <option value="25" <?php if (!(strcmp(25, $colname_daysback))) {echo "selected=\"selected\"";} ?>>25</option>
            <option value="30" <?php if (!(strcmp(30, $colname_daysback))) {echo "selected=\"selected\"";} ?>>30</option>
            <option value="60" <?php if (!(strcmp(60, $colname_daysback))) {echo "selected=\"selected\"";} ?>>60</option>
            <option value="90" <?php if (!(strcmp(90, $colname_daysback))) {echo "selected=\"selected\"";} ?>>90</option>
            <option value="120" <?php if (!(strcmp(120, $colname_daysback))) {echo "selected=\"selected\"";} ?>>120</option>
            <option value="180" <?php if (!(strcmp(180, $colname_daysback))) {echo "selected=\"selected\"";} ?>>180</option>
            <option value="365" <?php if (!(strcmp(365, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1 yr</option>
            <option value="1825" <?php if (!(strcmp(1825, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5 yr</option>
          </select>			</td>
            <td width="5%"><?php // echo $colname_daysback ?>&nbsp;</td>
          </tr>
   </form>
        </table>
	   </td>
	  </tr>
	  <tr>
		<td>
		 <table align="center" bgcolor="#F5F5F5">
          <tr>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Ord #</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order Date</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Patient</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Status</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Urg</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Doctor</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Paid</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Location</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Bed</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order*</div></td>
          </tr>
          <?php do { 
			  $bkg = "#FFFFFF";
	if ($row_orders['paid'] != 'Y') {
	  $bkg = "FFCC00";
	}	?>
          <tr>
            <td bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['ordid']; ?></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_orders['lastname']; ?>,<?php echo $row_orders['firstname']; ?>(<?php echo $row_orders['othername']; ?>)</td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['status']; ?></td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['urgency']; ?></td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>
		<?php if($row_orders['rate'] == '0') { ?>
      		<td nowrap="nowrap" class="BlackBold_11" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="right">Rt:0</div></td>
		<?php } else {?>
           	<td bgcolor="<?php echo $bkg ?>"title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="center"><?php echo $row_orders['paid']; ?></div></td>

      		<?php } ?>
            <td bgcolor="#FFFFFF" title="Visit#: <?php echo $row_orders['visitid']; ?>"><?php echo $row_orders['location']; ?></td>
            <td bgcolor="#FFFFFF" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10; Test Descr: <?php echo $row_orders['descr']; ?>&#10; Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['bed']; ?></td>
            
            <td bgcolor="#FFFFFF" class="BlueBold_1212" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10;Visit ID: <?php echo $row_orders['vid']; ?>&#10;Test: <?php echo $row_orders['descr']; ?>&#10;Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['name'] ?></td>
<!-- if (condition) {
  code to be executed if condition is true;
} elseif (condition) {
  code to be executed if condition is true;
} else {
  code to be executed if condition is false;
}--> 
            <?php if($row_orders['status'] == 'ordered' AND allow(24,3) == 1) { ?>

            <td nowrap="nowrap"><a href="../Patient/PatShow1.php?&mrn=<?php echo $row_orders['medrecnum']; ?>&vid=<?php echo $row_orders['vid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesViewPSR.php&notetype=Surgery">Result --></a> </td>

            <?php } elseif($row_orders['status'] == 'Resulted' AND allow(25,3) == 1) { ?>
            <td nowrap="nowrap"><a href="../Patient/PatShow1.php?&mrn=<?php echo $row_orders['medrecnum']; ?>&vid=<?php echo $row_orders['vid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesViewPSR.php&notetype=Surgery">Review --></a> </td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>			
            <td><?php echo $row_orders['comments']; ?></td>

            <?php if (($row_orders['status'] == "ordered" or $row_orders['status'] == "Resulted") and $row_orders['amtpaid'] > 0 and allow(25,4) == 1) { ?>
            <td title="REFUND"><a href="javascript:void(0)" onclick="MM_openBrWindow('LabPopRefund.php?ordid=<?php echo $row_orders['ordid']; ?>&amp;name=<?php echo $row_orders['name'] ?>&amp;amtpaid=<?php echo $row_orders['amtpaid'] ?>&amp;dept=<?php echo $row_orders['dept']; ?>&amp;section=<?php echo $row_orders['section']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">$</a></td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>

            <?php if (($row_orders['status'] == "Reviewed" and allow(25,4) == 1)) { ?>
            <td title="BACK TO RESULTED"><a href="../Patient/PatShow1.php?&mrn=<?php echo $row_orders['medrecnum']; ?>&vid=<?php echo $row_orders['vid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesViewPSR.php&notetype=Surgery">Re-Review --></a> </td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>
          </tr>
          <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
        </table>
	  </td>
	 </tr>
</table>

</body>
</html>
