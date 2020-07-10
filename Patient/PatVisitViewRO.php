<?php  $pt = "Patient Visit View RO"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php $_SESSION['today'] = date("Y-m-d");  ?>

<?php
$colid_visitview = "-1";
if (isset($_GET['vid'])) {
  $colid_visitview = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
else {
if (isset($_SESSION['vid'])) {
  $colid_visitview = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']);
}}
	
$colmrn_visitview = "-1";
if (isset($_GET['mrn'])) {
  $colmrn_visitview = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
else {
if (isset($_SESSION['mrn'])) {
  $colmrn_visitview = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
}}
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitview = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE id = %s AND medrecnum = %s", $colid_visitview,$colmrn_visitview);
$visitview = mysql_query($query_visitview, $swmisconn) or die(mysql_error());
$row_visitview = mysql_fetch_assoc($visitview);
$totalRows_visitview = mysql_num_rows($visitview);
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <table align="center">
	<tr>
    	<td><form id="form1" name="form1" method="post" action="">
      		<table width="100%" border="0" align="center">
        		<tr>
          			<td nowrap="nowrap"><div align="right">Visit Date:</div></td>
          			<td><input name="visitdate" type="text" readonly="readonly" id="visitdate" value="<?php echo $row_visitview['visitdate']; ?>" size="12" /></td>
          			<td><div align="right">Location:</div></td>
          			<td><input name="location" type="text" readonly="readonly" id="location" value="<?php echo $row_visitview['location']; ?>" size="20" /></td>
				    <td nowrap="nowrap"><div align="right">Patient Type:</div></td>
				    <td><input name="pat_type" type="text" readonly="readonly" id="pat_type" value="<?php echo $row_visitview['pat_type']; ?>" /></td>
				    <td><div align="right">Urgency:</div></td>
				    <td><input name="urgency" type="text" readonly="readonly" id="urgency" value="<?php echo $row_visitview['urgency']; ?>" size="12" /></td>
				    <td nowrap="nowrap"><div align="right">Discharge Date: </div></td>
				    <td><input name="discharge" type="text" readonly="readonly" id="discharge" value="<?php echo $row_visitview['discharge']; ?>" size="12" />
					    <input type="hidden" name="todaydate" value="Date('Y-m-d H:i:s')"/>	</td>
				</tr>
				<tr>
					<td nowrap="nowrap"><div align="right">Visit Reason </div></td>
					<td colspan="5"><textarea name="visitreason" cols="50" rows="1" readonly="readonly" id="visitreason"><?php echo $row_visitview['visitreason']; ?></textarea></td>
					<td><div align="right">Diagnosis:</div></td>
					<td colspan="3"><textarea name="diagnosis" cols="40" rows="1" readonly="readonly" id="diagnosis"><?php echo $row_visitview['diagnosis']; ?></textarea></td>
				</tr>
		  </table>
        </form>
		</td>
	</tr>
</table>

</body>
</html>
<?php
mysql_free_result($visitview);
?>
