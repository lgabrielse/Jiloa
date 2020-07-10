<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
	}
if (isset($_SESSION['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']);
	}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.rate, o.doctor, substr(o.status,1,7) status, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtdue, o.amtpaid, f.section, f.name, f.descr FROM orders o, fee f WHERE o.feeid = f.id and f.dept = 'Dental' and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_vid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);
?>


<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_Dental = "SELECT id, dept, `section`, name, unit, descr, fee, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM fee WHERE Active = 'Y' and dept = 'Dental' and Section = 'Procedure' ORDER BY name ASC";
$Dental = mysql_query($query_Dental, $swmisconn) or die(mysql_error());
$row_Dental = mysql_fetch_assoc($Dental);
$totalRows_Dental = mysql_num_rows($Dental);
?>

<?php

mysql_select_db($database_swmisconn, $swmisconn);
$query_reason = "Select id, list, name, seq from dropdownlist where list = 'Rate Reason' Order By seq";
$reason = mysql_query($query_reason, $swmisconn) or die(mysql_error());
$row_reason = mysql_fetch_assoc($reason);
$totalRows_reason = mysql_num_rows($reason);

mysql_select_db($database_swmisconn, $swmisconn);
$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
$totalRows_doctor = mysql_num_rows($doctor);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>

<body>

<table width="90%">
  <tr>
    <td>&nbsp;</td>
    <td><form id="formplv1" name="formplv1" method="post" action="checkbox-form.php" >
      <table width="100%">
        <tr>
          <td rowspan="2" bgcolor="#DCDCDC"><div align="center" class="BlackBold_16">Ordered</div></td>
          <td rowspan="2" bgcolor="#DCDCDC"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></div>
		    <div align="center">&nbsp;</div>
            <div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesViewPSR.php&notetype=Dental">Notes</a></div>
		  </td>
          <td nowrap="nowrap"><h1 align="center" class="subtitlebl">Order Dental Work </h1></td>
          <td nowrap="nowrap">Urg:
            <select name="urgency" id="urgency">
            <option value="Routine">Routine</option>
            <option value="Scheduled">Scheduled</option>
            <option value="ASAP">ASAP</option>
            <option value="STAT">STAT</option>
          </select> <input type="hidden" name="status" value="ordered"/></td>
		  <td nowrap="nowrap">Doctor:<select name="doctor">
		    <option value="NA">NA</option>
		    <?php
