<?php  $pt = "Auto Discharged patients"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php

$colname_sort = "id";
if (isset($_GET['sort'])) {
  $colname_sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

  $colname_daysback = "7";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_autoDischList = "SELECT id, visitid, visitdate, status, pat_type, location, urgency, SUBSTR(visitreason,1,40) visitreason40, visitreason, SUBSTR(diagnosis,1,40) diagnosis40, diagnosis, discharged, labcnt, labdone, qry FROM autodischarged WHERE discharged >= SYSDATE() - INTERVAL " .$colname_daysback." DAY Order By ".$colname_sort;
$autoDischList = mysql_query($query_autoDischList, $swmisconn) or die(mysql_error());
$row_autoDischList = mysql_fetch_assoc($autoDischList);
$totalRows_autoDischList = mysql_num_rows($autoDischList);

mysql_select_db($database_swmisconn, $swmisconn);
$query_dischDate = "SELECT distinct discharged FROM autodischarged order by discharged desc";
$dischDate = mysql_query($query_dischDate, $swmisconn) or die(mysql_error());
$row_dischDate = mysql_fetch_assoc($dischDate);
$totalRows_dischDate = mysql_num_rows($dischDate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>AutoDischarged</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<br />
<table width="1111" border="1" align="center">
  <tr>
    <td colspan="3"align="center" class="BlueBold_24">AUTO DISCHARGED VISITS</td>
    <td width="755" rowspan="2" nowrap="nowrap">Qry 1: Discharge undischarged patents that have diagnosis and lab tests are done<br />
Qry 2: Discharge undischarged patients that have diagnosis and VisitDate is >; 2 days from current date<br />
Qry 3: Discharge patients where discharge is null and diagnosis is null and current date is > 7 days after visitdate.<br />
If diagnosis is blank, enter 'No Diagnosis  Documented'<br /></td>
  </tr>
  <tr>
    <td width="81"><div align="center"><a href="ReportsMenu.php" class="navLink">Report Menu </a></div></td>
    <td width="153">
	  <table width="50%" align="center">
	    <form id="formdaysback" name="formdaysback" method="post" action="Autodischarged.php">
        <tr>
          <td nowrap="nowrap"></td>
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
      </table></td>
    <td width="94" nowrap="nowrap"><?php echo Date('d-M-Y')?></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td class="BlueBold_16"><div align="center">id</div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=visitid desc">visitid</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=discharged desc">discharged</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=visitdate desc">visit<br />
    date</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=status desc">status</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=Pat_type">patient<br />
    type</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=location">location</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=urgency">urgency</a></div></td>
    <td class="BlueBold_16"><div align="center">visit * <br />
    reason</div></td>
    <td class="BlueBold_16"><div align="center">diagnosis *</div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=labcnt desc"># of lab<br />
    orders</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=labdone desc"># of lab<br />
    done</a></div></td>
    <td class="BlueBold_16"><div align="center"><a href="AutoDischarged.php?sort=qry">qry</a></div></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><div align="center"><?php echo $row_autoDischList['id']; ?></div></td>
    <td><div align="center"><?php echo $row_autoDischList['visitid']; ?></div></td>
    <td><?php echo $row_autoDischList['discharged']; ?></td>
    <td><?php echo $row_autoDischList['visitdate']; ?></td>
    <td><?php echo $row_autoDischList['status']; ?></td>
    <td><?php echo $row_autoDischList['pat_type']; ?></td>
    <td><div align="center"><?php echo $row_autoDischList['location']; ?></div></td>
    <td><div align="center"><?php echo $row_autoDischList['urgency']; ?></div></td>
    <td title="<?php echo $row_autoDischList['visitreason']; ?>"><?php echo $row_autoDischList['visitreason40']; ?></td>
    <td title="{autoDischList.diagnosis}"><?php echo $row_autoDischList['diagnosis40']; ?></td>
    <td><div align="center"><?php echo $row_autoDischList['labcnt']; ?></div></td>
    <td><div align="center"><?php echo $row_autoDischList['labdone']; ?></div></td>
    <td><div align="center"><?php echo $row_autoDischList['qry']; ?></div></td>
  </tr>
  <?php } while ($row_autoDischList = mysql_fetch_assoc($autoDischList)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($autoDischList);

mysql_free_result($dischDate);
?>
