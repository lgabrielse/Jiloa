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
  $deleteSQL = sprintf("DELETE FROM orders WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
  

  $deleteSQL = sprintf("DELETE FROM ipmeds WHERE orderid=%s",    // in case it has scheduled meds
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
  
// DELETE SURGERY AND ANESTHESIA RECORDS IF SURGERY ORDER IS DELETED
	if(isset($_POST['dept']) and $_POST['dept'] == 'Surgery'){
	//find the surgery id for this order		 
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_surgid = "Select id from surgery where ordid = '".$_POST['id']."'";
		$surgid = mysql_query($query_surgid, $swmisconn) or die(mysql_error());
		$row_surgid = mysql_fetch_assoc($surgid);
		$totalRows_surgid = mysql_num_rows($surgid);
	//find the anesthesia id for this surgery		
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_anestid = "Select id from anesthesia where surgid = '".$row_surgid['id']."'";
		$anestid = mysql_query($query_anestid, $swmisconn) or die(mysql_error());
		$row_anestid = mysql_fetch_assoc($anestid);
		$totalRows_anestid = mysql_num_rows($anestid);
	// delete the surgery record		
		$deleteSQL = sprintf("DELETE FROM surgery WHERE id=%s",   
			GetSQLValueString($row_surgid['id'], "int"));
		mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
	// delete the anesthesia record		
		$deleteSQL = sprintf("DELETE FROM anesthesia WHERE id=%s",   
			GetSQLValueString($row_anestid['id'], "int"));
		mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());		
	 }




  $deleteGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= str_replace('&ordchg=PatOrdersDelete.php','',$_SERVER['QUERY_STRING']); // replace function takes &ordchg=PatOrdersDelete.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_reason = "Select id, list, name, seq from dropdownlist where list = 'Rate Reason' Order By seq";
$reason = mysql_query($query_reason, $swmisconn) or die(mysql_error());
$row_reason = mysql_fetch_assoc($reason);
$totalRows_reason = mysql_num_rows($reason);

$colname_ordedit = "-1";
if (isset($_GET['id'])) {
  $colname_ordedit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordedit = sprintf("Select o.id ordid, o.medrecnum, o.visitid, o.feeid, o.status, substr(o.urgency,1,1) urg, o.item, DATE_FORMAT(o.entrydt,'%%d-%%b-%%Y %%H:%%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.rate, o.ratereason, o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee FROM orders o join fee f on o.feeid = f.id WHERE o.id = %s", $colname_ordedit);
$ordedit = mysql_query($query_ordedit, $swmisconn) or die(mysql_error());
$row_ordedit = mysql_fetch_assoc($ordedit);
$totalRows_ordedit = mysql_num_rows($ordedit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="40%">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" bgcolor="#FBD0D7">
        <tr>
          <td nowrap="nowrap" title="M:<?php echo $row_ordedit['medrecnum']  ?> V:<?php echo $row_ordedit['visitid']  ?> O:<?php echo $row_ordedit['ordid']  ?> "><?php echo $row_ordedit['entrydt']  ?></td>
          <td colspan="2" nowrap="nowrap"><div align="center" class="BlackBold_18">Order Delete&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nav11"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=hist&pge=PatOrdersView.php">Close</a></span> </div>
          <td nowrap="nowrap"> <div align="center">
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" />
          </div></td>
        </tr>
        <tr>
          <td rowspan="2" nowrap="nowrap">Dept: <span class="BlackBold_14"><?php echo $row_ordedit['dept']  ?></span></td>
          <td colspan="2" rowspan="2" nowrap="nowrap">Section:<span class="BlackBold_14"> <?php echo $row_ordedit['section']  ?></span></td>
          <td nowrap="nowrap">Order:<span class="BlackBold_14"> <?php echo $row_ordedit['name']; ?> </span></td>
        </tr>
        <tr>
          <td class="BlackBold_14" nowrap="nowrap"><?php echo $row_ordedit['item']; ?></td>
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
			  </select>		</td>
		<td nowrap="nowrap">&nbsp;</td>
		<td nowrap="nowrap">Rate Reason:
		  <select name="ratereason">
		    <option value="0" <?php if (!(strcmp(0, $row_ordedit['ratereason']))) {echo "selected=\"selected\"";} ?>>None</option>
		    <?php
do {  
?><option value="<?php echo $row_reason['id']?>"<?php if (!(strcmp($row_reason['id'], $row_ordedit['ratereason']))) {echo "selected=\"selected\"";} ?>><?php echo $row_reason['name']?></option>
		    <?php
} while ($row_reason = mysql_fetch_assoc($reason));
  $rows = mysql_num_rows($reason);
  if($rows > 0) {
      mysql_data_seek($reason, 0);
	  $row_reason = mysql_fetch_assoc($reason);
  }
?>
          </select></td>
		<td nowrap="nowrap">		  Fee: <span class="BlackBold_14"><?php echo $row_ordedit['fee']?></span></td>
        </tr>
        <tr>
          <td nowrap="nowrap">Status: <span class="BlackBold_14"><?php echo $row_ordedit['status']  ?></span></td>
          <td colspan="2" nowrap="nowrap">Urg:
            <select name="urgency" id="urgency">
            <option value="Routine">Routine</option>
            <option value="Scheduled">Scheduled</option>
            <option value="ASAP">ASAP</option>
            <option value="STAT">STAT</option>
          </select>           
          <td nowrap="nowrap">Amt Due:  <span class="BlackBold_14"><?php echo $row_ordedit['amtdue']  ?></span></td>
        </tr>
        <tr>
          <td colspan="4" nowrap="nowrap">Comments:
            <input name="comments" type="text" size="40" />

			<input name="dept" type="hidden" id="dept" value="<?php echo $row_ordedit['dept']; ?>" />
			<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="id" type="hidden" id="id" value="<?php echo $row_ordedit['ordid']; ?>" />			</td>
          </tr>
        <tr>
          <td bgcolor="#FFFF00" colspan="4" nowrap="nowrap">Deleting a Surgery will delete Surgery Order and Surgery and Anesthesia records, but not Anesthesia Orders.</td>
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
mysql_free_result($ordedit);

mysql_free_result($reason);
?>
