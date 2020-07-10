<?php require_once('../../Connections/swmisconn.php'); ?><?php  $pt = "PatPermMatch"; //PatPermMatch.php?> 
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
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
?>
<?php $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['dob']) && $_POST['dob'] > '1914-01-01')  || (isset($_POST['age']) && $_POST['age'] > 0)) {   //  DOB or AGE CHECK

  if (isset($_POST['dob'])  AND strlen($_POST['dob'])>1) {
		$calcdob = $_POST['dob'];
		$est = "N";
	}
	else {
		if (isset($_POST['age']) && $_POST['age'] > 0) {
//			$calcdob = "2013-12-11";
			$calcdob = Date('Y-m-d', strtotime("- ".$_POST['age']." years"));
			$est = "Y";
			}
		else {
	//		if (isset($_POST['age'])) {
				$calcdob = "0000-00-00";
	//			$calcdob = Date('Y-m-d', strtotime("- ".$_POST['age']." years"));
			$est = "Y";
			}
		}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO patperm (hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, dob, est) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hospital'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['othername'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['ethnicgroup'], "text"),
                       GetSQLValueString($calcdob, "date"),
                       GetSQLValueString($est, "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());


$colname_NewMRN = "-1";
if ((isset($_POST['lastname'])) && (isset($_POST['firstname']))) {
  $colname_NewMRN = (get_magic_quotes_gpc()) ? $_POST['lastname'] : addslashes($_POST['lastname']);
  $colname_NewMRN1 = (get_magic_quotes_gpc()) ? $_POST['firstname'] : addslashes($_POST['firstname']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_NewMRN = sprintf("SELECT MAX(medrecnum) medrecnum FROM patperm WHERE lastname = '%s' and firstname = '%s'", $colname_NewMRN, $colname_NewMRN1);
$NewMRN = mysql_query($query_NewMRN, $swmisconn) or die(mysql_error());
$row_NewMRN = mysql_fetch_assoc($NewMRN);
$totalRows_NewMRN = mysql_num_rows($NewMRN);

// Add billing
  $insertSQL = sprintf("INSERT INTO orders (medrecnum, visitid, feeid, rate, ratereason, status, urgency, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       $row_NewMRN['medrecnum'],
                       0,
                       19,
                       GetSQLValueString($_POST['RegFee'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

		  mysql_select_db($database_swmisconn, $swmisconn);
		  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

	//echo "Rate ".$_POST['RegFee']. "     ". "Reason ".$_POST['ratereason'];
    $insertGoTo = "PatShow1.php?mrn=".$row_NewMRN['medrecnum'];
	mysql_free_result($NewMRN);
    header(sprintf("Location: %s", $insertGoTo));
      } // if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formppa"))
    }  // DOB or Age check


?>





<?php 
$colname_lastname = "-1";
if ((isset($_GET['lastname'])) && (isset($_GET['firstname']))) {
  $colname_lastname = (get_magic_quotes_gpc()) ? $_GET['lastname'] : addslashes($_GET['lastname']);
  $colname_firstname = (get_magic_quotes_gpc()) ? $_GET['firstname'] : addslashes($_GET['firstname']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_dupchk = "SELECT medrecnum, lastname, firstname, othername, gender, ethnicgroup, dob, est, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age FROM patperm WHERE lastname = '".$colname_lastname."' and firstname = '". $colname_firstname."'";
$dupchk = mysql_query($query_dupchk, $swmisconn) or die(mysql_error());
$row_dupchk = mysql_fetch_assoc($dupchk);
$totalRows_dupchk = mysql_num_rows($dupchk);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p align="center" class="BlackBold_14">A patient with identical first and last name already exist in this system. <br />
If this patient is not one of the existing patients, click <em><strong>ADD</strong></em> to add this new patient.<br />
  If this patient is one of the existing patients listed in white area, clickon the<em><strong> Medrecnum</strong></em> of the patient. </p>
<table width="30%" border="1" align="center">
 <form name="form1" id="form1" action="PatSearch.php" method="get"> <tr>
    <td bgcolor="#FFFDDA">Patient:<span class="RedBold_18"><?php echo $_GET['lastname'].', '.$_GET['firstname']; ?></span></td>
    </tr>
  <tr>
    <td bgcolor="#FFFDDA" class="RedBold_16">Already exists in this system. </td>
    </tr>
 </form>
</table>


<table border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center" class="BlackBold_14"><div align="center">medrecnum</div></td>
    <td class="BlackBold_14"><div align="center">lastname</div></td>
    <td class="BlackBold_14"><div align="center">firstname</div></td>
    <td class="BlackBold_14"><div align="center">othername</div></td>
    <td class="BlackBold_14"><div align="center">gender</div></td>
    <td class="BlackBold_14"><div align="center">ethnicgroup </div></td>
    <td class="BlackBold_14"> <div align="center">dob </div></td>
    <td class="BlackBold_14"><div align="center">age </div></td>
    <td class="BlackBold_14">Est</td>
  </tr>
<form name= "form4" id="form4" action="PatPermMatch.php" method="post">
  <tr>
    <td align="center" bgcolor="#FFFDDA"><input name="submit" type="submit" value="ADD" /></td>
    <td bgcolor="#FFFDDA"><?php echo $_GET['lastname']; ?></td>
    <td bgcolor="#FFFDDA"><?php echo $_GET['firstname']; ?></td>
    <td bgcolor="#FFFDDA"><?php echo $_GET['othername']; ?></td>
    <td bgcolor="#FFFDDA"><div align="center"><?php echo $_GET['gender']; ?></div></td>
    <td bgcolor="#FFFDDA"><?php echo $_GET['ethnicgroup']; ?></td>
    <td bgcolor="#FFFDDA"><div align="center"><?php echo $_GET['dob']; ?></div></td>
<?php   if(isset($_GET['dob'])){
	$age = date_diff(date_create($_GET['dob']), date_create('now'))->y; ?>
    <td bgcolor="#FFFDDA"><div align="center"><?php echo $age; ?></div></td>
<?php  }
	else {
?>
    <td bgcolor="#FFFDDA"><div align="center"><?php echo $_GET['age']; ?></div></td>	
<?php } ?>
    <td bgcolor="#FFFDDA">&nbsp;</td>
    <input name="lastname" type="hidden" value="<?php echo $_GET['lastname']; ?>" />
	<input name="firstname" type="hidden" value="<?php echo $_GET['firstname']; ?>" />
	<input name="othername" type="hidden" value="<?php echo $_GET['othername']; ?>" />
	<input name="gender" type="hidden" value="<?php echo $_GET['gender']; ?>" />
	<input name="ethnicgroup" type="hidden" value="<?php echo $_GET['ethnicgroup']; ?>" />
	  <input name="dob" type="hidden" value="<?php echo $_GET['dob']; ?>" />
	  <input name="age" type="hidden" value="<?php echo $_GET['age']; ?>" />
	  <input name="RegFee" type="hidden" value="<?php echo $_GET['RegFee']; ?>" />
	  <input name="ratereason" type="hidden" value="<?php echo $_GET['ratereason']; ?>" />
	    <input name="active" type="hidden" id="active" value="Y" />
        <input name="hospital" type="hidden" id="hospital" value="Bethany" />
        <input name="status" type="hidden" id="status" value="Registerd" />
        <input name="urgency" type="hidden" id="urgency" value="Routine" />
        <input name="comments" type="hidden" id="comments" value="none" />
        <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" /></td>
        <input type="hidden" name="MM_insert" value="form4">

  </tr>
</form>

  <?php do { ?>
    <tr>
	  <td align="center" class="navLink"><a href="PatShow1.php?mrn=<?php echo $row_dupchk['medrecnum']; ?>"><?php echo $row_dupchk['medrecnum']; ?></a></td>
      <td bgcolor="#FFFFFF"><?php echo $row_dupchk['lastname']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_dupchk['firstname']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_dupchk['othername']; ?></td>
      <td bgcolor="#FFFFFF"><div align="center"><?php echo $row_dupchk['gender']; ?></div></td>
      <td bgcolor="#FFFFFF"><?php echo $row_dupchk['ethnicgroup']; ?></td>
      <td bgcolor="#FFFFFF"><div align="center"><?php echo $row_dupchk['dob']; ?></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><?php echo $row_dupchk['age']; ?></div></td>
      <td bgcolor="#FFFFFF"><div align="center"><?php echo $row_dupchk['est']; ?></div></td>
    </tr>
    <?php } while ($row_dupchk = mysql_fetch_assoc($dupchk)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($dupchk);
?>
