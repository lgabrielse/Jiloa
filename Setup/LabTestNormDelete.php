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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM testnormalvalues WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "LabTestNorms.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_norm = "-1";
if (isset($_GET['id'])) {
  $colname_norm = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_norm = sprintf("SELECT id, begindate, enddate, testid, gender, agemin, agemax, normlow, normhigh, paniclow, panichigh, interpretation, entryby, entrydt FROM testnormalvalues WHERE id = %s", $colname_norm);
$norm = mysql_query($query_norm, $swmisconn) or die(mysql_error());
$row_norm = mysql_fetch_assoc($norm);
$totalRows_norm = mysql_num_rows($norm);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Fee Schedule</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%"  bgcolor="#FBD0D7">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Delete LabTest Normal Values </td>
        </tr>

        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">BeginDate:</div></td>
          <td><input name="begindate" type="text" id="begindate" value="<?php echo $row_norm['begindate']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">EndDate:</div></td>
          <td><input name="enddate" type="text" id="enddate" value="<?php echo $row_norm['enddate']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">TestID:</div></td>
          <td><input name="testid"type="text"  size="5"id="testid" value="<?php echo $row_norm['testid']; ?> " /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Gender:</div></td>
          <td><input name="gender"type="text"  size="5"id="gender" value="<?php echo $row_norm['gender']; ?> " /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Agemin:</div></td>
          <td><input name="agemin"type="text"  size="5"id="agemin" value="<?php echo $row_norm['agemin']; ?> " /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Agemax:</div></td>
          <td><input name="agemax" type="text" id="agemax" size="5" value="<?php echo $row_norm['agemax']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Normlow:</div></td>
          <td><input name="normlow" type="text" size="8" id="Normlow" value="<?php echo $row_norm['normlow']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Panichigh:</div></td>
          <td><input name="panichigh" type="text" size="5" id="panichigh" value="<?php echo $row_norm['panichigh']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Paniclow:</div></td>
          <td><input name="paniclow" type="text" size="5" id="paniclow" value="<?php echo $row_norm['paniclow']; ?>" /></td>
        </tr>
        <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Normhigh:</div></td>
          <td><input name="normhigh" type="text" size="5" id="normhigh" value="<?php echo $row_norm['normhigh']; ?>" /></td>
        </tr>
         <tr bgcolor="#FBD0D7">
          <td class="BlackBold_14"><div align="right">Interpretation:</div></td>
          <td><textarea name="interpretation" id="interpretation"><?php echo $row_norm['interpretation']; ?></textarea></td>
        </tr>
       
         <tr>
           <td>&nbsp;</td>
           <td class="RedBold_11">Do not delete normal values if it has already been used to report normal values. Rather,edit the  enddate of the current normal and add a new normal range with begin date of new normal = enddate of current + 1. </td>
         </tr>
         <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
			<input name="id" type="hidden" value="<?php echo $row_norm['id']; ?>" /></td>

         <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete LabTestNorm" />
          </p></td>
        </tr>
      </table>
        <input type="hidden" name="MM_update" value="form1">
    </form>
    </td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($norm);
?>
