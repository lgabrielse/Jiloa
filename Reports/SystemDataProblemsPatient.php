<?php require_once('../../Connections/swmisconn.php'); ?><?php  $pt = "vwduplicatepatient"; ?>
<?php  include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php  require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php
  $colname_daysback = "90";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
//Query to find list of duplicate patients and use $totalRows_duplpatientsc as count
mysql_select_db($database_swmisconn, $swmisconn); //where DATEDIFF(CURRENT_DATE, o.entrydt) < 900)
$query_duplpatientslist = "select count(*) as count, lastname, firstname, othername from patperm where DATEDIFF(CURRENT_DATE, entrydt) < '". $colname_daysback ."' group by lastname, firstname having (count(0) > 1)";
$duplpatientslist = mysql_query($query_duplpatientslist, $swmisconn) or die(mysql_error());
$row_duplpatientslist = mysql_fetch_assoc($duplpatientslist);
$totalRows_duplpatientslist = mysql_num_rows($duplpatientslist);

mysql_select_db($database_swmisconn, $swmisconn);
$query_DuplPatlist2 = "SELECT p.medrecnum, p.firstname, p.lastname, p.othername, p.gender FROM patperm p INNER JOIN ( SELECT lastname, firstname, COUNT(*) FROM patperm where DATEDIFF(CURRENT_DATE, entrydt) < '". $colname_daysback ."' GROUP BY lastname, firstname HAVING count(*) > 1) as dup  ON p.lastname = dup.lastname and p.firstname = dup.firstname order by p.lastname, p.firstname; ";
$DuplPatlist2 = mysql_query($query_DuplPatlist2, $swmisconn) or die(mysql_error());
$row_DuplPatlist2 = mysql_fetch_assoc($DuplPatlist2);
$totalRows_DuplPatlist2 = mysql_num_rows($DuplPatlist2);

//  //Query to find list and count of patients with no PatInfo data
mysql_select_db($database_swmisconn, $swmisconn);
$query_noPatInfoList = "select p.medrecnum, p.lastname, p.firstname, p.othername from patperm p where DATEDIFF(CURRENT_DATE, p.entrydt) < '". $colname_daysback ."' and  medrecnum not in (SELECT medrecnum from patinfo) Order by p.lastname, p.firstname";
$noPatInfoList = mysql_query($query_noPatInfoList, $swmisconn) or die(mysql_error());
$row_noPatInfoList = mysql_fetch_assoc($noPatInfoList);
$totalRows_noPatInfoList = mysql_num_rows($noPatInfoList);


