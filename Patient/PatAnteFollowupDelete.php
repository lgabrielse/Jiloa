<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST['folupid'])) && ($_POST['folupid'] != "" && isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "formpafd")) {
  $deleteSQL = sprintf("DELETE FROM anfollowup WHERE id=%s",
                       GetSQLValueString($_POST['folupid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= str_replace('&pge2=PatAnteFollowupDelete.php','',$_SERVER['QUERY_STRING']);
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


?>
<?php
$colname_folup = "-1";
if (isset($_GET['id'])) {
  $colname_folup = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_folup = sprintf("SELECT id, medrecnum, visitid, pregid, hof, ega, lie, prespos, fetalheart, bldpres, weight, oedema, foluptext, nextvisit, entryby, entrydt FROM anfollowup WHERE id = %s", $colname_folup);
$folup = mysql_query($query_folup, $swmisconn) or die(mysql_error());
$row_folup = mysql_fetch_assoc($folup);
$totalRows_folup = mysql_num_rows($folup);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Preg Followup Delete</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

</head>

<body>
<form name="formpafd" id="formpafd" method="POST" action="<?php echo $editFormAction; ?>">

<table cellpadding="0" cellspacing="0"  bgcolor="#FBD0D7">
  <tr>
    <td class="BlackBold_12"><div align="center">Edit<br />
      PregID,<br />
    FIUD</div></td>
    <td class="BlackBold_12"><div align="center">Date</div></td>
    <td class="BlackBold_12"><div align="center">EGA</div></td>
    <td class="BlackBold_12"><div align="center"> Fundus<br />
    Height</div></td>
    <td class="BlackBold_12"><div align="center">Lie</div></td>
    <td class="BlackBold_12"><div align="center">Presentation<br />
    and Position </div></td>
    <td nowrap="nowrap" class="BlackBold_12"><div align="center">Foetal<br />
      Heart Rate</div></td>
    <td class="BlackBold_12"><div align="center"> Blood<br />
    Pressure</div></td>
    <td class="BlackBold_12"><div align="center">Weight</div></td>
    <td class="BlackBold_12"><div align="center">Oedema</div></td>
    <td class="BlackBold_12"><div align="center">Remarks</div></td>
    <td class="BlackBold_12"><div align="center">Return</div></td>
    <td class="BlackBold_12">Examiner</td>
    <td><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td Title="MRN: <?php echo $row_folup['medrecnum']; ?>&#10;VisitID: <?php echo $row_folup['visitid']; ?>&#10;PregID: <?php echo $row_folup['pregid']; ?>">
	      <input name="pregid" type="text" id="pregid" size="8" maxlength="12" style="text-align:center;" class="BlackBold_10"  value="<?php echo $row_folup['pregid']; ?>, <?php echo $row_folup['id']; ?>" />
	      <input name="visitid" type="hidden" id="visitid" value="<?php echo $row_folup['visitid']; ?>" />
	      <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_folup['medrecnum']; ?>" />
		  <input name="folupid" type="hidden" id="folupid" value="<?php echo $row_folup['id']; ?>"/>		  </td>
      <td><input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" class="BlackBold_10" value="<?php echo date("Y-m-d H:i"); ?>" /></td>
      <td><input name="ega" type="text" id="ega" size="2" maxlength="2" style="text-align:center;" value="<?php echo $row_folup['hof']; ?>" /></td>
      <td><input name="hof" type="text" id="hof" size="2" maxlength="3" style="text-align:center;" value="<?php echo $row_folup['hof']; ?>" /></td>
      <td><input name="lie" type="text" id="lie" size="8" maxlength="12" value="<?php echo $row_folup['lie']; ?>" /></td>
      <td><input name="prespos" type="text" id="prespos" size="10" maxlength="12" value="<?php echo $row_folup['prespos']; ?>" /></td>

      <td><input name="fetalheart" type="text" id="fetalheart" size="2" maxlength="2" style="text-align:center;" value="<?php echo $row_folup['fetalheart']; ?>" /></td>
      <td><input name="bldpres" type="text" id="bldpres" size="4" maxlength="9" style="text-align:center;" value="<?php echo $row_folup['bldpres']; ?>" /></td>
      <td><input name="weight" type="text" id="weight" size="2" maxlength="2" style="text-align:center;" value="<?php echo $row_folup['weight']; ?>" /></td>

      <td><input name="oedema" type="text" id="oedema" size="4" maxlength="6" style="text-align:center;" value="<?php echo $row_folup['oedema']; ?>" /></td>

      <td><textarea name="foluptext" cols="30" rows="1"><?php echo $row_folup['foluptext']; ?></textarea></td>

      <td><input name="nextvisite" type="text" id="nextvisite" size="6" maxlength="12" class="BlackBold_10" value="<?php echo $row_folup['nextvisit']; ?>" /></td>

      <td><input name="entryby" type="text" id="entryby" size="6" maxlength="12" class="BlackBold_10" readonly="readonly" value="<?php echo $_SESSION['user']; ?>" /></td>
      <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" />
	  </td>
    </tr>
    <?php } while ($row_folup = mysql_fetch_assoc($folup)); ?>
</table>
<input type="hidden" name="MM_delete" value="formpafd">
</form>
</body>
</html>
<?php
mysql_free_result($folup);
?>
