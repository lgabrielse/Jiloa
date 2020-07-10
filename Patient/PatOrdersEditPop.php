<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
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
  $saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

	mysql_select_db($database_swmisconn, $swmisconn);
	$query_Fee = sprintf("SELECT fee from fee where id = '".$_POST['feeid']."'");
	$Fee = mysql_query($query_Fee, $swmisconn) or die(mysql_error());
	$row_Fee = mysql_fetch_assoc($Fee);
	$totalRows_Fee = mysql_num_rows($Fee);
	
	$amtdue = $row_Fee['fee']*($_POST['rate']/100); 	

  $updateSQL = sprintf("UPDATE orders SET rate=%s, ratereason=%s, amtdue=%s, urgency=%s, doctor=%s, comments=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['rate'], "int"),
                       GetSQLValueString($_POST['ratereason'], "int"),
                       GetSQLValueString($amtdue, "int"),
                       GetSQLValueString($_POST['urgency'], "text"),
                       GetSQLValueString($_POST['doctor'], "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $saved = "true";
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_reason = "Select id, list, name, seq from dropdownlist where list = 'Rate Reason' Order By seq";
$reason = mysql_query($query_reason, $swmisconn) or die(mysql_error());
$row_reason = mysql_fetch_assoc($reason);
$totalRows_reason = mysql_num_rows($reason);

$colname_mrn = "-1";
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id FROM orders o WHERE o.visitid = 0 AND o.medrecnum ='". $colname_mrn."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);

mysql_select_db($database_swmisconn, $swmisconn);
$query_ordedit = sprintf("Select o.id ordid, o.medrecnum, o.visitid, o.feeid, o.doctor, o.status, o.urgency, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%%d-%%b-%%Y %%H:%%i') entrydt, o.entryby, o.amtdue, o.rate, o.ratereason, o.amtpaid, o.comments,  f.dept, f.section, f.name, f.descr, f.fee, f.id feeid FROM orders o join fee f on o.feeid = f.id WHERE o.id = %s", $row_ordered['id']);
$ordedit = mysql_query($query_ordedit, $swmisconn) or die(mysql_error());
$row_ordedit = mysql_fetch_assoc($ordedit);
$totalRows_ordedit = mysql_num_rows($ordedit);


mysql_select_db($database_swmisconn, $swmisconn);
$query_doctor = "SELECT userid FROM users WHERE active = 'Y' and docflag = 'Y' ORDER BY userid ASC";
$doctor = mysql_query($query_doctor, $swmisconn) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
$totalRows_doctor = mysql_num_rows($doctor);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</head>

<?php if($saved == "true") {?>
	<body onload="out()">
	<?php }?>
<body>
<table width="40%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#F8FDCE">
        <tr>
          <td nowrap="nowrap" title="M:<?php echo $row_ordedit['medrecnum'] ?> V:<?php echo $row_ordedit['visitid'] ?> O:<?php echo $row_ordedit['ordid'] ?>"><?php echo $row_ordedit['entrydt'] ?></td>
          <td nowrap="nowrap"><div align="center" class="BlackBold_18">Order Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
          <td nowrap="nowrap"><div align="center">
              <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit" />
          </div></td>
        </tr>
        <tr>
          <td nowrap="nowrap">Dept: <span class="BlackBold_14"><?php echo $row_ordedit['dept']  ?></span></td>
          <td  nowrap="nowrap">Section:<span class="BlackBold_14"> <?php echo $row_ordedit['section']  ?></span></td>
          <td nowrap="nowrap">Order:<span class="BlackBold_14"> <?php echo $row_ordedit['name']  ?></span></td>
        </tr>
        <tr>
          <td nowrap="nowrap">Rate:
            <select name="rate" id="rate">
                <option value="200" <?php if (!(strcmp(200, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>200%</option>
                <option value="150" <?php if (!(strcmp(150, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>150%</option>
                <option value="125" <?php if (!(strcmp(125, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>125%</option>
                <option value="100" <?php if (!(strcmp(100, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>Standard</option>
                <option value="75" <?php if (!(strcmp(75, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>75%</option>
                <option value="50" <?php if (!(strcmp(50, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>50%</option>
                <option value="25" <?php if (!(strcmp(25, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>25%</option>
                <option value="0" <?php if (!(strcmp(0, $row_ordedit['rate']))) {echo "selected=\"selected\"";} ?>>None</option>
              </select>          </td>
          <td nowrap="nowrap">Rate Reason:
            <select name="ratereason">
                <option value="103" <?php if (!(strcmp(103, $row_ordedit['ratereason']))) {echo "selected=\"selected\"";} ?>>None</option>
                <?php
do {  
?>
                <option value="<?php echo $row_reason['id']?>"<?php if (!(strcmp($row_reason['id'], $row_ordedit['ratereason']))) {echo "selected=\"selected\"";} ?>><?php echo $row_reason['name']?></option>
                <?php
} while ($row_reason = mysql_fetch_assoc($reason));
  $rows = mysql_num_rows($reason);
  if($rows > 0) {
      mysql_data_seek($reason, 0);
	  $row_reason = mysql_fetch_assoc($reason);
  }
?>
            </select></td>
          <td nowrap="nowrap"> Fee: <span class="BlackBold_14"><?php echo $row_ordedit['fee']?></span>&nbsp;Amt Due: <span class="BlackBold_14"><?php echo $row_ordedit['amtdue']  ?></span></td>
        </tr>
        <tr>
          <td nowrap="nowrap">Status: <span class="BlackBold_14"><?php echo $row_ordedit['status']  ?></span></td>
          <td nowrap="nowrap">Urg:
            <select name="urgency" id="urgency">
                <option value="Routine" <?php if (!(strcmp("Routine", $row_ordedit['urgency']))) {echo "selected=\"selected\"";} ?>>Routine</option>
                <option value="Scheduled" <?php if (!(strcmp("Scheduled", $row_ordedit['urgency']))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
                <option value="ASAP" <?php if (!(strcmp("ASAP", $row_ordedit['urgency']))) {echo "selected=\"selected\"";} ?>>ASAP</option>
                <option value="STAT" <?php if (!(strcmp("STAT", $row_ordedit['urgency']))) {echo "selected=\"selected\"";} ?>>STAT</option>
              </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Doctor: </td>
          <td nowrap="nowrap"><select name="doctor">
            <option value="NA" <?php if (!(strcmp("NA", $row_ordedit['doctor']))) {echo "selected=\"selected\"";} ?>>NA</option>
            <!--<option value="" <?php if (!(strcmp("", $row_ordedit['doctor']))) {echo "selected=\"selected\"";} ?>>Select</option>-->
            <?php
do {  
?><option value="<?php echo $row_doctor['userid']?>"<?php if (!(strcmp($row_doctor['userid'], $row_ordedit['doctor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doctor['userid']?></option>
            <?php
} while ($row_doctor = mysql_fetch_assoc($doctor));
  $rows = mysql_num_rows($doctor);
  if($rows > 0) {
      mysql_data_seek($doctor, 0);
	  $row_doctor = mysql_fetch_assoc($doctor);
  }
?>
		  </select></td> 
        </tr>
        <tr>
          <td colspan="3" nowrap="nowrap">Comments:
            <input name="comments" type="text" id="comments" value="<?php echo $row_ordedit['comments']; ?>" size="40" />
              <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
              <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
              <input name="feeid" type="hidden" id="feeid" value="<?php echo $row_ordedit['feeid']; ?>" />
              <input name="id" type="hidden" id="id" value="<?php echo $row_ordedit['ordid']; ?>" />
              <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_ordedit['medrecnum']; ?>" />
              <input name="visitid" type="hidden" id="visitid" value="<?php echo $row_ordedit['visitid']; ?>" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
    </form>    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($ordedit);

mysql_free_result($ordered);

mysql_free_result($doctor);

mysql_free_result($reason);
?>
