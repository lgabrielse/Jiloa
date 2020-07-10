<?php require_once('../../Connections/swmisconn.php'); ?><?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
  $insertSQL = sprintf("INSERT INTO tests (feeid1, feeid2, feeid3, feeid4, feeid5, feeid6, feeid7, feeid8, feeid9, feeid0, feeidA, feeidB, feeidC, feeidD, feeidE, feeidF, feeidG, feeidH, feeidI, feeidJ, feeidK, feeidL, feeidM, feeidN, test, description, formtype, ddl, units, reportseq, flag1, active, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['feeid1'], "int"),
                       GetSQLValueString($_POST['feeid2'], "int"),
                       GetSQLValueString($_POST['feeid3'], "int"),
                       GetSQLValueString($_POST['feeid4'], "int"),
                       GetSQLValueString($_POST['feeid5'], "int"),
                       GetSQLValueString($_POST['feeid6'], "int"),
                       GetSQLValueString($_POST['feeid7'], "int"),
                       GetSQLValueString($_POST['feeid8'], "int"),
                       GetSQLValueString($_POST['feeid9'], "int"),
                       GetSQLValueString($_POST['feeid0'], "int"),
                       GetSQLValueString($_POST['feeidA'], "int"),
                       GetSQLValueString($_POST['feeidB'], "int"),
                       GetSQLValueString($_POST['feeidC'], "int"),
                       GetSQLValueString($_POST['feeidD'], "int"),
                       GetSQLValueString($_POST['feeidE'], "int"),
                       GetSQLValueString($_POST['feeidF'], "int"),
                       GetSQLValueString($_POST['feeidG'], "int"),
                       GetSQLValueString($_POST['feeidH'], "int"),
                       GetSQLValueString($_POST['feeidI'], "int"),
                       GetSQLValueString($_POST['feeidJ'], "int"),
                       GetSQLValueString($_POST['feeidK'], "int"),
                       GetSQLValueString($_POST['feeidL'], "int"),
                       GetSQLValueString($_POST['feeidM'], "int"),
                       GetSQLValueString($_POST['feeidN'], "int"),
                       GetSQLValueString($_POST['test'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['formtype'], "text"),
                       GetSQLValueString($_POST['dropdownlist'], "text"),
                       GetSQLValueString($_POST['units'], "text"),
                       GetSQLValueString($_POST['reportseq'], "text"),
                       GetSQLValueString($_POST['flag1'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "LabTests.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<?php 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_fee = "Select id, name from fee where dept = 'Laboratory' order by name";
	$fee = mysql_query($query_fee, $swmisconn) or die(mysql_error());
	$row_fee = mysql_fetch_assoc($fee);
	$totalRows_fee = mysql_num_rows($fee);

mysql_select_db($database_swmisconn, $swmisconn);
//$query_list = "Select distinct t.test, d.list from dropdownlist d join tests t on d.list = t.ddl  order by list";
$query_list = "Select distinct d.list from dropdownlist d order by list";
$list = mysql_query($query_list, $swmisconn) or die(mysql_error());
$row_list = mysql_fetch_assoc($list);
$totalRows_list = mysql_num_rows($list);
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SpecimenAdd</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%"  bgcolor="#BCFACC">
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="subtitlebk">Add Lab Test </td>
        </tr>
        <tr>
          <td colspan="2" class="BlackBold_14">A test can be included in up to 24 fee items to be ordered </td>
          </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeid1" id="feeid1">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          1</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid2" id="feeid2">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          2</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid3" id="feeid3">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          3</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid4" id="feeid4">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          4</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid5" id="feeid5">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          5</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid6" id="feeid6">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          6</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid7" id="feeid7">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          7</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid8" id="feeid8">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          8</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid9" id="feeid9">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          9</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td colspan="3"><select name="feeid0" id="feeid0">
            <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          10</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidA" id="feeidA">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          11</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidB" id="feeidB">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          12</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidC" id="feeidC">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          13</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidD" id="feeidD">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          14</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidE" id="feeidE">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          15</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidF" id="feeidF">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          16</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidG" id="feeidG">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          17</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidH" id="feeidH">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          18</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidI" id="feeidI">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          19</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidJ" id="feeidJ">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          20</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidK" id="feeidK">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          21</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidL" id="feeidL">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          22</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidM" id="feeidM">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          23</td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">OrderName:</div></td>
          <td><select name="feeidN" id="feeidN">
		  <option value="">Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_fee['id']?>"><?php echo $row_fee['name']?></option>
            <?php
} while ($row_fee = mysql_fetch_assoc($fee));
  $rows = mysql_num_rows($fee);
  if($rows > 0) {
      mysql_data_seek($fee, 0);
	  $row_fee = mysql_fetch_assoc($fee);
  }
?>
          </select>
          24</td>
        </tr>
        
        
        
        
        <tr>
          <td class="BlackBold_14"><div align="right">Test:</div></td>
          <td colspan="3"><input name="test" type="text" id="test" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Description:</div></td>
          <td colspan="3" class="BlackBold_14"><textarea  name="description" cols="40" rows="2" id="description" type="text"></textarea></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">ReportSeq:</div></td>
          <td colspan="3"><input name="reportseq" type="text" id="reportseq" size="5" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Units:</div></td>
          <td colspan="3"><input name="units"type="text"  id="units"  size="30" maxlength="30" />          </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">FormType:</div></td>
          <td colspan="3"><select name="formtype" id="formtype">
            <option value="TextField">TextField</option>
            <option value="DropDown">DropDown</option>
          </select></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">DropDownList:</div></td>
          <td colspan="3"><select name="dropdownlist" id="dropdownlist">
            <option value="" id="select"></option>
            <?php
do {  
?>
            <option value="<?php echo $row_list['list']?>"><?php echo $row_list['list']?></option>
            <?php
} while ($row_list = mysql_fetch_assoc($list));
  $rows = mysql_num_rows($list);
  if($rows > 0) {
      mysql_data_seek($list, 0);
	  $row_list = mysql_fetch_assoc($list);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Flag1:</div></td>
          <td colspan="3"><select name="flag1" id="flag1">
            <option value="">None</option>
            <option value="P">P</option>
            <option value="N">N</option>
          </select>
          Use only for antibiotics. </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Active:</div></td>
          <td colspan="3"><select name="active" id="active">
            <option value="Y" <?php if (!(strcmp("Y", "Y"))) {echo "selected=\"selected\"";} ?>>Y</option>
            <option value="N" <?php if (!(strcmp("N", "Y"))) {echo "selected=\"selected\"";} ?>>N</option>
          </select></td>
        </tr>
        <tr>
          <td nowrap="nowrap"> <a href="LabTests.php">Close </a>
		     <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" /></td>
          <td colspan="3"><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Lab Test" />
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
mysql_free_result($fee);

mysql_free_result($list);
?>

