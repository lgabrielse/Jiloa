<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['SubmitEdOut']) AND $_POST['SubmitEdOut'] == 'EDIT' AND isset($_POST["MM_UpdateOut"]) AND $_POST["MM_UpdateOut"] == "formfluidsOut") {
 if (isset($_POST['amt']) AND $_POST['amt'] > 0) {
  $insertSQL = sprintf("Update ipfluids Set dt_time=%s, fluid=%s, in_out=%s, amt=%s, comment=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['dt_time'], "int"),
                       GetSQLValueString($_POST['fluid'], "text"),
                       GetSQLValueString($_POST['in_out'], "text"),
                       GetSQLValueString($_POST['amt'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	$saved = "true";
  }
 }
?>
<?php
  $colname_schedate = date("Y-m-d H:i"); 
  $mkmin = date('i',strtotime($colname_schedate)) ;
  if($mkmin >=0 && $mkmin <=15 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(15*60));  //show 15 minutes past hour 
  }
  elseif($mkmin >15 && $mkmin <=30 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(30*60));
  }
  elseif($mkmin >30 && $mkmin <=45 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(45*60));
  }
  elseif($mkmin >45 && $mkmin <=59 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(60*60));
  }

 
?>
<?php
$colname_FluidsEditOut = "-1";
if (isset($_GET['id'])) {
  $colname_FluidsEditOut = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_FluidsEditOut = sprintf("SELECT id, visitid, dt_time, fluid, in_out, amt, comment, entryby, entrydt FROM ipfluids WHERE id = %s ORDER BY dt_time ASC", $colname_FluidsEditOut);
$FluidsEditOut = mysql_query($query_FluidsEditOut, $swmisconn) or die(mysql_error());
$row_FluidsEditOut = mysql_fetch_assoc($FluidsEditOut);
$totalRows_FluidsEditOut = mysql_num_rows($FluidsEditOut);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>FluidOuttake</title>
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
<table width="30%">
      <form id="formfluidsOut" name="formfluidsOut" method="post" action="<?php echo $editFormAction; ?>">
  <tr>
    <td><table width="100%" border="1">
        &nbsp;
        <tr>
          <td><div align="center">Fluids</div></td>
          <td><div align="center" class="BlueBold_18"> OUT </div></td>
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
          <td><select name="dt_time">
            <?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>
            <option value="<?php echo $t; ?>"<?php if(!(strcmp($t, $row_FluidsEditOut['dt_time']))) {echo "selected=\"selected\"";} ?>><?php echo date('M-d h:i A',$t);?></option>
            <?php }?>
          </select></td>
		
          <td><select name="fluid">
            <option value="urine" <?php if (!(strcmp("urine", $row_FluidsEditOut['fluid']))) {echo "selected=\"selected\"";} ?>>urine</option>
            <option value="drainage" <?php if (!(strcmp("drainage", $row_FluidsEditOut['fluid']))) {echo "selected=\"selected\"";} ?>>drainage</option>
            <option value="vomit" <?php if (!(strcmp("vomit", $row_FluidsEditOut['fluid']))) {echo "selected=\"selected\"";} ?>>vomit</option>
            <option value="stool" <?php if (!(strcmp("stool", $row_FluidsEditOut['fluid']))) {echo "selected=\"selected\"";} ?>>stool</option>
            <option value="other" <?php if (!(strcmp("other", $row_FluidsEditOut['fluid']))) {echo "selected=\"selected\"";} ?>>other</option>
          </select></td>
          <td nowrap="nowrap"><input name="amt" type="text" id="amt" value="<?php echo $row_FluidsEditOut['amt']; ?>" size="4" maxlength="4" />ml</td>
			<input name="id" type="hidden" value="<?php echo $row_FluidsEditOut['id']; ?>" />
			<input name="visitid" type="hidden" value="<?php echo $row_FluidsEditOut['visitid']; ?>" />
			<input name="in_out" type="hidden" value="out" />
			<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
			<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
			<input name="MM_UpdateOut" type="hidden" value="formfluidsOut" />

          <td><input type="submit" name="SubmitEdOut" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="EDIT" />          </td>
        </tr>
        <tr>
          <td>Comment:</td>
          <td colspan="3"><textarea name="comment" value="<?php echo $row_FluidsEditOut['comment']; ?>"><?php echo $row_FluidsEditOut['comment']; ?></textarea></td>
          </tr>
    </table>
	  </td>
  </tr>
 </form>
</table>

</body>
</html>
