<?php ob_start(); ?>
<?php  $pt = "Add notices"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_notices = "SELECT id, notice, tooltip, bkgcolor, entrydt, entryby FROM notices ORDER BY notice";
$notices = mysql_query($query_notices, $swmisconn) or die(mysql_error());
$row_notices = mysql_fetch_assoc($notices);
$totalRows_notices = mysql_num_rows($notices);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Setup DropDownList</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<p>&nbsp;</p>
<table width="800px" align="center">
  <tr>
    <td valign="top">
	 <table width="400px">
      <tr>
        <td colspan="2"><div align="center" class="navLink"><a href="NoticesList.php?act=NoticesAdd.php">Add</a></div></td>
        <td colspan="3" class="subtitlebl"><div align="center">Patient Notices Setup </div></td>
        <td class="subtitlebl"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
      </tr>
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl"><div align="center">Notice</div></td>
        <td class="subtitlebl"><div align="center">Tooltip</div></td>
        <td class="subtitlebl"><div align="center">Background</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF"><div align="center"><a href="NoticesList.php?act=NoticesEdit.php&id=<?php echo $row_notices['id'] ?>">E</a></div></td>
        <td bgcolor="#FFFFFF"><div align="center"><a href="NoticesList.php?act=NoticesDelete.php&id=<?php echo $row_notices['id'] ?>">D</a></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_12" title="Entered By: <?php echo $row_notices['entryby']; ?>&#10;Entered: <?php echo $row_notices['entrydt']; ?>"><?php echo $row_notices['notice']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_12"><?php echo $row_notices['tooltip']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_12"><?php echo $row_notices['bkgcolor']; ?></td>
     </tr>
      <?php } while ($row_notices = mysql_fetch_assoc($notices)); ?>
    </table>
  </td>
  <td valign="top" ><?php if (isset($_GET['act'])) {
	$feeact = $_GET['act'];
 ?>
    <table width="400px">
		  <tr>
			<td valign="top"><?php require_once($feeact); ?></td>
		  </tr>
	  </table>
<?php }
	else {
?>		
    <table width="400px">
		  <tr>
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		  </tr>
	  </table>

<?php	} ?>
	</td>
  </tr>
</table>

<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($notices);
ob_end_flush();
?>
