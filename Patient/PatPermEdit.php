<?php ob_start(); ?>
<?php  $pt = "Add Patient"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php // include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php $_SESSION['today'] = date("Y-m-d");  // H:i:s ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if (isset($_POST['dob'])  AND strlen($_POST['dob'])>1) {
		$calcdob = $_POST['dob'];
		$est = "N";
			$_SESSION['age'] = $calcdob;
	}
	else {
		if (isset($_POST['age'])) {
//			$calcdob = "2013-12-11";
			$calcdob = Date('Y-m-d', strtotime("- ".$_POST['age']." years"));
			$est = "Y";
		}
	}
	
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formppe")) {
  $insertSQL = sprintf("UPDATE patperm SET hospital=%s, active=%s, ddate=%s, entrydt=%s, entryby=%s, lastname=%s, firstname=%s, othername=%s, gender=%s, ethnicgroup=%s, dob=%s, est=%s  WHERE medrecnum=%s",
                       GetSQLValueString($_POST['hospital'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['ddate'], "date"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['othername'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['ethnicgroup'], "text"),
                       GetSQLValueString($calcdob, "date"),
                       GetSQLValueString($est, "text"),
					   GetSQLValueString($_POST['medrecnum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PatShow1.php?mrn=".GetSQLValueString($_POST['medrecnum'], "int");
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_remove"])) && ($_POST["MM_remove"] == "formppe2")) {
  $removeSQL = sprintf("UPDATE patperm SET photofile = '' WHERE medrecnum=%s",
                     GetSQLValueString($_POST['medrecnum'], "int"));
					   
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($removeSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PatShow1.php?mrn=".GetSQLValueString($_POST['medrecnum'], "int");
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php 
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_reason = "Select id, list, name, seq from dropdownlist where list = 'Rate Reason' Order By seq";
		$reason = mysql_query($query_reason, $swmisconn) or die(mysql_error());
		$row_reason = mysql_fetch_assoc($reason);
		$totalRows_reason = mysql_num_rows($reason);
?>

<?php 	mysql_select_db($database_swmisconn, $swmisconn);
		$query_ethnicddl = "Select list, name, seq from dropdownlist where list = 'Ethnic Group' Order By seq";
		$ethnicddl = mysql_query($query_ethnicddl, $swmisconn) or die(mysql_error());
		$row_ethnicddl = mysql_fetch_assoc($ethnicddl);
		$totalRows_ethnicddl = mysql_num_rows($ethnicddl);

$colname_pats = "-1";
if (isset($_SESSION['mrn'])) {
  $colname_pats = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_pats = sprintf("SELECT medrecnum, hospital, active, ddate, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, dob, est, photofile FROM patperm WHERE medrecnum = %s", $colname_pats);
$pats = mysql_query($query_pats, $swmisconn) or die(mysql_error());
$row_pats = mysql_fetch_assoc($pats);
$totalRows_pats = mysql_num_rows($pats);

mysql_select_db($database_swmisconn, $swmisconn);
$query_ratereas = "SELECT id, medrecnum, visitid, feeid, rate, ratereason, amtpaid FROM orders Where visitid = 0 and  medrecnum = '".$_SESSION['mrn']."'";
$ratereas = mysql_query($query_ratereas, $swmisconn) or die(mysql_error());
$row_ratereas = mysql_fetch_assoc($ratereas);
$totalRows_ratereas = mysql_num_rows($ratereas);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>

<body onLoad="document.forms.form1.lastname.focus()">
<table width="100%" border="0" align="center">
  <tr>
     <td>
	    <table>
		  <caption align="top" class="subtitlebl">
			EDIT PATIENT PERMANENT DATA
		  </caption>
		  <tr>
			<td>
			<form name="formppe" method="POST" action="<?php echo $editFormAction; ?>">
			 <table width="100%" border="1" bgcolor="#F8FDCE">
		  <tr>
			<td class="sidebar">MRN, entry date, enteredby, <br />
			  hospital, and Active are<br />
			  added automatically </td>
			<td>Enter 1st character of name in upper case<br /> 
			  and remainder in lowercase...no spaces. </td>
			</tr>
		  <tr>
			<td class="subtitlebl" div align="right">Last Name:</td>
			<td><input name="lastname" type="text" id="lastname" autocomplete="off" value="<?php echo $row_pats['lastname']; ?>" /></td>
			</tr>
		  <tr>
			<td class="subtitlebl" div align="right">First Name:</td>
			<td><input name="firstname" type="text" id="firstname" autocomplete="off" value="<?php echo $row_pats['firstname']; ?>" /></td>
			</tr>
		  <tr>
			<td class="subtitlebl" align="right">Other Name:</td>
			<td><input name="othername" type="text" id="othername" autocomplete="off" value="<?php echo $row_pats['othername']; ?>" /></td>
			</tr>
		  <tr>
			<td class="subtitlebl" align="right">Gender (Sex):      </td>
			<td><select name="gender" id="gender" >
			  <option value="0">Select</option>
			  <option value="F" <?php if (!(strcmp("F", $row_pats['gender']))) {echo "selected=\"selected\"";} ?>>Female</option>
			  <option value="M" <?php if (!(strcmp("M", $row_pats['gender']))) {echo "selected=\"selected\"";} ?>>Male</option>
				</select></td>
			</tr>
		  <tr>
			<td class="subtitlebl" align="right">Ethnic Group:</td>
			<td><select name="ethnicgroup" id="ethnicgroup">
			<option value="0">Select</option>
			  <?php
		do {  
		?><option value="<?php echo $row_ethnicddl['name']?>"<?php if (!(strcmp($row_ethnicddl['name'], $row_pats['ethnicgroup']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ethnicddl['name']?></option>
			  <?php
		} while ($row_ethnicddl = mysql_fetch_assoc($ethnicddl));
		  $rows = mysql_num_rows($ethnicddl);
		  if($rows > 0) {
			  mysql_data_seek($ethnicddl, 0);
			  $row_ethnicddl = mysql_fetch_assoc($ethnicddl);
		  }
		?>
			</select></td>
			</tr>
		  <tr>
			<td class="subtitlebl" align="right" title="If DOB and AGE are blank, patient record will not be added&#10;DOB must have Year dash Month dash Day Format&#10;Age must be a number between 1 and 100 &#10; Newborns or children under 1 year must have DOB">Date of Birth:</td>
			<td nowrap="nowrap" title="If DOB and AGE are blank, patient record will not be added&#10;DOB must have Year dash Month dash Day Format&#10;Age must be a number between 1 and 100 &#10; Newborns or children under 1 year must have DOB"><input name="dob" type="text" id="dob" autocomplete="off" data-validation="date" data-validation-optional="true" value="<?php echo $row_pats['dob']; ?>" />
			  <span class="subtitlebl">      YYYY-MM-DD <br />
			  </span>
				 <Span>(enter age to calculate a dob)<br />
					 or Age</span> 
				<input name="age" type="text" size="3" maxlength="3" autocomplete="off" data-validation-allowing="range[1;100]" data-validation-optional="true" />&nbsp;&nbsp;&nbsp; 
				(Delete date  if age is entered.)  </td>
			</tr>
			
		  <tr>
		    <td title="Deceased Date must have Year dash Month dash Day" class="subtitlebl" align="right">Deceased Date: </td>
		    <td title="Deceased Date must have Year dash Month dash Day"><input type="text" name="ddate" autocomplete="off" data-validation="date" data-validation-optional="true" value="<?php echo $row_pats['ddate']; ?>" /><span class="subtitlebl">      YYYY-MM-DD</span></td>
		    </tr>
		  <tr>
			<td title="If Amt Paid has a value greater than 0, the payment rate cannot be edited">
			  <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_pats['medrecnum']; ?>" />
			  <input name="active" type="hidden" id="active" value="Y" />
			  <input name="hospital" type="hidden" id="hospital" value="Bethany" />
			  <input name="status" type="hidden" id="status" value="Registerd" />
			  <input name="urgency" type="hidden" id="urgency" value="Routine" />
			  <input name="comments" type="hidden" id="comments" value="none" />
			  <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
			  <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
			  <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>">Close </a>      (Amt Paid: Naira <?php echo $row_ratereas['amtpaid'] ?>  )</td>
			  
			<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="EDIT PATIENT" /></td>
			</tr>
		</table>
			<input type="hidden" name="MM_update" value="formppe">
			</form></td>
<script  type="text/javascript">
 var frmvalidator = new Validator("formppe");
 frmvalidator.EnableMsgsTogether();
 
 frmvalidator.addValidation("lastname","req","Please enter patient Last Name");
 frmvalidator.addValidation("lastname","maxlen=30", "Max length for LastName is 30");
 frmvalidator.addValidation("lastname","alnum", "alphabetic or numeric characters only - no spaces");

 frmvalidator.addValidation("firstname","req","Please enter patient First Name");
 frmvalidator.addValidation("firstname","maxlen=30", "Max length for FirstName is 30");
 frmvalidator.addValidation("firstname","alnum", "alphabetic or numeric characters only - no spaces");

 //frmvalidator.addValidation("othername","req","Please enter patient Other Name");
 frmvalidator.addValidation("othername","maxlen=30", "Max length for Other Name is 30");
 frmvalidator.addValidation("othername","alnum", "alphabetic or numeric characters only - no spaces");

 frmvalidator.addValidation("gender","dontselect=0", "Please Select Gender");
 frmvalidator.addValidation("ethnicgroup","dontselect=0", "Please Select Ethnic Group");

</script>
<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>

/* important to locate this script AFTER the closing form element, so form object is loaded in DOM before setup is called */
   $.validate({
		//modules : 'date, security'
    });</script>

<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>

/* important to locate this script AFTER the closing form element, so form object is loaded in DOM before setup is called */
    $.validate({
		//modules : 'date, security'
    });</script>


		  </tr>
        </table>
      </td>
	  <td valign="top">
	  	<table>
<?php if (!empty($row_pats['photofile'])) { // photofile ?>
		   <tr>
		      <td><img src="<?php echo "../../DATA_SWMIS/images/".$row_pats['photofile']; ?>" /></td>  <!--must be a URL address-->
		      <!-- Display PATIENT PERMANENT PHOTO APP  -->
		   </tr>
		   <tr>
		   		<td>
				<form name="formppe2" id="formppe2" method="post" enctype="multipart/form-data" action="">
			        <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_pats['medrecnum']; ?>" />
			        <input type="hidden" name="MM_remove" value="formppe2">
					<input type="submit" name="remove" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="REMOVE PHOTO"/>
				</form>			    </td>
		   </tr>
<?php } 
	  else {?> 

<?php $patpic = "PatPermPhoto.php"?>

		<td valign="top">
			<?php require_once($patpic); ?></td>

<?php }?> 
		</table>
	  </td>
   </tr>
</table>
</body>
</html>
<?php mysql_free_result($pats);

mysql_free_result($ratereas);?>

<?php ob_end_flush(); ?>
