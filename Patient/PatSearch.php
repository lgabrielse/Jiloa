<?php ob_start();?>
<?php  $pt = "Patient Search"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 

$msg = "";
if(!isset($_SESSION['xyz'])) {
   $_SESSION['xyz'] = "";
}
$colname_medrecnum = "-1";
if (isset($_POST['medrecnum']) && ($_POST["MM_update"] == "formps1")) {
  $colname_medrecnum = (get_magic_quotes_gpc()) ? $_POST['medrecnum'] : addslashes($_POST['medrecnum']);
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_medrecnum = sprintf("SELECT medrecnum FROM patperm WHERE medrecnum = %s", $colname_medrecnum);
	$medrecnum = mysql_query($query_medrecnum, $swmisconn) or die(mysql_error());
	$row_medrecnum = mysql_fetch_assoc($medrecnum);
	$totalRows_medrecnum = mysql_num_rows($medrecnum);
	
	if ($totalRows_medrecnum > 0) {
	  $_SESSION['mrn'] = $row_medrecnum['medrecnum'];
	  $MrnGoTo = "PatShow1.php?mrn=".$row_medrecnum['medrecnum'];
	  mysql_free_result($medrecnum);
	  header(sprintf("Location: %s", $MrnGoTo));
	}
	else {
	$msg = "No Patient with Medical Record Number  " . $_POST['medrecnum'];
	  $MrnGoTo = "PatSearch.php?msg=".$msg;
	  mysql_free_result($medrecnum);
	  header(sprintf("Location: %s", $MrnGoTo));
	}
}
	$xyz = "";
	$colname_search1 = "-1";
  if (isset($_POST['lastname'])  && strlen($_POST['lastname'])>1 && ($_POST["MM_update"] == "formps2")) {
	$colname_search1 = (get_magic_quotes_gpc()) ? $_POST['lastname'] : addslashes($_POST['lastname']);
	$_SESSION['xyz'] = $colname_search1;
	$xyz = $colname_search1;
  }

mysql_select_db($database_swmisconn, $swmisconn);
$query_search = "SELECT `patperm`.`medrecnum`, `patperm`.`lastname`, `patperm`.`firstname`, `patperm`.`othername`, `patperm`.`gender`, `patperm`.`dob` FROM patperm WHERE (concat(`patperm`.`lastname`, ' ',`patperm`.`firstname`, ' ', IFNULL(othername,' ')) like '%".$colname_search1."%') AND `patperm`.`active` = 'Y'";
$search = mysql_query($query_search, $swmisconn) or die(mysql_error());
$row_search = mysql_fetch_assoc($search);
$totalRows_search = mysql_num_rows($search);

	if ($totalRows_search == 0 && (strlen($xyz)) > 0) {
	$msg = "No Patient with  ". $_SESSION['xyz'] . "  in a name" ;
	  $MrnGoTo = "PatSearch.php?msg=".$msg;
	  mysql_free_result($search);
	  header(sprintf("Location: %s", $MrnGoTo));
	}
//	else {
//	$_GET['msg'] = "";
//	}

