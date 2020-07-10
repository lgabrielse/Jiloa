<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php include_once("../../functions/functions.php"); ?>
<?php
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
	}
if (isset($_GET['visitid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
	}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.rate, o.doctor, substr(o.status,1,7) status, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtdue, o.amtpaid, f.section, f.name, f.descr FROM orders o, fee f WHERE o.feeid = f.id and f.dept = 'Surgery' and Section = 'Anesthesia' and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_visitid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);
?>

 <!-- Anesthesia -->
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_Anesth = "SELECT id, dept, `section`, name, unit, descr, fee, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y %H:%i') entrydt FROM fee WHERE Active = 'Y' and dept = 'Surgery' and Section = 'Anesthesia' ORDER BY name ASC";
$Anesth = mysql_query($query_Anesth, $swmisconn) or die(mysql_error());
$row_Anesth = mysql_fetch_assoc($Anesth);
$totalRows_Anesth = mysql_num_rows($Anesth);
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
<script language="JavaScript" type="text/JavaScript">
//<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>

<body>
<table>
  <tr>
    <td>&nbsp;</td>
    <td><form id="formAnestPreop" name="formAnestPreop" method="post" action="checkbox-form.php" >
      <table>
        <tr>
          <td bgcolor="#DCDCDC"><div align="center" class="BlackBold_16">Ordered</div></td>
          <td bgcolor="#DCDCDC"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&sid=<?php echo $_GET['sid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestPreopEdit.php"><span style="background-color:#f81829">Close</span></a></div>

<!--          <td><div align="center"><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></div><br />
-->             <div align="center">&nbsp;</div>
           </td>
          <td><table>
              <tr>
                <td nowrap="nowrap"><h1 align="center" class="subtitlebl">Order Anesthesia</h1></td>
                <td nowrap="nowrap">Urg:
                  <select name="urgency" id="urgency">
                      <option value="Routine">Routine</option>
                      <option value="Scheduled">Scheduled</option>
                      <option value="ASAP">ASAP</option>
                      <option value="STAT">STAT</option>
                    </select>
                    <input type="hidden" name="status" value="Ordered"/></td>
                <td nowrap="nowrap">Doctor:
                  <select name="doctor">
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
                        </select>
                </p></td>
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
                    </select>
                </td>
                <td rowspan="2"><input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_GET['mrn']; ?>" />
                    <input name="visitid" type="hidden" id="visitid" value="<?php echo $_GET['visitid']; ?>" />
                    <input name="feeid" type="hidden" id="feeid" value="<?php echo $row_ordered['feeid']; ?>" />
                    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
                    <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                    <input name="qrystr" type="hidden" id="qrystr" value="<?php echo 'mrn='.$_GET['mrn'].'&vid='.$_GET['visitid'].'&sid='.$_GET['sid'].'&visit=PatVisitView.php&act=lab&pge=PatAnestPreopEdit.php' ?>" />
                    <input type="hidden" name="MM_insert" value="formAnestPreop" />
			<?php if(allow(51,3) == 1) { ?>
				  <input type="submit" name="formSubmit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
			<?php } else {?>
				<td nowrap="nowrap" class="BlackBold_11">Read Only</td>	
			<?php }?>
              </tr>
              <tr>
                <td colspan = "5" nowrap="nowrap">Order Comments:
                  <input name="comments" type="text" id="comments" size="80" /></td>
              </tr>
          </table>
		  </td>
        </tr>
        <tr>
          <td colspan="2" valign="top" bgcolor="#DCDCDC" class="subtitlebk" align="center">
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
                  <?php if (!empty($row_ordered['id']) and empty($row_ordered['amtpaid']) ) {  // and allow(51,4) == 1 ?>
                  <td class="BlackBold_11" nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_GET['mrn']; ?>&vid=<?php echo $_GET['visitid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php&ordchg=PatOrdersDelete.php&id=<?php echo $row_ordered['id'] ?>">Del</a></td>
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
				 </td>
				  <!-- minor -->
				  <td valign="top" class="subtitlebk" align="center">
					<table>
					  <tr>
						<td valign="top" >
				  
					  <table>
						<tr>
						  <td colspan="3" align="center">Aneshesia</td>
						</tr>
						<?php do { ?>
						<tr>
						  <td ><input type="radio" name="anestorder" value="<?php echo $row_Anesth['id']; ?>" /></td>
						  <td nowrap="nowrap" class="BlackBold_11" title="<?php echo $row_Anesth['descr']; ?>"><?php echo $row_Anesth['name']; ?></td>
						  <td class="BlackBold_11"><?php echo $row_Anesth['fee']; ?></td>
						</tr>
						<?php } while ($row_Anesth = mysql_fetch_assoc($Anesth)); ?>
					  </table>
				  </td>
				</tr>
			  </table>
          </td>
        </tr>
      </table>
    </form>    </td>
  </tr>
</table>

<script  type="text/javascript">
 var frmvalidator = new Validator("formAnestPreop");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>

</body>
</html>
<?php

mysql_free_result($Anesth);

mysql_free_result($reason);

mysql_free_result($doctor);
?>
