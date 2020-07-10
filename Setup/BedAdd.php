<?php require_once('../../Connections/swmisconn.php'); ?><?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
//**************************  MySqli add form action  **************************
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO patbed (feeid, bed, active, entryby, entrydt) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['bed'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "BedAdd.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_beds = "SELECT b.id, b.feeid, f.name, b.bed, b.medrecnum, b.active, b.entryby, b.entrydt FROM patbed b JOIN fee f ON b.feeid=f.id ORDER BY b.feeid, b.bed";
$beds = mysql_query($query_beds, $swmisconn) or die(mysql_error());
$row_beds = mysql_fetch_assoc($beds);
$totalRows_beds = mysql_num_rows($beds);

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
<link rel="stylesheet" type="text/css" href="../../CSS/Level3_1.css"/>
<title>Add Bed</title>
</head>

<body>
<p>&nbsp;</p>
<table bgcolor="#c1ecd6" width="25%" border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:collapse">
   <caption class="BlueBold_24">
      Add Bed
   </caption>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
   <tr>
      <td colspan="2" align="center">Add Bed max 10 characters</td>
   </tr>
   <tr>
      <td align="right">Location:</td>
      <td>
            <select name="feeid" id="feeid">
               <option value=""></option>
               <?php
do {  
?>
               <option value="<?php echo $row_locationddl['id']?>"><?php echo $row_locationddl['name']?></option>
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
      <td align="right">Bed:</td>
      <td>
         <input name="bed" type="text" id="bed" size="5" maxlength="10" />
      </td>
   </tr>
   <tr>
      <td align="right">Active:</td>
      <td>
         <select name="active" id="active">
            <option value="Y" selected="selected">Y</option>
            <option value="N">N</option>
         </select>
      </td>
   </tr>
   <tr>
      <td><a href="SetUpMenu.php">Menu</a></td>
      <td>
        <input name="entryby" type="hidden" id="entryby" Value = "Grace" /> <!--value="<?php echo $_SESSION['user']; ?>" />-->
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />

         <input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Bed" />
      </td>
   </tr>
   <input type="hidden" name="MM_insert" value="form1" />
</form>

</table>
<p>&nbsp;</p>
<p align="center" class="BlueBold_30">Bed List</p>
<table border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:collapse">
   <tr class="BlueBold_16">
      <td>&nsbp;</td>
      <td align="center">Ward</td>
      <td align="center">Bed</td>
      <td align="center">MRN</td>
      <td align="center">Active</td>
      <td align="center">Entryby</td>
      <td align="center">Entrydt</td>
   </tr>
   <?php do { ?>
      <tr>
       <td><a href="BedEdit.php?id=<?php echo $row_beds['id']; ?>" title="Click to Edit">Edit</a></td>  
       <td bgcolor="#FFFFCC" title="id=<?php echo $row_beds['id']; ?>&#10;feeid=<?php echo $row_beds['feeid']; ?>"><?php echo $row_beds['name']; ?></td>
         <td bgcolor="#FFFFCC" title="id=<?php echo $row_beds['id']; ?>&#10;feeid=<?php echo $row_beds['feeid']; ?>"><?php echo $row_beds['bed']; ?></td>
         <td bgcolor="#FFFFCC"><?php echo $row_beds['medrecnum']; ?></td>
         <td bgcolor="#FFFFCC"><?php echo $row_beds['active']; ?></td>
         <td bgcolor="#FFFFCC"><?php echo $row_beds['entryby']; ?></td>
         <td bgcolor="#FFFFCC"><?php echo $row_beds['entrydt']; ?></td>
      </tr>
      <?php } while ($row_beds = mysql_fetch_assoc($beds)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($beds);

mysql_free_result($locationddl);
?>
