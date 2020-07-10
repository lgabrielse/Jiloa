<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 

<?php $colname_status = "Incomplete";
if (isset($_POST['status'])  && strlen($_POST['status'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_status = (get_magic_quotes_gpc()) ? $_POST['status'] : addslashes($_POST['status']);
}
?>
<?php $colname_surgeon = "%";
if (isset($_POST['surgeon'])  && strlen($_POST['surgeon'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_surgeon = (get_magic_quotes_gpc()) ? $_POST['surgeon'] : addslashes($_POST['surgeon']);
}

?>
<?php 
if($colname_status == 'Incomplete'){
mysql_select_db($database_swmisconn, $swmisconn);
$query_surgsched = "SELECT s.id sid, s.medrecnum, s.visitid, s.feeid, s.surgdate, s.surgeon, s.surgeonassist, s.anesthetist, s.anesttechnique, s.preopdiag, s.status, s.findings,  s.ordid, s.entrydt, s.entryby, p.lastname, p.firstname, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, v.discharge, f.name, f.descr, b.bed,  (Select userid from users where id = s.surgeon) surgeon, (Select userid from users where id = s.surgeonassist) surgassist, (Select userid from users where id = s.anesthetist) anesthetist, (Select name from fee where id = b.feeid) location FROM surgery s join patperm p on s.medrecnum = p.medrecnum join patvisit v on s.visitid = v.id join fee f on s.feeid = f.id LEFT OUTER JOIN patbed b on p.medrecnum = b.medrecnum WHERE s.status != 'Complete' and ((surgeon like '%".$colname_surgeon."' OR surgeon IS NULL) OR (surgeonassist like '%".$colname_surgeon."' OR surgeonassist IS NULL) OR (anesthetist like '%".$colname_surgeon."' OR anesthetist IS NULL)) ORDER BY s.surgdate DESC, entrydt DESC";
$surgsched = mysql_query($query_surgsched, $swmisconn) or die(mysql_error());
$row_surgsched = mysql_fetch_assoc($surgsched);
$totalRows_surgsched = mysql_num_rows($surgsched);

} else {

mysql_select_db($database_swmisconn, $swmisconn);
$query_surgsched = "SELECT s.id sid, s.medrecnum, s.visitid, s.feeid, s.surgdate, s.surgeon, s.surgeonassist, s.anesthetist, s.anesttechnique, s.preopdiag, s.status, s.findings, s.ordid, s.entrydt, s.entryby, p.lastname, p.firstname, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, v.discharge, f.name, f.descr, b.bed,  (Select userid from users where id = s.surgeon) surgeon, (Select userid from users where id = s.surgeonassist) surgassist, (Select userid from users where id = s.anesthetist) anesthetist, (Select name from fee where id = b.feeid) location FROM surgery s join patperm p on s.medrecnum = p.medrecnum join patvisit v on s.visitid = v.id join fee f on s.feeid = f.id LEFT OUTER JOIN patbed b on p.medrecnum = b.medrecnum WHERE s.status like '%".$colname_status."' and ((surgeon like '%".$colname_surgeon."' OR surgeon IS NULL) OR (surgeonassist like '%".$colname_surgeon."' OR surgeonassist IS NULL) OR (anesthetist like '%".$colname_surgeon."' OR anesthetist IS NULL)) ORDER BY s.surgdate DESC, entrydt DESC";
$surgsched = mysql_query($query_surgsched, $swmisconn) or die(mysql_error());
$row_surgsched = mysql_fetch_assoc($surgsched);
$totalRows_surgsched = mysql_num_rows($surgsched);
}
// query for surgeon and assisted surgeon ddl
mysql_select_db($database_swmisconn, $swmisconn);
$query_surgeon = "SELECT id uid, userid FROM users WHERE docflag = 'Y' or anflag = 'Y' and active = 'Y' Order BY userid";
$surgeon = mysql_query($query_surgeon, $swmisconn) or die(mysql_error());
$row_surgeon = mysql_fetch_assoc($surgeon);
$totalRows_surgeon = mysql_num_rows($surgeon);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Surgery Schedule</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
</head>

<body>

<table border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:collapse" >
  <tr>
        <td colspan="7"><div align="center" class="subtitlebl"> Surgery Schedule </div></td>
      </tr>
      <tr>
	    <form id="form3" name="form3" method="post" action="PatSurgSchedule.php">
          <td bgcolor="#FBD0D7" nowrap="nowrap"><div align="right">Discharged w/ Findings:</div></td>
          <td bgcolor="#FBD0D7">&nbsp;</td>

          <td nowrap="nowrap"><div align="right">Surgery Status:</div></td>
					<td>
            <select name="status" id="status" onChange="document.form3.submit();">
              <option value="Incomplete" <?php if (!(strcmp("Incomplete", $colname_status))) {echo "selected=\"selected\"";} ?>>Incomplete</option>
              <option value="%" <?php if (!(strcmp("%", $colname_status))) {echo "selected=\"selected\"";} ?>>All</option>
              <option value="Ordered" <?php if (!(strcmp("Ordered", $colname_status))) {echo "selected=\"selected\"";} ?>>Ordered</option>
              <option value="Scheduled" <?php if (!(strcmp("Scheduled", $colname_status))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
              <option value="In-Progress" <?php if (!(strcmp("In-Progress", $colname_status))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
              <option value="Recovery" <?php if (!(strcmp("Recovery", $colname_status))) {echo "selected=\"selected\"";} ?>>Recovery</option>
              <option value="Complete" <?php if (!(strcmp("Complete", $colname_status))) {echo "selected=\"selected\"";} ?>>Complete</option>
              <option value="Cancelled" <?php if (!(strcmp("Cancelled", $colname_status))) {echo "selected=\"selected\"";} ?>>Cancelled</option>
           </select>
          </td>
          <td><div align="right">Surgeon Or SurgeonAssist Or Anesthetist Or Unassigned:</div></td>
          <td><select name="surgeon" id="surgeon" onChange="document.form3.submit();">
                 <option value="%">ALL</option>
              <?php do {  ?>
      					<option value="<?php echo $row_surgeon['uid']?>"<?php if (!(strcmp($row_surgeon['uid'], $colname_surgeon))) {echo "selected=\"selected\"";} ?>><?php echo $row_surgeon['uid']?> - <?php echo $row_surgeon['userid']?></option>
      				<?php }
							 while ($row_surgeon = mysql_fetch_assoc($surgeon));
								$rows = mysql_num_rows($surgeon);
								if($rows > 0) {
										mysql_data_seek($surgeon, 0);
									$row_surgeon = mysql_fetch_assoc($surgeon);
								} ?>
				    </select>
          <input type="hidden" name="MM_update" value="form3" />		 </td>
        </td>
        </form>
      </tr>
    </table>


<table align="center" cellpadding="1" cellspacing="1" border="1" style="border-collapse:collapse">  
  <tr>
  		<td><a href="../Patient/PatShow1.php?mrn=11846&vid=34774&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php">Select</a></td>
      <td>surgdate</td>
      <td>time</td>
      <td>status*</td>
      <td>name</td>
      <td>patient</td>
      <td>age-sex</td> 
      <td>ward-bed</td>     
      <td>surgeon</td>
      <td>surgeonassist</td>
      <td>anesthetist</td>
      <td>anesttechnique</td>
      <td>preopdiag</td>
      <td>dischargedg</td>
      <td>findings(20)</td>
    </tr>
    <?php do { 
		if(empty($row_surgsched['surgdate'])) { 
		$sgdate = '';
		$sgtime = '';
		} else {
		$sgdate = date('D-d-M-y',$row_surgsched['surgdate']);
		$sgtime = date('H:i',$row_surgsched['surgdate']);
		}
		?>
    <?php if(isset($row_surgsched['discharge']) && isset($row_surgsched['findings'])){
			$bkgd = '#FBD0D7';
			} else {
			$bkgd = "#FFFDDA";		
			}
			$string = "";
			$date = 0; 
	  ?>
      <tr>
        <td bgcolor=<?php echo $bkgd ?> title="Entry Date = <?php echo $row_surgsched['entrydt']; ?>"><a href="../Patient/PatShow1.php?mrn=<?php echo $row_surgsched['medrecnum']; ?>&vid=<?php echo $row_surgsched['visitid']; ?>& sids=<?php echo $row_surgsched['sid']; ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php">Select</a></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $sgdate ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $sgtime ?></td>
        <td bgcolor=<?php echo $bkgd ?> title="Surgid: <?php echo $row_surgsched['sid']; ?>&#10;MedRecNum; <?php echo $row_surgsched['medrecnum']; ?>&#10;VisitID: <?php echo $row_surgsched['visitid']; ?>&#10;FeeID: <?php echo $row_surgsched['feeid']; ?>&#10;EntryDt: <?php echo $row_surgsched['entrydt']; ?>&#10;EntryBy: <?php echo $row_surgsched['entryby']; ?>&#10;Order ID: <?php echo $row_surgsched['ordid']; ?>"><?php echo $row_surgsched['status']; ?></td>
        <td bgcolor=<?php echo $bkgd ?> title="Description: <?php echo $row_surgsched['descr']; ?>"><?php echo $row_surgsched['name']; ?></td>
				<td bgcolor=<?php echo $bkgd ?> title="Surgid: <?php echo $row_surgsched['sid']; ?>&#10;MedRecNum; <?php echo $row_surgsched['medrecnum']; ?>&#10;VisitID: <?php echo $row_surgsched['visitid']; ?>&#10;FeeID: <?php echo $row_surgsched['feeid']; ?>&#10;EntryDt: <?php echo $row_surgsched['entrydt']; ?>&#10;EntryBy: <?php echo $row_surgsched['entryby']; ?>&#10;Order ID: <?php echo $row_surgsched['ordid']; ?>"><a href="../Patient/PatShow1.php?mrn=<?php echo $row_surgsched['medrecnum']; ?>&vid=<?php echo $row_surgsched['visitid']; ?>& sids=<?php echo $row_surgsched['sid']; ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php"><?php echo $row_surgsched['lastname'].', '.$row_surgsched['firstname']; ?></a>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['age']. ' - '. $row_surgsched['gender']; ?></td>       
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['location'].'-'.$row_surgsched['bed']; ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['surgeon']; ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['surgassist']; ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['anesthetist']; ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['anesttechnique']; ?></td>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $row_surgsched['preopdiag']; ?></td>
 			<?php if(isset($row_surgsched['discharge'])) { $date = date_create($row_surgsched['discharge']);?>
        <td bgcolor=<?php echo $bkgd ?>><?php echo date_format($date, 'Y-m-d');; ?></td>  <!--date_format($date, 'Y-m-d H:i:s')-->
      <?php } else { ?>  
        <td bgcolor=<?php echo $bkgd ?>>&nbsp;</td>
			<?php  }?>
      <?php $string = (strlen($row_surgsched['findings']) > 20) ? substr($row_surgsched['findings'],0,20).'...' : $row_surgsched['findings'];?>
        <td bgcolor=<?php echo $bkgd ?>><?php echo $string; ?></td>
      </tr>
      <?php } while ($row_surgsched = mysql_fetch_assoc($surgsched)); ?>
  </table>

</body>
</html>