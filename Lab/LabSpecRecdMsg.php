<?php  $pt = "Spec Coll Msg"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 
	$colname_ordlist = "-1";
if (isset($_GET['ordlist'])) {
  $colname_ordlist = (get_magic_quotes_gpc()) ? $_GET['ordlist'] : addslashes($_GET['ordlist']);
  
mysql_select_db($database_swmisconn, $swmisconn);
$query_SpecRecd = "SELECT p.hospital, p.lastname, p.firstname, o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y') entrydt, f.dept, f.section, f.name, s.name spname FROM orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum join specimens s on f.specid = s.id WHERE o.id in ($colname_ordlist) ORDER BY o.entrydt ASC";
$SpecRecd = mysql_query($query_SpecRecd, $swmisconn) or die(mysql_error());
$row_SpecRecd = mysql_fetch_assoc($SpecRecd);
$totalRows_SpecRecd = mysql_num_rows($SpecRecd);
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- Display PATIENT PERMANENT Data  -->
<?php $patview = "../Patient/PatPermView.php"?>
<table align="center">
	<tr>
		<td valign="top">
			<?php require_once($patview); ?></td>
	</tr>
</table>

<table align="center">
  <tr>
    <td colspan="4" align="center" class="BlueBold_24">Specimens Collected</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="BlueBold_24"><?php echo $row_SpecRecd['lastname'] ?>,<?php echo $row_SpecRecd['firstname'] ?></td>
  </tr>
  <tr>
    <td colspan="4" align="center" nowrap="nowrap" class="BlueBold_30"><?php echo $row_SpecRecd['hospital'] ?> HOSPITAL</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="BlueBold_18"><?php echo date('d-M-Y H:i') ?></td>
  </tr>
<?php $total = 0;
 do { 
	$total = $total + 1;?>
    <tr>
      <td class="BlueBold_14"><?php echo $row_SpecRecd['ordid']; ?></td>
      <td class="BlueBold_14"><?php echo $row_SpecRecd['dept']; ?></td>
      <td class="BlueBold_14"><?php echo $row_SpecRecd['section']; ?></td>
	  <td class="BlueBold_14"><?php echo $row_SpecRecd['name']; ?></td>
      <td class="BlueBold_14"><?php echo $row_SpecRecd['spname']; ?></td>
    </tr>
<?php } while ($row_SpecRecd = mysql_fetch_assoc($SpecRecd)); ?>
  <tr>
    <td colspan="4" align="center" class="BlueBold_24">Received: <?php echo $total ?></td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="BlueBold_30">By: <?php echo $_SESSION['user']; ?></td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($SpecRecd);
?>
