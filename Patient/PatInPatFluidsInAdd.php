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

if (isset($_POST['Submit']) AND $_POST['Submit'] == 'Submit' AND isset($_POST["MM_insert"]) AND $_POST["MM_insert"] == "formfluids") {
 if (isset($_POST['amt']) AND $_POST['amt'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipfluids (visitid, dt_time, fluid, in_out, amt, comment, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['dt_time'], "int"),
                       GetSQLValueString($_POST['fluid'], "text"),
                       GetSQLValueString($_POST['in_out'], "text"),
                       GetSQLValueString($_POST['amt'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>FluidIntake</title>
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
<?php } else {?>
<body>
<?php } ?>
<table width="30%">
      <form id="formfluids" name="formfluids" method="post" action="<?php echo $editFormAction; ?>">
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
          <td><select name="dt_time">
			<?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>  
			<!-- if 24*60*60 is changed, change $editbackto = strtotime(date("Y-m-d H:i") - (24*60*60); in PaInPatVitals.php -->
				<!--echo date('Y-m-d h:i A',$t);-->
			<option value="<?php echo $t; ?>"><?php echo date('M-d h:i A',$t);?>
			<?php }?>
            </select>          </td>
          <td><select name="fluid">
              <option value="water">water</option>
              <option value="soft drink">soft drink</option>
              <option value="IV">IV</option>
           <!--   <option value="food">food</option>-->
              <option value="other">other</option>
          </select></td>
          <td nowrap="nowrap"><input name="amt" type="text" id="amt" size="4" maxlength="4" />
            ml
			<input name="visitid" type="hidden" value="<?php echo $_SESSION['vid']; ?>" />
			<input name="in_out" type="hidden" value="in" />
			<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
			<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
			<input name="MM_insert" type="hidden" value="formfluids" />
		  </td>
          <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" />          </td>
        </tr>
        <tr>
          <td>Comment:</td>
          <td colspan="3"><textarea name="comment" cols="40" rows="1" id="comment"></textarea></td>
          </tr>
    </table>
	</td>
  </tr>
 </form>
</table>

</body>
</html>
