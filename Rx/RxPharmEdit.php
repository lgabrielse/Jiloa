<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //session_start()?>

<?php
if(!function_exists("GetSQLValueString")) {
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
}
  $saved = "";

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
  if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "formrx2")) {

	if($_POST['pat_type'] == 'InPatient'){
  $updateSQL = sprintf("UPDATE orders SET urgency=%s, doctor=%s, quant=%s, ofee=%s, amtdue=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['quant'], "text"),
                       GetSQLValueString($_POST['ofee'], "int"),
                       GetSQLValueString($_POST['ofee'], "int"),  //ofee = amtdue
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

} else {
  $updateSQL = sprintf("UPDATE orders SET urgency=%s, doctor=%s, item=%s, nunits=%s, unit=%s, every=%s, evperiod=%s, fornum=%s, forperiod=%s, quant=%s, ofee=%s, amtdue=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['item'], "text"),
                       GetSQLValueString($_POST['nunits'], "int"),
                       GetSQLValueString($_POST['unit'], "text"),
                       GetSQLValueString($_POST['every'], "int"),
                       GetSQLValueString($_POST['evperiod'], "text"),
                       GetSQLValueString($_POST['fornum'], "int"),
                       GetSQLValueString($_POST['forperiod'], "text"),
                       GetSQLValueString($_POST['quant'], "text"),
                       GetSQLValueString($_POST['ofee'], "int"),
                       GetSQLValueString($_POST['ofee'], "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
}
  $saved = "true";
}
 
 ?>

<?php

mysql_select_db($database_swmisconn, $swmisconn);
$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
$totalRows_doctor = mysql_num_rows($doctor);

mysql_select_db($database_swmisconn, $swmisconn);
$query_other = "Select id, dept, section, name, descr from fee where dept in ('pharm', 'admin') Order By id";
$other = mysql_query($query_other, $swmisconn) or die(mysql_error());
$row_other = mysql_fetch_assoc($other);
$totalRows_other = mysql_num_rows($other);

?><!--query for an order record-->
<?php if(isset($_GET['ordid'])) {  
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_editdel = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.quant, o.ofee, o.rate, o.doctor, substr(o.status,1,7) status, o.billstatus, o.comments, o.urgency, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby,  o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee, v.pat_type FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept in ('pharm', 'admin') and o.id = '". $colname_ordid ."'";
$editdel = mysql_query($query_editdel, $swmisconn) or die(mysql_error());
$row_editdel = mysql_fetch_assoc($editdel);
$totalRows_editdel = mysql_num_rows($editdel);
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>

<body style="background-color:#FFFFFF;">

<table align="center">
  <tr>
   <td>
<form id="formrx2" name="formrx2" method="post" action="<?php echo $editFormAction; ?>"> 
    <table border="1"  bgcolor="#F8FDCE">
   
     <tr>
       <td colspan="2" nowrap="nowrap"><div align="center" class="BlueBold_20">EDIT DRUG </div></td>
     </tr>
     <tr>
		<td  nowrap="nowrap">Drugs - Patient Type: <?php echo $row_editdel['pat_type']; ?></td>
		<td colspan="2" nowrap="nowrap">Urg:
		  <select name="urgency" id="urgency">
		    <option value="Routine" <?php if (!(strcmp("Routine", $row_editdel['urgency']))) {echo "selected=\"selected\"";} ?>>Routine</option>
		    <option value="Scheduled" <?php if (!(strcmp("Scheduled", $row_editdel['urgency']))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
		    <option value="ASAP" <?php if (!(strcmp("ASAP", $row_editdel['urgency']))) {echo "selected=\"selected\"";} ?>>ASAP</option>
<option value="STAT" <?php if (!(strcmp("STAT", $row_editdel['urgency']))) {echo "selected=\"selected\"";} ?>>STAT</option>
			</select>
		  Doctor:
            <select name="doctor">
              <option value="Select" <?php if (!(strcmp("Select", $row_editdel['doctor']))) {echo "selected=\"selected\"";} ?>>Select</option>
              <?php
do {  
?>
              <option value="<?php echo $row_doctor['userid']?>"<?php if (!(strcmp($row_doctor['userid'], $row_editdel['doctor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doctor['userid']?></option>
              <?php
} while ($row_doctor = mysql_fetch_assoc($doctor));
  $rows = mysql_num_rows($doctor);
  if($rows > 0) {
      mysql_data_seek($doctor, 0);
	  $row_doctor = mysql_fetch_assoc($doctor);
  }
?>
            </select>
            Quant:
            <input name="quant" type="text" id="quant" value="<?php echo $row_editdel['quant']; ?>" size="4" />
            Naira:            
            <input name="ofee" type="text" value="<?php echo $row_editdel['ofee']; ?>" size="6" />
<?php if(isset($row_editdel['billstatus']) && $row_editdel['billstatus'] == 'paylater') {?>
			<span class="flagBlackonOrange">PL</span></td>
<?php }?>      
		</tr>
	    <tr>
	      <td colspan="2"><div align="center" class="BlueBold_1414">Prescription</div></td>
	    </tr>
<!--inpatient>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->		
<?php if($row_editdel['pat_type'] == "InPatient") {   //can't change these becasuse of imed dependency?>
	    <tr>
		  <td><input name="item" type="text" id="item" disabled="disabled" value="<?php echo $row_editdel['item']; ?>" size="30" maxlength="100" /></td>
		  <td nowrap="nowrap" bgcolor="#F8FDCE"><input name="nunits" type="text" id="nunits" disabled="disabled"  value="<?php echo $row_editdel['nunits']; ?>" size="3" maxlength="3" />
		    <?php if(strpos(' Tablet(s) Capsules(s)', $row_editdel['unit']) > 0) { ?>
		    <select name="unit" id="unit" disabled="disabled" > 
		      <option value="Tablet(s)" <?php if (!(strcmp("Tablet(s)", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>Tablet(s)</option>
		      <option value="Capsules(s)" <?php if (!(strcmp("Capsules(s)", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>Capsule(s)</option>
            </select>
			  <input name="every" type="hidden" id="every"  disabled="disabled" value="" />
			  <select name="evperiod" id="evperiod" disabled="disabled">
				<option value="OD" <?php if (!(strcmp("OD", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>OD</option>
				<option value="NOCTE" <?php if (!(strcmp("NOCTE", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>NOCTE</option>
				<option value="BD" <?php if (!(strcmp("BD", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>BD</option>
				<option value="TDS" <?php if (!(strcmp("TDS", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>TDS</option>
				<option value="QDS" <?php if (!(strcmp("QDS", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>QDS</option>
				<option value="PRN" <?php if (!(strcmp("PRN", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>PRN</option>
				<option value="STAT" <?php if (!(strcmp("STAT", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>STAT</option>
             </select>
<?php } else {?>
		    <select name="unit" id="unit" disabled="disabled"> 
		       <option value="ml" <?php if (!(strcmp("ml", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>ml</option>
		       <option value="ounces" <?php if (!(strcmp("ounces", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>ounces</option>
		       <option value="mg" <?php if (!(strcmp("mg", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>mg</option>
		       <option value="iU" <?php if (!(strcmp("iU", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>iU</option>
		       <option value="mU" <?php if (!(strcmp("mU", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>mU</option>
            </select>
		    Every
		  <input name="every" type="text" id="every" disabled="disabled" value="<?php echo $row_editdel['every']; ?>" size="3" />
		  <select name="evperiod" id="evperiod" disabled="disabled">
		    <option value="minutes" <?php if (!(strcmp("minutes", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>minutes</option>
			<option value="hour(s)" selected="selected" <?php if (!(strcmp("hour(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>hour(s)</option>
			<option value="day(s)" <?php if (!(strcmp("day(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>day(s)</option>
			<option value="week(s)" <?php if (!(strcmp("week(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>week(s)</option>
		  </select>
<?php } ?>
		    for
            <input name="fornum" type="text" id="fornum" disabled="disabled" value="<?php echo $row_editdel['fornum']; ?>" size="3" />
            <select name="forperiod" id="forperiod" disabled="disabled">
              <option value="minutes" <?php if (!(strcmp("minutes", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>minutes</option>
              <option value="hour(s)" <?php if (!(strcmp("hour(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>hour(s)</option>
              <option value="day(s)" selected="selected" <?php if (!(strcmp("day(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>day(s)</option>
              <option value="week(s)" <?php if (!(strcmp("week(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>week(s)</option>
            </select>			</td>
	  </tr>
<!-- OutPatient Antenatal, etc - i.e. Not InPatient-->
<?php } else {?>
	    <tr>
		  <td><input name="item" type="text" id="item" value="<?php echo $row_editdel['item']; ?>" size="30" maxlength="100" /></td>
		  <td nowrap="nowrap" bgcolor="#BCFACC"><input name="nunits" type="text" id="nunits" value="<?php echo $row_editdel['nunits']; ?>" size="3" maxlength="3" />
<?php if(strpos(' Tablet(s) Capsules(s)', $row_editdel['unit']) > 0) { ?>
		    <select name="unit" id="unit"> 
		      <option value="Tablet(s)" <?php if (!(strcmp("Tablet(s)", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>Tablet(s)</option>
		      <option value="Capsules(s)" <?php if (!(strcmp("Capsules(s)", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>Capsule(s)</option>
            </select>
			  <input name="every" type="hidden" id="every" value="" />
			  <select name="evperiod" id="evperiod">
				<option value="OD" <?php if (!(strcmp("OD", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>OD</option>
				<option value="NOCTE" <?php if (!(strcmp("NOCTE", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>NOCTE</option>
				<option value="BD" <?php if (!(strcmp("BD", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>BD</option>
				<option value="TDS" <?php if (!(strcmp("TDS", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>TDS</option>
				<option value="QDS" <?php if (!(strcmp("QDS", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>QDS</option>
				<option value="PRN" <?php if (!(strcmp("PRN", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>PRN</option>
				<option value="STAT" <?php if (!(strcmp("STAT", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>STAT</option>
             </select>
<?php } else {?>
		    <select name="unit" id="unit"> 
		       <option value="ml" <?php if (!(strcmp("ml", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>ml</option>
		       <option value="ounces" <?php if (!(strcmp("ounces", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>ounces</option>
		       <option value="mg" <?php if (!(strcmp("mg", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>mg</option>
		       <option value="iU" <?php if (!(strcmp("iU", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>iU</option>
		       <option value="mU" <?php if (!(strcmp("mU", $row_editdel['unit']))) {echo "selected=\"selected\"";} ?>>mU</option>
            </select>
		    Every
		  <input name="every" type="text" id="every" value="<?php echo $row_editdel['every']; ?>" size="3" />
		  <select name="evperiod" id="evperiod">
		    <option value="minutes" <?php if (!(strcmp("minutes", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>minutes</option>
			<option value="hour(s)" selected="selected" <?php if (!(strcmp("hour(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>hour(s)</option>
			<option value="day(s)" <?php if (!(strcmp("day(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>day(s)</option>
			<option value="week(s)" <?php if (!(strcmp("week(s)", $row_editdel['evperiod']))) {echo "selected=\"selected\"";} ?>>week(s)</option>
		  </select>
<?php } ?>
		    for
            <input name="fornum" type="text" id="fornum" value="<?php echo $row_editdel['fornum']; ?>" size="3" />
            <select name="forperiod" id="forperiod">
              <option value="minutes" <?php if (!(strcmp("minutes", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>minutes</option>
              <option value="hour(s)" <?php if (!(strcmp("hour(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>hour(s)</option>
              <option value="day(s)" selected="selected" <?php if (!(strcmp("day(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>day(s)</option>
              <option value="week(s)" <?php if (!(strcmp("week(s)", $row_editdel['forperiod']))) {echo "selected=\"selected\"";} ?>>week(s)</option>
            </select>			</td>
	  </tr>
<!--end outpatient-->
<?php } ?>
	  <tr>
		<td nowrap="nowrap">Order Comments:<br /></td>
		<td nowrap="nowrap"><textarea name="comments" cols="60" rows="3" id="comments"><?php echo $row_editdel['comments']; ?></textarea></td>
	  </tr>
	  <tr>
	    <td nowrap="nowrap">Order #: <?php echo $row_editdel['id']; ?></td>
	    <td nowrap="nowrap"><div align="right">
	      <input name="Submit" type="submit" id="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Order" />
	      </div></td>
	    </tr>
	  
				<input name="pat_type" type="hidden" id="pat_type" value="<?php echo $row_editdel['pat_type']; ?>" />
				<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
				<!--<input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />-->
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
				<input name="ordid" type="hidden" id="ordid" value="<?php echo $_GET['ordid']; ?>" />
				<input type="hidden" name="MM_Update" value="formrx2">
	</table>
   </form>
   </td>
  </tr>
</table>
<script  type="text/javascript">
// var frmvalidator = new Validator("formplv1");
//frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>



</body>
</html>
