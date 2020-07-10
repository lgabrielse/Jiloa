<?php  $pt = "AddrStateView"; ?>
<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 

<?php
$colname_country = "4";
if (isset($_GET['countryid'])) {
  $colname_country = (get_magic_quotes_gpc()) ? $_GET['countryid'] : addslashes($_GET['countryid']);
}
else {
$_GET['countryid'] = 4;
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_stt = sprintf("SELECT s.id, countryid, country, state FROM state s join country c on s.countryid = c.id WHERE c.id = %s ORDER BY country, state ASC",$colname_country);
//$query_country = sprintf("SELECT id, country FROM country WHERE id = %s", $colname_country);
$stt = mysql_query($query_stt, $swmisconn) or die(mysql_error());
$row_stt = mysql_fetch_assoc($stt);
$totalRows_stt = mysql_num_rows($stt);

mysql_select_db($database_swmisconn, $swmisconn);
$query_countries = "SELECT id, country FROM country ORDER BY country ASC";
$countries = mysql_query($query_countries, $swmisconn) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
$totalRows_countries = mysql_num_rows($countries);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>AddrStateView</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  	<td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center" class="BlueBold_18">STATE</td>
    <td align="center" class="BlueBold_12"><a href="AddrCountryView.php">Country</a> - <a href="AddrLocGovtView.php">LocGovt</a> - <a href="AddrCityView.php">City</a></td>
  </tr>
  <tr>
  	<td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
  </tr>
  <tr>
    <td valign="top">
	<form id="form1" name="form1" method="get" action="AddrStateView.php">
	<table width="100%" border="1" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Select Country </td>
      </tr>
      <tr>
        <td>
          <select name="countryid" id="countryid">
            <?php
do {  
?>
            <option value="<?php echo $row_countries['id']?>"<?php if (!(strcmp($row_countries['id'], $_GET['countryid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_countries['country']?></option>
            <?php
} while ($row_countries = mysql_fetch_assoc($countries));
  $rows = mysql_num_rows($countries);
  if($rows > 0) {
      mysql_data_seek($countries, 0);
	  $row_countries = mysql_fetch_assoc($countries);
  }
?>
          </select>        </td>
      </tr>
      <tr>
        <td><input type="submit" name="Submit" value="Select" /></td>
      </tr>
    </table>
    </form></td>
    <td valign="top"><table width="100%" align="center">
      <tr>
        <td valign="top"><table width="100%">
             <tr>
              <td class="subtitlebl">&nbsp;</td>
              <td class="subtitlebl"><div align="left"><a href="AddrStateView.php?act=AddrStateAdd.php&amp;countryid=<?php echo $_GET['countryid'] ?>">Add</a></div></td>
              <td class="subtitlebl"><div align="left">Country</div></td>
              <td class="subtitlebl"><div align="left">State</div></td>
            </tr>
            <?php do { ?>
            <tr>
              <td bgcolor="#FFFFFF"><div align="center"><a href="AddrStateView.php?act=AddrStateEdit.php&amp;id=<?php echo $row_stt['id']; ?>">Edit</a></div></td>
              <td bgcolor="#FFFFFF"><div align="center"><a href="AddrStateView.php?act=AddrStateDelete.php&amp;id=<?php echo $row_stt['id']; ?>">Delete</a></div></td>
              <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_stt['country']; ?></td>
              <td bgcolor="#FFFFFF" class="BlackBold_11"Title="Country=<?php echo $row_stt['countryid']; ?>&#10;State=<?php echo $row_stt['id']; ?>"><?php echo $row_stt['state']; ?></td>
            </tr>
            <?php } while ($row_stt = mysql_fetch_assoc($stt)); ?>
        </table>        </td>
      </tr>
    </table>		</td>
        <td><?php if (isset($_GET['act'])) {
	$sttact = $_GET['act'];
 ?>
            <table width="100%">
              <tr>
                <td valign="top"><?php require_once($sttact); ?></td>
              </tr>
            </table>
          <?php } ?>	</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($stt);

mysql_free_result($countries);
ob_end_flush();
?>