$colname_vstatus = "%";
if (isset($_POST['vstatus'])  && strlen($_POST['vstatus'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_vstatus = (get_magic_quotes_gpc()) ? $_POST['vstatus'] : addslashes($_POST['vstatus']);
}

$colname_pat_type = "%";
if (isset($_POST['pat_type'])  && strlen($_POST['pat_type'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_pat_type = (get_magic_quotes_gpc()) ? $_POST['pat_type'] : addslashes($_POST['pat_type']);
}

$colname_location = "%";
if (isset($_POST['location'])  && strlen($_POST['location'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_location = (get_magic_quotes_gpc()) ? $_POST['location'] : addslashes($_POST['location']);
}

$colname_daysback = "1";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 && ($_POST["MM_update"] == "form3")) {
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}
$bed = '';
mysql_select_db($database_swmisconn, $swmisconn);  //DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y Years %m Months %d Days') AS age
$query_currPat = "select p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, p.entrydt, p.entryby, v.id vid, v.visitdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge, v.entrydt ventrydt, v.entryby ventryby FROM patvisit v join patperm p ON p.medrecnum = v.medrecnum WHERE discharge is null and v.status like '%".$colname_vstatus."%' and v.pat_type like '%".$colname_pat_type."%' and v.location like '".$colname_location."%' and visitdate >= SYSDATE() - INTERVAL " .($colname_daysback)." DAY Order BY v.id";
$currPat = mysql_query($query_currPat, $swmisconn) or die(mysql_error());
$row_currPat = mysql_fetch_assoc($currPat);
$totalRows_currPat = mysql_num_rows($currPat);  //CASE WHEN o.rate > 0 AND o.item IS NULL THEN f.fee*(o.rate/100) ELSE o.ofee END
?>
<?php 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_NoVisit = "select p.medrecnum, p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, p.entryby, p.entrydt, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, v.id from patperm p left outer join patvisit v on p.medrecnum = v.medrecnum WHERE v.id is null and  p.entrydt >= SYSDATE() - INTERVAL " .($colname_daysback + 1)." DAY";
	$NoVisit = mysql_query($query_NoVisit, $swmisconn) or die(mysql_error());
	$row_NoVisit = mysql_fetch_assoc($NoVisit);
	$totalRows_NoVisit = mysql_num_rows($NoVisit);
?>
<?php
	$pat_type = '%';
   if(isset($_POST['pat_type'])) {
	   $pat_type = $_POST['pat_type'] ;
		}
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_loc = "SELECT section, name FROM fee WHERE active='Y' and dept = 'records' and section NOT IN('Registration', 'ReturnLoc') and section like '". $pat_type."' ORDER BY section"; 
	$loc = mysql_query($query_loc, $swmisconn) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
?>
  
<?php //echo $database_swmisconn; ?> <?php // echo $swmisconn;?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>
<body  onLoad="document.forms.formps1.medrecnum.focus()">
	<table align="center">
      <tr>
        <td class="subtitlebk">PATIENT SEARCH <?php echo $_SESSION['sysconn']; ?><?php echo $_SESSION['sysdata']; ?></td>
      </tr>
      <tr>
        <td valign="top">
		<table align="left" >
          <?php if(isset($_GET['msg']) && strlen($_GET['msg']) > 1) { ?>
          <tr>
            <td class="RedBold_14"><?php echo $_GET['msg']; ?></td>
          </tr>
          <?php }  ?>
          <tr>
            <td><form method="post" name="formps1" id="formps1" enctype="multipart/form-data">
              <table width="200px">
                 <tr>
                   <td align="center" title="Numbers Only!"><input name="medrecnum" type="text" id="medrecnum" autocomplete="off" /></td>
                   <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="GO" /></td>
                  <td nowrap="nowrap">Enter Patient's Medical Record Number </td>
                </tr>
              </table>
              <input type="hidden" name="MM_update" value="formps1" />
            </form></td>
          </tr>
          <tr>
            <td><form method="post" name="formps2" id="formps2" enctype="multipart/form-data">
              <table width="200px" align="center">
                <tr>
                  <td align="center" title="Allows MINIMUM OF 3 alphabetic, numeric, or space characters "><input name="lastname" type="text" id="lastname" autocomplete="off" value="<?php echo $_SESSION['xyz'] ?>" /></td>
                  <td align="center"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Search" /></td>
                  <td colspan="2" align="center" nowrap="nowrap">Enter 3 or more Characters of a Patient Name </td>
               </tr>
              </table>
              <input type="hidden" name="MM_update" value="formps2" />
            </form></td>
          </tr>
         </table>
		</td>
      </tr>

<?php if($totalRows_search > 0) { ?>
      <tr>
        <td valign="top"><form id="form2" name="form2" method="post" action="">
          <table bgcolor="F5F5F5" border="1">
            <tr>
              <td><?php echo $colname_search1 ; ?> </td>
              <td class="subtitlebk"><div align="center">Last Name </div></td>
              <td class="subtitlebk"><div align="center">First Name </div></td>
              <td class="subtitlebk"><div align="center">Other Name </div></td>
              <td class="subtitlebk"><div align="center">Gender</div></td>
              <td class="subtitlebk"><div align="center">Age</div></td>
              <td class="subtitlebk"><div align="center">DOB</div></td>
            </tr>
            <?php do { ?>
            <tr>
              <td class="navLink"><a href="PatShow1.php?mrn=<?php echo $row_search['medrecnum']; ?>"><?php echo $row_search['medrecnum']; ?></a></td>
              <td bgcolor="#FFFFFF" class="sidebar"><a href="PatShow1.php?mrn=<?php echo $row_search['medrecnum']; ?>"><?php echo $row_search['lastname']; ?></a></td>
              <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['firstname']; ?></td>
              <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['othername']; ?></td>
              <td bgcolor="#FFFFFF" class="sidebar"><?php echo $row_search['gender']; ?></td>
              <?php  //calculate Age
	$patage = "";
	$patdob = "";
	if (strtotime($row_search['dob'])) {
		$c= date('Y');
		$y= date('Y',strtotime($row_search['dob']));
		$patage = $c-$y;
	//format date of birth
		$datetime = strtotime($row_search['dob']);
		$patdob = date("m/d/y", $datetime);
} ?>
              <td bgcolor="#FFFFFF" class="sidebar"><?php echo $patage; ?></td>
              <td bgcolor="#FFFFFF" class="sidebar"><?php echo $patdob; ?></td>
            </tr>
            <?php } while ($row_search = mysql_fetch_assoc($search)); ?>
          </table>
        </form></td>
      </tr>
<?php }?>
    </table>
	
	
<table width="70%" align="center">
  <tr>
    <td valign="top">
	 <table align="center">
      <tr>
        <td colspan="7"><div align="center" class="subtitlebl"> CURRENT PATIENTS &nbsp;&nbsp;&nbsp;&nbsp;(Not Discharged)</div></td>
      </tr>
      <tr>
	    <form id="form3" name="form3" method="post" action="PatSearch.php">
          <td nowrap="nowrap"><div align="right">VisitStatus:</div></td>
          <td>&nbsp;</td>

          <td nowrap="nowrap"><div align="right">Patient Type:</div></td>
          <td>
             <select name="pat_type" id="pat_type" onChange="document.form3.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>All</option>
<option value="OutPatient" <?php if (!(strcmp("OutPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>OutPatient</option>
              <option value="InPatient" <?php if (!(strcmp("InPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>InPatient</option>
              <option value="Antenatal" <?php if (!(strcmp("Antenatal", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>Antenatal</option>
          </select>
          </td>
          <td><div align="right">Location:</div></td>
          <td><select name="location" id="location" onChange="document.form3.submit();">
                 <option value="%">ALL</option>
              <?php do {  ?>
      					<option value="<?php echo $row_loc['name']?>"<?php if (!(strcmp($row_loc['name'], $colname_location))) {echo "selected=\"selected\"";} ?>><?php echo $row_loc['section']?> - <?php echo $row_loc['name']?></option>
      				<?php }
							 while ($row_loc = mysql_fetch_assoc($loc));
								$rows = mysql_num_rows($loc);
								if($rows > 0) {
										mysql_data_seek($loc, 0);
									$row_loc = mysql_fetch_assoc($loc);
								} ?>
				    </select>
        </td>
        <td>Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.form3.submit();">
            <!--onChange="document.form3.submit();"-->
            <option value="0" <?php if (!(strcmp(0, $colname_daysback))) {echo "selected=\"selected\"";} ?>>0</option>
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
            <option value="360" <?php if (!(strcmp(360, $colname_daysback))) {echo "selected=\"selected\"";} ?>>360</option>
            <option value="720" <?php if (!(strcmp(720, $colname_daysback))) {echo "selected=\"selected\"";} ?>>720</option>
            <option value="1080" <?php if (!(strcmp(1080, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1080</option>
          </select>
          <input type="hidden" name="MM_update" value="form3" />		 </td>
        <td>&nbsp;<?php echo $colname_pat_type ?><!--<input name="Input" type="submit" value="Submit" />--></td>
        </form>
      </tr>
    </table>
        <table align="center">
          <tr>
            <td class="BlackBold_14"><div align="center">Visit#</div></td>
            <td class="BlackBold_14"><div align="center">Urg</div></td>
            <td class="BlackBold_14">V-Stat</td>
            <td class="BlackBold_14"><div align="center">MRN</div></td>
            <td class="BlackBold_14"><div align="center">LAST NAME </div></td>
            <td class="BlackBold_14"><div align="center">SEX</div></td>
            <td class="BlackBold_14"><div align="center">ETHNICITY</div></td>
            <td class="BlackBold_14"><div align="center">AGE</div></td>
            <td nowrap="nowrap" class="BlackBold_14"><div align="center">VISIT DATE </div></td>
            <td class="BlackBold_14"><div align="center">PAT. TYPE </div></td>
            <td class="BlackBold_14"><div align="center">LOCATION</div></td>
            <td class="BlackBold_14"><div align="center">BED</div></td>
          </tr>
          <?php 
		  if ($totalRows_currPat > 0){
		  	do { ?>
          <tr>
            <td class="BlackBold_12" title="Entry by:  <?php echo $row_currPat['ventryby']; ?>&#10;Entry date: <?php echo $row_currPat['ventrydt']; ?>&#10;Visit reason: <?php echo $row_currPat['visitreason']; ?>"><?php echo $row_currPat['vid']; ?></td>
            <td class="BlackBold_12" title="Urgency: <?php echo $row_currPat['urgency']; ?>"><?php echo $row_currPat['urg']; ?></td>
            <td class="BlackBold_12" title="Urgency: <?php echo $row_currPat['urgency']; ?>"><?php echo $row_currPat['status']; ?></td>
            <td nowrap="nowrap" class="BlackBold_12" title="Entry by:  <?php echo $row_currPat['entryby']; ?>&#10;Entry date: <?php echo $row_currPat['entrydt']; ?>"><div align="left"><a href="PatShow1.php?mrn=<?php echo $row_currPat['medrecnum']; ?>"><?php echo $row_currPat['medrecnum']; ?></a></div></td>
            <td nowrap="nowrap" class="BlackBold_12"><a href="PatShow1.php?mrn=<?php echo $row_currPat['medrecnum']; ?>&vid=<?php echo $row_currPat['vid']; ?>"><?php echo $row_currPat['lastname']; ?>, <?php echo $row_currPat['firstname']; ?> (<?php echo $row_currPat['othername']; ?>)</a></td>
            <td class="BlackBold_12"><?php echo $row_currPat['gender']; ?></td>
            <td class="BlackBold_12"><?php echo $row_currPat['ethnicgroup']; ?></td>
            <td class="BlackBold_12"><?php echo $row_currPat['age']; ?></td>
            <td nowrap="nowrap" class="BlackBold_12"><?php echo $row_currPat['visitdate']; ?></td>
            <td class="BlackBold_12"><?php echo $row_currPat['pat_type']; ?></td>
            <td class="BlackBold_12"><?php echo $row_currPat['location']; ?></td>
<?php 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_bed = "SELECT bed FROM patbed b where b.medrecnum ='".$row_currPat['medrecnum']."'";
	$bed = mysql_query($query_bed, $swmisconn) or die(mysql_error());
	$row_bed = mysql_fetch_assoc($bed);
	$totalRows_bed = mysql_num_rows($bed);
?>
						<td class="BlackBold_12"><?php echo $row_bed['bed']; ?></td>



          </tr>
          <?php } while ($row_currPat = mysql_fetch_assoc($currPat));
		  } ?>
		  
          <?php 
		  if ($colname_pat_type == "%" AND $totalRows_NoVisit > 0){
		   do { ?>
          <tr>
            <td class="BlackBold_12">&nbsp;</td>
            <td class="BlackBold_12">&nbsp;</td>
            <td class="BlackBold_12">&nbsp;</td>
            <td nowrap="nowrap" class="BlackBold_12" title="Entry by:  <?php echo $row_NoVisit['entryby']; ?>&#10;Entry date: <?php echo $row_NoVisit['entrydt']; ?>"><div align="left"><a href="PatShow1.php?mrn=<?php echo $row_NoVisit['medrecnum']; ?>"><?php echo $row_NoVisit['medrecnum']; ?></a></div></td>
            <td nowrap="nowrap" class="BlackBold_12"><a href="PatShow1.php?mrn=<?php echo $row_NoVisit['medrecnum']; ?>"><?php echo $row_NoVisit['lastname']; ?>, <?php echo $row_NoVisit['firstname']; ?> (<?php echo $row_NoVisit['othername']; ?>)</a></td>
            <td class="BlackBold_12"><?php echo $row_NoVisit['gender']; ?></td>
            <td class="BlackBold_12"><?php echo $row_NoVisit['ethnicgroup']; ?></td>
            <td class="BlackBold_12"><?php echo $row_NoVisit['age']; ?></td>
            <td class="BlackBold_12">No Visit</td>
            <td class="BlackBold_12">&nbsp;</td>
            <td class="BlackBold_12">&nbsp;</td>
          </tr>
          <?php } while ($row_NoVisit = mysql_fetch_assoc($NoVisit));
		 } ?>
      </table></td>
    <td valign="top">
</td>
  </tr>
</table>
<script  type="text/javascript">
 var frmvalidator = new Validator("formps1");
frmvalidator.addValidation("medrecnum","num","Numbers Only");

var frmvalidator = new Validator("formps2");
frmvalidator.addValidation("lastname","alnum_s","Allows only alphabetic, numeric and space characters ");
frmvalidator.addValidation("lastname","minlen=3","Allows MINIMUM OF 3 alphabetic, numeric, or space characters ");


</script>

<p>&nbsp;</p>
</body>
</html>

<?php
ob_end_flush();

mysql_free_result($NoVisit);

mysql_free_result($currPat);

?>