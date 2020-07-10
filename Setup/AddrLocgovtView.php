<?php ob_start(); ?>
<?php  $pt = "AddrLocgovtView"; ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 

<?php
mysql_select_db($database_swmisconn, $swmisconn);  //list of countries for selection
$query_countries = "SELECT id, country FROM country ORDER BY country ASC";
$countries = mysql_query($query_countries, $swmisconn) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
$totalRows_countries = mysql_num_rows($countries);

$colname_countryid = "4";
if (isset($_GET['countryid'])) {
  $colname_countryid = (get_magic_quotes_gpc()) ? $_GET['countryid'] : addslashes($_GET['countryid']);
}
else {
$_GET['countryid'] = "4";
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_states = sprintf("SELECT id, countryid, `state` FROM `state` WHERE countryid = %s ORDER BY `state` ASC", $colname_countryid);
$states = mysql_query($query_states, $swmisconn) or die(mysql_error());
$row_states = mysql_fetch_assoc($states);
$totalRows_states = mysql_num_rows($states);


$colname_stateid = "2";
if (isset($_GET['stateid'])) {
  $colname_stateid = (get_magic_quotes_gpc()) ? $_GET['stateid'] : addslashes($_GET['stateid']);
}
else {
$_GET['stateid'] = "2";
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_locgovts = sprintf("SELECT id, locgovt, stateid FROM locgovt WHERE stateid = %s ORDER BY locgovt ASC", $colname_stateid);
$locgovts = mysql_query($query_locgovts, $swmisconn) or die(mysql_error());
$row_locgovts = mysql_fetch_assoc($locgovts);
$totalRows_locgovts = mysql_num_rows($locgovts);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>AddrLocgovtView</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="600px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  	<td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center" class="BlueBold_18">LOCAL GOVT</td>
    <td align="center" class="BlueBold_12"><a href="AddrCountryView.php">Country</a> - <a href="AddrStateView.php">State</a> - <a href="AddrCityView.php">City</a></td>
  </tr>
  <tr>
  	<td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center" class="BlueBold_18">&nbsp;</td>
    <td align="center"><a href="SetUpMenu.php" class="navLink">Setup Menu</a></td>
  </tr>
  <tr>
    <td valign="top">
	<table width="200px" border="1" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5">
	<form id="form1" name="form1" method="get" action="AddrLocgovtView.php">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>1st Select Country </td>
      </tr>
      <tr>
        <td>
          <select name="countryid" id="countryid" value="<?php echo $_GET['countryid'] ?>">  <!--onchange="document.form1.submit();"-->
			<option value="0">Select</option>
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
        <td><input type="submit" name="Submit" value="Select country" /></td>
      </tr>
 
 
      <tr>
        <td>Click Select Country </td>
      </tr>
      <tr>
        <td>Then Select State </td>
      </tr>
      <tr>
        <td>
          <select name="stateid" id="stateid"  value="<?php echo $_GET['stateid'] ?>" >  <!--  onchange="document.form1.submit();" -->
		   <option value="0">Selct</option>            
<?php
do {  
?>
            <option value="<?php echo $row_states['id']?>"<?php if (!(strcmp($row_states['id'], $_GET['stateid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_states['state']?></option>
            <?php
} while ($row_states = mysql_fetch_assoc($states));
  $rows = mysql_num_rows($states);
  if($rows > 0) {
      mysql_data_seek($states, 0);
	  $row_states = mysql_fetch_assoc($states);
  }
?>
          </select>        </td>
      </tr>
      <tr>
        <td><input type="submit" name="Submit" value="Select" /></td>
      </tr>
    </form>
    </table></td>
    <td valign="top">
	<table width="200px" align="center">
            <tr>
              <td class="subtitlebl">&nbsp;</td>
              <td class="subtitlebl"><div align="center"><a href="AddrLocgovtView.php?act=AddrLocgovtAdd.php&amp;countryid=<?php echo $_GET['countryid'] ?>&amp;stateid=<?php echo $_GET['stateid'] ?>">Add</a></div></td>
              <td class="subtitlebl"><div align="center">Locgovt</div></td>
            </tr>
            <?php do { ?>
            <tr>
              <td bgcolor="#FFFFFF"><div align="center"><a href="AddrLocgovtView.php?countryid=<?php echo $_GET['countryid'] ?>&stateid=<?php echo $_GET['stateid'] ?>&act=AddrLocgovtEdit.php&id=<?php echo $row_locgovts['id']; ?>">Edit</a></div></td>
              <td bgcolor="#FFFFFF"><div align="center"><a href="AddrLocgovtView.php?countryid=<?php echo $_GET['countryid'] ?>&stateid=<?php echo $_GET['stateid'] ?>&act=AddrLocgovtDelete.php&id=<?php echo $row_locgovts['id']; ?>">Delete</a></div></td>
              <td bgcolor="#FFFFFF" class="BlackBold_11" Title="Country=<?php echo $_GET['countryid'] ?>&#10;State=<?php echo $row_locgovts['stateid']; ?>&#10;LocGovt=<?php echo $row_locgovts['id']; ?>"><?php echo $row_locgovts['locgovt']; ?></td>
            </tr>
            <?php } while ($row_locgovts = mysql_fetch_assoc($locgovts)); ?>
         </table>		</td>

        <td valign="top"><?php if (isset($_GET['act'])) {
	$sttact = $_GET['act'];
 ?>
            <table width="200px">
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

mysql_free_result($countries);

mysql_free_result($locgovts);

mysql_free_result($states);
ob_end_flush();
?>