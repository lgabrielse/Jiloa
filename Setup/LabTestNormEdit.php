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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE testnormalvalues SET begindate=%s, enddate=%s, testid=%s, gender=%s, agemin=%s, agemax=%s, normlow=%s, normhigh=%s, paniclow=%s, panichigh=%s, interpretation=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['begindate'], "date"),
                       GetSQLValueString($_POST['enddate'], "date"),
                       GetSQLValueString($_POST['testid'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['agemin'], "text"),
                       GetSQLValueString($_POST['agemax'], "text"),
                       GetSQLValueString($_POST['normlow'], "text"),
                       GetSQLValueString($_POST['normhigh'], "text"),
                       GetSQLValueString($_POST['paniclow'], "text"),
                       GetSQLValueString($_POST['panichigh'], "text"),
                       GetSQLValueString($_POST['interpretation'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "LabTestNorms.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $updateGoTo));
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

mysql_select_db($database_swmisconn, $swmisconn);
$query_tests = "Select id, test, units from tests order by test";
$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
$row_tests = mysql_fetch_assoc($tests);
$totalRows_tests = mysql_num_rows($tests);
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
      <table width="100%"  bgcolor="#F8FDCE">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Edit LabTest Normal Values  </td>
        </tr>

        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">BeginDate:</div></td>
          <td><input name="begindate" type="text" id="begindate" value="<?php echo $row_norm['begindate']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">EndDate:</div></td>
          <td><input name="enddate" type="text" id="enddate" value="<?php echo $row_norm['enddate']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">TestID:</div></td>
          <td><select name="testid" id="testid">
            <?php
do {  
?>
            <option value="<?php echo $row_tests['id']?>"<?php if (!(strcmp($row_tests['id'], $row_norm['testid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tests['test']?></option>
            <?php
} while ($row_tests = mysql_fetch_assoc($tests));
  $rows = mysql_num_rows($tests);
  if($rows > 0) {
      mysql_data_seek($tests, 0);
	  $row_tests = mysql_fetch_assoc($tests);
  }
?>
          </select></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Gender:</div></td>
          <td><select name="gender" id="gender">
            <option value="F" <?php if (!(strcmp("F", $row_norm['gender']))) {echo "selected=\"selected\"";} ?>>F</option>
            <option value="M" <?php if (!(strcmp("M", $row_norm['gender']))) {echo "selected=\"selected\"";} ?>>M</option>
            <option value="MF" <?php if (!(strcmp("MF", $row_norm['gender']))) {echo "selected=\"selected\"";} ?>>MF</option>
          </select>
          </td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Agemin:</div></td>
          <td><input name="agemin"type="text"  size="5"id="agemin" value="<?php echo $row_norm['agemin']; ?> " /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Agemax:</div></td>
          <td><input name="agemax" type="text" id="agemax" size="5" value="<?php echo $row_norm['agemax']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Normlow:</div></td>
          <td><input name="normlow" type="text" size="8" id="Normlow" value="<?php echo $row_norm['normlow']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Normhigh:</div></td>
          <td><input name="normhigh" type="text" size="5" id="normhigh" value="<?php echo $row_norm['normhigh']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Paniclow:</div></td>
          <td><<input name="paniclow" type="text" size="5" id="paniclow" value="<?php echo $row_norm['paniclow']; ?>" /></td>
        </tr>
        <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Panichigh:</div></td>
          <td>><input name="panichigh" type="text" size="5" id="panichigh" value="<?php echo $row_norm['panichigh']; ?>" /></td>
        </tr>
         <tr bgcolor="#F8FDCE">
          <td class="BlackBold_14"><div align="right">Interpretation:</div></td>
          <td><textarea name="interpretation" id="interpretation"><?php echo $row_norm['interpretation']; ?></textarea></td>
        </tr>
       
        <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
			<input name="id" type="hidden" value="<?php echo $row_norm['id']; ?>" /></td>

         <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit LabTestNorm" />
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

mysql_free_result($tests);
?>
