<?php 
 if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
if(!function_exists("GetSQLValueString")) {
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
}
  $saved = "";

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
  if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "formmedstat")) {
  $schedt = strtotime($_POST['scheddt'].' '.$_POST['schedtime']);
  
  if($_POST['status'] == 'ordered'){
  $updateSQL = sprintf("UPDATE ipmeds SET givendt=%s, status=%s, givenby=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString("", "text"),
                       GetSQLValueString($_SESSION['user'], "text"),
                       GetSQLValueString(date("Y-m-d H:i:s"), "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['mmid'], "int"));
  
  
  
  
  }	else {						//id, visitid, orderid, med, status,schedt, comments, givenby, entryby, entrydt
  $updateSQL = sprintf("UPDATE ipmeds SET givendt=%s, status=%s, givenby=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($schedt, "int"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['givenby'], "text"),
                       GetSQLValueString($_SESSION['user'], "text"),
                       GetSQLValueString(date("Y-m-d H:i:s"), "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['mmid'], "int"));
  }
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
}
?>



<?php
	if (isset($_GET['mmid'])) {
	  $colname_mmid = (get_magic_quotes_gpc()) ? $_GET['mmid'] : addslashes($_GET['mmid']);
	
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_medsched = "SELECT id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt from ipmeds where id
	 = '".$colname_mmid."'";
	$medsched = mysql_query($query_medsched, $swmisconn) or die(mysql_error());
	$row_medsched = mysql_fetch_assoc($medsched);
	$totalRows_medsched = mysql_num_rows($medsched);
	}?>
<?php
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_users = "Select userid from users where active = 'Y'  order by userid";
	$users = mysql_query($query_users, $swmisconn) or die(mysql_error());
	$row_users = mysql_fetch_assoc($users);
	$totalRows_users = mysql_num_rows($users);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Med Status</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>

<body>
<p>&nbsp;</p>
<table width="80%" border="0" align="center">
  <tr>
    <td><form id="formmedstat" name="formmedstat" method="post" action="PatInPatMedStatus.php">
      <table width="100%" border="1">
        <tr>
          <td nowrap="nowrap">&nbsp;</td>
          <td colspan="4" nowrap="nowrap"><div align="center" class="BlueBold_16">Update Medication Status</div></td>
          <td>Status</td>
          <td nowrap="nowrap">Given By: </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Scheduled at:</td>
			<td nowrap="nowrap" bgcolor="#FFFFFF"><?php echo date('D M-d_h:i_A' ,$row_medsched['schedt']); ?></td>
			<td title="ID: <?php echo $row_medsched['id']; ?>&#10;VisitID: <?php echo $row_medsched['visitid']; ?>&#10;OrderID: <?php echo $row_medsched['orderid']; ?>&#10;EntryDt: <?php echo $row_medsched['entrydt']; ?>&#10;EntryBy: <?php echo $row_medsched['entryby']; ?>&#10;Comments: <?php echo $row_medsched['comments']; ?>"><?php echo $row_medsched['med']; ?></td>
			<td><?php echo $row_medsched['nunits']; ?></td>
			<td><?php echo $row_medsched['unit']; ?></td>
			<td><select name="status">
              <option value="" <?php if (!(strcmp("", $row_medsched['status']))) {echo "selected=\"selected\"";} ?>></option>
              <option value="given" <?php if (!(strcmp("given", $row_medsched['status']))) {echo "selected=\"selected\"";} ?>>given</option>
              <option value="skip" <?php if (!(strcmp("skip", $row_medsched['status']))) {echo "selected=\"selected\"";} ?>>skip</option>
              <option value="ordered" <?php if (!(strcmp("ordered", $row_medsched['status']))) {echo "selected=\"selected\"";} ?>>ordered</option>
            </select></td>
            <td><select name="givenby" id="givenby">
              <?php
do {  
?>
              <option value="<?php echo $row_users['userid']?>"<?php if (!(strcmp(trim($_SESSION['user']), $row_users['userid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_users['userid']?></option>
              <?php
} while ($row_users = mysql_fetch_assoc($users));
  $rows = mysql_num_rows($users);
  if($rows > 0) {
      mysql_data_seek($users, 0);
	  $row_users = mysql_fetch_assoc($users);
  }
?>
            </select></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
      <td colspan="2"  align="right" nowrap="nowrap">Given at:	  
        <input id="scheddt" name="scheddt" type="text" size="12" maxlength="15" value="<?php echo  date('D, Y-M-d', $row_medsched['schedt']) ;?>" />        
        <input id="schedtime" name="schedtime" type="text" size="8" maxlength="10" /></td>
          <td><div align="right">Comment:</div></td>
          <td colspan="4"><textarea name="comments" cols="40" rows="2" id="comments"><?php echo $row_medsched['comments']; ?></textarea></td>
          <td>	<input type="hidden" name="MM_Update" value="formmedstat">
				<input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Update" />
				<input type="hidden" name="mmid" value="<?php echo $row_medsched['id']; ?>" /></td>
<!--set session to be used in script below-->
				<?php $_SESSION['medtime'] = date('H:i', $row_medsched['schedt']);?>
        </tr>
      </table>
        </form>
    </td>
  </tr>
</table>
<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">
ng.ready( function() {
    var my_cal = new ng.Calendar({
        input:'scheddt'
    });

    var my_timepicker = new ng.TimePicker({
        input:'schedtime',
		value:'<?php echo $_SESSION['medtime']; ?>',
    });   
});

</script>

</body>
</html>
<?php
mysql_free_result($users);
?>
