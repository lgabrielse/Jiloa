<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE patbed SET feeid=%s, bed=%s, medrecnum=%s, active=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['bed'], "text"),
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "BedAdd.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

 if(isset($_GET['id'])){
$id = $_GET['id'];
} 


?>
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_bed = "SELECT id, feeid, bed, medrecnum, active FROM patbed where id='".$id."'";
$bed = mysql_query($query_bed, $swmisconn) or die(mysql_error());
$row_bed = mysql_fetch_assoc($bed);
$totalRows_bed = mysql_num_rows($bed);

?>


<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_locationddl = "SELECT id, name FROM fee where section  = 'InPatient' and name != 'antenatal' order by name";
$locationddl = mysql_query($query_locationddl, $swmisconn) or die(mysql_error());
$row_locationddl = mysql_fetch_assoc($locationddl);
$totalRows_locationddl = mysql_num_rows($locationddl);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bed Edit</title>
</head>

<body>
<table bgcolor="#fffdda" width="25%" border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:collapse">
   <caption class="BlueBold_24">
      Edit Bed
   </caption>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
   <tr>
      <td colspan="2" align="center">Edit Bed max 10 characters</td>
   </tr>
   <tr>
      <td align="right">Location:</td> <br />
       <!--<?php echo "bedid: ".$id. "<br />locationid: ".$row_locationddl['id']?>'<br />name: <?php echo $row_locationddl['name']?>-->
      <td>
         <select name="feeid" id="feeid">
<?php do { ?> 
           <option value="<?php echo $row_locationddl['id']?>"<?php if (!(strcmp($row_locationddl['id'], $row_bed['feeid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_locationddl['name']?></option>

<?php
} while ($row_locationddl = mysql_fetch_assoc($locationddl));
  $rows = mysql_num_rows($locationddl);
  if($rows > 0) {
      mysql_data_seek($locationddl, 0);
	  $row_locationddl = mysql_fetch_assoc($locationddl);
  }
?>
            </select>
      </td>
   </tr>
   <tr>
      <td align="right"></td>
      <td>
         <input name="bed" type="text" id="bed" size="5" maxlength="10" value="<?php echo $row_bed['bed'];?>"/>
      </td>
   </tr>
    <tr>
      <td align="right">MRN:</td>
      <td>
         <input name="medrecnum" type="text" id="medrecnum" size="6" maxlength="10" value="<?php echo $row_bed['medrecnum'];?>"/>
      </td>
   </tr>
  <tr>
      <td align="right">Active:</td>
      <td>
         <select name="active" id="active">
            <option value="Y" <?php if (!(strcmp("Y", $row_bed['active']))) {echo "selected=\"selected\"";} ?>>Y</option>
            <option value="N" <?php if (!(strcmp("N", $row_bed['active']))) {echo "selected=\"selected\"";} ?>>N</option>
         </select>
      </td>
   </tr>
   <tr>
      <td><a href="SetUpMenu.php">Menu</a></td>
      <td>
        <input name="id" type="hidden" id="id" Value = "<?php echo $id; ?>" />       	
        <input name="entryby" type="hidden" id="entryby" Value = "Grace" /> <!--value="<?php echo $_SESSION['user']; ?>" />-->
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />

         <input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Bed" />
      </td>
   </tr>
   <input type="hidden" name="MM_insert" value="form1" />
   <input type="hidden" name="MM_update" value="form1" />
</form>

</table>
</body>
</html>