// ***** PatRegistNotPaidList and count*****
mysql_select_db($database_swmisconn, $swmisconn);
$query_PatRegistNotPaidList = "select p.medrecnum, p.lastname, p.firstname, o.rate, CASE WHEN o.item IS NULL THEN round(f.fee * (o.rate / 100)) ELSE o.ofee END as amtdue, (Select count(*) from patinfo i where i.medrecnum = p.medrecnum) as info, (Select count(*) from patvisit v where v.medrecnum = p.medrecnum) as visits, (Select count(*) from patnotes n where n.medrecnum = p.medrecnum) as notes from orders o join fee f ON o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum where amtpaid is null and visitid = '0'  and CASE WHEN o.item IS NULL THEN round(f.fee * (o.rate / 100)) ELSE o.ofee END > 0  and DATEDIFF(CURRENT_DATE, o.entrydt) < '". $colname_daysback ."';  ";
$PatRegistNotPaidList = mysql_query($query_PatRegistNotPaidList, $swmisconn) or die(mysql_error());
$row_PatRegistNotPaidList = mysql_fetch_assoc($PatRegistNotPaidList);
$totalRows_PatRegistNotPaidList = mysql_num_rows($PatRegistNotPaidList);


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>System Problems</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<p>&nbsp;</p>
<table width="60%" border="1" align="center">
  <tr>
    <td nowrap="nowrap"><div align="center" class="subtitlebl">Patient Data Problems </div></td>
    <td>&nbsp;</td>
	<td>
	  <table align="center">
	    <form id="formdaysback" name="formdaysback" method="post" action="SystemDataProblemsPatient.php">
        <tr>
        <td nowrap="nowrap">Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.formdaysback.submit();">    <!--onChange="document.form3.submit();"-->
            <option value="1" <?php if (!(strcmp(1, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1</option>
            <option value="2" <?php if (!(strcmp(2, $colname_daysback))) {echo "selected=\"selected\"";} ?>>2</option>
            <option value="3" <?php if (!(strcmp(3, $colname_daysback))) {echo "selected=\"selected\"";} ?>>3</option>
            <option value="4" <?php if (!(strcmp(4, $colname_daysback))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="5" <?php if (!(strcmp(5, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5</option>
            <option value="6" <?php if (!(strcmp(6, $colname_daysback))) {echo "selected=\"selected\"";} ?>>6</option>
            <option value="7" <?php if (!(strcmp(7, $colname_daysback))) {echo "selected=\"selected\"";} ?>>7</option>
            <option value="10" <?php if (!(strcmp(10, $colname_daysback))) {echo "selected=\"selected\"";} ?>>10</option>
            <option value="15" <?php if (!(strcmp(15, $colname_daysback))) {echo "selected=\"selected\"";} ?>>15</option>
            <option value="20" <?php if (!(strcmp(20, $colname_daysback))) {echo "selected=\"selected\"";} ?>>20</option>
            <option value="25" <?php if (!(strcmp(25, $colname_daysback))) {echo "selected=\"selected\"";} ?>>25</option>
            <option value="30" <?php if (!(strcmp(30, $colname_daysback))) {echo "selected=\"selected\"";} ?>>30</option>
            <option value="60" <?php if (!(strcmp(60, $colname_daysback))) {echo "selected=\"selected\"";} ?>>60</option>
            <option value="90" <?php if (!(strcmp(90, $colname_daysback))) {echo "selected=\"selected\"";} ?>>90</option>
            <option value="120" <?php if (!(strcmp(120, $colname_daysback))) {echo "selected=\"selected\"";} ?>>120</option>
            <option value="180" <?php if (!(strcmp(180, $colname_daysback))) {echo "selected=\"selected\"";} ?>>180</option>
            <option value="365" <?php if (!(strcmp(365, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1 yr</option>
            <option value="1825" <?php if (!(strcmp(1825, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5 yr</option>
          </select>			</td>
        </tr>
		</form>
      </table>
	</td>
    <td>&nbsp;</td>
    <td nowrap="nowrap"><div align="center"><span class="navLink"><a href="ReportsMenu.php">Report Menu </a></span>&nbsp;&nbsp;&nbsp;</div></td>
  </tr>
  <tr>
    <td><table>
      <tr>
        <td class="BlueBold_14">Number of Duplicate Patients: </td>
         <td class="BlackBold_14"><?php echo $totalRows_duplpatientslist; ?></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td><table>
      <tr>
        <td class="BlueBold_14">Number of Patients with no Patient Info: </td>
        <td class="BlackBold_14"><?php echo $totalRows_noPatInfoList ; ?></td>
      </tr>
    </table> </td>
    <td>&nbsp;</td>
    <td><table>
      <tr>
        <td class="BlueBold_14">Number of Patients with unpaid Registration: </td>
        <td class="BlackBold_14"><?php echo $totalRows_PatRegistNotPaidList; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><span class="BlackBold_14">List of Duplicate Patients:</span></td>
    <td>&nbsp;</td>
    <td><span class="BlackBold_14">List of Patients with no Patient Info: </span></td>
    <td>&nbsp;</td>
    <td><span class="BlackBold_14">List of Patients with unpaid Registration: </span></td>
  </tr>
  <tr>
    <td valign="top">
	<table cellpadding="0" cellspacing="0">
      <tr>
        <td class="BlackBold_11">MRN &nbsp;&nbsp; </td>
        <td class="BlackBold_11">lastname &nbsp;&nbsp;</td>
        <td class="BlackBold_11">firstname &nbsp;&nbsp;</td>
        <td class="BlackBold_11">othername &nbsp;&nbsp;</td>
        <td class="BlackBold_11">gender &nbsp;&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr>
        <td class="Black_1010"><?php echo $row_DuplPatlist2['medrecnum']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist2['lastname']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist2['firstname']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist2['othername']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist2['gender']; ?></td>
      </tr>
      <?php } while ($row_DuplPatlist2 = mysql_fetch_assoc($DuplPatlist2)); ?>
    </table></td>
    <td>&nbsp;</td>
    <td valign="top"><table cellpadding="0" cellspacing="0">
      <tr>
        <td class="BlackBold_11">medrecnum&nbsp;</td>
        <td class="BlackBold_11">lastname</td>
        <td class="BlackBold_11">firstname</td>
        <td class="BlackBold_11">othername</td>
      </tr>
      <?php do { ?>
      <tr>
        <td class="Black_1010"><?php echo $row_noPatInfoList['medrecnum']; ?></td>
        <td class="Black_1010"><?php echo $row_noPatInfoList['lastname']; ?></td>
        <td class="Black_1010"><?php echo $row_noPatInfoList['firstname']; ?></td>
        <td class="Black_1010"><?php echo $row_noPatInfoList['othername']; ?></td>
      </tr>
      <?php } while ($row_noPatInfoList = mysql_fetch_assoc($noPatInfoList)); ?>
    </table></td>
    <td>&nbsp;</td>
    <td valign="top"><table cellpadding="0" cellspacing="0">
      <tr>
        <td class="BlackBold_11">medrecnum&nbsp;</td>
        <td class="BlackBold_11">lastname&nbsp;</td>
        <td class="BlackBold_11">firstname&nbsp;</td>
        <td class="BlackBold_11"><div align="center">rate&nbsp;</div></td>
        <td class="BlackBold_11"><div align="center">amtdue&nbsp;</div></td>
        <td class="BlackBold_11"><div align="center">info&nbsp;</div></td>
        <td class="BlackBold_11"><div align="center">visits&nbsp;</div></td>
        <td class="BlackBold_11"><div align="center">notes</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td class="Black_1010"><?php echo $row_PatRegistNotPaidList['medrecnum']; ?></td>
        <td class="Black_1010"><?php echo $row_PatRegistNotPaidList['lastname']; ?></td>
        <td class="Black_1010"><?php echo $row_PatRegistNotPaidList['firstname']; ?></td>
        <td class="Black_1010"><div align="center"><?php echo $row_PatRegistNotPaidList['rate']; ?></div></td>
        <td class="Black_1010"><div align="center"><?php echo $row_PatRegistNotPaidList['amtdue']; ?></div></td>
        <td class="Black_1010"><div align="center"><?php echo $row_PatRegistNotPaidList['info']; ?></div></td>
        <td class="Black_1010"><div align="center"><?php echo $row_PatRegistNotPaidList['visits']; ?></div></td>
        <td class="Black_1010"><div align="center"><?php echo $row_PatRegistNotPaidList['notes']; ?></div></td>
      </tr>
      <?php } while ($row_PatRegistNotPaidList = mysql_fetch_assoc($PatRegistNotPaidList)); ?>
    </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<br/>
<br />
</body>
</html>
<?php
mysql_free_result($duplpatientslist);

mysql_free_result($PatRegistNotPaidList);

mysql_free_result($DuplPatlist2);

mysql_free_result($noPatInfoList);

?>
