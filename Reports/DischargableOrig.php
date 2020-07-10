<?php  $pt = "Dischargable patients"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
$colname_vstatus = "%";
if (isset($_POST['vstatus'])  && strlen($_POST['vstatus'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_vstatus = (get_magic_quotes_gpc()) ? $_POST['vstatus'] : addslashes($_POST['vstatus']);
}

$colname_pat_type = "%";
if (isset($_POST['pat_type'])  && strlen($_POST['pat_type'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_pat_type = (get_magic_quotes_gpc()) ? $_POST['pat_type'] : addslashes($_POST['pat_type']);
}

$colname_location = "%";
if (isset($_POST['location'])  && strlen($_POST['location'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_location = (get_magic_quotes_gpc()) ? $_POST['location'] : addslashes($_POST['location']);
}

$colname_daysback = "180";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}

mysql_select_db($database_swmisconn, $swmisconn);  //DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y Years %m Months %d Days') AS age
$query_currPat = "select p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, p.entrydt, p.entryby, v.id, DATE_FORMAT(v.visitdate,'%d-%b-%Y') vdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, 
(Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id) labcnt, 
(Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone 
from patvisit v join patperm p ON p.medrecnum = v.medrecnum
where discharge is null and 
(Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id) = 
(Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) and 
v.diagnosis is not null
and length(v.diagnosis) > 0 
and v.status like '%".$colname_vstatus."%' and v.pat_type like '%".$colname_pat_type."%' and v.location like '%".$colname_location."%' Order BY v.id";  //and visitdate >= SYSDATE() - INTERVAL 200 DAY 
$currPat = mysql_query($query_currPat, $swmisconn) or die(mysql_error());
$row_currPat = mysql_fetch_assoc($currPat);
$totalRows_currPat = mysql_num_rows($currPat);


mysql_select_db($database_swmisconn, $swmisconn);  //DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y Years %m Months %d Days') AS age
$query_dischPat2 = "select p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, p.entrydt, p.entryby, v.id, DATE_FORMAT(v.visitdate,'%d-%b-%Y') vdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id) labcnt, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone from patvisit v join patperm p ON p.medrecnum = v.medrecnum where v.discharge is null and length(v.diagnosis) > 0 and v.status like '%".$colname_vstatus."%' and v.pat_type like '%".$colname_pat_type."%' and v.location like '%".$colname_location."%' and visitdate <= SYSDATE() - INTERVAL 2 DAY Order BY v.id";
$dischPat2 = mysql_query($query_dischPat2, $swmisconn) or die(mysql_error());
$row_dischPat2 = mysql_fetch_assoc($dischPat2);
$totalRows_dischPat2 = mysql_num_rows($dischPat2);


mysql_select_db($database_swmisconn, $swmisconn);  //DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y Years %m Months %d Days') AS age
$query_dischPat7 = "select p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, p.entrydt, p.entryby, v.id, DATE_FORMAT(v.visitdate,'%d-%b-%Y') vdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id) labcnt, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.medrecnum = p.medrecnum and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone from patvisit v join patperm p ON v.medrecnum = p.medrecnum where v.discharge is null and (v.diagnosis is NULL or length(v.diagnosis) < 1) and v.status like '%".$colname_vstatus."%' and v.pat_type like '%".$colname_pat_type."%' and v.location like '%".$colname_location."%' and visitdate <= SYSDATE() - INTERVAL 7 DAY Order BY v.id";
$dischPat7 = mysql_query($query_dischPat7, $swmisconn) or die(mysql_error());
$row_dischPat7 = mysql_fetch_assoc($dischPat7);
$totalRows_dischPat7 = mysql_num_rows($dischPat7);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p align="center"><span class="navLink"><a href="ReportsMenu.php">Report Menu </a></span>&nbsp;&nbsp;&nbsp;<span class="BlueBold_16"> These undischarged patients have a diagnosis and Lab tests done and can be discharged. </span></p>
<!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="DischUpdate1.php">&nbsp;<a href="DischUpdate1.php">Discharge</a>-->

<table align="center">
  <tr>
    <td class="BlackBold_14"><div align="center">MRN</div></td>
    <td class="BlackBold_14"><div align="center">LAST NAME </div></td>
    <td class="BlackBold_14"><div align="center">Sex</div></td>
    <td class="BlackBold_14"><div align="center">Ethnic</div></td>
    <td class="BlackBold_14"><div align="center">Age</div></td>
    <td class="BlackBold_14">&nbsp;</td>
    <td class="BlackBold_14"><div align="center">Visit#</div></td>
    <td class="BlackBold_14"><div align="center">Urg</div></td>
    <td class="BlackBold_14">V-Stat</td>
    <td nowrap="nowrap" class="BlackBold_14"><div align="center">Visit Date </div></td>
    <td class="BlackBold_14"><div align="center">Pat. Type </div></td>
    <td class="BlackBold_14"><div align="center">Loc</div></td>
    <td class="BlackBold_14">Lab Done </td>
    <td class="BlackBold_14">Diagnosis</td>
  </tr>
  <?php 
		  if ($totalRows_currPat > 0){
		  	do { ?>
  <tr>
    <td nowrap="nowrap" class="BlueBold_12" title="Entry by:  <?php echo $row_currPat['entryby']; ?>&#10;Entry date: <?php echo $row_currPat['entrydt']; ?>"><div align="left"><?php echo $row_currPat['medrecnum']; ?></div></td>
    <td nowrap="nowrap" class="BlueBold_12"><?php echo $row_currPat['lastname']; ?>, <?php echo $row_currPat['firstname']; ?> (<?php echo $row_currPat['othername']; ?>)</td>
    <td class="BlackBold_12"><?php echo $row_currPat['gender']; ?></td>
    <td class="BlackBold_12"><?php echo $row_currPat['ethnicgroup']; ?></td>
    <td class="BlackBold_12"><?php echo $row_currPat['age']; ?></td>
    <td bgcolor="#FF99FF" class="BlackBold_12" title="Entry by:  <?php echo $row_currPat['ventryby']; ?>
Entry date: <?php echo $row_currPat['ventrydt']; ?>
Visit reason: <?php echo $row_currPat['visitreason']; ?>">&nbsp;</td>
    <td class="BlackBold_12" title="Entry by:  <?php echo $row_currPat['ventryby']; ?>
Entry date: <?php echo $row_currPat['ventrydt']; ?>
Visit reason: <?php echo $row_currPat['visitreason']; ?>"><?php echo $row_currPat['id']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_currPat['urgency']; ?>"><?php echo $row_currPat['urg']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_currPat['urgency']; ?>"><?php echo $row_currPat['status']; ?></td>
    <td nowrap="nowrap" class="BlackBold_12"><?php echo $row_currPat['vdate']; ?></td>
    <td class="BlackBold_12"><?php echo $row_currPat['pat_type']; ?></td>
    <td class="BlackBold_12"><?php echo $row_currPat['location']; ?></td>
    <td class="GreenBold_12"><div align="center"><?php echo $row_currPat['labdone']; ?> of <?php echo $row_currPat['labcnt']; ?></div></td>
    <td class="GreenBold_12"><?php echo $row_currPat['diagnosis']; ?></td>
  </tr>
  <?php } while ($row_currPat = mysql_fetch_assoc($currPat));
		  } ?>
</table>

<p align="center" class="BlueBold_16">These undischarged patients have visit dates greater than 2 days ago and have a diagnosis. </p>
<table align="center">
  <tr>
    <td class="BlackBold_14"><div align="center">MRN</div></td>
    <td class="BlackBold_14"><div align="center">LAST NAME </div></td>
    <td class="BlackBold_14"><div align="center">Sex</div></td>
    <td class="BlackBold_14"><div align="center">Ethnic</div></td>
    <td class="BlackBold_14"><div align="center">Age</div></td>
    <td class="BlackBold_14">&nbsp;</td>
    <td class="BlackBold_14"><div align="center">Visit#</div></td>
    <td class="BlackBold_14"><div align="center">Urg</div></td>
    <td class="BlackBold_14">V-Stat</td>
    <td nowrap="nowrap" class="BlackBold_14"><div align="center">Visit Date </div></td>
    <td class="BlackBold_14"><div align="center">Pat. Type </div></td>
    <td class="BlackBold_14"><div align="center">Loc</div></td>
    <td class="BlackBold_14">Lab Done </td>
    <td class="BlackBold_14">Diagnosis</td>
  </tr>
  <?php 
		  if ($totalRows_dischPat2 > 0){
		  	do { ?>
  <tr>
    <td nowrap="nowrap" class="BlueBold_12" title="Entry by:  <?php echo $row_dischPat2['entryby']; ?>&#10;Entry date: <?php echo $row_dischPat2['entrydt']; ?>"><div align="left"><?php echo $row_dischPat2['medrecnum']; ?></div></td>
    <td nowrap="nowrap" class="BlueBold_12"><?php echo $row_dischPat2['lastname']; ?>, <?php echo $row_dischPat2['firstname']; ?> (<?php echo $row_dischPat2['othername']; ?>)</td>
    <td class="BlackBold_12"><?php echo $row_dischPat2['gender']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat2['ethnicgroup']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat2['age']; ?></td>
    <td bgcolor="#FF99FF" class="BlackBold_12" title="Entry by:  <?php echo $row_dischPat2['ventryby']; ?>
Entry date: <?php echo $row_dischPat2['ventrydt']; ?>
Visit reason: <?php echo $row_dischPat2['visitreason']; ?>">&nbsp;</td>
    <td class="BlackBold_12" title="Entry by:  <?php echo $row_dischPat2['ventryby']; ?>
Entry date: <?php echo $row_dischPat2['ventrydt']; ?>
Visit reason: <?php echo $row_dischPat2['visitreason']; ?>"><?php echo $row_dischPat2['id']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_dischPat2['urgency']; ?>"><?php echo $row_dischPat2['urg']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_dischPat2['urgency']; ?>"><?php echo $row_dischPat2['status']; ?></td>
    <td nowrap="nowrap" class="BlackBold_12"><?php echo $row_dischPat2['vdate']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat2['pat_type']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat2['location']; ?></td>
    <td class="GreenBold_12"><div align="center"><?php echo $row_dischPat2['labdone']; ?> of <?php echo $row_dischPat2['labcnt']; ?></div></td>
    <td class="GreenBold_12"><?php echo $row_dischPat2['diagnosis']; ?></td>
  </tr>
  <?php } while ($row_dischPat2 = mysql_fetch_assoc($dischPat2));
		  } ?>
</table>

<p align="center" class="BlueBold_16">These undischarged patients have visit dates greater than 7 days ago. </p>
<table align="center">
  <tr>
    <td class="BlackBold_14"><div align="center">MRN</div></td>
    <td class="BlackBold_14"><div align="center">LAST NAME </div></td>
    <td class="BlackBold_14"><div align="center">Sex</div></td>
    <td class="BlackBold_14"><div align="center">Ethnic</div></td>
    <td class="BlackBold_14"><div align="center">Age</div></td>
    <td class="BlackBold_14">&nbsp;</td>
    <td class="BlackBold_14"><div align="center">Visit#</div></td>
    <td class="BlackBold_14"><div align="center">Urg</div></td>
    <td class="BlackBold_14">V-Stat</td>
    <td nowrap="nowrap" class="BlackBold_14"><div align="center">Visit Date </div></td>
    <td class="BlackBold_14"><div align="center">Pat. Type </div></td>
    <td class="BlackBold_14"><div align="center">Loc</div></td>
    <td class="BlackBold_14">Lab Done </td>
    <td class="BlackBold_14">Diagnosis</td>
  </tr>
  <?php 
		  if ($totalRows_dischPat7 > 0){
		  	do { ?>
  <tr>
    <td nowrap="nowrap" class="BlueBold_12" title="Entry by:  <?php echo $row_dischPat7['entryby']; ?>&#10;Entry date: <?php echo $row_dischPat7['entrydt']; ?>"><div align="left"><?php echo $row_dischPat7['medrecnum']; ?></div></td>
    <td nowrap="nowrap" class="BlueBold_12"><?php echo $row_dischPat7['lastname']; ?>, <?php echo $row_dischPat7['firstname']; ?> (<?php echo $row_dischPat7['othername']; ?>)</td>
    <td class="BlackBold_12"><?php echo $row_dischPat7['gender']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat7['ethnicgroup']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat7['age']; ?></td>
    <td bgcolor="#FF99FF" class="BlackBold_12" title="Entry by:  <?php echo $row_dischPat7['ventryby']; ?>
Entry date: <?php echo $row_dischPat7['ventrydt']; ?>
Visit reason: <?php echo $row_dischPat7['visitreason']; ?>">&nbsp;</td>
    <td class="BlackBold_12" title="Entry by:  <?php echo $row_dischPat7['ventryby']; ?>
Entry date: <?php echo $row_dischPat7['ventrydt']; ?>
Visit reason: <?php echo $row_dischPat7['visitreason']; ?>"><?php echo $row_dischPat7['id']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_dischPat7['urgency']; ?>"><?php echo $row_dischPat7['urg']; ?></td>
    <td class="BlackBold_12" title="Urgency: <?php echo $row_dischPat7['urgency']; ?>"><?php echo $row_dischPat7['status']; ?></td>
    <td nowrap="nowrap" class="BlackBold_12"><?php echo $row_dischPat7['vdate']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat7['pat_type']; ?></td>
    <td class="BlackBold_12"><?php echo $row_dischPat7['location']; ?></td>
    <td class="GreenBold_12"><div align="center"><?php echo $row_dischPat7['labdone']; ?> of <?php echo $row_dischPat7['labcnt']; ?></div></td>
    <td class="GreenBold_12"><?php echo $row_dischPat7['diagnosis']; ?></td>
  </tr>
  <?php } while ($row_dischPat7 = mysql_fetch_assoc($dischPat7));
		  } ?>
</table>

</body>
</html>
