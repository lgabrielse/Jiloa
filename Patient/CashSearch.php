<?php  $pt = "Cashier Menu"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php  
$colname_search1 = "zzzzz";
$xyz = "";
if (isset($_POST['qrytxt']) AND strlen($_POST['qrytxt'])>1) {
  $colname_search1 = (get_magic_quotes_gpc()) ? $_POST['qrytxt'] : addslashes($_POST['qrytxt']);
  $xyz = $colname_search1;
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_search = "SELECT `patperm`.`medrecnum`, `patperm`.`lastname`, `patperm`.`firstname`, `patperm`.`othername`, `patperm`.`gender`, DATE_FORMAT(`patperm`.`dob`,'%d-%b-%Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age FROM patperm WHERE (concat(`patperm`.`lastname`, ' ',`patperm`.`firstname`, ' ', IFNULL(othername,' ')) like '%".$colname_search1."%') AND `patperm`.`active` = 'Y'";
$search = mysql_query($query_search, $swmisconn) or die(mysql_error());
$row_search = mysql_fetch_assoc($search);
$totalRows_search = mysql_num_rows($search);

  $colname_daysback = "3";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}

$colname_pat_type = "%";
if (isset($_POST['pat_type'])  && strlen($_POST['pat_type'])>0) {
  $colname_pat_type = (get_magic_quotes_gpc()) ? $_POST['pat_type'] : addslashes($_POST['pat_type']);
}

$colname_location = "%";  // && ($_POST["MM_update"] == "form3")
if (isset($_POST['location'])  && strlen($_POST['location'])>0 ) {
  $colname_location = (get_magic_quotes_gpc()) ? $_POST['location'] : addslashes($_POST['location']);
}

mysql_select_db($database_swmisconn, $swmisconn);
/*$query_pats = "SELECT distinct DATE_FORMAT(o.entrydt,'%d %b %y') oentrydt, o.medrecnum, p.lastname, p.firstname, p.othername, p.gender, p.dob, CASE WHEN o.status = 'Refund' THEN 'Refund' ELSE (CASE WHEN o.status = 'RxCosted' THEN 'RxCosted' ElSE 'ordered' END) END status, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age, p.ethnicgroup, p.active, p.hospital FROM `orders` o join `patperm` p on o.medrecnum = p.medrecnum WHERE (o.status = 'Refund' 
OR (o.amtpaid is null and rate > 0) 
OR (o.item is NOT null and o.Status in ('RxCosted', 'Refund'))) 
and o.status not in ('RxOrdered', 'RxPaid', 'RxDispensed', 'RxReferred') and o.entrydt >= SYSDATE() - INTERVAL " .$colname_daysback." DAY order by DATE(o.entrydt) DESC, lastname, firstname";*/
$query_pats = "SELECT DISTINCT DATE_FORMAT(o.entrydt,'%d %b %y') oentrydt, o.medrecnum, o.billstatus, lastname, p.firstname, p.othername, p.gender, p.dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age, p.ethnicgroup, p.active, p.hospital, v.pat_type, v.location, b.bed FROM `orders` o join `patperm` p on o.medrecnum = p.medrecnum join patvisit v on o.visitid = v.id left outer JOIN patbed b on p.medrecnum = b.medrecnum WHERE v.pat_type like '".$colname_pat_type."%' and v.location like '".$colname_location."%' and billstatus In ('Due', 'paylater', 'Refund', 'PartPaid') and o.entrydt >= SYSDATE() - INTERVAL " .$colname_daysback." DAY order by DATE(o.entrydt) DESC, lastname, firstname";
/* (pat_type commented out in html section)
-----the query below allows pattype selection, but does not allow refund of visit orders----
$query_pats = "SELECT distinct DATE_FORMAT(o.entrydt,'%d %b %y') oentrydt, o.medrecnum, o.billstatus, lastname, p.firstname, p.othername, p.gender, p.dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age, p.ethnicgroup, p.active, p.hospital, v.pat_type FROM `orders` o join `patperm` p on o.medrecnum = p.medrecnum right outer join patvisit v on o.visitid = v.id WHERE v.pat_type like '".$colname_pat_type."%' and billstatus In ('Due', 'Refund', 'PartPaid') and o.entrydt >= SYSDATE() - INTERVAL " .$colname_daysback." DAY order by DATE(o.entrydt) DESC, lastname, firstname";*/
$pats = mysql_query($query_pats, $swmisconn) or die(mysql_error());  //DATE_FORMAT(o.entrydt,'%Y-%m-%d') entrydt,
$row_pats = mysql_fetch_assoc($pats);
$totalRows_pats = mysql_num_rows($pats);
?>
<?php
	$pat_type = '%';
   if(isset($_POST['pat_type'])) {
	   $pat_type = $_POST['pat_type'] ;
		}
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_loc = "SELECT section, name FROM fee WHERE active='Y' and dept = 'records' and section NOT IN('Registration', 'ReturnLoc') and section like '". $pat_type."' ORDER BY section"; 
	$loc = mysql_query($query_loc, $swmisconn) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cash Search</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=25,screenX=300,screenY=25';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

