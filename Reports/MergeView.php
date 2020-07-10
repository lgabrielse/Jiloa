<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/bethanyconn.php'); ?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DuplPatlist2 = "SELECT p.medrecnum, p.firstname, p.lastname, p.othername, p.gender FROM patperm p INNER JOIN ( SELECT lastname, firstname, COUNT(*) FROM patperm GROUP BY lastname, firstname HAVING count(*) > 1) as dup  ON p.lastname = dup.lastname and p.firstname = dup.firstname order by p.lastname, p.firstname; ";
$DuplPatlist2 = mysql_query($query_DuplPatlist2, $swmisconn) or die(mysql_error());
$row_DuplPatlist2 = mysql_fetch_assoc($DuplPatlist2);
$totalRows_DuplPatlist2 = mysql_num_rows($DuplPatlist2); 
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DuplPatlist3 = "SELECT p.medrecnum, p.firstname, p.lastname, p.othername, p.gender FROM patperm p INNER JOIN ( SELECT lastname, firstname, othername, COUNT(*) FROM patperm GROUP BY lastname, firstname, othername HAVING count(*) > 1) as dupl ON p.lastname = dupl.lastname and p.firstname = dupl.firstname and p.othername = dupl.othername order by p.lastname, p.firstname;";
$DuplPatlist3 = mysql_query($query_DuplPatlist3, $swmisconn) or die(mysql_error());
$row_DuplPatlist3 = mysql_fetch_assoc($DuplPatlist3);
$totalRows_DuplPatlist3 = mysql_num_rows($DuplPatlist3); 
?>
<?php
$colname_MRNone = "0";
if (isset($_GET['mrn1'])) {
  $colname_MRNone = (get_magic_quotes_gpc()) ? $_GET['mrn1'] : addslashes($_GET['mrn1']);
}

