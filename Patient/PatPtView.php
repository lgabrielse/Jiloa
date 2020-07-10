<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
	}
if (isset($_SESSION['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']);
	}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.rate, o.doctor, substr(o.status,1,7) status, o.comments, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtdue, o.amtpaid, f.section, f.name, f.descr, DATE_FORMAT(v.discharge,'%d-%b-%Y') discharge FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept = 'Physiotherapy' and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_vid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);
?>


<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_Treatment = "SELECT id, dept, `section`, name, unit, descr, fee, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM fee WHERE  Active = 'Y' and dept = 'Physiotherapy' and Section = 'Treatment' ORDER BY name ASC";
$Treatment = mysql_query($query_Treatment, $swmisconn) or die(mysql_error());
$row_Treatment = mysql_fetch_assoc($Treatment);
$totalRows_Treatment = mysql_num_rows($Treatment);
?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_Supportive = "SELECT id, dept, `section`, name, unit, descr, fee, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM fee WHERE  Active = 'Y' and dept = 'Physiotherapy' and Section = 'Supportive' ORDER BY name ASC LIMIT 5";
$Supportive = mysql_query($query_Supportive, $swmisconn) or die(mysql_error());
$row_Supportive = mysql_fetch_assoc($Supportive);
$totalRows_Supportive = mysql_num_rows($Supportive);
?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_Supportive2 = "SELECT id, dept, `section`, name, unit, descr, fee, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM fee WHERE  Active = 'Y' and dept = 'Physiotherapy' and Section = 'Supportive' ORDER BY name ASC LIMIT 5, 6"; //get 10 records beginning with the one after 4
$Supportive2 = mysql_query($query_Supportive2, $swmisconn) or die(mysql_error());
$row_Supportive2 = mysql_fetch_assoc($Supportive2);
$totalRows_Supportive2 = mysql_num_rows($Supportive2);
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

mysql_select_db($database_swmisconn, $swmisconn);
$query_ptlist = "SELECT userid FROM users WHERE active = 'Y' and ptflag = 'Y' ORDER BY userid ASC";
$ptlist = mysql_query($query_ptlist, $swmisconn) or die(mysql_error());
$row_ptlist = mysql_fetch_assoc($ptlist);
$totalRows_ptlist = mysql_num_rows($ptlist);

mysql_select_db($database_swmisconn, $swmisconn);
$query_pt = "SELECT ptflag FROM users WHERE active = 'Y' and userid = '".$_SESSION['user']."' ORDER BY userid ASC";
$pt = mysql_query($query_pt, $swmisconn) or die(mysql_error());
$row_pt = mysql_fetch_assoc($pt);
$totalRows_pt = mysql_num_rows($pt);
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
      <table>
        <tr>
          <td rowspan="2" bgcolor="#DCDCDC"><div align="center" class="BlackBold_16">Ordered</div></td>
          <td rowspan="2" bgcolor="#DCDCDC">

<?php//     $pageGoTo = "PatPtUnDischarge.php";
//      if (isset($_SERVER['QUERY_STRING'])) {
//    $pageGoTo .= (strpos($pageGoTo, '?')) ? "&" : "?";
//    $pageGoTo .= $_SERVER['QUERY_STRING']; 
//			}
?>          
              <div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></div>
              <div align="center">&nbsp;</div>
              <div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatNotesViewPSR.php&notetype=Physiotherapy">Notes</a></div>
              <div align="center">&nbsp;</div>
<?php if($row_pt['ptflag']=='Y'){ ?> <!--if this is a PT user -->
							<div align="center">Discharge Date: <?php echo $row_ordered['discharge']; ?></div>
              <div align="center"><a href="PatPtUnDischarge.php?<?php echo $_SERVER['QUERY_STRING'] ?>">Remove Discharge</a></div>
<?php } ?>
          </td>
		  <td colspan = "6">
		  	<table><tr>
          <td nowrap="nowrap" align="center" class="BlueBold_1414">Order Physiotherapy </td>
          </tr>
		  <tr>
		  	<td>Referral Order Comment:</td>
		  	<td colspan="3" bgcolor="#CCFFFF">Order ID: <?php echo $row_ordered['id']; ?>: <?php echo $row_ordered['comments']; ?></td>
          </tr>
		  <tr>
		  <td nowrap="nowrap">Urg:
            <select name="urgency" id="urgency">
              <option value="Routine">Routine</option>
              <option value="Scheduled">Scheduled</option>
              <option value="ASAP">ASAP</option>
              <option value="STAT">STAT</option>
            </select> 
            <input type="hidden" name="status" value="Ordered"/></td>

