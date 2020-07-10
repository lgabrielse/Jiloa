<?php $pt = "Upfront Deposit"; ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>

<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
  $saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, rate, ratereason, amtdue, amtpaid, billstatus, status, urgency, doctor, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($_POST['amtdue'], "int"),
                       GetSQLValueString($_POST['amtpaid'], "int"),
                       GetSQLValueString($_POST['billstatus'], "text"),
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['Urgency'], "text"),
                       GetSQLValueString($_POST['Doctor'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

	mysql_select_db($database_swmisconn, $swmisconn);   // find the receipt number
	$query_maxid = "SELECT MAX(id) mxid from orders";  
	$maxid = mysql_query($query_maxid, $swmisconn) or die(mysql_error());
	$row_maxid = mysql_fetch_assoc($maxid);
	$totalRows_maxid = mysql_num_rows($maxid);
 
$insertGoTo = "CashPaid.php?Status=Deposit&selorder=".$row_maxid['mxid'];  
  header(sprintf("Parent.Location: %s", $insertGoTo));
  //$saved = "true";
}

?>

<?php

$colname_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
$colname_visitid = "-1";
if (isset($_GET['visitid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
}
  if($colname_visitid == 0){
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_PatVisit = "Select p.medrecnum, lastName, firstName, otherName, gender, dob, est from patPerm p where p.medrecnum = '".$colname_mrn."'";
	$PatVisit = mysql_query($query_PatVisit, $swmisconn) or die(mysql_error());
	$row_PatVisit = mysql_fetch_assoc($PatVisit);
	$totalRows_PatVisit = mysql_num_rows($PatVisit);
} else {
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_PatVisit = "Select p.medrecnum, lastName, firstName, otherName, gender, dob, est, pat_Type, location from patPerm p join patVisit v on p.medrecnum = v.medrecnum where v.id <> 0 and  p.medrecnum = '".$colname_mrn."' and v.id = '".$colname_visitid."'";
	$PatVisit = mysql_query($query_PatVisit, $swmisconn) or die(mysql_error());
	$row_PatVisit = mysql_fetch_assoc($PatVisit);
	$totalRows_PatVisit = mysql_num_rows($PatVisit);
  }
?>
<?php  //calculate patient Age and assign it to $patage variable
$patage = 0;
$patdob = 0;
$est = "";
if ($row_PatVisit['est'] === "Y") {
	$est = "*";
}
if (strtotime($row_PatVisit['dob'])) {
	$c= date('Y');
	$y= date('Y',strtotime($row_PatVisit['dob']));
	$patage = $c-$y;
//format date of birth
	$datetime = strtotime($row_PatVisit['dob']);
	$patdob = $est.date("d-M-Y", $datetime);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upfront Deposit</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<?php if($saved == "true") { ?>
<body onload="out()">
  <?php } else { ?>
	<body>
	<?php }?>
	
<div align="center" class="GreenBold_18">UPFRONT DEPOSIT</div>
<table width="40%" border="1" align="center" bgcolor="#BCFACC">
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <tr>
    <td align="right" nowrap="nowrap" class="BlackBold_14">MedRecNum = </td>
    <td><span class="BlackBold_14"><?php echo $row_PatVisit['medrecnum']; ?></span></td>
  </tr>
  <tr>
    <td class="BlackBold_14">Patient Name </td>
    <td nowrap="nowrap" bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $row_PatVisit['lastName']; ?>, <?php echo $row_PatVisit['firstName']; ?> (<?php echo $row_PatVisit['otherName']; ?>)</td>
  </tr>
  <tr>
    <td class="BlackBold_14">Age, (Gender) </td>
    <td bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $patage ?>, (<?php echo $row_PatVisit['gender']; ?>)</td>
  </tr>
<?php if($colname_visitid > 0){ ?>
  <tr>
    <td class="BlackBold_14">Location</td>
    <td nowrap="nowrap" bgcolor="#FAFAF0" class="GreenBold_14"><?php echo $row_PatVisit['pat_Type']; ?>-<?php echo $row_PatVisit['location']; ?></td>
  </tr>
<?php  } else { ?>
  <tr>
    <td class="BlackBold_14">Location</td>
    <td bgcolor="#FAFAF0" class="GreenBold_14">No Visit Yet Registration Only</td>
  </tr>
<?php  } ?>

  <tr>
    <td class="BlackBold_14">Deposit Amount </td>
    <td>
      	<input type="text" name="amtpaid" autocomplete="off"/>    </td>
  </tr>
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
		<input name="medrecnum" type="hidden" value="<?php echo $colname_mrn ?>" />
		<input name="visitid" type="hidden" value="<?php echo $colname_visitid ?>" />
		<input name="feeid" type="hidden" value="279" />
		<input name="rate" type="hidden" value="100" />
		<input name="ratereason" type="hidden" value="103" />	
		<input name="billstatus" type="hidden" value="Paid" />
		<input name="Status" type="hidden" value="ordered" />
		<input name="Urgency" type="hidden" value="Routine" />
		<input name="Doctor" type="hidden" value="NA" />
		<input name="amtdue" type="hidden" value="0" />
		<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
		<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />	</td>
    <td align="right"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
  </tr>
  <input type="hidden" name="MM_insert" value="form1">
  </form>
</table>

</body>
</html>
<?php
mysql_free_result($PatVisit);
?>
