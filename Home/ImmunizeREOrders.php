<?php  $pt = "Immunization RE Orders"; ?>
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
	if (isset($_POST['ostatus'])) { // if a new section is selected
	  $colname_section = (get_magic_quotes_gpc()) ? $_POST['ostatus'] : addslashes($_POST['ostatus']);
	 $_SESSION['ostatus'] = $colname_section;
	}	 
	 $colname_daysback = "20";
    if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
     $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
// get list of orders for immunizations not yet given	 
mysql_select_db($database_swmisconn, $swmisconn);  //select list of lab ordersby section and status
$query_orders = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, p.dob, p.gender, p.ethnicgroup, o.id ordid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, o.urgency, o.doctor, o.status, o.billstatus, o.rate, o.comments, o.amtdue, o.amtpaid, o.feeid, o.visitid, v.id vid, v.pat_type, v.location, v.visitdate, f.dept, f.section, f.name, f.descr FROM orders o join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id join `patperm` p  on p.medrecnum = o.medrecnum WHERE f.dept = 'Immunization' AND f.section = 'Immunization' AND o.status = 'ordered' and o.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback)." DAY"; 
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
<?php // get list of patients with visit for immunization but no orders 
mysql_select_db($database_swmisconn, $swmisconn);  //select list of lab ordersby section and status
$query_visits = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, p.dob, p.gender, p.ethnicgroup, v.id vid, v.pat_type, v.location, v.visitdate, DATE_FORMAT(v.visitdate,'%d-%b-%Y_%H:%i') vvisitdate, v.entryby, v.entrydt FROM `patvisit` v join patperm p on v.medrecnum = p.medrecnum WHERE NOT EXISTS (Select o.id, o.feeid, f.section, f.dept, f.name  from orders o join patvisit v2 on o.visitid = v2.id join fee f on o.feeid = f.id where v2.id = v.id and f.dept = 'immunization') and v.location = 'Immunization'  and v.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback)." DAY"; 
$visits = mysql_query($query_visits, $swmisconn) or die(mysql_error());
$row_visits = mysql_fetch_assoc($visits);
$totalRows_visits = mysql_num_rows($visits);

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
<h1 align="center">Immunization Results Entry</h1>
		 <table width="40%" align="center">
          <form id="form1" name="form1" method="post" action="immunizeREOrders.php" > 
          <tr>
            <td width="5%">&nbsp;</td>
            <td><div align="right">Order Status:</div></td>
            <td><select name="ostatus" id="ostatus" onChange="document.form1.submit();">
                <option value="%" <?php if (!(strcmp("%", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>All</option>
                <option value="Ordered" <?php if (!(strcmp("ordered", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
                <option value="Immune" <?php if (!(strcmp("Resulted", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Immune</option>
               <!-- <option value="Reviewed" <?php if (!(strcmp("Reviewed", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Reviewed</option>
                <option value="Refunded" <?php if (!(strcmp("Refunded", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Refunded</option> -->
              </select>
			  <?php // echo $_SESSION['ostatus'] ?>
        </td>
        <td>Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.form1.submit();">    <!-- onChange="document.form3.submit();" -->
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
            <td width="5%">&nbsp;</td>
          </tr>
   </form>
        </table>
		  <table align="center" bgcolor="#fbf9ee">
        <tr>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Ord #</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Order Date</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Patient</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Status</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Pat. Type</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Location</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Order*</div></td>
        </tr>
      <?php do { 
			     $bkg = "#FFFFFF";
    	?>
        <tr>
          <td bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['ordid']; ?></td>
          <td nowrap="nowrap" bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
          <td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_orders['lastname']; ?>,<?php echo $row_orders['firstname']; ?>(<?php echo $row_orders['othername']; ?>)</td>
          <td bgcolor="#FFFFFF"><?php echo $row_orders['status']; ?></td>
          <td bgcolor="#FFFFFF" title="Visit#: <?php echo $row_orders['visitid']; ?>"><?php echo $row_orders['pat_type']; ?></td>
          <td bgcolor="#FFFFFF" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10; Test Descr: <?php echo $row_orders['descr']; ?>&#10; Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['location'] ?></td>
          <td bgcolor="#FFFFFF" class="BlueBold_1212" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10;Visit ID: <?php echo $row_orders['vid']; ?>&#10;Test: <?php echo $row_orders['descr']; ?>&#10;Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['name'] ?></td>
          <?php if($row_orders['status'] == 'Ordered' || $row_orders['status'] == 'ordered' AND allow(27,3) == 1) { ?>

          <td nowrap="nowrap"><a href="../Patient/PatShow1.php?&mrn=<?php echo $row_orders['medrecnum']; ?>&vid=<?php echo $row_orders['vid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&visit=PatVisitView.php&pge=ImmunizREOrders.php">Result -></a> </td>
					<?php } ?>
          <td><?php echo $row_orders['comments']; ?></td>

       </tr>
          <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
      </table>
		  <table >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
          <h1 align="center">Immunization Visit with No Order for immunization</h1>

		  <table align="center" bgcolor="#fbf9ee">
        <tr>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Date</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Patient</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Pat. Type</div></td>
          <td bgcolor="#fbf9ee" class="BlackBold_11"><div align="center">Location</div></td>
        </tr>
    <?php do { 
         $bkg = "#FFFFFF";
    ?>
        <tr>
          <td nowrap="nowrap" bgcolor="#FFFFFF" title="VisitID: <?php echo $row_visits['vid']; ?> &#10;Visit Entry by: <?php echo $row_visits['entryby']; ?>&#10;Visit Entry Date: <?php echo $row_visits['entrydt']; ?>"><?php echo $row_visits['vvisitdate'] ?></td>
          <td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_visits['lastname']; ?>,<?php echo $row_visits['firstname']; ?>(<?php echo $row_visits['othername']; ?>)</td>
          <td bgcolor="#FFFFFF" title="Visit#: <?php echo $row_visits['vid']; ?>"><?php echo $row_visits['pat_type']; ?></td>
          <td bgcolor="#FFFFFF" title="Location: <?php echo $row_visits['location'] ?>"><?php echo $row_visits['location']; ?></td>
           <td nowrap="nowrap"><a href="../Patient/PatShow1.php?&mrn=<?php echo $row_visits['medrecnum']; ?>&vid=<?php echo $row_visits['vid']; ?>&visit=PatVisitView.php&pge=ImmunizREOrders.php">Order -></a> </td>
       </tr>
          <?php } while ($row_visits = mysql_fetch_assoc($visits)); ?>
		  </table>
	  </td>
	</tr>
</table>

</body>
</html>
