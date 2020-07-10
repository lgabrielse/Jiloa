<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formedit")) {
  $insertSQL = sprintf("Update ipadmit SET admitfrom = %s, patstatus = %s, admitto = %s, provdiag = %s, pastmedhist = %s, currmeds = %s, problist = %s, admissionnotes = %s, admitby = %s, entryby = %s, entrydt = %s WHERE id = %s",
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
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['admitid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('&pge=PatInPatAdmitEdit.php','&pge=PatInPatAdmitView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}
?> 

<?php 

$colid_admitid = "-1";
if (isset($_GET['admitid'])) {
  $colid_admitid = (get_magic_quotes_gpc()) ? $_GET['admitid'] : addslashes($_GET['admitid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_admit = "SELECT id, visitid, admitfrom, patstatus, admitto, provdiag, pastmedhist, currmeds, problist, admissionnotes, admitby, entryby, entrydt FROM ipadmit WHERE id = '".$colid_admitid."'";
$admit = mysql_query($query_admit, $swmisconn) or die(mysql_error());
$row_admit = mysql_fetch_assoc($admit);
$totalRows_admit = mysql_num_rows($admit);
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

<table width="80%" border="1" bgcolor="#F8FDCE">
  <form id="formedit" name="formedit" method="post" action="<?php echo $editFormAction; ?>">
	<tr>
    <?php if(allow(35,4) == 1) {?>
		<td><input name="button622" class="btngradblu75" value="Delete" onclick="parent.location='PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatAdmitDelete.php&admitid=<?php echo $row_admit['id']?>'" /></td>
<?php } else { ?>
		<td>&nbsp;</td>
<?php }?>

		<td>&nbsp;</td>
		<td colspan="5"><div align="center" class="BlueBold_18">EDIT InPatient Admission</div></td>
		<td>&nbsp;</td>
	</tr>
	  <tr>
		<td>Admitted From: </td>
		<td>
		  <select name="admitfrom" id="admitfrom">
		    <option value="Clinic" <?php if (!(strcmp("Clinic", $row_admit['admitfrom']))) {echo "selected=\"selected\"";} ?>>Clinic</option>
		    <option value="Labour Room" <?php if (!(strcmp("Labour Room", $row_admit['admitfrom']))) {echo "selected=\"selected\"";} ?>>Labour Room</option>
		    <option value="Emergency" <?php if (!(strcmp("Emergency", $row_admit['admitfrom']))) {echo "selected=\"selected\"";} ?>>Emergency</option>
		  </select>    </td>
		<td><div align="right">Patient Status: </div></td>
		<td><select name="patstatus" id="patstatus">
		  <option value="Stable" <?php if (!(strcmp("Stable", $row_admit['patstatus']))) {echo "selected=\"selected\"";} ?>>Stable</option>
		  <option value="Contageous" <?php if (!(strcmp("Contageous", $row_admit['patstatus']))) {echo "selected=\"selected\"";} ?>>Contageous</option>
		  <option value="Critical" <?php if (!(strcmp("Critical", $row_doctor['userid']))) {echo "selected=\"selected\"";} ?>>Critical</option>
		  <option value="Very Critical" <?php if (!(strcmp("Very Critical", $row_admit['patstatus']))) {echo "selected=\"selected\"";} ?>>Very Critical</option>
		</select>		</td>
		<td><div align="right">Admit Patient Into: </div></td>
		<td><select name="admitto" id="admitto">
		  <option value="Amenity Ward" <?php if (!(strcmp("Amenity Ward", $row_admit['admitto']))) {echo "selected=\"selected\"";} ?>>Amenity Ward</option>
		  <option value="Labour Ward" <?php if (!(strcmp("Labour Ward", $row_admit['admitto']))) {echo "selected=\"selected\"";} ?>>Labour Ward</option>
		  <option value="Male Ward" <?php if (!(strcmp("Male Ward", $row_admit['admitto']))) {echo "selected=\"selected\"";} ?>>Male Ward</option>
        </select></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>Provisional Diagnosis </td>
		<td colspan="7"><textarea name="provdiag" cols="100" rows="1" id="provdiag"><?php echo $row_admit['provdiag']; ?></textarea></td>
	  </tr>
	  <tr>
		<td>Past Medical History </td>
		<td colspan="7"><textarea name="pastmedhist" cols="100" rows="1" id="pastmedhist"><?php echo $row_admit['pastmedhist']; ?></textarea></td>
	  </tr>
	  <tr>
		<td>Current Medications </td>
		<td colspan="7"><textarea name="currmeds" cols="100" rows="1" id="currmeds"><?php echo $row_admit['currmeds']; ?></textarea></td>
	  </tr>
	  <tr>
		<td>Problem List </td>
		<td colspan="7"><textarea name="problist" cols="100" rows="1" id="problist"><?php echo $row_admit['problist']; ?></textarea></td>
	  </tr>
	  <tr>
		<td>Admission Notes </td>
		<td colspan="7"><textarea name="admissionnotes" cols="100" rows="1" id="admissionnotes"><?php echo $row_admit['admissionnotes']; ?></textarea></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	    <td><div align="right">Entry By:        </div></td>
		<td><input name="entryby" type="text" id="entryby" value="<?php echo $_SESSION['user']; ?>" readonly="readonly" size="15" /></td>
		<td>&nbsp;</td>
		<td><div align="right">Admitted By: </div></td>
		<td><select name="admitby">
		  <option value="Select" <?php if (!(strcmp("Select", $row_admit['admitby']))) {echo "selected=\"selected\"";} ?>>Select</option>
		  <?php
do {  
?><option value="<?php echo $row_admit['admitby']?>"<?php if (!(strcmp($row_admit['admitby'], $row_doctor['userid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doctor['userid']?></option>
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
			<input name="admitid" type="hidden" id="admitid" value="<?php echo $row_admit['id']; ?>" />
            <input type="hidden" name="MM_insert" value="formedit">
		<td><div align="right">
		  <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" />
	    </div></td>
	  </tr>
 </form>
</table>

</body>
</html>
