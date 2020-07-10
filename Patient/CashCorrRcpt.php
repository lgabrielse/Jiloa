<?php $pt = "Exam Setup Menu"; ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php require_once('../../Connections/swmisconn.php'); ?>
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
$saved = '';

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE receipts SET nbc=%s, amt=%s WHERE id=%s",
                       GetSQLValueString($_POST['nbc'], "text"),
                       GetSQLValueString($_POST['amt'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  $saved =  'true';
}

$colname_rid = "-1";
if (isset($_GET['rid'])) {
  $colname_rid = (get_magic_quotes_gpc()) ? $_GET['rid'] : addslashes($_GET['rid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_rcpt = "Select r.id rid, r.medrecnum, p.lastname, p.firstname, ordlist, amt, nbc from receipts r join patperm p on r.medrecnum = p.medrecnum where id = '".$colname_rid."'";
$rcpt = mysql_query($query_rcpt, $swmisconn) or die(mysql_error());
$row_rcpt = mysql_fetch_assoc($rcpt);
$totalRows_rcpt = mysql_num_rows($rcpt);
?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_PayBy = "SELECT name FROM dropdownlist WHERE list = 'PayBy' ORDER BY seq ASC";
$PayBy = mysql_query($query_PayBy, $swmisconn) or die(mysql_error());
$row_PayBy = mysql_fetch_assoc($PayBy);
$totalRows_PayBy = mysql_num_rows($PayBy);
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Correct Receipt</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>


<?php if($saved == "true") { ?>
	<body onload="out()">
	<?php }?>

<p align="center" class="BlueBold_20">Correct Receipt <?php echo $colname_rid ?> Payment Type  </p>
<p align="center" class="BlueBold_20">&nbsp;</p>
<p class="style1">&nbsp;</p>

<table border="1" bg bgcolor="#DDFFBB"align="center">
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"><?php do { ?>
  <tr>
    <td>Patient</td>
    <td colspan="2"><?php echo $row_rcpt['lastname']; ?>, <?php echo $row_rcpt['firstname']; ?></td>
  </tr>
  <tr>
    <td>medrecnum</td>
    <td colspan="2"><?php echo $row_rcpt['medrecnum']; ?></td>
  </tr>
  <tr>
    <td>ordlist</td>
    <td colspan="2"><?php echo $row_rcpt['ordlist']; ?></td>
  </tr>
  <tr>
    <td>amt</td>
    <td><?php echo $row_rcpt['amt']; ?></td>
    <td><input type="text" name="amt" value="<?php echo $row_rcpt['amt'] ?>" /></td>
  </tr>
  <tr>
    <td>nbc</td>
    <td><?php echo $row_rcpt['nbc']; ?></td>
			    <td align="left"><select name="nbc" id="nbc" >
		<?php do { ?>
		<option value="<?php echo $row_PayBy['name']?>"<?php if (!(strcmp($row_PayBy['name'], $row_rcpt['nbc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_PayBy['name']?></option>
		<?php } while ($row_PayBy = mysql_fetch_assoc($PayBy));
	  $rows = mysql_num_rows($PayBy);
	  if($rows > 0) {
		  mysql_data_seek($PayBy, 0);
		  $row_PayBy = mysql_fetch_assoc($PayBy);
	  } ?>
	  </select></td>
  </tr>
  <tr>
    <td><input name="id" type="hidden" value="<?php echo $colname_rid ?>" /></td>
    <td colspan="2">
      <label>
        <div align="left">
          <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" />
        </div>
      </label>
      <div align="right"></div></td>
  </tr>
    <?php } while ($row_rcpt = mysql_fetch_assoc($rcpt)); ?>
    <input type="hidden" name="MM_update" value="form1">
    </form>
</table>
</body>
</html>
<?php
mysql_free_result($rcpt);
?>
