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
  $insertSQL = sprintf("INSERT INTO fee (dept, `section`, name, unit, descr, specid, fee, Active, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['dept'], "text"),
                       GetSQLValueString($_POST['section'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['unit'], "text"),
                       GetSQLValueString($_POST['descr'], "text"),
                       GetSQLValueString($_POST['specid'], "int"),
                       GetSQLValueString($_POST['fee'], "int"),
                       GetSQLValueString($_POST['Active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "FeeSchedule.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_Dept = "SELECT name FROM dropdownlist WHERE list = 'dept' ORDER BY seq ASC";
$Dept = mysql_query($query_Dept, $swmisconn) or die(mysql_error());
$row_Dept = mysql_fetch_assoc($Dept);
$totalRows_Dept = mysql_num_rows($Dept);

mysql_select_db($database_swmisconn, $swmisconn);
$query_Section = "SELECT name FROM dropdownlist WHERE list like '%section' ORDER BY name, seq ASC";
$Section = mysql_query($query_Section, $swmisconn) or die(mysql_error());
$row_Section = mysql_fetch_assoc($Section);
$totalRows_Section = mysql_num_rows($Section);

mysql_select_db($database_swmisconn, $swmisconn);
$query_spec = "Select id, name from specimens order by name";
$spec = mysql_query($query_spec, $swmisconn) or die(mysql_error());
$row_spec = mysql_fetch_assoc($spec);
$totalRows_spec = mysql_num_rows($spec);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%"  bgcolor="#BCFACC">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Add Fee Item </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Department:</div></td>
          <td><select name="dept" id="dept">
            <?php
do {  
?>
            <option value="<?php echo $row_Dept['name']?>"<?php if (!(strcmp($row_Dept['name'], $row_Dept['name']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Dept['name']?></option>
            <?php
} while ($row_Dept = mysql_fetch_assoc($Dept));
  $rows = mysql_num_rows($Dept);
  if($rows > 0) {
      mysql_data_seek($Dept, 0);
	  $row_Dept = mysql_fetch_assoc($Dept);
  }
?>
          </select>          </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Section:</div></td>
          <td><select name="section" id="section">
            <?php
do {  
?>
            <option value="<?php echo $row_Section['name']?>"<?php if (!(strcmp($row_Section['name'], $row_Section['name']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Section['name']?></option>
            <?php
} while ($row_Section = mysql_fetch_assoc($Section));
  $rows = mysql_num_rows($Section);
  if($rows > 0) {
      mysql_data_seek($Section, 0);
	  $row_Section = mysql_fetch_assoc($Section);
  }
?>
          </select>          </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Name:</div></td>
          <td><input name="name" type="text" id="name" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Unit:</div></td>
          <td><input name="unit" type="text" id="unit" value="each" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Description:</div></td>
          <td><textarea name="descr" id="descr"></textarea></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Specimen:</div></td>
          <td><select name="specid" id="specid">
		  <option value="8">None</option>
            <?php
do {  
?>
            <option value="<?php echo $row_spec['id']?>"><?php echo $row_spec['name']?></option>
            <?php
} while ($row_spec = mysql_fetch_assoc($spec));
  $rows = mysql_num_rows($spec);
  if($rows > 0) {
      mysql_data_seek($spec, 0);
	  $row_spec = mysql_fetch_assoc($spec);
  }
?>
          </select>          </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Fee (Naira):</div></td>
          <td><input name="fee" type="text" id="fee" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Active:</div></td>
          <td><select name="Active" id="Active">
            <option value="Y" selected="selected">Y</option>
            <option value="N">N</option>
          </select>
          </td>
        </tr>
        <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" /></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Fee Item" />
          </p></td>
        </tr>
      </table>
        <input type="hidden" name="MM_insert" value="form1" />
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Dept);

mysql_free_result($Section);

mysql_free_result($spec);
?>