$colname_MRNtwo = "0";
if (isset($_GET['mrn2'])) {
  $colname_MRNtwo = (get_magic_quotes_gpc()) ? $_GET['mrn2'] : addslashes($_GET['mrn2']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_MRNone = sprintf("SELECT medrecnum, hospital, active, IFNULL(ddate,'---') ddate, entrydt, entryby, lastname, firstname, IFNULL(othername,'---') othername, gender, ethnicgroup, dob, est, IFNULL(photofile,'---') photofile FROM patperm WHERE medrecnum = %s", $colname_MRNone);
$MRNone = mysql_query($query_MRNone, $swmisconn) or die(mysql_error());
$row_MRNone = mysql_fetch_assoc($MRNone);
$totalRows_MRNone = mysql_num_rows($MRNone);

mysql_select_db($database_swmisconn, $swmisconn);
$query_MRNtwo = sprintf("SELECT medrecnum, hospital, active, IFNULL(ddate,'---') ddate, entrydt, entryby, lastname, firstname, IFNULL(othername,'---') othername, gender, ethnicgroup, dob, est, IFNULL(photofile,'---') photofile FROM patperm WHERE medrecnum = %s", $colname_MRNtwo);
$MRNtwo = mysql_query($query_MRNtwo, $swmisconn) or die(mysql_error());
$row_MRNtwo = mysql_fetch_assoc($MRNtwo);
$totalRows_MRNtwo = mysql_num_rows($MRNtwo);

mysql_select_db($database_swmisconn, $swmisconn);
$query_INFOone = sprintf("Select p.medrecnum, p.title, p.occup, p.married, s.state, l.locgovt, c.city, 
IFNULL(p.phone1,'---') phone1, p.em_lname, p.em_fname, IFNULL(p.comments,'---') comments, p.entrydt, p.entryby  
from patinfo p join city c on p.city = c.id join locgovt l on p.locgovt = l.id join state s on p.state = s.id 
where p.medrecnum = %s", $colname_MRNone);
$INFOone = mysql_query($query_INFOone, $swmisconn) or die(mysql_error());
$row_INFOone = mysql_fetch_assoc($INFOone);
$totalRows_INFOone = mysql_num_rows($INFOone);

mysql_select_db($database_swmisconn, $swmisconn);
$query_INFOtwo = sprintf("Select p.medrecnum, p.title, p.occup, p.married, s.state, l.locgovt, c.city, 
IFNULL(p.phone1,'---') phone1, p.em_lname, p.em_fname, IFNULL(p.comments,'---') comments, p.entrydt, p.entryby  
from patinfo p join city c on p.city = c.id join locgovt l on p.locgovt = l.id join state s on p.state = s.id 
where p.medrecnum = %s", $colname_MRNtwo);
$INFOtwo = mysql_query($query_INFOtwo, $swmisconn) or die(mysql_error());
$row_INFOtwo = mysql_fetch_assoc($INFOtwo);
$totalRows_INFOtwo = mysql_num_rows($INFOtwo);

mysql_select_db($database_swmisconn, $swmisconn);
$query_VISITone = sprintf("SELECT id, medrecnum, visitdate, status, pat_type, location, urgency, IFNULL(discharge,'---') discharge, 
IFNULL(height,'---') height, IFNULL(weight,'---') weight, visitreason, IFNULL(diagnosis,'---') diagnosis, entryby, entrydt 
FROM patvisit WHERE medrecnum = %s", $colname_MRNone);
$VISITone = mysql_query($query_VISITone, $swmisconn) or die(mysql_error());
$row_VISITone = mysql_fetch_assoc($VISITone);
$totalRows_VISITone = mysql_num_rows($VISITone);

mysql_select_db($database_swmisconn, $swmisconn);
$query_VISITtwo = sprintf("SELECT id, medrecnum, visitdate, status, pat_type, location, urgency, IFNULL(discharge,'---') discharge, 
IFNULL(height,'---') height, IFNULL(weight,'---') weight, visitreason, IFNULL(diagnosis,'---') diagnosis, entryby, entrydt 
FROM patvisit WHERE medrecnum = %s", $colname_MRNtwo);
$VISITtwo = mysql_query($query_VISITtwo, $swmisconn) or die(mysql_error());
$row_VISITtwo = mysql_fetch_assoc($VISITtwo);
$totalRows_VISITtwo = mysql_num_rows($VISITtwo);

mysql_select_db($database_swmisconn, $swmisconn);
$query_ORDERone = sprintf("select o.medrecnum, o.visitid, o.id ordid, f.section, f.name, IFNULL(o.item,'---') item , 
IFNULL(o.doctor,'---') doctor, IFNULL(o.amtpaid,'---') amtpaid, o.entryby, o.entrydt 
from orders o join fee f on o.feeid = f.id where o.medrecnum = %s", $colname_MRNone);
$ORDERone = mysql_query($query_ORDERone, $swmisconn) or die(mysql_error());
$row_ORDERone = mysql_fetch_assoc($ORDERone);
$totalRows_ORDERone = mysql_num_rows($ORDERone);

mysql_select_db($database_swmisconn, $swmisconn);
$query_ORDERtwo = sprintf("select o.medrecnum, o.visitid, o.id ordid, f.section, f.name, IFNULL(o.item,'---') item, IFNULL(o.doctor,'---') doctor, IFNULL(o.amtpaid,'---') amtpaid, o.entryby, o.entrydt from orders o join fee f on o.feeid = f.id where o.medrecnum = %s", $colname_MRNtwo);
$ORDERtwo = mysql_query($query_ORDERtwo, $swmisconn) or die(mysql_error());
$row_ORDERtwo = mysql_fetch_assoc($ORDERtwo);
$totalRows_ORDERtwo = mysql_num_rows($ORDERtwo);

mysql_select_db($database_swmisconn, $swmisconn);
$query_NOTESone = sprintf("select n.medrecnum, n.visitid, substr(n.notes,1,20) notesabbr, n.notes, n.entryby, n.entrydt from patnotes n where medrecnum = %s", $colname_MRNone);
$NOTESone = mysql_query($query_NOTESone, $swmisconn) or die(mysql_error());
$row_NOTESone = mysql_fetch_assoc($NOTESone);
$totalRows_NOTESone = mysql_num_rows($NOTESone);

mysql_select_db($database_swmisconn, $swmisconn);
$query_NOTEStwo = sprintf("select n.medrecnum, n.visitid, substr(n.notes,1,20) notesabbr, n.notes, n.entryby, n.entrydt from patnotes n where n.medrecnum = %s", $colname_MRNtwo);
$NOTEStwo = mysql_query($query_NOTEStwo, $swmisconn) or die(mysql_error());
$row_NOTEStwo = mysql_fetch_assoc($NOTEStwo);
$totalRows_NOTEStwo = mysql_num_rows($NOTEStwo);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MergeView</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<table>
  <tr>
    <td valign="top"><table cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="5" bgcolor="#999999">&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" bgcolor="#999999">&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5"><span class="BlackBold_14">List of Duplicate Patients: 1st &amp; Last match </span></td>
      </tr>
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
      <tr> </tr>
      <tr>
        <td colspan="5" bgcolor="#999999">&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" bgcolor="#999999">&nbsp;&nbsp;</td>
      </tr>
      <td colspan="5"><span class="BlackBold_14">List of Duplicate Patients: All 3 names match </span></td>
      </tr>
      <tr>
        <td class="BlackBold_11">MRN &nbsp;&nbsp; </td>
        <td class="BlackBold_11">lastname &nbsp;&nbsp;</td>
        <td class="BlackBold_11">firstname &nbsp;&nbsp;</td>
        <td class="BlackBold_11">othername &nbsp;&nbsp;</td>
        <td class="BlackBold_11">gender &nbsp;&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr>
        <td class="Black_1010"><?php echo $row_DuplPatlist3['medrecnum']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist3['lastname']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist3['firstname']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist3['othername']; ?></td>
        <td class="Black_1010"><?php echo $row_DuplPatlist3['gender']; ?></td>
      </tr>
      <?php } while ($row_DuplPatlist3 = mysql_fetch_assoc($DuplPatlist3)); ?>
    </table></td>
    <td><table width="40%" border="0" align="center">
      <tr>
        <td><form id="form1" name="form1" method="get" action="MergeView.php">
          <table width="100%" border="1" align="center">
            <tr>
              <td colspan="8" bgcolor="#FFFFFF"><div align="center">Enter two MRNs to be merged, Click GO, Then select what to keep & delete.</div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Merge Data View </td>
              <td>&nbsp;</td>
              <td>MergeData View </td>
              <td nowrap="nowrap"><div align="center"><span class="navLink"><a href="ReportsMenu.php">Report Menu </a></span>&nbsp;&nbsp;&nbsp;</div></td>
              <td colspan="2" rowspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <?php if($_SESSION['user'] = "L.GABRIELSE") { ?>
              <td rowspan="4"><table>
                <tr>
                  <td nowrap="nowrap"><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNtwo ?>&MRNB=<?php echo $colname_MRNone ?>&INFO=<?php echo $colname_MRNone ?>"> Delete </a><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNtwo ?>&MRNB=<?php echo $colname_MRNone ?>&INFO=<?php echo $colname_MRNone ?>"><?php echo $colname_MRNtwo ?></a><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNtwo ?>&MRNB=<?php echo $colname_MRNone ?>&INFO=<?php echo $colname_MRNone ?>"> &amp; Keep <?php echo $colname_MRNone ?> &amp; Keep Info of <?php echo $colname_MRNone ?></a> </td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNtwo ?>&MRNB=<?php echo $colname_MRNone ?>&INFO=<?php echo $colname_MRNtwo ?>"> Delete <?php echo $colname_MRNtwo ?> &amp; Keep <?php echo $colname_MRNone ?> &amp; Keep Info of <?php echo $colname_MRNtwo ?></a> </td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNone ?>&MRNB=<?php echo $colname_MRNtwo ?>&INFO=<?php echo $colname_MRNone ?>"> Delete <?php echo $colname_MRNone ?> &amp; Keep <?php echo $colname_MRNtwo ?> &amp; Keep Info of <?php echo $colname_MRNone ?></a> </td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNone ?>&MRNB=<?php echo $colname_MRNtwo ?>&INFO=<?php echo $colname_MRNtwo ?>"> Delete <?php echo $colname_MRNone ?> &amp; Keep <?php echo $colname_MRNtwo ?> &amp; Keep Info of </a><a href="MergeRecordsbyMRN.php?MRNA=<?php echo $colname_MRNone ?>&MRNB=<?php echo $colname_MRNtwo ?>&INFO=<?php echo $colname_MRNtwo ?>"><?php echo $colname_MRNtwo ?></a> </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">All Visits will have MRN Kept MRN. </td>
                </tr>
              </table></td>
              <?php }?>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>MRN #1 </td>
              <td>&nbsp;</td>
              <td>MRN #2</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="mrn1" type="text" id="mrn1" value="<?php echo $colname_MRNone ?>"/></td>
              <td>&nbsp;</td>
              <td><input name="mrn2" type="text" id="mrn2" value="<?php echo $colname_MRNtwo ?>" /></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="submit" name="Submit" value="Go" /></td>
            </tr>
          </table>
        </form></td>
      </tr>
    </table>
        <!-- ***** PATIENT *****-->
        <!-- ***** PATIENT *****-->
        <table width="40%" border="0" align="center">
          <tr>
            <td bgcolor="#FFFFCC">Patient</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table border="0" cellpadding="1" cellspacing="1">
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>hospital</td>
                </tr>
                <tr>
                  <td>active</td>
                </tr>
                <tr>
                  <td>ddate</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>lastname</td>
                </tr>
                <tr>
                  <td>firstname</td>
                </tr>
                <tr>
                  <td>othername</td>
                </tr>
                <tr>
                  <td>gender</td>
                </tr>
                <tr>
                  <td>ethnicgroup</td>
                </tr>
                <tr>
                  <td>dob</td>
                </tr>
                <tr>
                  <td>est</td>
                </tr>
                <tr>
                  <td>photofile</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table border="0" cellpadding="1" cellspacing="1">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNone['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['hospital']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['active']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['ddate']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['entrydt']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['entryby']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNone['lastname']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNone['firstname']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNone['othername']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['gender']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['ethnicgroup']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['dob']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNone['est']; ?></td>
                </tr>
                <tr>
                  <td><?php echo substr($row_MRNone['photofile'],0,14); ?></td>
                </tr>
            </table></td>
            <?php } while ($row_MRNone = mysql_fetch_assoc($MRNone)); ?>
            <?php do { ?>
            <td><table border="0" cellpadding="1" cellspacing="1">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNtwo['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['hospital']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['active']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['ddate']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['entrydt']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['entryby']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNtwo['lastname']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNtwo['firstname']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_MRNtwo['othername']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['gender']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['ethnicgroup']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['dob']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_MRNtwo['est']; ?></td>
                </tr>
                <tr>
                  <td><?php echo substr($row_MRNtwo['photofile'],0,14); ?></td>
                </tr>
            </table></td>
            <?php } while ($row_MRNtwo = mysql_fetch_assoc($MRNtwo)); ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <!-- ***** INFO *****-->
          <!-- ***** INFO *****-->
          <tr>
            <td bgcolor="#FFFFCC">Info</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table cellpadding="0" cellspacing="0">
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>title</td>
                </tr>
                <tr>
                  <td>occup</td>
                </tr>
                <tr>
                  <td>married</td>
                </tr>
                <tr>
                  <td>state</td>
                </tr>
                <tr>
                  <td>locgovt</td>
                </tr>
                <tr>
                  <td>city</td>
                </tr>
                <tr>
                  <td>phone1</td>
                </tr>
                <tr>
                  <td>em_lname</td>
                </tr>
                <tr>
                  <td>em_fname</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
                <tr>
                  <td>comments</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_INFOone['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['title']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['occup']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['married']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['state']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['locgovt']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['city']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOone['phone1']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['em_lname']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['em_fname']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOone['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOone['entrydt']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOone['comments']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_INFOone = mysql_fetch_assoc($INFOone)); ?>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_INFOtwo['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['title']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOtwo['occup']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['married']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['state']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['locgovt']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['city']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOtwo['phone1']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['em_lname']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['em_fname']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_INFOtwo['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOtwo['entrydt']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_INFOtwo['comments']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_INFOtwo = mysql_fetch_assoc($INFOtwo)); ?>
          </tr>
          <!-- ***** VISIT *****-->
          <!-- ***** VISIT *****-->
          <?php if($_SESSION['user'] = "L.GABRIELSE") { ?>
          <tr>
            <td>Add an option to select a visit to delete along with all linked orders and receipts</td>
            <td>Display rcptord and receipt records with orders</td>
          </tr>
          <tr>
            <td colspan="5"><form id="form_visit" action="MergeRecordsByVisit.php" method="get" >
                <table>
                  <tr>
                    <td nowrap="nowrap">Merge Visits</td>
                    <td>MRN:</td>
                    <td><input name="MRN" type="text" size="5" maxlength="9" /></td>
                    <td>Change<br />
                      Visitid:</td>
                    <td><input name="VisitDelete" type="text" id="VisitDelete" size="5" maxlength="9" /></td>
                    <td>&nbsp;</td>
                    <td>To<br />
                      Visitid:</td>
                    <td><input name="VisitKeep" type="text" id="VisitKeep" size="5" maxlength="9" /></td>
                    <td nowrap="nowrap">&quot;Change Visit record&quot; will be deleted. Visit Notes<br />
                      and Orders will 
                      appear with the &quot;To Visit record&quot; ..</td>
                    <td><input id="submit" name="submit" type="submit" value="Merge" /></td>
                  </tr>
                </table>
            </form></td>
          </tr>
          <?php  }?>
          <tr>
            <td bgcolor="#FFFFCC">Visits</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table cellpadding="0" cellspacing="0">
                <tr>
                  <td>visitid</td>
                </tr>
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>visitdate</td>
                </tr>
                <tr>
                  <td>status</td>
                </tr>
                <tr>
                  <td>pat_type</td>
                </tr>
                <tr>
                  <td>location</td>
                </tr>
                <tr>
                  <td>urgency</td>
                </tr>
                <tr>
                  <td>discharge</td>
                </tr>
                <tr>
                  <td>height</td>
                </tr>
                <tr>
                  <td>weight</td>
                </tr>
                <tr>
                  <td>visitreason</td>
                </tr>
                <tr>
                  <td>diagnosis</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlueBold_12"><?php echo $row_VISITone['id']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_VISITone['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITone['visitdate']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['status']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['pat_type']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['location']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['urgency']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITone['discharge']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['height']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['weight']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_VISITone['visitreason']; ?>"><?php echo substr($row_VISITone['visitreason'],0,30); ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_VISITone['diagnosis']; ?>"><?php echo substr($row_VISITone['diagnosis'],0,30); ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITone['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITone['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_VISITone = mysql_fetch_assoc($VISITone)); ?>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlueBold_12"><?php echo $row_VISITtwo['id']; ?></td>
                </tr>
                <tr>
                  <td class="BlackBold_14"><?php echo $row_VISITtwo['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITtwo['visitdate']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['status']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['pat_type']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['location']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['urgency']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITtwo['discharge']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['height']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['weight']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_VISITtwo['visitreason']; ?>"><?php echo substr($row_VISITtwo['visitreason'],0,30); ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_VISITtwo['diagnosis']; ?>"><?php echo substr($row_VISITtwo['diagnosis'],0,30); ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_VISITtwo['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_VISITtwo['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_VISITtwo = mysql_fetch_assoc($VISITtwo)); ?>
            <!-- ***** VISIT *****-->
            <!-- ***** VISIT *****-->
            <!-- ***** NOTES *****-->
            <!-- ***** NOTES *****-->
          </tr>
          <tr>
            <td bgcolor="#FFFFCC">Notes</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table cellpadding="0" cellspacing="0">
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>visitid</td>
                </tr>
                <tr>
                  <td>notes</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_NOTESone['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_NOTESone['visitid']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_NOTESone['notes']; ?>"><?php echo $row_NOTESone['notesabbr']; ?>&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_NOTESone['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_NOTESone['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_NOTESone = mysql_fetch_assoc($NOTESone)); ?>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_NOTEStwo['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_NOTEStwo['visitid']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap" title="<?php echo $row_NOTEStwo['notes']; ?>"><?php echo $row_NOTEStwo['notesabbr']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_NOTEStwo['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_NOTEStwo['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_NOTEStwo = mysql_fetch_assoc($NOTEStwo)); ?>
            <!-- ***** ORDERS *****-->
            <!-- ***** ORDERS *****-->
          </tr>
          <tr>
            <td bgcolor="#FFFFCC">Orders</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
            <td bgcolor="#FFFFCC">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><table cellpadding="0" cellspacing="0">
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>visitid</td>
                </tr>
                <tr>
                  <td>ordid</td>
                </tr>
                <tr>
                  <td>section/td> </td>
                </tr>
                <tr>
                  <td>name</td>
                </tr>
                <tr>
                  <td>item</td>
                </tr>
                <tr>
                  <td>doctor</td>
                </tr>
                <tr>
                  <td>amtpaid</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_ORDERone['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['visitid']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERone['ordid']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['section']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['name']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['item']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['doctor']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERone['amtpaid']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERone['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERone['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_ORDERone = mysql_fetch_assoc($ORDERone)); ?>
          </tr>
          <!--make second row for orders-->
          <tr>
            <td align="right"><table cellpadding="0" cellspacing="0">
                <tr>
                  <td>medrecnum&nbsp&nbsp&nbsp </td>
                </tr>
                <tr>
                  <td>visitid</td>
                </tr>
                <tr>
                  <td>ordid</td>
                </tr>
                <tr>
                  <td>section/td> </td>
                </tr>
                <tr>
                  <td>name</td>
                </tr>
                <tr>
                  <td>item</td>
                </tr>
                <tr>
                  <td>doctor</td>
                </tr>
                <tr>
                  <td>amtpaid</td>
                </tr>
                <tr>
                  <td>entryby</td>
                </tr>
                <tr>
                  <td>entrydt</td>
                </tr>
            </table></td>
            <?php do { ?>
            <td><table cellpadding="0" cellspacing="0">
                <tr>
                  <td class="BlackBold_14"><?php echo $row_ORDERtwo['medrecnum']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['visitid']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERtwo['ordid']; ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['section']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['name']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['item']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['doctor']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERtwo['amtpaid']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $row_ORDERtwo['entryby']; ?></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"><?php echo $row_ORDERtwo['entrydt']; ?></td>
                </tr>
            </table></td>
            <?php } while ($row_ORDERtwo = mysql_fetch_assoc($ORDERtwo)); ?>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>


</body>
</html>
<?php
mysql_free_result($MRNone);
mysql_free_result($MRNtwo);

mysql_free_result($INFOone);
mysql_free_result($INFOtwo);

mysql_free_result($VISITone);
mysql_free_result($VISITtwo);

mysql_free_result($ORDERone);
mysql_free_result($ORDERtwo);

mysql_free_result($NOTESone);
mysql_free_result($NOTEStwo);
?>
