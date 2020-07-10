<?php //require_once('../../Connections/bethanyconn2.php'); ?>
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
 
if (isset($_POST['SubmitEd']) AND $_POST['SubmitEd']  == 'EDIT')  {
  $updateSQL = sprintf("UPDATE ipvitals SET schedt=%s, value=%s WHERE id=%s",
					   GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['valued']*10, "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  
  $saved = "true";
 // $insertGoTo = "PatShow1.php?show=PatInfoView.php&mrn=".$_SESSION['mrn'];  
 // header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php 
// http://web-tech.ga-usa.com/2012/05/creating-a-custom-hot-to-cold-temperature-color-gradient-for-use-with-rrdtool/
// Centigrade-Farenheit.xlsx
?>
<?php
$Temps2 = array(
array('Celcius', 'Fahrenheit', "#ffffff"), //$Temps2[0][0],$Temps2[0][1],$Temps2[0][2]
array(41.0, 105.8, "#FF00F0"),             //$Temps2[1][0],$Temps2[1][1],$Temps2[1][2]
array(40.8, 105.4, "#FF0050"),
array(40.6, 105.1, "#FF0030"),
array(40.4, 104.7, "#FF0010"),
array(40.2, 104.4, "#FF0a00"),
array(40.0, 104.0, "#FF1e00"), 
array(39.8, 103.6, "#FF3200"),
array(39.6, 103.3, "#FF4600"),
array(39.4, 102.9, "#FF5a00"),
array(39.2, 102.6, "#FF6e00"),
array(39.0, 102.2, "#FF8200"),
array(38.8, 101.8, "#FF9600"),
array(38.6, 101.5, "#FFaa00"),
array(38.4, 100.8, "#FFbe00"),
array(38.2, 100.8, "#FFd200"),
array(38.0, 100.4, "#FFe600"),
array(37.8, 100.0, "#FFfa00"),
array(37.6, 99.7, "#d7ff00"),
array(37.4, 99.3, "#8aff00"),
array(37.2, 99.0, "#3eff00"),
array(37.0, 98.6, "#00ff10"),
array(36.8, 98.2, "#00ff5c"),
array(36.6, 97.9, "#00ffa8"),
array(36.4, 97.5, "#00fff4"),
array(36.2, 97.2, "#00d4ff"),
array(36.0, 96.8, "#00b4ff"),
array(35.8, 96.4, "#0094ff"),
array(35.6, 96.1, "#0074ff"),
array(35.4, 95.7, "#0054ff"),
array(35.2, 95.4, "#0032ff"),
array(35.0, 95.0, "#0000ff"),
array('Edit', '', "#F3F3F3")
);
$Entry = array();  // use a second array to store Entryby and Entrydt for tooltip
	for ($zz = 0; $zz <33; $zz++){  // set values for first to columns
	 $Entry[$zz][0] = "Celcius";
	 $Entry[$zz][1] = "Farenheit";
	}
?>
<?php
$colname_Vitaledit = "-1";
if (isset($_GET['id'])) {
  $colname_Vitaledit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Vitaledit = sprintf("SELECT id, visitid, schedt, vital, value, entryby, entrydt FROM ipvitals WHERE id = %s ORDER BY schedt ASC", $colname_Vitaledit);
$Vitaledit = mysql_query($query_Vitaledit, $swmisconn) or die(mysql_error());
$row_Vitaledit = mysql_fetch_assoc($Vitaledit);
$totalRows_Vitaledit = mysql_num_rows($Vitaledit);
?>
  <?php // do {
//  		if($row_Vitaledit['vital'] == 'temp'){
//		$temped = $row_Vitaledit['value']
//		}
//		elseif($row_Vitaledit['vital'] == 'pulse'){
//		}
//     } while ($row_Vitaledit = mysql_fetch_assoc($Vitaledit)); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Vital</title>
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
 <form id="formvitaled" name="formvitaled" method="post" action="<?php echo $editFormAction; ?>">
  <tr>
    <td>Time</td>
<?php if($row_Vitaledit['vital'] == 'temp'){?>
    <td><div align="center">Temp</div></td>
<?php } ?>
<?php if($row_Vitaledit['vital'] == 'temp'){?>
    <td><div align="center">Pulse</div></td>
<?php } ?>
<?php if($row_Vitaledit['vital'] == 'temp'){?>
    <td><div align="center">Resp</div></td>
<?php } ?>
<?php if($row_Vitaledit['vital'] == 'temp'){?>
    <td><div align="center">Blood Pressure </div></td>
<?php } ?>
    <td><input type="submit" name="SubmitEd" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="EDIT" /></td>
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
          <td><select name="schedt" size="29">
            <?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>
            <option value="<?php echo $t; ?>"<?php if(!(strcmp($t, $row_Vitaledit['schedt']))) {echo "selected=\"selected\"";} ?>><?php echo date('M-d h:i A',$t);?></option>
            <!--			<option value="<?php //echo $t; ?>"><?php //echo date('M-d h:i A',$t);?>-->
            <?php }?>
          </select></td>
		</tr>
	  </table>
	</td>
    <td>

<?php if($row_Vitaledit['vital'] == 'temp'){?>
      <table width="100%" border="0">
        <tr>
          <td><select name="valued" size="29">
		  <option value="<?php echo $row_Vitaledit['value'] ?>" selected="selected">Select</option>
            <?php for ($row = 40.8; $row > 34.8; $row-=.2) {?>
     
            <option value="<?php echo $row; ?>"<?php if(!(strcmp(number_format($row, 1)*10, $row_Vitaledit['value']))) {echo "selected=\"selected\"";} ?>><?php echo number_format($row, 1) ;?> -- <?php echo number_format(($row * 1.8) + 32,1) ;?></option>
            <?php }?>
			
          </select></td>
        </tr>
<?php } ?>
      </table>
      	<input name="id" type="hidden" id="id" value="<?php echo $row_Vitaledit['id']; ?>" />
      	<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
      	<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
        <input name="MM_update" type="hidden" value="formvitaled" />
    </td>
 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td valign="top">Edit: <?php echo number_format($row_Vitaledit['value']/10,1) ?></td>
  </tr>
        </form>
</table>

</body>
</html>
