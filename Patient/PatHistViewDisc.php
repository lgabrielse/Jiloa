<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$colname_patperm = "-1";
if (isset($_GET['mrn'])) {
  $colname_patperm = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $colname_patperm."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

mysql_select_db($database_swmisconn, $swmisconn);
$query_visits = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, height, weight, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE medrecnum = %s", $colname_patperm);
$visits = mysql_query($query_visits, $swmisconn) or die(mysql_error());
$row_visits = mysql_fetch_assoc($visits);
$totalRows_visits = mysql_num_rows($visits);

?>
<?php //echo $row_visits['pat_type'] ?>
<?php if($row_visits['pat_type']== "Antenatal"){
    $RedirectGoTo = "PatHistAll.php?mrn=".$colname_patperm; 
	header(sprintf("Location: %s", $RedirectGoTo));
    exit;
	}

} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
textarea.autosize {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	width: 600px;
	height: auto;
	overflow: visible;
}
</style>

</head>

<body>

<!-- Begin PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT -   -->
  <a name="top"></a>
  <table width="70%" align="center">
	  <tr>
	  	<td><div align="center"><a href="PatHistPrint.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Print</a></div></td>
	  	<td colspan="6" class ="BlueBold_18"><div align="center">Patient History</div></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?></td>
		<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_16"><?php echo $row_patperm['lastname']; ?></span>, <span class="BlueBold_16"><?php echo $row_patperm['firstname']; ?></span> (<span class="BlueBold_16"><?php echo $row_patperm['othername']; ?></span>) </td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
		<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
		<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_patperm['dob']; ?></span>:<?php echo $row_patperm['est']; ?></td>
	  </tr>
	
