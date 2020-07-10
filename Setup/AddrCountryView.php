<?php ob_start(); ?>
<?php  $pt = "AddrCountryView"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_cntry = "SELECT id, country FROM country ORDER BY country ASC";
$cntry = mysql_query($query_cntry, $swmisconn) or die(mysql_error());
$row_cntry = mysql_fetch_assoc($cntry);
$totalRows_cntry = mysql_num_rows($cntry);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>AddrCountryView</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="400px" align="center">
  <tr>
  	<td align="center" class="BlueBold_18">COUNTRY</td>
    <td align="center" class="BlueBold_12"><a href="AddrStateView.php">State</a> - <a href="AddrLocGovtView.php">LocGovt</a> - <a href="AddrCityView.php">City</a></td>
  </tr>
  <tr>
    <td align="center" valign="top" class="BlueBold_18">&nbsp;</td>
    <td align="center"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
  </tr>
  <tr>
    <td valign="top">
	 <table width="200px">
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl"><div align="center"><a href="AddrCountryView.php?act=AddrCountryAdd.php">Add</a></div></td>
        <td class="subtitlebl"><div align="center">country</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF"><div align="center"><a href="AddrCountryView.php?act=AddrCountryEdit.php&id=<?php echo $row_cntry['id']; ?>">Edit</a></div></td>
        <td bgcolor="#FFFFFF"><div align="center"><a href="AddrCountryView.php?act=AddrCountryDelete.php&id=<?php echo $row_cntry['id']; ?>">Delete</a></div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11" Title="Country ID=<?php echo $row_cntry['id']; ?> "><?php echo $row_cntry['country']; ?></td>
      </tr>
      <?php } while ($row_cntry = mysql_fetch_assoc($cntry)); ?>
    </table>  </td>
  <td valign="top">
<?php if (isset($_GET['act'])) {
	$cntryact = $_GET['act'];
 ?>	
		<table width="200px">
		  <tr>
			<td valign="top"><?php require_once($cntryact); ?></td>
		  </tr>
		</table>
<?php } ?>	</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($cntry);
ob_end_flush();
?>