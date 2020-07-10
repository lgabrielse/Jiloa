<?php  $pt = "Patient List"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php

$colname_sort = "medrecnum";
if (isset($_GET['sort'])) {
  $colname_sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_pat = "SELECT pp.medrecnum, pp.hospital, pp.active, DATE_FORMAT(pp.entrydt,'%m-%d-%Y') patdt, pp.entryby patby, pp.lastname, pp.firstname, pp.othername, pp.gender, pp.ethnicgroup,  DATE_FORMAT(pp.dob,'%m-%d-%Y') dob, pi.id iid, pi.medrecnum imedrecnum, pi.title, pi.occup, pi.married, pi.street, pi.city, pi.locgovt, pi.state, pi.country, pi.phone1, pi.phone2, pi.phone3, pi.em_rel, pi.em_fname, pi.em_lname, pi.em_phone1, pi.em_phone2, DATE_FORMAT(pi.entrydt,'%m-%d-%Y') infodt, pi.entryby infoby, pi.comments FROM patperm pp left outer join patinfo pi on pp.medrecnum = pi.medrecnum Order By ".$colname_sort;

$pat = mysql_query($query_pat, $swmisconn) or die(mysql_error());
$row_pat = mysql_fetch_assoc($pat);
$totalRows_pat = mysql_num_rows($pat);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient List</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table border="1">
  <caption align="center"><div align="center"><span class="navLink"><a href="ReportsMenu.php">Report Menu </a></span>&nbsp;&nbsp;&nbsp;<span class="subtitlebl"> LIST OF ALL PATIENTS, PATIENT INFORMATION, AND VISITS</span></caption>
  <tr>
    <td colspan="4" class="BlackBold_14"><div align="center">Patient Permanent </div></td>
    <td class="BlackBold_14">&nbsp;</td>
    <td colspan="7" class="BlackBold_14"><div align="center">Patient Information </div></td>
    <td class="BlackBold_14"><div align="center">Patient Visit(s) </div></td>
  </tr>
  <tr>
    <td class="BlackBold_14"><div align="center"><a href="PatientList.php?sort=lastname">lastname</a>&nbsp;<a href="PatientList.php?sort=lastname desc">^^</a><br />
        <a href="PatientList.php?sort=firstname">firstname</a><br />
        <a href="PatientList.php?sort=othername">othername</a>    </div></td>
    <td class="BlackBold_14"><div align="center"><a href="PatientList.php?sort=gender">gender</a><br />
        <a href="PatientList.php?sort=ethnicgroup">ethnicgroup</a><br />
        <a href="PatientList.php?sort=dob">dob</a></div></td>
    <td class="BlackBold_14"><div align="center"><a href="PatientList.php?sort=medrecnum">mrn</a>&nbsp;<a href="PatientList.php?sort=medrecnum desc">^^</a><br />
      hospital<br />
      active</div></td>
    <td class="BlackBold_14"><div align="center"><a href="PatientList.php?sort=patdt">entry dt</a><br />
        <a href="PatientList.php?sort=patby">entry by</a></div></td>
    <td class="BlackBold_14"><div align="center">iid</div></td>
    <td class="BlackBold_14"><div align="center">medrecnum<br />
      title<br />
      <a href="PatientList.php?sort=married">married</a></div></td>
    <td class="BlackBold_14"><div align="center">occup<br />
      street<br />
      <a href="PatientList.php?sort=city">city</a></div></td>
    <td class="BlackBold_14"><div align="center"><a href="PatientList.php?sort=locgovt">locgovt</a><br />
        <a href="PatientList.php?sort=state">state</a><br />
        <a href="PatientList.php?sort=country">country</a></div></td>
    <td class="BlackBold_14"><div align="center">phone1<br />
      phone2<br />
      phone3<br />
    </div></td>
    <td class="BlackBold_14"><div align="center">em_rel<br />
      em_fname<br />
      em_lname</div></td>
    <td class="BlackBold_14"><div align="center">em_phone1<br />
      em_phone2</div></td>
    <td class="BlackBold_14"><div align="center">entrydt by 
      entryby<br />
      comments</div></td>
    <td class="BlackBold_14">
		<table>
			<tr>
	
			  <td bgcolor="#FFFFFF"><div align="center"><strong>id</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>MRN</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>visitdate</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>pat_type</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>location</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>urgency</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>discharge</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>visitreason</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>diagnosis</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>entryby</strong></div></td>
				<td bgcolor="#FFFFFF"><div align="center"><strong>entrydt</strong></div></td>
			</tr>
	  </table>	</td>
  </tr>
  <?php do { ?>
  <tr>
    <td bgcolor="#FFFDDA" class="BlueBold_12"><?php echo $row_pat['lastname']; ?><br />
        <?php echo $row_pat['firstname']; ?><br />
        <?php echo $row_pat['othername']; ?></td>
    <td bgcolor="#FFFDDA" class="BlueBold_12"><?php echo $row_pat['gender']; ?><br />
        <?php echo $row_pat['ethnicgroup']; ?><br />
        <?php echo $row_pat['dob']; ?></td>
    <td bgcolor="#FFFDDA" class="BlueBold_12"><?php echo $row_pat['medrecnum']; ?><br />
      <?php echo $row_pat['hospital']; ?><br />
      <?php echo $row_pat['active']; ?></td>
    <td bgcolor="#FFFDDA" class="BlueBold_12"><?php echo $row_pat['patdt']; ?><br />
        <?php echo $row_pat['patby']; ?></td>
    <td bgcolor="ffeedd" class="BlueBold_12"><h3><?php echo $row_pat['iid']; ?></h3></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['imedrecnum']; ?><br />
        <?php echo $row_pat['title']; ?><br />
        <?php echo $row_pat['married']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['occup']; ?><br />
        <?php echo $row_pat['street']; ?><br />
        <?php echo $row_pat['city']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['locgovt']; ?><br />
        <?php echo $row_pat['state']; ?><br />
        <?php echo $row_pat['country']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['phone1']; ?><br />
        <?php echo $row_pat['phone2']; ?><br />
        <?php echo $row_pat['phone3']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['em_rel']; ?><br />
        <?php echo $row_pat['em_fname']; ?><br />
        <?php echo $row_pat['em_lname']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['em_phone1']; ?><br />
        <?php echo $row_pat['em_phone2']; ?></td>
    <td bgcolor="#ffeedd" class="BlueBold_12"><?php echo $row_pat['infodt']; ?><br />
        <?php echo $row_pat['infoby']; ?><br />
        <?php echo $row_pat['comments']; ?></td>
    <td class="BlueBold_12">



  <?php // add Visit data
mysql_select_db($database_swmisconn, $swmisconn);
$query_Visit = "SELECT id, medrecnum, DATE_FORMAT(visitdate,'%m-%d-%Y') visitdate, pat_type, location, urgency, discharge, visitreason, diagnosis, entryby, DATE_FORMAT(entrydt,'%m-%d-%Y') entrydt FROM patvisit Where medrecnum = ".$row_pat['medrecnum'];
$Visit = mysql_query($query_Visit, $swmisconn) or die(mysql_error());
$row_Visit = mysql_fetch_assoc($Visit);
$totalRows_Visit = mysql_num_rows($Visit);

 if ($row_Visit['medrecnum'] > 0) {
?>
	<table>
  <?php do { ?>
	  <tr>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['id']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['medrecnum']; ?></td>
		<td width="200" bgcolor="#FFFFFF"><?php echo $row_Visit['visitdate']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['pat_type']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['location']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['urgency']; ?></td>
		<td width="100" width="65" bgcolor="#00FFFF"><?php echo $row_Visit['discharge']; ?></td>
		<td width="200" bgcolor="#FFFFFF"><?php echo $row_Visit['visitreason']; ?></td>
		<td width="200" bgcolor="#00FFFF"><?php echo $row_Visit['diagnosis']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['entryby']; ?></td>
		<td width="100" bgcolor="#FFFFFF"><?php echo $row_Visit['entrydt']; ?></td>
  	  </tr>
  <?php } while ($row_Visit = mysql_fetch_assoc($Visit)); ?>
	</table>

  <?php 	} ?> 	</td>
  </tr>
  <?php } while ($row_pat = mysql_fetch_assoc($pat)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($pat);
?>