do {  
?>
				<option value="<?php echo $row_doctor['userid']?>"<?php if (!(strcmp($row_doctor['userid'], $_SESSION['user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doctor['userid']?></option>
		    <?php
} while ($row_doctor = mysql_fetch_assoc($doctor));
  $rows = mysql_num_rows($doctor);
  if($rows > 0) {
      mysql_data_seek($doctor, 0);
	  $row_doctor = mysql_fetch_assoc($doctor);
  }
?>
		  </select></td>

          <td nowrap="nowrap"><p>Rate:
            <select name="rate" id="rate">
              <option value="200">200</option>
              <option value="150">150</option>
              <option value="125">125</option>
              <option value="100" selected="selected">Standard</option>
              <option value="75">75%</option>
              <option value="50">50%</option>
              <option value="25">25%</option>
              <option value="0">None</option>
              </select></td>
          <td nowrap="nowrap">Rate Reason:            </td>
          <td nowrap="nowrap"><select name="ratereason">
            <option value="103">None</option>
            <?php do {  ?>
            <option value="<?php echo $row_reason['id']?>"><?php echo $row_reason['name']?></option>
            <?php
} while ($row_reason = mysql_fetch_assoc($reason));
  $rows = mysql_num_rows($reason);
  if($rows > 0) {
      mysql_data_seek($reason, 0);
	  $row_reason = mysql_fetch_assoc($reason);
  }
?>
          </select></td>
          <td nowrap="nowrap">&nbsp;</td>
		<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
          <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
          <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
          <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
          <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
          <input name="qrystr" type="hidden" id="qrystr" value="<?php echo $_SERVER['QUERY_STRING']; ?>" />
		  <input type="hidden" name="MM_insert" value="formplv1" />		
        </tr>
        <tr>
          <td colspan = "5" nowrap="nowrap">Order Comments: 
            <input name="comments" type="text" id="comments" size="80" /></td>
    <?php if(allow(46,3) == 1) { ?>
		  <td><input type="submit" name="formSubmit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
	<?php } else {?>
		<td nowrap="nowrap" class="BlackBold_11">Read Only</td>	
	<?php }?>
        </tr>
        <tr>
          <td colspan="2" valign="top" bgcolor="#DCDCDC" class="subtitlebk"><div align="center">
            <table>
                  <tr>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">Date/Time</td>
                    <td nowrap="nowrap" class="BlackBold_11">Ord#*</td>
                    <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>">Test*</td>
                    <td nowrap="nowrap" class="BlackBold_11">Urg</td>
                    <td nowrap="nowrap" class="BlackBold_11">Status</td>
                    <td nowrap="nowrap" class="BlackBold_11">Due</td>
                    <td nowrap="nowrap" class="BlackBold_11">Paid</td>
                  </tr>
              <?php do { ?>
                  <tr>
			  	<?php if(!empty($row_ordered['id']) and empty($row_ordered['amtpaid']) and allow(46,4) == 1) {?>  <!--and empty($row_ordered['rate']) -->
					<td class="BlackBold_11" nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['id'] ?>">Del</a></td>
				<?php } else {?>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
				<?php } ?>
                    <td nowrap="nowrap" class="BlackBold_11" title="VID: <?php echo $row_ordered['visitid']; ?> "><?php echo $row_ordered['entrydt']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11" title="Doctor: <?php echo $row_ordered['doctor']; ?>"><div align="center"><?php echo $row_ordered['id']; ?></div></td>
                <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>"><?php echo $row_ordered['name']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['urg']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['status']; ?></td>
			  <?php if($row_ordered['rate'] == '0') { ?>
                <td nowrap="nowrap" class="BlackBold_11"><div align="right">Rt:0</div></td>
			  <?php } else {?>
                <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtdue']; ?></div></td>
			  <?php } ?>
                <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtpaid']; ?></div></td>
              </tr>
              <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
            </table>
          </div>		  </td>

          <td valign="top" class="subtitlebk"><div align="center">
            <table>
                <tr>
                  <td colspan="3"><div align="center">Dental Procedures</div></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td ><input type="checkbox" name="laborder[]" value="<?php echo $row_Dental['id']; ?>" /></td>
                    <td nowrap="nowrap" class="BlackBold_11" title="<?php echo $row_Dental['descr']; ?>"><?php echo $row_Dental['name']; ?></td>
                    <td class="BlackBold_11"><?php echo $row_Dental['fee']; ?></td>
                  </tr>
                  <?php } while ($row_Dental = mysql_fetch_assoc($Dental)); ?>
              </table>
          </div>		  </td>

          <td valign="top" class="subtitlebk"><div align="center">
            
          </div>		  </td>

          <td valign="top" class="subtitlebk"><div align="center">
          </div>		  </td>
          <td valign="top" class="subtitlebk"><div align="center">
          </div>		  </td>
          <td colspan="3" valign="top" class="subtitlebk"><div align="center">
          </div>		  </td>
        </tr>
      </table>
    </form>    </td>
  </tr>
</table>

<?php $notetype = 'Dental';  //must be department name  ?> 
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Patient/PatNotesViewPSR.php') ?> 

<script  type="text/javascript">
 var frmvalidator = new Validator("formplv1");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>

</body>
</html>
<?php
mysql_free_result($Dental);

mysql_free_result($reason);

mysql_free_result($doctor);
?>
