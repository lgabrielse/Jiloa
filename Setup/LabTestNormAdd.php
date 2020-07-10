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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {   
  $insertSQL = sprintf("INSERT INTO testnormalvalues (begindate, enddate, testid, gender, agemin, agemax, normlow, normhigh, paniclow, panichigh, interpretation, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "LabTestNorms.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));
}

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
<title>LabTestNormAdd</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%"  bgcolor="#BCFACC">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Add LabTest Normal Values </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Begin Date :</div></td>
          <td><input name="begindate" type="text" id="begindate" value="<?php echo '2014-01-01 00:00:00' ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">End Date:</div></td>
          <td><input name="enddate" type="text" id="enddate" value="<?php echo '2019-01-01 00:00:00' ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">testid:</div></td>
          <td><select name="testid" id="testid">
            <?php
do {  
?>
            <option value="<?php echo $row_tests['id']?>"><?php echo $row_tests['test']?> : <?php echo $row_tests['units']?></option>
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
        <tr>
          <td class="BlackBold_14"><div align="right">gender:</div></td>
          <td><select name="gender" id="gender">
            <option value="F">F</option>
            <option value="M">M</option>
            <option value="MF">MF</option>
          </select>
          </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">agemin:</div></td>
          <td><input name="agemin" type="text"  id="agemin" size="5" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">agemax:</div></td>
          <td><input name="agemax" type="text" id="agemax" size="5" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">normlow:</div></td>
          <td><input name="normlow" type="text" id="normlow" size="8" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">normhigh:</div></td>
          <td><input name="normhigh" id="normhigh" size="8" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">paniclow:</div></td>
          <td><<input name="paniclow" type="text" id="paniclow" size="8" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">panichigh:</div></td>
          <td>><input name="panichigh" id="panichigh" size="8" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Interpretation:</div></td>
          <td><textarea name="interpretation" cols="40" rows="3" id="interpretation" ></textarea></td>
        </tr>
        <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" /></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add LabTestNorm" />
          </p></td>
        </tr>
      </table>
        <input type="hidden" name="MM_insert" value="form1">
    </form>
    </td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($tests);
?>

