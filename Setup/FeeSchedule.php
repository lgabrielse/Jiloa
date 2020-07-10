<?php ob_start(); ?>
<?php  $pt = "Add Fee"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$colname_Sort = "dept";
if (isset($_GET['Sort'])) {
  $colname_Sort = (get_magic_quotes_gpc()) ? $_GET['Sort'] : addslashes($_GET['Sort']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_fees = "SELECT f.id, f.dept, f.`section`, f.name, f.descr, f.unit, f.specid, f.fee, f.Active, s.name spname FROM fee f join specimens s on f.specid = s.id ORDER BY ".$colname_Sort;  //f.dept, f.section, f.name ASC";
$fees = mysql_query($query_fees, $swmisconn) or die(mysql_error());
$row_fees = mysql_fetch_assoc($fees);
$totalRows_fees = mysql_num_rows($fees);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Fee Schedule</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<p>&nbsp;</p>
<table width="60%" align="center">
  <tr>
    <td valign="top">
	 <table width="100%">
      <tr>
        <td colspan="2"><div align="center"><a href="FeeSchedule.php?act=FeeSchedAdd.php">Add</a></div></td>
        <td colspan="3" class="subtitlebl"><div align="center">Fee Schedule </div></td>
        <td class="subtitlebl"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
      </tr>
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=id">id</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=dept">dept</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=section">section</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=name">name</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=descr">descr</a></div></td>
        <td class="subtitlebl"><div align="center">unit</div></td>
        <td class="subtitlebl"><div align="center">specid</div></td>
        <td class="subtitlebl"><a href="FeeSchedule.php?Sort=spname">Specimen</a></td>
        <td class="subtitlebl"><div align="center"><a href="FeeSchedule.php?Sort=fee">fee</a></div></td>
        <td class="subtitlebl"><a href="FeeSchedule.php?Sort=Active">Active</a></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF"><div align="center"><a href="FeeSchedule.php?act=FeeSchedEdit.php&id=<?php echo $row_fees['id']; ?>">Edit</a></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['id']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['dept']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['section']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['name']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['descr']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['unit']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['specid']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['spname']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['fee']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_fees['Active']; ?></td>
      </tr>
      <?php } while ($row_fees = mysql_fetch_assoc($fees)); ?>
    </table>
  </td>
  <td valign="top">
<?php if (isset($_GET['act'])) {
	$feeact = $_GET['act'];
 ?>	
		<table width="100%">
		  <tr>
			<td valign="top"><?php require_once($feeact); ?></td>
		  </tr>
		</table>
<?php } ?>
	</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($fees);
ob_end_flush();
?>
