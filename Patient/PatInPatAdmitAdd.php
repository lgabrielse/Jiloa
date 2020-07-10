<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 
$morethanone = "N";

$colid_visit = "-1";
if (isset($_GET['vid'])) {
  $colid_visit = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
else {
 if(isset($_SESSION['vid'])) {
  $colid_visit = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']); 
 }
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_admit = "SELECT id, visitid, admitfrom, patstatus, admitto, provdiag, pastmedhist, currmeds, problist, admissionnotes, admitby, entryby, entrydt FROM ipadmit WHERE visitid = '".$colid_visit."'";
$admit = mysql_query($query_admit, $swmisconn) or die(mysql_error());
$row_admit = mysql_fetch_assoc($admit);
$totalRows_admit = mysql_num_rows($admit);

if($totalRows_admit > 0){
	$morethanone = "Y";
	}
?>

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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO ipadmit (visitid, admitfrom, patstatus, admitto, provdiag, pastmedhist, currmeds, problist, admissionnotes, admitby, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['admitfrom'], "text"),
                       GetSQLValueString($_POST['patstatus'], "text"),
                       GetSQLValueString($_POST['admitto'], "text"),
                       GetSQLValueString($_POST['provdiag'], "text"),
                       GetSQLValueString($_POST['pastmedhist'], "text"),
                       GetSQLValueString($_POST['currmeds'], "text"),
                       GetSQLValueString($_POST['problist'], "text"),
                       GetSQLValueString($_POST['admissionnotes'], "text"),
                       GetSQLValueString($_POST['admitby'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('&pge=PatInPatAdmitAdd.php','&pge=PatInPatAdmitView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}
?> 
<?php 
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
</head>

<body>
<div align="center" class="BlueBold_18">ADD InPatient Admission</div>

<?php if($morethanone == "Y"){?>
	<div> 
	  <div align="center" class="RedBold_24">Only One Admission form per visit.  Admission form already exists.</div>
	</div>
<?php } else{ ?>

<table width="80%" border="1"  bgcolor="#BCFACC">
  <form id="form1" name="form1" method="post" action="PatInPatAdmitAdd.php">
	  <tr>
		<td>Admitted From: </td>
		<td>
		  <select name="admitfrom" id="admitfrom">
			<option value="Clinic">Clinic</option>
			<option value="Labour Room">Labour Room</option>
			<option value="Emergency">Emergency</option>
		  </select>    </td>
		<td><div align="right">Patient Status: </div></td>
		<td><select name="patstatus" id="patstatus">
		  <option value="Stable">Stable</option>
		  <option value="Contageous">Contageous</option>
		  <option value="Critical">Critical</option>
		  <option value="Very Critical">Very Critical</option>
		</select>		</td>
		<td><div align="right">Admit Patient Into: </div></td>
		<td><select name="admitto" id="admitto">
          <option value="Amenity Ward">Amenity Ward</option>
          <option value="Labour Ward">Labour Ward</option>
          <option value="Male Ward">Male Ward</option>
        </select></td>
	  </tr>
	  <tr>
		<td>Provisional Diagnosis </td>
		<td colspan="5"><textarea name="provdiag" cols="100" rows="1" id="provdiag"></textarea></td>
	  </tr>
	  <tr>
		<td>Past Medical History </td>
		<td colspan="5"><textarea name="pastmedhist" cols="100" rows="1" id="pastmedhist"></textarea></td>
	  </tr>
	  <tr>
		<td>Current Medications </td>
		<td colspan="5"><textarea name="currmeds" cols="100" rows="1" id="currmeds"></textarea></td>
	  </tr>
	  <tr>
		<td>Problem List </td>
		<td colspan="5"><textarea name="problist" cols="100" rows="1" id="problist"></textarea></td>
	  </tr>
	  <tr>
		<td>Admission Notes </td>
		<td colspan="5"><textarea name="admissionnotes" cols="100" rows="1" id="admissionnotes"></textarea></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	    <td><div align="right">Entry By:        </div></td>
		<td><input name="entryby" type="text" id="entryby" value="<?php echo $_SESSION['user']; ?>" readonly="readonly" size="15" /></td>
		<td><div align="right">Admitted By: </div></td>
		<td><select name="admitby">
              <option value="Select">Select</option>
              <?php
	do {  
	?>
              <option value="<?php echo $row_doctor['userid']?>"><?php echo $row_doctor['userid']?></option>
              <?php
	} while ($row_doctor = mysql_fetch_assoc($doctor));
	  $rows = mysql_num_rows($doctor);
	  if($rows > 0) {
		  mysql_data_seek($doctor, 0);
		  $row_doctor = mysql_fetch_assoc($doctor);
	  }
	?>
</select>
		  <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
			<input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
            <input type="hidden" name="MM_insert" value="form1">
		
		<td><div align="right">
		  <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" />
	    </div></td>
	  </tr>
 </form>
</table>
<?php } ?>
</body>
</html>
