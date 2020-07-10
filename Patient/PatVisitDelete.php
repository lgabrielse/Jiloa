<?php $pt = "Delete Patient Visit"; ?>
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
  $deleteSQL = sprintf("DELETE FROM patvisit WHERE id=%s", GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteOrdSQL = sprintf("DELETE FROM `swmisbethany`.`orders` WHERE feeid != 19 and visitid =%s", GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteOrdSQL, $swmisconn) or die(mysql_error());

   $updateSQL = sprintf("UPDATE patbed SET medrecnum = %s WHERE medrecnum = %s",
                       GetSQLValueString(Null, "int"),
                       GetSQLValueString($_POST['medrecnum'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());


  $deleteGoTo = "PatShow1.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colid_visitdelete = "-1";
if (isset($_GET['vid'])) {
  $colid_visitdelete = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
$colmrn_visitdelete = "-1";
if (isset($_GET['mrn'])) {
  $colmrn_visitdelete = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
  $_SESSION['MRN'] = $colmrn_visitdelete;  // set to retrieve MRN in PatShow1.php when it goes back
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitdelete = sprintf("SELECT id, medrecnum, visitdate, vfeeid, pat_type, location, urgency, discharge, visitreason, diagnosis, entryby, entrydt FROM patvisit WHERE id = %s AND medrecnum = %s", $colid_visitdelete, $colmrn_visitdelete);
$visitdelete = mysql_query($query_visitdelete, $swmisconn) or die(mysql_error());
$row_visitdelete = mysql_fetch_assoc($visitdelete);
$totalRows_visitdelete = mysql_num_rows($visitdelete);

mysql_select_db($database_swmisconn, $swmisconn);  //find visit fee paid
$query_visitfee = "Select id ordid, visitid, feeid from orders where medrecnum = '".$colmrn_visitdelete."' and visitid = '".$colid_visitdelete."' and feeid = '".$row_visitdelete['vfeeid']."'";
$visitfee = mysql_query($query_visitfee, $swmisconn) or die(mysql_error());
$row_visitfee = mysql_fetch_assoc($visitfee);
$totalRows_visitfee = mysql_num_rows($visitfee);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <table align="center">
	<tr>
		<td>
			<table align="center">
				<tr>
          			<td><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn'] ?>&vid=<?php echo $row_visitdelete['id']; ?>">Close</a></td>
				  	<td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
					<td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="center" class="subtitlebl">Delete Patient Visit</td>
					<td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<?php  if ($_SESSION['vnum'] > 0) { ?>
					<td nowrap="nowrap">Visits: &nbsp; 
					<?php if(allow(20,1) == 1) { ?>
				  <td align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitList.php"><?php echo $_SESSION['vnum']; ?></a></td>
					<?php  } ?>
				<?php  } 
						else {?>
					<td nowrap="nowrap">Visits: &nbsp; 0 &nbsp;</td>
				<?php  } ?>
					<td align="center" class="subtitlebl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
		  </table>
		</td>	
	<tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="0" bgcolor="#FBD0D7">
        <tr>
          <td nowrap="nowrap"><div align="right">Visit Date:</div></td>
          <td><input name="visitdate" type="text" id="visitdate" value="<?php echo $row_visitdelete['visitdate']; ?>" size="12" /></td>
          <td><div align="right">Location:</div></td>
          <td><input name="location" type="text" id="location" value="<?php echo $row_visitdelete['location']; ?>" size="20" /></td>
          <td nowrap="nowrap"><div align="right">Patient Type:</div></td>
          <td><input name="pat_type" type="text" id="pat_type" value="<?php echo $row_visitdelete['pat_type']; ?>" /></td>
          <td><div align="right">Urgency:</div></td>
          <td><input name="urgency" type="text" id="urgency" value="<?php echo $row_visitdelete['urgency']; ?>" size="12" /></td>
          <td nowrap="nowrap"><div align="right">Discharge Date: </div></td>
          <td><input name="discharge" type="text" id="discharge" value="<?php echo $row_visitdelete['discharge']; ?>" size="12" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap"><div align="right">Visit Reason </div></td>
          <td colspan="4"><textarea name="visitreason" cols="50" rows="2" id="visitreason"><?php echo $row_visitdelete['visitreason']; ?></textarea></td>
          <td><div align="right">Diagnosis:</div></td>
          <td colspan="4"><textarea name="diagnosis" cols="40" rows="2" id="diagnosis"><?php echo $row_visitdelete['diagnosis']; ?></textarea></td>
          <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Visit" />
		  <input name="visitid" type="hidden" value="<?php echo $row_visitfee['visitid']; ?>" />
		  <input name="ordid" type="hidden" value="<?php echo $row_visitfee['ordid']; ?>" />
		  <input name="id" type="hidden" value="<?php echo $row_visitdelete['id']; ?>" />
		  <input name="medrecnum" type="hidden" value="<?php echo $row_visitdelete['medrecnum']; ?>" /></td>
          </tr>
      </table>
        </form>
    </td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($visitdelete);
?>