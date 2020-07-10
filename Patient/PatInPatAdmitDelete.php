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

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM patnotes WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= str_replace('&pge=PatInPatAdmitDelete.php','&pge=PatInPatAdmitView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesDelete.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div align="center" class="BlueBold_18"></div>
<table width="800px" border="1" bgcolor="#FBD0D7">
  <form id="form1" name="form1" method="post" action="">
	  <tr>
	   <td>		<input name="button622" class="btngradblu75" value="Edit" onclick="parent.location='PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatAdmitEdit.php&admitid=<?php echo $row_admit['id']?>'" /></td>
		<td>&nbsp;</td>
	    <td colspan="5"><div align="center"><span class="BlueBold_18">Delete InPatient Admission</span></div></td>
	    <td>Visit ID: <?php echo $row_admit['visitid']; ?></td>
    </tr>
	  <tr>
		<td>Admitted From: </td>
		<td bgcolor="#FFFFFF"><?php echo $row_admit['admitfrom']; ?></td>
		<td><div align="right">Patient Status:</div></td>
		<td bgcolor="#FFFFFF"><?php echo $row_admit['patstatus']; ?></td>
		<td><div align="right">Admit Patient Into: </div></td>
		<td bgcolor="#FFFFFF"><?php echo $row_admit['admitto']; ?></td>
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
		<td><div align="right">Entry By: </div></td>
		<td><input name="entryby" type="text" id="entryby" value="<?php echo $row_admit['entryby']; ?>" readonly="readonly" size="15" /></td>
		<td><div align="right">Admitted By: </div></td>
		<td>&nbsp;</td>
		<td><input name="admitBy" type="text" id="admitBy" value="<?php echo $row_admit['admitby']; ?>" readonly="readonly" size="15" /></td>
		  	  
		<td>&nbsp;<input name="id" type="hidden" value="<?php echo $row_admit['id']; ?>" /></td>
	  </tr>
 </form>
</table>

</body>
</html>
