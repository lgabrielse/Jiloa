<?php //require_once('../../Connections/swmisconn.php'); ?><?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$colname_patperm = "-1";
if (isset($_GET['mrn'])) {
  $colname_patperm = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}  // Patient Perm data
mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $colname_patperm."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

// Visit data
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitlist = sprintf("SELECT id, medrecnum, visitdate visitpregdt, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, weight, height, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE medrecnum = %s order by id", $colname_patperm);
$visitlist = mysql_query($query_visitlist, $swmisconn) or die(mysql_error());
$row_visitlist = mysql_fetch_assoc($visitlist);
$totalRows_visitlist = mysql_num_rows($visitlist);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient History</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
function MM_closeBrWindow() { // this works too
  window.close(); 
}
//-->
</script>
</head>

<body>
<!-- Begin PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT -   -->
  <a name="top"></a>
<table width = "1000px" >
	<tr>
		<td height="5px" bgcolor="#6699CC" class="legal"> <div align="center"><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </div></td>
	</tr>
</table>
  <table width="1000px">
	  <tr>
	  	<td bgcolor="#32ff32"><div align="center"><A HREF="javascript:window.print()">Print</A></td>
	    <td><div align="center"><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></a></td>
		<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?> Medical Center</td>
	    <td colspan="2" nowrap="nowrap" class ="BlueBold_18">Patient History</td>
	    <td colspan="2" align="right" class ="BlueBold_12">Printed:<?php echo date("d-M-Y") ?></td>
      </tr>
	  <tr>
	    <td class ="BlueBold_18">&nbsp;</td>
		<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
		<td bgcolor="#FFFFFF" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_20" ><?php echo $row_patperm['lastname']; ?></span>,<span class="BlueBold_18"><?php echo $row_patperm['firstname']; ?></span>(<span class="BlueBold_18"><?php echo $row_patperm['othername']; ?></span>)</td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
		<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_patperm['dob']; ?></span>:<?php echo $row_patperm['est']; ?></td>
	  </tr>
</table>
<table width = "1000px" >
	<tr>
		<td height="5px" bgcolor="#6699CC" class="legal"> <div align="center"><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </div></td>
	</tr>
</table>


<!-- Begin VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT -   -->
<?php do { 
	$pregid = '0';
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_preg = sprintf("SELECT id, visitid, medrecnum, firstvisit, entrydt, UNIX_TIMESTAMP('entrydt') FROM anpreg WHERE visitid = '".$row_visitlist['id']."' and medrecnum = '". $colname_patperm."' ORDER BY id ASC");
	$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
	$row_preg = mysql_fetch_assoc($preg);
	$totalRows_preg = mysql_num_rows($preg);
	
	echo $row_preg['visitid'] ."<br/>";
	
 //exit;
	$visitid = $row_visitlist['id'];
	include('PatHistAllVisit.inc2.php');  //show visit info and notes
	
	if($totalRows_preg <> 0){  // show notes, orders, results
		$mrn = $row_visitlist['medrecnum'];
		$pregid = $row_preg['id'];
		include('PatHistAllAnte.inc.php'); }
		
		include('PatHistAllOrders.inc.php');
		include('PatHistAllMeds.inc.php');
		
	if($row_visitlist['pat_type'] == 'InPatient'){  // show notes, orders, results
		include('PatHistAllVitalFluid.inc.php'); }
		
 	include('PatHistAllLab.inc.php');
?>


<!--^^^^^^^^^^^^^^^^^end of visit^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^-->
<?php } while ($row_visitlist = mysql_fetch_assoc($visitlist)); ?>
      </table>
<!--^^^^^^^^^^^^^^^^^end of visit^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^-->



<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->


</body>
</html>