<?php if($row_pt['ptflag']=='Y'){ ?> <!--if this is a PT user, display PT dept orders-->
		  <td nowrap="nowrap">P.T.:<select name="doctor">
		    <option value="Select">Select</option>
		    <?php
do {  
?>
		    <option value="<?php echo $row_ptlist['userid']?>"<?php if (!(strcmp($row_ptlist['userid'], $_SESSION['user']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ptlist['userid']?></option>
		    <?php
} while ($row_ptlist = mysql_fetch_assoc($ptlist));
  $rows = mysql_num_rows($ptlist);
  if($rows > 0) {
      mysql_data_seek($ptlist, 0);
	  $row_ptlist = mysql_fetch_assoc($ptlist);
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
			  </select>      
		</td>
		<td nowrap="nowrap">Rate Reason:
				<select name="ratereason">
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
              </select>		</td>

<?php } else {?>
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
			  <option value="0">None</option>
			  </select>      
		</td>
		<td nowrap="nowrap">Rate Reason:
				<select name="ratereason">
				<option value="103">None</option>
              </select>		</td>
<?php } ?>
          <td nowrap="nowrap"><input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
          <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
          <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
          <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
          <input name="qrystr" type="hidden" id="qrystr" value="<?php echo $_SERVER['QUERY_STRING']; ?>" />
		  <input type="hidden" name="MM_insert" value="formplv1" />		  </td>
		  </tr>
		  </table>
		  </td>	
        </tr>
        <tr>
          <td colspan = "3" nowrap="nowrap">Order Comments: 
            <input name="comments" type="text" id="comments" size="80" />
 			<?php if(allow(49,3) == 1) { ?>
				  <input type="submit" name="formSubmit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
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
			  	<?php if (!empty($row_ordered['id']) and empty($row_ordered['amtpaid']) and $row_pt['ptflag']=='Y' and allow(49,4) == 1) {?>
					<td class="BlackBold_11" nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['id'] ?>">Del</a></td>
				<?php } else {?>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
				<?php } ?>
                    <td nowrap="nowrap" class="BlackBold_11" title="VID: <?php echo $row_ordered['visitid']; ?> "><?php echo $row_ordered['entrydt']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11" title="Doctor: <?php echo $row_ordered['doctor']; ?>"><div align="center"><?php echo $row_ordered['id']; ?></div></td>
                <td nowrap="NOWRAP" class="BlackBold_11" title="<?php echo $row_ordered['descr']; ?>"><?php echo $row_ordered['name']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['urg']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><?php echo $row_ordered['status']; ?></td>
                <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtdue']; ?></div></td>
                <td nowrap="nowrap" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtpaid']; ?></div></td>
              </tr>
              <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
            </table>
          </div>		  </td>


<?php if($row_pt['ptflag']=='Y'){?> <!--if this is a PT user, display PT dept orders-->
          <td valign="top" class="subtitlebk"><div align="center">
            <table>
                <tr>
                  <td><div align="center">Treatment (Rx)</div></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td align="right" ><input type="checkbox" name="laborder[]2" value="<?php echo $row_Treatment['id']; ?>" /></td>
                    <td nowrap="nowrap" class="BlackBold_11" title="<?php echo $row_Treatment['descr']; ?>"><?php echo $row_Treatment['name']; ?></td>
                    <td class="BlackBold_11"><?php echo $row_Treatment['fee']; ?></td>
                  </tr>
                  <?php } while ($row_Treatment = mysql_fetch_assoc($Treatment)); ?>
              </table>
          </div>
		  </td>
          <td valign="top" class="subtitlebk"><div align="center">
            <table>
                <tr>
                  <td><div align="center">Supportive Devices</div></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td align="right"><input type="checkbox" name="laborder[]" value="<?php echo $row_Supportive['id']; ?>" /></td>
                    <td class="BlackBold_11" nowrap="nowrap" title="<?php echo $row_Supportive['descr']; ?>"><?php echo $row_Supportive['name']; ?></td>
                    <td class="BlackBold_11"><?php echo $row_Supportive['fee']; ?></td>
                  </tr>
                  <?php } while ($row_Supportive = mysql_fetch_assoc($Supportive)); ?>
              </table>
          </div>
		  </td>
		  <td valign="top" class="subtitlebk"><div align="center">
             <table>
                <tr>
                  <td><div align="center">Supportive Devices</div></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td align="right"><input type="checkbox" name="laborder[]" value="<?php echo $row_Supportive2['id']; ?>" /></td>
                    <td class="BlackBold_11" nowrap="nowrap" title="<?php echo $row_Supportive2['descr']; ?>"><?php echo $row_Supportive2['name']; ?></td>
                    <td class="BlackBold_11"><?php echo $row_Supportive2['fee']; ?></td>
                  </tr>
                  <?php } while ($row_Supportive2 = mysql_fetch_assoc($Supportive2)); ?>
              </table>
          </div>
		  </td>

<?php }  else { ?> <!-- if not a PT user-->
          <td valign="top" class="subtitlebk"><div align="center">
            <table>
                <tr>
                  <td colspan="3"><div align="center">Order Physical Therapy</div></td>
				</tr>
				<tr>
                    <td ><input type="checkbox" name="laborder[]" value="182" /></td><!--176 = fee id of PT Order-->  
                    <td nowrap="nowrap" class="BlackBold_11" title="Doctor refers patient for PT">PT Referral</td>
                    <td class="BlackBold_11">0</td>
                 </tr>
 
			</table>
			</div></td>
		  
<?php } ?> 
       </tr>
      </table>
        </form>    </td>
  </tr>
</table>
<?php $notetype = 'PhysioTherapy'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Patient/PatNotesView.php') ?>   <!--'.$row_ordered['visitid'].'   ?vid=1432&notetype=PhysioTherapy-->

<script  type="text/javascript">
 var frmvalidator = new Validator("formplv1");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>

</body>
</html>
<?php
mysql_free_result($Treatment);

mysql_free_result($Supportive);
mysql_free_result($Supportive2);

mysql_free_result($reason);

mysql_free_result($doctor);
?>
