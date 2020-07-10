<?php  $pt = "Lab RE Orders"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
//	$_SESSION['status'] = "InLab";  //set default selection
//	if (isset($_GET['status'])) {
//	  $colname_status = (get_magic_quotes_gpc()) ? $_GET['status'] : addslashes($_GET['status']);
//	 $_SESSION['status'] = $colname_status;
//	}
	if(!isset($_SESSION['ostatus'])) {	 //check for session already set ... so when reselect form LabRETests.php is selected, the selected section will be used
	$_SESSION['ostatus'] = "InLab";
	 }
	if (isset($_POST['ostatus'])) { // if a new section is selected
	  $colname_section = (get_magic_quotes_gpc()) ? $_POST['ostatus'] : addslashes($_POST['ostatus']);
	 $_SESSION['ostatus'] = $colname_section;
	}	 
	if(!isset($_SESSION['section'])) {	 //check for session already set ... so when reselect form LabRETests.php is selected, the selected section will be used
	$_SESSION['section'] = "%";
	 }
	if (isset($_POST['section'])) { // if a new section is selected
	  $colname_section = (get_magic_quotes_gpc()) ? $_POST['section'] : addslashes($_POST['section']);
	 $_SESSION['section'] = $colname_section;
	}
	 $colname_daysback = "20";
    if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
     $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
	 
mysql_select_db($database_swmisconn, $swmisconn);  //select list of lab ordersby section and status
$query_orders = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, p.dob, p.gender, p.ethnicgroup, o.id ordid, o.entryby, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, o.urgency, o.doctor, o.status, o.billstatus, o.rate, o.comments, o.amtdue, o.amtpaid, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, o.feeid, o.visitid, v.id vid, v.pat_type, v.location, v.visitdate, f.dept, f.section, f.name, f.descr, s.name spname, b.bed FROM `patperm` p join orders o on p.medrecnum = o.medrecnum join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id join specimens s on f.specid = s.id LEFT OUTER JOIN patbed b on p.medrecnum = b. medrecnum WHERE f.dept = 'Laboratory' AND o.status like '".$_SESSION['ostatus']."%' AND f.section like '".$_SESSION['section']."%' and o.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback + 1)." DAY"; 
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

mysql_select_db($database_swmisconn, $swmisconn);
$query_sections = "select name from dropdownlist where list = 'labsection' order by seq";
$sections = mysql_query($query_sections, $swmisconn) or die(mysql_error());
$row_sections = mysql_fetch_assoc($sections);
$totalRows_sections = mysql_num_rows($sections);
?>
<?php

mysql_select_db($database_swmisconn, $swmisconn);  //select list of lab ordersby section and status
$query_colinp = "SELECT distinct p.medrecnum, p.lastname, p.firstname, p.othername, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, p.dob)),'%y') AS age, est, p.gender, o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, DATE_FORMAT(o.entrydt,'%d-%b') entrydm, DATE_FORMAT(o.entrydt,'%H:%i') entryhm, o.urgency, o.status, o.billstatus, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, o.feeid, o.visitid, v.id vid, v.pat_type, v.location, v.visitdate, v.vfeeid vfeeid, f.dept, f.section, f.name, f.descr, b.bed FROM `patperm` p join orders o on p.medrecnum = o.medrecnum join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id LEFT OUTER JOIN patbed b on p.medrecnum = b.medrecnum WHERE f.dept = 'Laboratory' AND o.status = 'ordered' AND v.pat_type = 'InPatient' and o.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback + 1)." DAY GROUP BY p.medrecnum, DATE_FORMAT(o.entrydt,'%d-%b'), o.urgency, o.billstatus, o.visitid, v.pat_type, v.visitdate, b.bed ORDER BY o.entrydt DESC"; 
$colinp = mysql_query($query_colinp, $swmisconn) or die(mysql_error());
$row_colinp = mysql_fetch_assoc($colinp);
$totalRows_colinp = mysql_num_rows($colinp);

