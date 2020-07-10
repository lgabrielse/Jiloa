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
  $deleteSQL = sprintf("DELETE FROM tests WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "LabTests.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST['id'])) && ($_POST['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tests WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "LabTests.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}


$colname_test = "-1";
if (isset($_GET['tid'])) {
  $colname_test = (get_magic_quotes_gpc()) ? $_GET['tid'] : addslashes($_GET['tid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_test = sprintf("SELECT t.id, feeid1, feeid2, feeid3, feeid4, feeid5, feeid6, feeid7, feeid8, feeid9, feeid0, feeidA, feeidB, feeidC, feeidD, feeidE, feeidF, feeidG, feeidH, feeidI, feeidJ, feeidK, feeidL, feeidM, feeidN, t.test, t.description, t.formtype, t.units, t.reportseq, t.active, t.entryby, t.entrydt, (select name from fee Where id = t.feeid1) as name1, (select name from fee Where id = t.feeid2) as name2, (select name from fee Where id = t.feeid3) as name3, (select name from fee Where id = t.feeid4) as name4, (select name from fee Where id = t.feeid5) as name5, (select name from fee Where id = t.feeid6) as name6, (select name from fee Where id = t.feeid7) as name7, (select name from fee Where id = t.feeid8) as name8, (select name from fee Where id = t.feeid9) as name9, (select name from fee Where id = t.feeid0) as name0, (select name from fee Where id = t.feeidA) as nameA, (select name from fee Where id = t.feeidB) as nameB, (select name from fee Where id = t.feeidC) as nameC, (select name from fee Where id = t.feeidD) as nameD, (select name from fee Where id = t.feeidE) as nameE, (select name from fee Where id = t.feeidF) as nameF, (select name from fee Where id = t.feeidG) as nameG, (select name from fee Where id = t.feeidH) as nameH, (select name from fee Where id = t.feeidI) as nameI, (select name from fee Where id = t.feeidJ) as nameJ, (select name from fee Where id = t.feeidK) as nameK, (select name from fee Where id = t.feeidL) as nameL, (select name from fee Where id = t.feeidM) as nameM, (select name from fee Where id = t.feeidN) as nameN FROM tests t WHERE t.id = %s", $colname_test);
$test = mysql_query($query_test, $swmisconn) or die(mysql_error());
$row_test = mysql_fetch_assoc($test);
$totalRows_test = mysql_num_rows($test);
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
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%"  bgcolor="#FBD0D7">
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="subtitlebk">Delete Lab Test </td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid1" type="text" id="feeid1" readonly="readonly" value="<?php echo $row_test['name1']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid2" type="text" id="feeid2" readonly="readonly" value="<?php echo $row_test['name2']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid3" type="text" id="feeid3" readonly="readonly" value="<?php echo $row_test['name3']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid4" type="text" id="feeid4" readonly="readonly" value="<?php echo $row_test['name4']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid5" type="text" id="feeid5" readonly="readonly" value="<?php echo $row_test['name5']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid6" type="text" id="feeid6" readonly="readonly" value="<?php echo $row_test['name6']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid7" type="text" id="feeid7" readonly="readonly" value="<?php echo $row_test['name7']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid8" type="text" id="feeid8" readonly="readonly" value="<?php echo $row_test['name8']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid9" type="text" id="feeid9" readonly="readonly" value="<?php echo $row_test['name9']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeid0" type="text" id="feeid0" readonly="readonly" value="<?php echo $row_test['name0']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidA" type="text" id="feeidA" readonly="readonly" value="<?php echo $row_test['nameA']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidB" type="text" id="feeidB" readonly="readonly" value="<?php echo $row_test['nameB']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidC" type="text" id="feeidC" readonly="readonly" value="<?php echo $row_test['nameC']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidD" type="text" id="feeidD" readonly="readonly" value="<?php echo $row_test['nameD']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidE" type="text" id="feeidE" readonly="readonly" value="<?php echo $row_test['nameE']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidF" type="text" id="feeidF" readonly="readonly" value="<?php echo $row_test['nameF']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidG" type="text" id="feeidG" readonly="readonly" value="<?php echo $row_test['nameG']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidH" type="text" id="feeidH" readonly="readonly" value="<?php echo $row_test['nameH']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidI" type="text" id="feeidI" readonly="readonly" value="<?php echo $row_test['nameI']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidJ" type="text" id="feeidJ" readonly="readonly" value="<?php echo $row_test['nameJ']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidK" type="text" id="feeidK" readonly="readonly" value="<?php echo $row_test['nameK']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidL" type="text" id="feeidL" readonly="readonly" value="<?php echo $row_test['nameL']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidM" type="text" id="feeidM" readonly="readonly" value="<?php echo $row_test['nameM']; ?>" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="BlackBold_14"><div align="right">Fee ID - Name:</div></td>
          <td><input name="feeidN" type="text" id="feeidN" readonly="readonly" value="<?php echo $row_test['nameN']; ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Test:</div></td>
          <td colspan="3"><input name="test" type="text" id="test" readonly="readonly" value="<?php echo $row_test['test']; ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Description:</div></td>
          <td colspan="3"><textarea name="description" cols="50" rows="2" readonly="readonly" id="description" type="text"><?php echo $row_test['description']; ?></textarea></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">ReportSeq:</div></td>
          <td colspan="3"><input name="reportseq" type="text" id="reportseq" readonly="readonly" value="<?php echo $row_test['reportseq']; ?>" size="5" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Units:</div></td>
          <td colspan="3"><input name="units"type="text"  id="units" readonly="readonly" value="<?php echo $row_test['units']; ?>"  size="30" maxlength="30" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">FormType:</div></td>
          <td colspan="3"><input name="formtype" type="text" id="formtype" readonly="readonly" value="<?php echo $row_test['formtype']; ?>" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Active:</div></td>
          <td colspan="3"><input name="active" type="text" id="active" readonly="readonly" value="<?php echo $row_test['active']; ?>" size="3" /></td>
        </tr>
        <tr>
          <td><a href="LabTests.php">Close </a>
		      <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
              <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
              <input name="id" type="hidden" id="id" value="<?php echo $row_test['id']; ?>" /></td>
          <td colspan="3"><p>
              <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Lab Test" />
          </p></td>
        </tr>
      </table>
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($test);

?>
