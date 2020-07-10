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

if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Submit' AND isset($_POST["MM_insert"]) AND $_POST["MM_insert"] == "formvital") {
 if (isset($_POST['temp']) AND $_POST['temp'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (visitid, schedt, vital, value, comment, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['vital'], "text"),
                       GetSQLValueString($_POST['temp']*10, "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	$saved = "true";
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>InPat Vitals Add</title>
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
<table width="80%" border="1">
 <form id="formvital" name="formvital" method="post" action="<?php echo $editFormAction; ?>">
  <tr>
    <td>Time</td>
    <td><div align="center">Temp</div></td>
    <td><div align="center">Pulse</div></td>
    <td><div align="center">Resp</div></td>
    <td><div align="center">Blood Pressure </div></td>
    <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0">
        <tr>
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
          <td><select name="schedt" size="32">
            <option value="0" selected="selected">Select</option>
			<?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>  
			<!-- if 24*60*60 is changed, change $editbackto = strtotime(date("Y-m-d H:i") - (24*60*60); in PaInPatVitals.php -->
				<!--echo date('Y-m-d h:i A',$t);-->
			<option value="<?php echo $t; ?>"><?php echo date('M-d h:i A',$t);?>
			<?php }?>


			</select>	

		  </td>
		</tr>
	  </table>
	</td>
    <td>
      <table width="100%" border="0">
        <tr>
          <td><select name="temp" size="32">
            <option value="0" selected="selected">Select</option>
            <?php for ($row = 40.8; $row > 34.8; $row-=.2) {?>
              <!-- printf("%.2f", $row);--> 
            <option value="<?php echo $row; ?>"><?php echo number_format($row, 1) ;?> -- <?php echo number_format(($row * 1.8) + 32,1) ;?></option>
            <?php }?>
          </select></td>
        </tr>
      </table>
        <input name="visitid" type="hidden" value="<?php echo $_SESSION['vid']; ?>" />
        <input name="vital" type="hidden" value="temp" />
        <input name="comment" type="hidden" value="" />
      	<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
      	<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
        <input name="MM_insert" type="hidden" value="formvital" />
    </td>
 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td valign="top">VID:<?php echo $_SESSION['vid']; ?></td>
  </tr>
        </form>
</table>

</body>
</html>
