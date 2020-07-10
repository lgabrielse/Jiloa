<?php ob_start(); ?>
<?php  $pt = "Lab Result Entry"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
	}
if (isset($_GET['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
	}
mysql_select_db($database_swmisconn, $swmisconn); // display list of orders for this patient
$query_ordered = "SELECT o.id ordid, o.medrecnum, o.visitid, o.feeid, substr(o.status,1,8) status, billstatus, rate, substr(o.urgency,1,1) urg, doctor, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.amtpaid, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, f.section, f.name, f.descr FROM orders o, fee f WHERE o.feeid = f.id and f.dept = 'Laboratory' and o.status in ('InLab', 'Resulted') and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_vid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);

if (isset($_GET['ordid'])) {
 $gmp = "";
 $gmn = "";
 $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
	}
	// look for POS or NEG result of Culture test in order to select antibiotics for sensitivity
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_tests = "SELECT t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN)  join  results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and t.test = 'Culture' and o.id = '".$colname_ordid."'";
	$culturetests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
	$row_culturetests = mysql_fetch_assoc($culturetests);	
	$totalRows_culturetests = mysql_num_rows($culturetests); 
  
    if (strpos($row_culturetests['result'],'POS') > 0) {
	  $gmp = 'P';	
	} 
    if (strpos($row_culturetests['result'],'NEG') > 0) {
	  $gmn = 'N';	
	} 


mysql_select_db($database_swmisconn, $swmisconn); // list of tests for an order
$query_tests = "SELECT o.feeid, o.status, t.id, t.test, t.description, t.formtype, t.units,t. reportseq, t.active FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN) where t.active = 'Y' and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') and o.id ='".$colname_ordid."' ORDER BY reportseq ";
$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
$row_tests = mysql_fetch_assoc($tests);
$totalRows_tests = mysql_num_rows($tests);
?>

<?php
mysql_select_db($database_swmisconn, $swmisconn); // use $totalRows_totresrecs to determime how many tests have result records
$query_totresrecs = "SELECT o.status, t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN)  join  results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and r.id > 0 and o.id = '".$colname_ordid."'";
$totresrecs = mysql_query($query_totresrecs, $swmisconn) or die(mysql_error());
$row_totresrecs = mysql_fetch_assoc($totresrecs);
$totalRows_totresrecs = mysql_num_rows($totresrecs);
?>
<?php
mysql_select_db($database_swmisconn, $swmisconn); // use $totalRows_totresults to determime how many tests have result values
$query_totresults = "SELECT o.status, t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN) join results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and length(r.result) > 0 and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') and o.id = '".$colname_ordid."'";
$totresults = mysql_query($query_totresults, $swmisconn) or die(mysql_error());
$row_totresults = mysql_fetch_assoc($totresults);
$totalRows_totresults = mysql_num_rows($totresults);
?>
<?php
mysql_select_db($database_swmisconn, $swmisconn); // use $totalRows_tottests to determime how many tests there are
$query_tottests = "SELECT t.test FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN) where t.active = 'Y' and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') and o.id = '".$colname_ordid."'";
$tottests = mysql_query($query_tottests, $swmisconn) or die(mysql_error());
$row_tottests = mysql_fetch_assoc($tottests);
$totalRows_tottests = mysql_num_rows($tottests);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- Display PATIENT PERMANENT Data  -->
<?php $patview = "../Patient/PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>
<!-- Display PATIENT Visit Data  -->
<?php $visitview = "../Patient/PatVisitViewRO.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($visitview); ?></td>
	</tr>
</table>

<?php if($totalRows_ordered == 0) { // if the test list is empty, go back to Order List
  $ResultsGoTo = "LabREOrders.php";
  if (isset($_SERVER['QUERY_STRING'])) {
  }
  header(sprintf("Location: %s", $ResultsGoTo));

}
?>
<table width="60%" align="center">
  <tr>
    <td valign="top"><div align="center">
            <table>
                  <tr>
                    <td nowrap="nowrap" class="BlackBold_11"><?php echo $totalRows_ordered ?>&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11"><a href="LabREOrders.php?status=InLab">Re-Select</a></td>
                    <td colspan="3" nowrap="nowrap" class="BlackBold_11"><div align="center">InLab or Resulted Orders for this visit </div></td>
                    <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>">t=<?php echo $totalRows_tottests ?>... r=<?php echo $totalRows_totresults ?> </td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">OrdID</td>
                    <td nowrap="nowrap" class="BlackBold_11">Date/Time</td>
                    <td nowrap="nowrap" class="BlackBold_11"><div align="center">Urg</div></td>
                    <td nowrap="nowrap" class="BlackBold_11"><div align="center">Status</div></td>
                    <td nowrap="nowrap" class="BlackBold_11"><div align="center">Billstatus</div></td>
                    <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>">Test</td>
                  </tr>
              <?php do { 
			  $bkg = "#DCDCDC";
	if ($row_ordered['paid'] != 'Y') {
	  $bkg = "FFCC00";
	  } ?>
                  <tr>
			  	<?php if (!empty($row_ordered['id']) and empty($row_ordered['amtpaid'])) {?>
					<td nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['id'] ?>">Del</a></td>
				<?php } else {?>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
				<?php } ?>
                    <td nowrap="nowrap" class="BlackBold_11" title="MRN: <?php echo $row_ordered['medrecnum']; ?>&#10; VisitID:  <?php echo $row_ordered['visitid']; ?>&#10; FeeID: <?php echo $row_ordered['feeid']; ?>"><?php echo $row_ordered['ordid']; ?></td>				
                    <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['entrydt']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['urg']; ?></div></td>
                <td nowrap="nowrap" class="BlackBold_11"><div align="center"><?php echo $row_ordered['status']; ?></div></td>

		<?php if($row_ordered['rate'] == '0') { ?>
      <td nowrap="nowrap" class="BlackBold_11" title="AMTDUE: <?php echo $row_ordered['amtdue']; ?>&#10;AMTPAID: <?php echo $row_ordered['amtpaid']; ?>&#10;BillStatus: <?php echo $row_ordered['billstatus']; ?>"><div align="center">Rt:0</div></td>

<?php } elseif($row_ordered['paid'] == 'N' && $row_ordered['billstatus'] == 'paylater') {?>
      <td bgcolor="<?php echo $bkg ?>" title="AMTDUE: <?php echo $row_ordered['amtdue']; ?>&#10;AMTPAID: <?php echo $row_ordered['amtpaid']; ?>&#10;BillStatus: <?php echo $row_ordered['billstatus']; ?>"><div align="center"><span class="flagBlackonYellow">PL</span></div></td>

		<?php } else {?>
      		<td bgcolor="<?php echo $bkg ?>"title="AMTDUE: <?php echo $row_ordered['amtdue']; ?>&#10;AMTPAID: <?php echo $row_ordered['amtpaid']; ?>&#10;BillStatus: <?php echo $row_ordered['billstatus']; ?>"><div align="center"><?php echo $row_ordered['billstatus']; ?></div></td>
		<?php } ?>

                <td nowrap="NOWRAP" class="BlackBold_11" title="Doctor: <?php echo $row_ordered['doctor']; ?>&#10;Test Descr: <?php echo $row_ordered['descr']; ?>"><a href="LabRETests.php?mrn=<?php echo $row_ordered['medrecnum'];?>&vid=<?php echo $row_ordered['visitid']; ?>&ordid=<?php echo $row_ordered['ordid']; ?>&name=<?php echo $row_ordered['name'] ?>"><?php echo $row_ordered['name'] ?></a></td>
              </tr>
              <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
            </table>
    </div>	</td>
    <td valign="top">
	
<!-- Display TEST RESULT Data  -->
<?php if (isset($_GET['app'])) {
	 $resentview = $_GET['app'];
	  } else
		if ($totalRows_totresults == $totalRows_tottests) { 
	 		$resentview = "LabREview.php"; 
	 	}
		if ($totalRows_totresults < $totalRows_tottests) { 
	 		$resentview = "LabREedit.php";
		} 
		if ($totalRows_totresrecs == 0) {
	 		$resentview = "LabREadd.php"; 
		}	 
	?>
<table width="600px" align="center">
	<tr>
		<td valign="top">
			<?php require_once($resentview); ?></td>
	</tr>
</table>


    </td>
  </tr>
</table>




</body>
</html>
<?php
mysql_free_result($totresults);
mysql_free_result($tottests);

mysql_free_result($tests);
ob_end_flush();
?>
