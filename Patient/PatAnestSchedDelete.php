<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
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

 $saved = '';

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}  
// set variables to store merged date and time in timestamp format
	  //$exschedt = strtotime($_POST['exscheddt'].' '.$_POST['exschedtime']);
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {


if($_GET['intradate'] == 'intraopdt'){  $updateSQL = sprintf("UPDATE anesthesia SET intraopdt=%s WHERE id=%s",
                       GetSQLValueString(null, "int"),
											 GetSQLValueString($_POST['aid'], "int"));}
											 
if($_GET['intradate'] == 'aneststarttime'){  $updateSQL = sprintf("UPDATE anesthesia SET aneststarttime=%s WHERE id=%s",
                       GetSQLValueString(null, "int"),
                       GetSQLValueString($_POST['aid'], "int"));}

if($_GET['intradate'] == 'surgstarttime'){  $updateSQL = sprintf("UPDATE anesthesia SET surgstarttime=%s WHERE id=%s",
                       GetSQLValueString(null, "int"),
                       GetSQLValueString($_POST['aid'], "int"));}

if($_GET['intradate'] == 'surgendtime'){  $updateSQL = sprintf("UPDATE anesthesia SET surgendtime=%s WHERE id=%s",
                       GetSQLValueString(null, "int"),
                       GetSQLValueString($_POST['aid'], "int"));}

if($_GET['intradate'] == 'anestendtime'){  $updateSQL = sprintf("UPDATE anesthesia SET anestendtime=%s WHERE id=%s",
                       GetSQLValueString(null, "int"),
                       GetSQLValueString($_POST['aid'], "int"));}

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

$saved = 'true'; // triggers <body onload="out()"  below to close the edit window and refresh the calling page with the new data
}
?>


<?php 
	if(isset($_GET['aid'])) {
		 $col_aid = $_GET['aid'];

mysql_select_db($database_swmisconn, $swmisconn);
$query_anestintraop = "SELECT id aid, medrecnum, visitid, surgid, intraopdt, aneststarttime, surgstarttime, surgendtime, anestendtime FROM anesthesia WHERE id='". $col_aid."'";
$anestintraop = mysql_query($query_anestintraop, $swmisconn) or die(mysql_error());
$row_anestintraop = mysql_fetch_assoc($anestintraop);
$totalRows_anestintraop = mysql_num_rows($anestintraop);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Anesthesia Dates</title>
</head>

<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>


<body>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1"  bgcolor="#FBD0D7">
 <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>"> 
  <tr>
    <td><input name="id" type="text" value="<?php echo $row_anestintraop['aid']?>" /></td>
    <td>Delete <?php echo $_GET['intradate'] ?> Date</td>
    <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
  </tr>
  <tr>
    <td align="right">Begin Time:</td>
    <td><label for="intradate"></label>
      <input name="intradate" type="text" id="intradate" value="<?php echo date('Y/m/d', $row_anestintraop[$_GET['intradate']]).'  '.date('h:i_A', $row_anestintraop[$_GET['intradate']]); ?>" /></td>
    <td nowrap="nowrap"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><input type="submit" name="submit" id="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete DateTime" /></td>
  </tr>
      <input type="hidden" name="aid" id="aid" value="<?php echo $col_aid ?>" />
  <input type="hidden" name="MM_update" value="form1" />
 </form>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<script language="JavaScript" type="text/JavaScript">
function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}

function MM_closeBrWindow() { // this works too
  window.close(); 
}
</script>
</body>
</html>
<?php
mysql_free_result($anestintraop);
?>
