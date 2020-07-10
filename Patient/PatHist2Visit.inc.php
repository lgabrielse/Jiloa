<?php //require_once('../../Connections/swmisconn.php'); ?><?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php // Visit data
mysql_select_db($database_swmisconn, $swmisconn);
$query_visits = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, weight, height, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE id = %s", $visitid);
$visits = mysql_query($query_visits, $swmisconn) or die(mysql_error());
$row_visits = mysql_fetch_assoc($visits);
$totalRows_visits = mysql_num_rows($visits);

?>
<table width="1000" bgcolor="#FFEEDD">
  <tr>
    <td nowrap="nowrap" Title="Entry Date: <?php echo $row_visits['entrydt']; ?>&#10; Entry By: <?php echo $row_visits['entryby']; ?>"><span class="BlueBold_16">Visit</span> #: <span class="BlueBold_16"><?php echo $row_visits['id']; ?></span></td>
    <td nowrap="nowrap" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</td>
    <td nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['visitdate']; ?></span></td>
    <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type-Location:<span class="BlueBold_16"><?php echo $row_visits['location']; ?>-<?php echo $row_visits['pat_type']; ?></span></td>
    <td colspan="2"nowrap="nowrap"><div align="right">Urgency:</div></td>
    <td colspan="2" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['urgency']; ?></span></td>
    <td><div align="right">Discharged:</div></td>
    <td colspan="7" nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_visits['discharge']; ?></span></td>
  </tr>
  <tr>
    <td width="110px" class="BlueBold_16"></td>
    <td width="110px">&nbsp; </td>
    <td><div align="right">Visit Reason:</div></td>
    <td colspan="2" bgcolor="#FFFDDA"><span class="BlueBold_14"><?php echo $row_visits['visitreason']; ?></span></td>
    <td>Height:</td>
    <td bgcolor="#FFFDDA"><?php echo $row_visits['height']; ?></td>
    <td>Weight:</td>
    <td bgcolor="#FFFDDA"><?php echo $row_visits['weight']; ?></td>
    <td><div align="right">Diagnosis:</div></td>
    <td colspan="7" bgcolor="#FFFDDA"><span class="BlueBold_14"><?php echo $row_visits['diagnosis']; ?></span></td>
  </tr>
</table>