</head>

<body onLoad="document.forms.formcps1.mrn.focus()">
<table width="800px" align="center">
  <tr>
    <td>
	   <table width="400px" align="center">
	    <form id="formdaysback" name="formdaysback" method="post" action="CashSearch.php">
        <tr>
          <td nowrap="nowrap"><div align="center" class="subtitlebl">CASHIER: UNPAID PATIENTS </div></td>
<!--   can't get query to work for patient type selection-->
          <td nowrap="nowrap">
             <div align="right">Patient <br />
                Type:</div></td>
          <td><select name="pat_type" id="pat_type" onChange="document.formdaysback.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>All</option>
			<option value="OutPatient" <?php if (!(strcmp("OutPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>OutPatient</option>
            <option value="InPatient" <?php if (!(strcmp("InPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>InPatient</option>
            <option value="Antenatal" <?php if (!(strcmp("Antenatal", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>Antenatal</option>
          </select>          </td>
          
          <td nowrap="nowrap"><div align="right">Loca<br />tion:</div></td>
          <td><select name="location" id="location" onChange="document.formdaysback.submit();">
                 <option value="%">ALL</option>
              <?php do {  ?>
      					<option value="<?php echo $row_loc['name']?>"<?php if (!(strcmp($row_loc['name'], $colname_location))) {echo "selected=\"selected\"";} ?>><?php echo $row_loc['section']?> - <?php echo $row_loc['name']?></option>
      				<?php }
							 while ($row_loc = mysql_fetch_assoc($loc));
								$rows = mysql_num_rows($loc);
								if($rows > 0) {
										mysql_data_seek($loc, 0);
									$row_loc = mysql_fetch_assoc($loc);
								} ?>
				    </select>
        </td>
          
          
        <td nowrap="nowrap">Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.formdaysback.submit();">    <!--onChange="document.form3.submit();"-->
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
        </tr>
		</form>
      </table>
      <table width="500px">
    <form method="post" name="form2" id="form2" enctype="multipart/form-data">
            <tr>
              <!--<td>ordered</td>-->
              <td align="center">MRN*</td>
              <td align="center">Lastname</td>
              <td align="center">Firstname</td>
              <td align="center">Othername</td>
              <td align="center">Sex</td>

              <td align="center">Age</td>
              <td>Location</td>
              <td>Bed</td>
              <td align="center">Order<br />
              Bal</td>
              <td align="center">Acct <br />
              Bal</td>
              <td align="center">Billing <br />
              status</td>
              <td>&nbsp;</td>
            </tr>
	<?php  $rowdate = "";
		do { //bgcolor="#BCFACC" 
		if($row_pats['billstatus'] == 'Refund'){
			$bkgd = "#DDEEFF";
		} else {
			$bkgd = "#FFFFFF";
		}
		
		If($rowdate <> $row_pats['oentrydt']) {?>	
		   <tr>
			  <td colspan="10"><strong><?php echo $row_pats['oentrydt'] ?></strong></td>
		  </tr>
			<?php }	?>  
<?php  ////////////////////////// get patient account balance     
// amount deposited by patient MRN (where ro.status = 'Deposited'
$AcctBal = 0;
$query_AcctDeposits = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'Deposited' and r.medrecnum = '".$row_pats['medrecnum']."' GROUP BY r.medrecnum";
$AcctDeposits = mysql_query($query_AcctDeposits, $swmisconn) or die(mysql_error());
$row_AcctDeposits = mysql_fetch_assoc($AcctDeposits);
$totalRows_AcctDeposits = mysql_num_rows($AcctDeposits);  //ok

//  amount paid by patient where ro.status = 'Paid' or 'Partaid' and nbc = 'Deposited'
$query_AcctPdByDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE (ro.status = 'Paid' OR ro.status = 'PartPaid') and r.nbc = 'Deposit' and r.medrecnum = '".$row_pats['medrecnum']."' GROUP BY r.medrecnum"; //ok
$AcctPdByDep = mysql_query($query_AcctPdByDep, $swmisconn) or die(mysql_error());
$row_AcctPdByDep = mysql_fetch_assoc($AcctPdByDep);
$totalRows_AcctPdByDep = mysql_num_rows($AcctPdByDep);

// amount refunded by patient wherero.status = 'Refunded' and r.nbc = 'Deposit' 
$query_AcctRefToDep = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'Refunded' and r.nbc = 'Deposit' and r.medrecnum = '".$row_pats['medrecnum']."' GROUP BY r.medrecnum"; //ok
$AcctRefToDep = mysql_query($query_AcctRefToDep, $swmisconn) or die(mysql_error());
$row_AcctRefToDep = mysql_fetch_assoc($AcctRefToDep);
$totalRows_AcctRefToDep = mysql_num_rows($AcctRefToDep);

// amount of Deposit refunded
$query_AcctDepRefund = "SELECT SUM(IFNULL(ro.amtpaid,0)) sumamtpaid FROM rcptord ro join receipts r on r.id = ro.rcptid WHERE ro.status = 'DepRefund' and r.medrecnum = '".$row_pats['medrecnum']."' GROUP BY r.medrecnum"; //ok
$AcctDepRefund = mysql_query($query_AcctDepRefund, $swmisconn) or die(mysql_error());
$row_AcctDepRefund = mysql_fetch_assoc($AcctDepRefund);
$totalRows_AcctDepRefund = mysql_num_rows($AcctDepRefund);

// Calculate 
$AcctBal = $row_AcctDeposits['sumamtpaid'] + $row_AcctDepRefund['sumamtpaid'] + (0 - $row_AcctRefToDep['sumamtpaid']) - $row_AcctPdByDep['sumamtpaid'];

$query_OrdBal = "SELECT p.lastname, p.firstname, p.othername, DATE_FORMAT(p.DOB,'%d-%c-%Y') DOB, p.gender, o.medrecnum, SUM(o.amtdue) sumamtdue, SUM(o.amtpaid) sumamtpaid, SUM(IFNULL(o.amtpaid,0)) - SUM(IFNULL(o.amtdue,0)) bal FROM patperm p join `orders` o on o.medrecnum = p.medrecnum WHERE o.billstatus <> 'Refunded' and o.feeid <> 279 and o.medrecnum = '".$row_pats['medrecnum']."' GROUP BY medrecnum";
$OrdBal = mysql_query($query_OrdBal, $swmisconn) or die(mysql_error());
$row_OrdBal = mysql_fetch_assoc($OrdBal);
$totalRows_OrdBal = mysql_num_rows($OrdBal);

 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     
?>      
      
          <tr>
<!--              <td nowrap="nowrap" bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Ordered: <?php echo $row_pats['oentrydt']; ?>&#10;Status: <?php echo $row_pats['status']; ?>&#10;Hospital: <?php echo $row_pats['hospital']; ?>&#10;Active= <?php echo $row_pats['active']; ?>"><?php echo $row_pats['oentrydt']; ?></td>-->
  <?php     if(allow(10,3)==1){?>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Ordered: <?php echo $row_pats['oentrydt']; ?>&#10;Hospital: <?php echo $row_pats['hospital']; ?>&#10;Active= <?php echo $row_pats['active']; ?>"><a href="CashShow.php?mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['medrecnum']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Hospital: <?php echo $row_pats['hospital']; ?>&#10;Active= <?php echo $row_pats['active']; ?>"><a href="CashShow.php?mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['lastname']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><a href="CashShow.php?mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['firstname']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><a href="CashShow.php?mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['othername']; ?></a></td>

<?php } else { ?>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Ordered: <?php echo $row_pats['oentrydt']; ?>&#10;Hospital: <?php echo $row_pats['hospital']; ?>&#10;Active= <?php echo $row_pats['active']; ?>"><?php echo $row_pats['medrecnum']; ?></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Hospital: <?php echo $row_pats['hospital']; ?>&#10;Active= <?php echo $row_pats['active']; ?>"><?php echo $row_pats['lastname']; ?></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_pats['firstname']; ?></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_pats['othername']; ?></td>
<?php }?>

              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_pats['gender']; ?></td>
              <td align="center" bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_pats['age']; ?></td>
              <td bgcolor="<?php echo $bkgd; ?>" nowrap class="BlackBold_11"><?php echo $row_pats['location']?></td>
              <td bgcolor="<?php echo $bkgd; ?>" nowrap class="BlackBold_11"><?php echo $row_pats['bed']?></td>

<!--show order balance-->
						  <td nowrap align="center" bgcolor="<?php echo $bkgd; ?>">              
	<?php if($row_OrdBal['bal'] < 0) { ?>
		<a href="javascript:void(0)" onclick="MM_openBrWindow('../Patient/PatAcctSum.php?mrn=<?php echo $row_pats['medrecnum']; ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=700')"></a> <span class="flagWhiteonRed"> <?php echo $row_OrdBal['bal']; ?> </span>
	<?php } else {?>
		<a href="javascript:void(0)" onclick="MM_openBrWindow('../Patient/PatAcctSum.php?mrn=<?php echo $row_pats['medrecnum']; ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=700')"></a><span class="flagWhiteonGreen"> <?php echo $row_OrdBal['bal']; ?> </span>
	<?php }?>
						  </td>

<!--show account balance-->
						  <td nowrap align="center" bgcolor="<?php echo $bkgd; ?>">              
	<?php if($AcctBal < 0) { ?>
		<a href="javascript:void(0)" onclick="MM_openBrWindow('../Patient/PatAcctSum.php?mrn=<?php echo $row_pats['medrecnum']; ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=700')"> <span class="flagWhiteonRed"> <?php echo $AcctBal; ?> </span></a>
	<?php } else {?>
		<a href="javascript:void(0)" onclick="MM_openBrWindow('../Patient/PatAcctSum.php?mrn=<?php echo $row_pats['medrecnum']; ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=700')"><span class="flagWhiteonGreen"> <?php echo $AcctBal; ?> </span></a> 
	<?php }?>
						  </td>
<!--show billstatus-->
<?php      if(allow(10,3)==1){?>
              <td align="center" bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11" title="mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><a href="CashShowAll.php?mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['billstatus']; ?></a></td>

	<?php If($row_pats['medrecnum'] > 0) {?>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11" title="Display All Orders"><a href="CashShowAll.php?mrn=<?php echo $row_pats['medrecnum']; ?>">All</a></td>
			 <?php  }?>
<?php } else {?>
  
               <td align="center" bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11" title="mrn=<?php echo $row_pats['medrecnum']; ?>&billstatus=<?php echo $row_pats['billstatus']; ?>"><?php echo $row_pats['billstatus']; ?></td>

	<?php If($row_pats['medrecnum'] > 0) {?>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11" title="Display All Orders">All</td>
			 <?php  }?>

<?php  }?>
            </tr>

            <?php $rowdate = $row_pats['oentrydt'];
			 } while ($row_pats = mysql_fetch_assoc($pats)); ?>
        </form> 
      </table>
</td>
    <td valign="top">
		<table width="500">
			<tr>
				<td>
					<table width="200px" align="center">
						<caption align="top" class="subtitlebl">CASHIER MRN SEARCH</caption>
				  <form method="get" name="formcps0" id="formcps0" action="CashShowAll.php">
					<tr>
					  <td align="center">Enter Patient <br />
				      Medical Record Number</td>
					</tr>
					<tr>
					  <td align="center"><input name="mrn" type="text" id="mrn" autocomplete="off" /></td>
					</tr>
					<tr>
<?php 		if (allow(10,3)==1){?>  
            <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Search MRN" /></td>
<?php 	}?>
  				</tr>
				</form>					
				  </table>
				</td>
				<td valign="top">
				  <table width="250px" align="center">
					<caption align="top" class="subtitlebl">
					  CASHIER PATIENT SEARCH
					  </caption>
				  <form method="post" name="formcps1" id="formcps1" enctype="multipart/form-data">
					<tr>
					  <td align="center">Enter 3 or more Characters<br />
						of a Patient Name </td>
					</tr>
					<tr>
					  <td align="center"><input name="qrytxt" type="text" id="qrytxt" autocomplete="off" /></td>
					</tr>
					<tr>
<?php 		if (allow(10,3)==1){?>  
					  <td align="center"><input type="submit" name="Submit2" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Search Name" /></td>
<?php }?>
					</tr>
				</form>
				  </table>
				</td>
			  </tr>
			  <tr>
				<td colspan="2" valign="top"><form id="form2" name="form2" method="post" action="">
				  <table bgcolor="F5F5F5" border="1">
					<tr>
					  <td><?php echo $colname_search1 ; ?></td>
					  <td class="subtitlebk"><div align="center">Last Name </div></td>
					  <td class="subtitlebk"><div align="center">First Name </div></td>
					  <td class="subtitlebk"><div align="center">Other Name </div></td>
					  <td class="subtitlebk"><div align="center">Gender</div></td>
					  <td class="subtitlebk"><div align="center">Age</div></td>
					  <td class="subtitlebk"><div align="center">DOB</div></td>
					</tr>
					<?php do { ?>
					<tr>
					  <td class="navLink"><a href="CashShowAll.php?mrn=<?php echo $row_search['medrecnum']; ?>"><?php echo $row_search['medrecnum']; ?></a></td>
					  <td bgcolor="#FFFFFF" class="sidebar"><a href="CashShow.php?mrn=<?php echo $row_search['medrecnum']; ?>"><?php echo $row_search['lastname']; ?></a></td>
					  <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['firstname']; ?></td>
					  <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['othername']; ?></td>
					  <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['gender']; ?></td>
<?php  //calculate Age
//	$patage = "";
//	$patdob = "";
//	if (strtotime($row_search['dob'])) {
//		$c= date('Y');
//		$y= date('Y',strtotime($row_search['dob']));
//		$patage = $c-$y;
//	//format date of birth
//		$datetime = strtotime($row_search['dob']);
//		$patdob = date("m/d/y", $datetime);
//} 
?>
					  <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['age']; ?></td>
					  <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['dob']; ?></td>
					</tr>
					<?php } while ($row_search = mysql_fetch_assoc($search)); ?>
				  </table>
			</form></td>
		 </tr>
	   </table>	</td>
  </tr>
</table>
<script  type="text/javascript">
var frmvalidator = new Validator("formcps1");
frmvalidator.addValidation("qrytxt","alnum_s","Allows only alphabetic, numeric and space characters ");
frmvalidator.addValidation("qrytxt","minlen=3","Allows MINIMUM OF 3 alphabetic, numeric, or space characters ");
var frmvalidator = new Validator("formcps0");
frmvalidator.addValidation("mrn","num","Numbers Only");

</script>

</body>
</html>
<?php
mysql_free_result($pats);
?>


