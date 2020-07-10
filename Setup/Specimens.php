<?php ob_start(); ?>
<?php  $pt = "View Specimens"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_specs = "SELECT id, name, method, container, minvolume, preservative, viablelimit, storage, instructions FROM specimens ORDER BY name ASC";
$specs = mysql_query($query_specs, $swmisconn) or die(mysql_error());
$row_specs = mysql_fetch_assoc($specs);
$totalRows_specs = mysql_num_rows($specs);
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
        <td colspan="3"><div align="center"><a href="Specimens.php?act=SpecimenAdd.php">Add</a></div></td>
        <td colspan="3" class="subtitlebl"><div align="center">Specimens</div></td>
        <td class="subtitlebl"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
      </tr>
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl"><div align="center">id</div></td>
        <td class="subtitlebl"><div align="center">name</div></td>
        <td class="subtitlebl"><div align="center">method</div></td>
        <td class="subtitlebl"><div align="center">container</div></td>
        <td class="subtitlebl"><div align="center">minvolume</div></td>
        <td class="subtitlebl"><div align="center">preservative</div></td>
        <td class="subtitlebl"><div align="center">viablelimit</div></td>
        <td class="subtitlebl"><div align="center">storage</div></td>
        <td class="subtitlebl"><div align="center">instructions</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF"><div align="center"><a href="Specimens.php?act=SpecimenEdit.php&id=<?php echo $row_specs['id']; ?>">Edit</a></div></td>
        <td bgcolor="#FFFFFF"><div align="center"><a href="Specimens.php?act=SpecimenDelete.php&id=<?php echo $row_specs['id']; ?>">Delete</a></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['id']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['name']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['method']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['container']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['minvolume']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['preservative']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['viablelimit']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['storage']; ?></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_specs['instructions']; ?></td>
      </tr>
      <?php } while ($row_specs = mysql_fetch_assoc($specs)); ?>
    </table>
  </td>
  <td>
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
mysql_free_result($specs);
ob_end_flush();
?>