mysql_select_db($database_swmisconn, $swmisconn);  //select list of lab ordersby section and status
$query_col = "SELECT p.medrecnum, p.lastname, p.firstname, p.othername, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, p.dob)),'%y') AS age, est, p.gender, o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y_%H:%i') entrydt, o.urgency, o.status, o.billstatus, CASE WHEN o.amtpaid > 4 THEN 'Y' ELSE 'N' END paid, o.feeid, o.visitid, v.id vid, v.pat_type, v.location, v.visitdate, v.vfeeid vfeeid, f.dept, f.section, f.name, f.descr FROM `patperm` p join orders o on p.medrecnum = o.medrecnum join patvisit v on o.visitid = v.id join `fee` f on o.feeid = f.id WHERE f.dept = 'Laboratory' AND o.status like '".$_SESSION['ostatus']."%' AND f.section != 'InPatient' and o.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback + 1)." DAY"; 
$col = mysql_query($query_col, $swmisconn) or die(mysql_error());
$row_col = mysql_fetch_assoc($col);
$totalRows_col = mysql_num_rows($col);
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
<h1 align="center">LAB RESULTS ENTRY </h1>
	<table width="80%" align="center">
          <form id="form1" name="form1" method="post" action="LabREOrders.php" > 
	  <tr>
	    <td>
		 <table width="50%" align="center">
          <tr>
 <!--            <td width=35%>Current Status Selected: <span class="BlueBold_16"><?php echo $_SESSION['ostatus'] ?></span></td>
           <td><div align="center"><a href="LabREOrders.php?status=%">ALL Incomplete</a></div></td>
            <td><div align="center"><a href="LabREOrders.php?status=ordered">Ordered</a></div></td>
            <td><div align="center"><a href="LabREOrders.php?status=InLab">InLab</a></div></td>
