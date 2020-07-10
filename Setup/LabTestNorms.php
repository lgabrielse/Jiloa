<?php ob_start(); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php  $pt = "View LabTestNorms"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_norms = "SELECT n.id, DATE_FORMAT(begindate,'%Y-%m-%d') begindate, DATE_FORMAT(enddate,'%Y-%m-%d') enddate, testid, gender, agemin, agemax, normlow, normhigh, paniclow, panichigh, interpretation, n.entryby, DATE_FORMAT(n.entrydt,'%Y-%m-%d') entrydt, t.test, t.description FROM testnormalvalues n join tests t on n.testid = t.id  ORDER BY t.test ASC";
$norms = mysql_query($query_norms, $swmisconn) or die(mysql_error());
$row_norms = mysql_fetch_assoc($norms);
$totalRows_norms = mysql_num_rows($norms);
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
        <td colspan="3"><div align="center"><a href="LabTestNorms.php?act=LabTestNormAdd.php">Add</a></div></td>
        <td colspan="3" class="subtitlebl"><div align="center">LabTest Normal Values</div></td>
        <td colspan="2" nowrap="nowrap" class="subtitlebl"><a href="SetUpMenu.php" class="navLink">Setup Menu  </a></td>
        <td colspan="2"><div align="center">&nbsp;</div></td>
        </tr>
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl"><div align="center">nid<br/>tid</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">BeginDate<br/>EndDate</div></td>
        <td nowrap="nowrap" bgcolor="#eeeeee" class="subtitlebl"><div align="center">Laboratory Test<br/>Gender</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">Agemin<br/>Agemax</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">NormLow<br/>NormHigh</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">PanicLow<br/>PanicHigh</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">Interpretation</div></td>
        <td bgcolor="#eeeeee" class="subtitlebl"><div align="center">EntryBy<br/>EntryDt</div></td>
      </tr>
      <?php do { ?>  
      <tr>
        <td bgcolor="#FFFFFF"><div align="center"><a href="LabTestNorms.php?act=LabTestNormEdit.php&id=<?php echo $row_norms['id']; ?>">Edit</a></div></td>
        <td bgcolor="#FFFFFF"><div align="center"><a href="LabTestNorms.php?act=LabTestNormDelete.php&id=<?php echo $row_norms['id']; ?>">Delete</a></div></td>
        <td bgcolor="#eeeeee" class="BlackBold_11"><div align="center"><?php echo $row_norms['id']; ?><br/><?php echo $row_norms['testid']; ?></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_norms['begindate']; ?><br /><?php echo $row_norms['enddate']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11" Title="Descr: <?php echo $row_norms['description']; ?>"><div align="center"><?php echo $row_norms['test']; ?><br/><?php echo $row_norms['gender']; ?></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><div align="center"><?php echo $row_norms['agemin']; ?><br/><?php echo $row_norms['agemax']; ?></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><div align="center"><?php echo $row_norms['normlow']; ?><br/><?php echo $row_norms['normhigh']; ?></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><div align="center"><<?php echo $row_norms['paniclow']; ?><br/>><?php echo $row_norms['panichigh']; ?></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_norms['interpretation']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_norms['entryby']; ?><br/><?php echo $row_norms['entrydt']; ?></td>
      </tr>
      <?php } while ($row_norms = mysql_fetch_assoc($norms)); ?>
    </table>
  </td>
  <td valign="top">
<?php
	$normact = "LabTestNoNorm.php";
 if (isset($_GET['act'])) {
	$normact = $_GET['act'];
  }?>	
		<table width="100%">
		  <tr>
			<td valign="top"><?php require_once($normact); ?></td>
		  </tr>
		</table>

  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($norms);
ob_end_flush();
?>
