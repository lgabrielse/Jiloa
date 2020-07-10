<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php $saved = ""; ?>
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
  $deleteSQL = sprintf("DELETE FROM ipfluids WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
  	$saved = "true";
} ?>

<?php
$colname_FluidsDelIn = "-1";
if (isset($_GET['id'])) {
  $colname_FluidsDelIn = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_FluidsDelIn = sprintf("SELECT id, visitid, dt_time, fluid, in_out, amt, comment, entryby, entrydt FROM ipfluids WHERE in_out = 'in' AND id = %s ORDER BY dt_time ASC", $colname_FluidsDelIn);
$FluidsDelIn = mysql_query($query_FluidsDelIn, $swmisconn) or die(mysql_error());
$row_FluidsDelIn = mysql_fetch_assoc($FluidsDelIn);
$totalRows_FluidsDelIn = mysql_num_rows($FluidsDelIn);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>
<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>

<body>
<table width="30%" bgcolor="#FBD0D7">
  <form id="formfluidsIn" name="formfluidsIn" method="post" action="">
    <tr>
      <td><table width="100%" border="1">
        &nbsp;
        <tr>
          <td><div align="center">Fluids</div></td>
          <td><div align="center" class="BlueBold_18"> IN </div></td>
          <td><div align="center"></div></td>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><div align="center">Date/Time</div></td>
          <td><div align="center">Fluid</div></td>
          <td><div align="center">Amount</div></td>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><input name="dt_time" type="text" readonly-"readonly" value="<?php echo date('d-m-Y h:i A',$row_FluidsDelIn['dt_time']); ?>"/></td>
          <td><input name="fluid" type="text" readonly-"readonly" value="<?php echo $row_FluidsDelIn['fluid']; ?>"/></td>
          <td nowrap="nowrap"><input name="amt" type="text" readonly-"readonly" id="amt" value="<?php echo $row_FluidsDelIn['amt']; ?>" size="4" maxlength="4" />ml</td>
          <input name="id" type="hidden" value="<?php echo $row_FluidsDelIn['id']; ?>" />
          <input name="visitid" type="hidden" value="<?php echo $row_FluidsDelIn['visitid']; ?>" />
          <input name="in_out" type="hidden" value="in" />
          <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
          <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
          <input name="MM_UpdateIn" type="hidden" value="formfluidsIn" />
          <td><input type="submit" name="SubmitDelIn" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="DELETE" /></td>
        </tr>
        <tr>
          <td>Comment:</td>
          <td colspan="3"><textarea name="comment" value="<?php echo $row_FluidsDelIn['comment']; ?>"><?php echo $row_FluidsDelIn['comment']; ?></textarea></td>
        </tr>
      </table></td>
    </tr>
  </form>
</table>
</body>
</html>
