<?php  $pt = "Add Patient Visit"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php $_SESSION['today'] = date("Y-m-d");  ?>
<?php

$colname_visitlist = "-1";
if (isset($_GET['mrn'])) {
  $colname_visitlist = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
  $_SESSION['mrn'] = $colname_visitlist;
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitlist = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, vfeeid, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, weight, height, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE medrecnum = %s", $colname_visitlist);
$visitlist = mysql_query($query_visitlist, $swmisconn) or die(mysql_error());
$row_visitlist = mysql_fetch_assoc($visitlist);
$totalRows_visitlist = mysql_num_rows($visitlist);
 ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table align="center">
    <tr>
	<td>
            <table align="center">
                <tr>
                    <td><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn'] ?>">Close</a></td>
                        <td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td align="center" class="subtitlebl">View Patient Visit List </td>
                        <td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <?php  if ($_SESSION['vnum'] > 0) { ?>
                        <td nowrap="nowrap">Visits: &nbsp; </td>
                <?php if(allow(20,1) == 1) { ?>
                        <td align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitList.php"><?php echo $_SESSION['vnum']; ?></a></td>
                <?php  } ?>
                <?php  } 
                                else {?>
                        <td nowrap="nowrap">Visits: &nbsp; 0 &nbsp;</td>
                <?php  } ?>
                        <td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
		  </table>
		</td>	
	<tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="0">
        <tr>
          <td colspan="2" nowrap="nowrap" class="BlackBold_11"><br />
            Urgency:</td>
		  <td nowrap="nowrap" class="BlackBold_11"><div align="center">Visit Date:<br />
		  Location:</div></td>
          <td class="BlackBold_11"><div align="center" class="Black_11">Disch. Date: <br />
          Patient Type: </div></td>
          <td nowrap="nowrap" class="BlackBold_11"><div align="center">Height:<br />
            Weight:<br />
          </div></td>
          <td class="BlackBold_11"><div align="center">Visit Reason </div></td>
          <td class="BlackBold_11"><div align="center">Diagnosis:</div></td>
        </tr>
<?php do {  // find order number of visit
	mysql_select_db($database_swmisconn, $swmisconn);  //find visit fee paid
	$query_visitord = "Select id ordid, feeid, rate, ratereason, amtpaid from orders where medrecnum = '".$row_visitlist['medrecnum']."' and visitid = '".$row_visitlist['id']."' and feeid = '".$row_visitlist['vfeeid']."'";
	$visitord = mysql_query($query_visitord, $swmisconn) or die(mysql_error());
	$row_visitord = mysql_fetch_assoc($visitord);
	$totalRows_visitord = mysql_num_rows($visitord);
		  
		  
?>
        <tr>
          <td nowrap="nowrap"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $row_visitlist['id']; ?>&visit=PatVisitView.php&disp=on">View</a>
	<?php //if(allow(20,2) == 1) { ?>  <!--edit previous visits option is removed-->
 <!--         &nbsp;&nbsp;<a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $row_visitlist['id']; ?>&visit=PatVisitEdit.php&vordid=<?php echo $row_visitord['ordid'] ?>&disp=on">Edit</a>-->
   </div></td>					    
          <td nowrap="nowrap" class="BlueBold_14" title="Entry Date: <?php echo $row_visitlist['entrydt']; ?>&#10;Entry By: <?php echo $row_visitlist['entryby']; ?>"><div align="center"><?php echo $row_visitlist['id']; ?></div></td>
          <?php // } ?>

            <td nowrap="nowrap"><input name="visitdate" type="text" id="visitdate" readonly="readonly" value="<?php echo $row_visitlist['visitdate']; ?>" size="12" /></td>
            <td><input name="discharge" type="text" id="discharge" readonly="readonly" value="<?php echo $row_visitlist['discharge']; ?>" size="15" /></td>
            <td nowrap="nowrap"><input name="height" type="text" id="height" readonly="readonly" value="<?php echo $row_visitlist['height']; ?>" size="12" /></td>
            <td><textarea name="visitreason" cols="30" rows="1" id="visitreason" readonly="readonly"><?php echo $row_visitlist['visitreason']; ?></textarea></td>
            <td><textarea name="diagnosis" cols="30" rows="1" id="diagnosis" readonly="readonly"><?php echo $row_visitlist['diagnosis']; ?></textarea></td>
 		</tr>
			<tr>
            <td colspan="2"><input name="urgency" type="text" id="urgency" readonly="readonly" value="<?php echo $row_visitlist['urgency']; ?>" size="8" /></td>

              <td><input name="pat_type" type="text" id="pat_type readonly="readonly" value="<?php echo $row_visitlist['pat_type']; ?>" size="15" /></td>
              <td><input name="location" type="text" id="location" readonly="readonly" value="<?php echo $row_visitlist['location']; ?>" size="15" /></td>
            <td nowrap="nowrap"><input name="weight" type="text" id="weight" readonly="readonly" value="<?php echo $row_visitlist['weight']; ?>" size="12" /></td>
		<tr>		</tr>
           <?php } while ($row_visitlist = mysql_fetch_assoc($visitlist)); ?>
      </table>
        </form>
    </td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($visitlist);
?>
