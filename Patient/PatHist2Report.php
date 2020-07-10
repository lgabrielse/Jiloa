<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>

<?php // for testing 2 ways to display values in arrays
//echo 'MRN: '. $_SESSION['mrn'].'<br>'; 
//print_r($_POST['visit']).'<br>';
//var_dump($_POST['visit']).'<br>';
//print_r($_POST['page']).'<br>';
//var_dump($_POST['page']).'<br>';
?>
<?php
$col_mrn = "-1";
if (isset($_POST['mrn'])) { // or $_SESSION['mrn']
  $col_mrn = (get_magic_quotes_gpc()) ? $_POST['mrn'] : addslashes($_POST['mrn']);
}  // Patient Perm data
mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $col_mrn."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

// Visit data
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitlist = "SELECT id, medrecnum, visitdate visitpregdt, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, weight, height, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE medrecnum = '". $col_mrn."' order by id";
$visitlist = mysql_query($query_visitlist, $swmisconn) or die(mysql_error());
$row_visitlist = mysql_fetch_assoc($visitlist);
$totalRows_visitlist = mysql_num_rows($visitlist);

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PatHist2Report</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
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
	  	<td nowrap="nowrap" bgcolor="#32ff32" align="center">
      <input name="button" style="background-color:#CAE5FF" type="button" onClick="location.href='PatHist2Menu.php?mrn=<?php echo $col_mrn ?>'" value="Re-Select" /></a>
      <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></a>
      <A HREF="javascript:window.print()">Print</a></td>
		<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?> Medical Center</td>
	    <td colspan="2" nowrap="nowrap" class ="BlueBold_18">Patient History</td>
	    <td colspan="2" align="right" class ="BlueBold_12">Printed:<?php echo date("d-M-Y") ?></td>
      </tr>
	  <tr>
		<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
		<td bgcolor="#FFFFFF" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_20" ><?php echo $row_patperm['lastname']; ?></span>,<span class="BlueBold_18"><?php echo $row_patperm['firstname']; ?></span>(<span class="BlueBold_18"><?php echo $row_patperm['othername']; ?></span>)</td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
		<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_patperm['dob']; ?></span>:<?php if($row_patperm['est'] == 'Y'){echo 'est';}; ?></td>
	  </tr>
</table>
<table width = "1000px" >
	<tr>
		<td height="5px" bgcolor="#6699CC" class="legal"> <div align="center"><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </div></td>
	</tr>
</table>
<?php 
?>
<!-- Begin VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT - VISIT -   -->
<?php do { 
// check if visit is included
  $vid = $row_visitlist['id'];
  if(in_array($vid, $_POST['visit'])){
		
//		$val_v = 'visits';
//		if(in_array($val_v, $_POST['page'])){
	
		$visitid = $row_visitlist['id'];
		include('PatHist2Visit.inc.php');  //show visit info and notes
//		} 
	?>
	<?php 	
		$val_n = 'notes';
		if(in_array($val_n, $_POST['page'])){
	
		$visitid = $row_visitlist['id'];
		include('PatHist2Notes.inc.php');  //show visit info and notes
		} 
	
	?><!-- ANTE ANTE ANTE ANTE ANTE ANTE ANTE ANTE ANTE ANTE ANTE  --> 	
	<?php
		$val_a = 'ante';
		if(in_array($val_a, $_POST['page'])){
			
	//look for a record of pregnancy in this visit		
			$pregid = '0';
			mysql_select_db($database_swmisconn, $swmisconn);
			$query_preg = sprintf("SELECT id, visitid, medrecnum, firstvisit, entrydt, UNIX_TIMESTAMP('entrydt') FROM anpreg WHERE visitid = '".$row_visitlist['id']."' and medrecnum = '". $col_mrn."' ORDER BY id ASC");
			$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
			$row_preg = mysql_fetch_assoc($preg);
			$totalRows_preg = mysql_num_rows($preg);
		
			 echo $row_preg['visitid'] ."<br/>";
		
			if($totalRows_preg <> 0){  // show notes, orders, results
			$mrn = $row_visitlist['medrecnum'];
			$pregid = $row_preg['id'];
			include('PatHistAllAnte.inc.php'); }
		}
	?>
<!--FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP    FOLLOWUP -->   
  	
	<?php
		$val_w = 'followup';
		if(in_array($val_w, $_POST['page'])){ include('PatHist2AnteFollowup.inc.php'); }
	 ?>
  
  
	<!-- ORDERS ORDERS ORDERS ORDERS ORDERS ORDERS ORDERS ORDERS ORDERS ORDERS  --> 	
	<?php
		$val_o = 'orders';
		if(in_array($val_o, $_POST['page'])){ include('PatHistAllOrders.inc.php'); }
	 ?>
	
	<!-- LABS LABS LABS LABS LABS LABS LABS LABS LABS LABS  --> 	
	<?php
		$val_l = 'labs';
		if(in_array($val_l, $_POST['page'])){ include('PatHistAllLab.inc.php'); }
	 ?>
	
	<!-- DRUGS DRUGS DRUGS DRUGS DRUGS DRUGS DRUGS DRUGS DRUGS DRUGS  --> 	
	<?php
		$val_d = 'drugs';
		if(in_array($val_d, $_POST['page'])){ include('PatHistAllMeds.inc.php'); }
	 ?>
	
	<!-- FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS FLUIDS  --> 	
	<?php
		$val_f = 'fluids';
		if(in_array($val_f, $_POST['page'])){ include('PatHistAllVitalFluid.inc.php'); }
	 ?>
	
	<!-- SURG SURG SURG SURG SURG SURG SURG SURG SURG SURG  --> 	
	<?php
		$val_s = 'surg';
		if(in_array($val_s, $_POST['page'])){ include('PatHist2Surg.inc.php'); }
	 ?>
<table>
	<tr>
		<td height="5px" bgcolor="#6699CC" class="legal"> <div align="center"><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </div></td>
	</tr>
	<tr>
		<td height="5px" bgcolor="#6699CC" class="legal"> <><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><a href="#Top" class="titlebar">Jump to Top</a> <><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </td>
	</tr>
		  <td height="25px" align="center" class="flagBlackonOrange" style="font-size:16px;"> Next Visit <?php
echo str_repeat("&nbsp;",10); ?> Next Visit <?php
echo str_repeat("&nbsp;",10); ?> Next Visit <?php
echo str_repeat("&nbsp;",10); ?> Next Visit <?php
echo str_repeat("&nbsp;",10); ?> Next Visit <?php
echo str_repeat("&nbsp;",10); ?> Next Visit  </td>
	</tr>
</table>
 
<!--^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^-->
<?php  }?>
<?php } while ($row_visitlist = mysql_fetch_assoc($visitlist)); 

?>
      </table>
<!--^^^^^^^^^^^^^^^^^end of visit^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^-->


</body>
</html>

<?php 
//	if($totalRows_preg <> 0){  // show notes, orders, results
//		$mrn = $row_visitlist['medrecnum'];
//		$pregid = $row_preg['id'];
//		include('PatHistAllAnte.inc.php'); }
//		
//		include('PatHistAllOrders.inc.php');
//		include('PatHistAllMeds.inc.php');
//		
//	if($row_visitlist['pat_type'] == 'InPatient'){  // show notes, orders, results
//		include('PatHistAllVitalFluid.inc.php'); }
//		
// 	include('PatHistAllLab.inc.php');
?>