</table>
<table width="70%" align="center">
<tr>
<td>
<!--Begin VISIT - VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT - -->  
  <?php do { ?>
<form>
<table width="100%"bgcolor="#FFEEDD">
    <tr>
<!--      <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
-->      <td nowrap="nowrap" Title="Entry Date: <?php echo $row_visits['entrydt']; ?>&#10; Entry By: <?php echo $row_visits['entryby']; ?>"><span class="BlueBold_16">Visit</span> #: <span class="BlueBold_16"><?php echo $row_visits['id']; ?></span></td>
      <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</td>
      <td nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['visitdate']; ?></span></td>
      <!--      <td valign="top">&nbsp;</td>
      <td width="200">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td width="200">&nbsp;</td>
-->      <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type-Location:<span class="BlueBold_16"><?php echo $row_visits['location']; ?>-<?php echo $row_visits['pat_type']; ?></span></td>
      <td colspan="2"nowrap="nowrap"><div align="right">Urgency:</div></td>
      <td colspan="2" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['urgency']; ?></span></td>
      <td><div align="right">Discharged:</div></td>
      <td colspan="7" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['discharge']; ?></span></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;Reason:</td>
        <td colspan="2" bgcolor="#FFFDDA"><span class="BlueBold_14"><?php echo $row_visits['visitreason']; ?></span></td>
        <td>Height:</td>
        <td bgcolor="#FFFDDA" width ="20"><?php echo $row_visits['height']; ?></td>
        <td>Weight:</td>
        <td bgcolor="#FFFDDA"width ="20"><?php echo $row_visits['weight']; ?></td>
        <td><div align="right">Diagnosis:</div></td>
        <td colspan="7" bgcolor="#FFFDDA"><span class="BlueBold_14"><?php echo $row_visits['diagnosis']; ?></span></td>
      </tr>
    

<!--Begin NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - -->
<?php 
	mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, notes, temp, pulse, bp_sys, bp_dia, entryby, entrydt FROM patnotes WHERE visitid =  '".$row_visits['id']."'";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);
?>
  <?php do { ?>
      <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td nowrap="nowrap" title="ENTRY BY: <?php echo $row_notes['entryby']; ?>&#10;ENTRY DATE: <?php echo $row_notes['entrydt']; ?>&#10;Notes  ID: "<?php echo $row_notes['visitid']; ?>>Notes</td>
      <td width="620" colspan="6" bgcolor="#FFFDDA" nowrap="nowrap" title="ENTRY BY: <?php echo $row_notes['entryby']; ?>&#10;ENTRY DATE: <?php echo $row_notes['entrydt']; ?>&#10;Notes  ID: "<?php echo $row_notes['visitid']; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <textarea class="autosize" name="notes" id="notes"><?php echo nl2br($row_notes['notes']); ?></textarea></td>
        <td><div align="right">Temp:</div></td>
        <td bgcolor="#FFFDDA" width ="20"><?php echo $row_notes['temp']; ?></td>
        <td>Pulse:</td>
        <td bgcolor="#FFFDDA" width ="20"><?php echo $row_notes['pulse']; ?></td>
        <td>Sys:</td>
        <td bgcolor="#FFFDDA" width ="20"><?php echo $row_notes['bp_sys']; ?></td>
        <td>Dia:</td>
        <td bgcolor="#FFFDDA" width ="20"><?php echo $row_notes['bp_dia']; ?></td>
	  </tr>
    <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
</table>
</form>	
<!--Begin ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - -->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.status, o.doctor, o.entryby, o.comments, o.revby, o.revdt, f.name, s.name spec, sc.collby, DATE_FORMAT(sc.colldt,'%d-%b-%Y %H:%i') colldt, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age from orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum join specimens s on f.specid= s.id left outer join speccollected sc on sc.ordnum = o.id where f.dept = 'Laboratory' and visitid = '".$row_visits['id']."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
<?php if($totalRows_orders > 0) { ?>
<table width="100%" bgcolor="EBF4FA">
  <tr>
    <td colspan="2" ><table bgcolor="CAE5FF" width="1000px">
      <tr>
        <td width ="30px" nowrap="nowrap"><span class="BlueBold_16">LAB</span>#</td>
        <td width ="80px">Ordered*</td>
        <td width ="110px">Specimen*</td>
        <td width ="80px">Collected*</td>
        <td width ="150px">Order*</td>
        <td width ="125px">Test*</td>
        <td width ="125px">Result*</td>
        <td width ="30px">Flag</td>
        <td width ="70px">Normal</td>
        <td width ="50px">Units</td>
        <td width ="150px">Interpretation</td>
      </tr>
    </table></td>
    <!--1-->
  </tr>
  <tr>
    <td><!--2-->
	  <table>
	     <tr>

			<?php do { ?>
			<td valign="top"><!--2a-->
				<table width="450">
				  <tr>
					<td width="30px" ></td>
					<td width="80px" ></td>
					<td width="110px" ></td>
					<td width="80px" ></td>
					<td width="150px" ></td>
				  </tr>
				  <tr>
					<td class="BlackBold_11"><?php echo $row_orders['ordid']; ?></td>
					<td class="BlackBold_11" title="Doctor: <?php echo $row_orders['doctor']; ?>&#10;Entry By: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
					<td class="BlackBold_11" title="Collected: <?php echo $row_orders['colldt']; ?>&#10; Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['spec']; ?></td>
					<td class="BlackBold_11" title="Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['colldt']; ?></td>
					<td class="BlackBold_14" title="Reviewed By: <?php echo $row_orders['revby']; ?>&#10;Reviewed Date: <?php echo $row_orders['revdt']; ?>"><?php echo $row_orders['name']; ?><br/>
						<span class="BlackBold_11"><?php echo $row_orders['status']; ?></span></td>
				  </tr>
				  <tr>
					<td colspan="5" class="BlackBold_14">Comments: <?php echo $row_orders['comments']; ?></td>
				  </tr>
			  </table>
			</td><!--2a-->
			<td><!--2b-->
				<table width="550">
				  <tr>
					<td width="125px" ></td>
					<td width="125px" ></td>
					<td width="30px" ></td>
					<td width="50px" ></td>
					<td width="70px" ></td>
					<td width="150px" ></td>
				  </tr>
				  <?php
					mysql_select_db($database_swmisconn, $swmisconn);
					$query_tests = "SELECT t.id, t.test, t.description, t.units, r.id rid, r.result, r.normflag, r.entryby, r.entrydt, n.normlow, n.normhigh ,n.paniclow, n.panichigh, n.interpretation FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3) join results r on (o.id = r.ordid and o.feeid = r.feeid and t.id = r.testid) join testnormalvalues n on (n.testid = t.id and instr(gender,'".$row_orders['gender']."') > 0 AND '".$row_orders['age']."' > agemin AND '".$row_orders['age']."' < agemax) WHERE t.active = 'Y' and o.id = '".$row_orders['ordid']."' ORDER BY reportseq";
					$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
					$row_tests = mysql_fetch_assoc($tests);
					$totalRows_tests = mysql_num_rows($tests);
			   
				do { ?>
				  <tr>
					<td nowrap="nowrap" class="BlackBold_11" title="Test ID: <?php echo $row_tests['id']; ?>&#10;Description: <?php echo $row_tests['description']; ?>"><?php echo $row_tests['test']; ?></td>
					<td nowrap="nowrap" class="BlackBold_14" title="Result ID: <?php echo $row_tests['rid']; ?> &#10;Entered By: <?php echo $row_tests['entryby']; ?>&#10;Entered Date: <?php echo $row_tests['entrydt']; ?>"><?php echo $row_tests['result']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normflag']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normlow']; ?> - <?php echo $row_tests['normhigh']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11"><?php echo $row_tests['units']; ?></td>
					<td class="BlackBold_11"><?php echo $row_tests['interpretation']; ?></td>
				  </tr>
				  <?php } while ($row_tests = mysql_fetch_assoc($tests)); ?>
			  </table>
			</td><!--2b-->
	  	  </tr>
  <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
  	  </table>
	</td><!--2-->
  </tr>

</table>
<?php } ?>
<table align="center">
	<tr>
		<td><a href="#Top" class="BlueBold_10">Jump to Top</a></td>
		<td width = "900px" height="5px" bgcolor="#6699CC" class="legal"></td>
	</tr>
</table>
<?php } while ($row_visits = mysql_fetch_assoc($visits)); ?>
</td>
</tr>
</table>

<!-- End visit loop -->
</body>
</html>
<?php
mysql_free_result($patperm);

mysql_free_result($visits);

mysql_free_result($notes);

mysql_free_result($orders);
?>