-->
            <td width="5%">&nbsp;</td>
            <td><div align="right">Order Status:</div></td>
            <td><select name="ostatus" id="ostatus" onChange="document.form1.submit();">
                <option value="%" <?php if (!(strcmp("%", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>All</option>
                <option value="Ordered" <?php if (!(strcmp("Ordered", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
                <option value="InLab" <?php if (!(strcmp("InLab", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>InLab</option>
                <option value="Resulted" <?php if (!(strcmp("Resulted", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Resulted</option>
                <option value="Recollect" <?php if (!(strcmp("Recollect", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Recollect</option>
                <option value="Reviewed" <?php if (!(strcmp("Reviewed", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Reviewed</option>
                <option value="Refunded" <?php if (!(strcmp("Refunded", $_SESSION['ostatus']))) {echo "selected=\"selected\"";} ?>>Refunded</option>
              </select><?php // echo $_SESSION['ostatus'] ?></td>
            <td><div align="right">Laboratory Section:</div></td>
            <td><select name="section" id="section" onChange="document.form1.submit();">
              <option value="%" <?php if (!(strcmp("%", $_SESSION['section']))) {echo "selected=\"selected\"";} ?>>All</option>
              <?php
do {  
?>
              <option value="<?php echo $row_sections['name']?>"<?php if (!(strcmp($row_sections['name'], $_SESSION['section']))) {echo "selected=\"selected\"";} ?>><?php echo $row_sections['name']?></option>
              <?php
} while ($row_sections = mysql_fetch_assoc($sections));
  $rows = mysql_num_rows($sections);
  if($rows > 0) {
      mysql_data_seek($sections, 0);
	  $row_sections = mysql_fetch_assoc($sections);
  }
?>
            </select> <?php //echo $_SESSION['section'] ?>            </td>
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
            <td width="5%">&nbsp;</td>
          </tr>
        </table>
      </td>
<?php if(allow(25,2)==1){ ?>     
<td align="center"><h2>Inpatient Collection List</h2></td>
<?php }?>
     </tr>
	  <tr>

		<td valign="top" >
		 <table align="center" bgcolor="#F5F5F5">
          <tr>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Ord #</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order Date</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Patient</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Status</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Doctor</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Paid</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Location</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Bed</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Section</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Specimen</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Urg</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Order*</div></td>
            <td bgcolor="#f5f5f5" class="BlackBold_11"><div align="center">Ord #</div></td>
          </tr>
          <?php do { 
			  $bkg = "#FFFFFF";
	if ($row_orders['paid'] != 'Y') {
	  $bkg = "FFCC00";
	}	?>
          <tr>
            <td bgcolor="#FFFFFF" title="MRN: <?php echo $row_orders['medrecnum']; ?>&#10;VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['ordid']; ?></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_orders['lastname']; ?>,<?php echo $row_orders['firstname']; ?>(<?php echo $row_orders['othername']; ?>)</td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['status']; ?></td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['doctor']; ?></td>

		<?php if($row_orders['rate'] == '0') { ?>
      <td nowrap="nowrap" class="BlackBold_11" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="right">Rt:0</div></td>

<?php } elseif($row_orders['paid'] == 'N' && $row_orders['billstatus'] == 'paylater') {?>
      <td bgcolor="<?php echo $bkg ?>" title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="center"><span class="flagBlackonYellow">PL</span></div></td>

		<?php } else {?>
      		<td bgcolor="<?php echo $bkg ?>"title="AMTDUE: <?php echo $row_orders['amtdue']; ?>&#10;AMTPAID: <?php echo $row_orders['amtpaid']; ?>&#10;BillStatus: <?php echo $row_orders['billstatus']; ?>"><div align="center"><?php echo $row_orders['paid']; ?></div></td>
		<?php } ?>

            <td bgcolor="#FFFFFF"><?php echo $row_orders['location']; ?></td>
            <td bgcolor="#FFFFFF" nowrap title="Visit#: <?php echo $row_orders['visitid']; ?>"><?php echo $row_orders['bed']; ?></td>
            <td bgcolor="#FFFFFF"><?php echo $row_orders['section']; ?></td>
            <td bgcolor="#FFFFFF" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10; Test Descr: <?php echo $row_orders['descr']; ?>&#10; Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['spname'] ?></td>

<?php if ($row_orders['urgency'] == 'STAT') {   ?>
            <td class="flagWhiteonRed"><?php echo $row_orders['urgency']; ?></td>
<?php } ELSE if ($row_orders['urgency'] == 'ASAP') {   ?>
            <td class="flagBlackonOrange"><?php echo $row_orders['urgency']; ?></td>
<?php } ELSE { ?>
            <td class="BlackBold_11"><?php echo $row_orders['urgency']; ?></td>
<?php } ?>

            <td bgcolor="#FFFFFF" title="Order ID: <?php echo $row_orders['ordid']; ?>&#10;Visit ID: <?php echo $row_orders['vid']; ?>&#10;Test: <?php echo $row_orders['descr']; ?>&#10;Fee ID: <?php echo $row_orders['feeid']; ?>&#10;Comments: <?php echo $row_orders['comments']; ?>"><?php echo $row_orders['name'] ?></td>
<!-- if (condition) {
  code to be executed if condition is true;
} elseif (condition) {
  code to be executed if condition is true;
} else {
  code to be executed if condition is false;
}--> 
            <?php if($row_orders['status'] == 'ordered' AND allow(24,3) == 1) { ?>
            <td nowrap="nowrap"><a href="LabSpecList.php?mrn=<?php echo $row_orders['medrecnum']; ?>">Collect --></a> </td>
						<?php }  ?>
<?php if($row_orders['paid'] =='Y' || $row_orders['billstatus'] == 'paylater' || $row_orders['rate'] =='0')  { ?>	
            <?php if($row_orders['status'] == 'InLab' AND allow(25,3) == 1) { ?>
            <td nowrap="nowrap"><a href="LabRETests.php?mrn=<?php echo $row_orders['medrecnum'];?>&vid=<?php echo $row_orders['visitid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&name=<?php echo $row_orders['name'] ?>">Result --></a> </td>
            <?php } elseif($row_orders['status'] == 'Resulted' AND allow(25,3) == 1) { ?>
            <td nowrap="nowrap"><a href="LabRETests.php?mrn=<?php echo $row_orders['medrecnum'];?>&vid=<?php echo $row_orders['visitid']; ?>&ordid=<?php echo $row_orders['ordid']; ?>&name=<?php echo $row_orders['name'] ?>">Review --></a> </td>
            <?php } 
	    }	else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">Not Paid</td>
         <?php } ?>
					 			
            <td bgcolor="#FFFFFF" title="VisitID: <?php echo $row_orders['visitid']; ?> &#10;Order Entry by: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['ordid']; ?></td>
            <td><?php echo $row_orders['comments']; ?></td>

            <?php if (($row_orders['status'] == "ordered" or $row_orders['status'] == "InLab" or $row_orders['status'] == "Recollect" or $row_orders['status'] == "Resulted") and $row_orders['amtpaid'] > 0 and allow(25,4) == 1) { ?>
            <td title="REFUND"><a href="javascript:void(0)" onclick="MM_openBrWindow('LabPopRefund.php?ordid=<?php echo $row_orders['ordid']; ?>&amp;name=<?php echo $row_orders['name'] ?>&amp;amtpaid=<?php echo $row_orders['amtpaid'] ?>&amp;dept=<?php echo $row_orders['dept']; ?>&amp;section=<?php echo $row_orders['section']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">$</a></td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>

            <?php if (($row_orders['status'] == "InLab" or $row_orders['status'] == "Resulted") and allow(25,3) == 1) { ?>
            <td title="RECOLLECT"><a href="javascript:void(0)" onclick="MM_openBrWindow('LabPopRecollect.php?ordid=<?php echo $row_orders['ordid']; ?>&amp;test=<?php echo $row_orders['name'] ?>&amp;spec=<?php echo $row_orders['spname'] ?>&amp;section=<?php echo $row_orders['section']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">R</a></td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>

            <?php if (($row_orders['status'] == "Reviewed" and allow(25,4) == 1)) { ?>
            <td title="BACK TO RESULTED"><a href="javascript:void(0)" onclick="MM_openBrWindow('LabPopReview.php?ordid=<?php echo $row_orders['ordid']; ?>&test=<?php echo $row_orders['name'] ?>&spec=<?php echo $row_orders['spname'] ?>&section=<?php echo $row_orders['section']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">R</a> </td>
            <?php } else { ?>
            <td bgcolor="#f5f5f5" class="BlackBold_11">&nbsp;</td>
            <?php }  ?>

          </tr>
          <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
        </table>
      </td>
<?php if(allow(25,2)==1){ ?>     
      <td valign="top" bgcolor="FFFDDA">
       <table>
        <tr class="BlackBold_11">
          <td align="center">Date<br />
          </td>
          <td align="center" title"Name is 1st 16 characters ">Patient<br />
          </td>
          <td align="center">Age-Sex</td>
          <td align="center">Urg</td>
          <td align="center">Pd</td>
          <td align="center">Ward</td>
          <td align="center">Bed</td>
       </tr>
        <tr>
        	<td colspan="5" ><hr style="height:1px; border:none; color:#D3D3D3; background-color:#D3D3D3; margin: 0 auto;"></td>
        </tr>
        <?php do {
        $names = '';
				$names = $row_colinp['lastname'].', '.$row_colinp['firstname'].', '.$row_colinp['othername'];
				 ?>
        <tr>
          <td class="Black_10" nowrap title="DateTime: <?php echo $row_colinp['entrydt']; ?>&#10;Test: <?php echo $row_colinp['name']; ?>"><?php echo $row_colinp['entrydm']; ?></td>
          <td class="Black_10" nowrap title="MRN=<?php echo $row_colinp['medrecnum']?>&#10; Name: <?php echo $names;?>"><a href="LabSpecList.php?mrn=<?php echo $row_colinp['medrecnum']; ?>"><?php echo substr($names,0,16);?></a></td>
          <td align="center" class="Black_10"><?php echo $row_colinp['age'].'-'.$row_colinp['gender']?></td>

				 <?php if ($row_colinp['urgency'] == 'STAT') { ?>
         <td class="flagWhiteonRed"><?php echo $row_colinp['urgency']; ?></td>
         <?php } ELSE if ($row_colinp['urgency'] == 'ASAP') {   ?>
         <td class="flagBlackonOrange"><?php echo $row_colinp['urgency']; ?></td>
         <?php } ELSE { ?>
         <td class="BlackBold_11"><?php echo substr($row_colinp['urgency'],0,4); ?></td>
         <?php } ?>

				 <?php if ($row_colinp['paid'] == 'N' && $row_colinp['billstatus'] == 'paylater') { ?>
        <td class="flagBlackonYellow">PL</td>

				 <?php } elseif($row_colinp['paid'] == 'N' && $row_colinp['billstatus'] == 'Due') { ?>
        <td class="flagWhiteonRed"><?php echo $row_colinp['paid']; ?></td>

             <?php } elseif ($row_colinp['paid'] == 'Y') {   ?>
         <td class="flagWhiteonGreen"><?php echo $row_colinp['paid']; ?></td>
            <?php } ?>

          <td class="Black_10" nowrap title="<?php echo $row_colinp['location']?>"><?php echo substr($row_colinp['location'],0,6)?></td>
			 <td><?php echo $row_colinp['bed']?></td>
        </tr>
        <tr>
        	<td colspan="5" ><hr style="height:1px; border:none; color:#D3D3D3; background-color:#D3D3D3; margin: 0 auto;"></td>
        </tr>
        <?php } while ($row_colinp = mysql_fetch_assoc($colinp)); ?>
      </table>
      	
      </td>
<?php }?>
	  </tr>
   </form>
</table>

</body>
</html>
<?php
mysql_free_result($sections);
?>
