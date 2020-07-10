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
 
if (isset($_POST['SubmitDel']) AND $_POST['SubmitDel']  == 'DELETE' AND $_POST['MM_Delete'] == 'formvitaldel')  {
    if(isset($_POST['vital']) AND $_POST['vital']  == 'temp') {
       $DeleteSQL = sprintf("Delete FROM ipvitals WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($DeleteSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital']  == 'bpsd') { 
       $DeleteSQL = sprintf("Delete FROM ipvitals WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($DeleteSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital'] == 'pulse') { 
       $DeleteSQL = sprintf("Delete FROM ipvitals WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($DeleteSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital']  == 'resp') { 
       $DeleteSQL = sprintf("Delete FROM ipvitals WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($DeleteSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
    }
}
?>
<?php
$colname_VitalDelete = "-1";
if (isset($_GET['id'])) {
  $colname_VitalDelete = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_VitalDelete = sprintf("SELECT id, visitid, schedt, vital, value, value2, entryby, entrydt FROM ipvitals WHERE id = %s ORDER BY schedt ASC", $colname_VitalDelete);
$VitalDelete = mysql_query($query_VitalDelete, $swmisconn) or die(mysql_error());
$row_VitalDelete = mysql_fetch_assoc($VitalDelete);
$totalRows_VitalDelete = mysql_num_rows($VitalDelete);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Delete Vital</title>
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
	<table width="80%" border="1" align="center">
      <div align="center"></div>
	  <caption class="BlueBold_20">
	    DELETE VITALS
      </caption>
	  <form id="formvitaldel" name="formvitaldel" method="post" action="<?php echo $editFormAction; ?>">
        <tr>
          <td><div align="center">Time</div></td>
          <?php if($row_VitalDelete['vital'] == 'temp'){?>
          <td><div align="center">Temp</div></td>
          <?php } ?>
          <?php if($row_VitalDelete['vital'] == 'pulse'){?>
          <td><div align="center">Pulse</div></td>
          <?php } ?>
          <?php if($row_VitalDelete['vital'] == 'resp'){?>
          <td><div align="center">Resp</div></td>
          <?php } ?>
          <?php if($row_VitalDelete['vital'] == 'bpsd'){?>
          <td nowrap="nowrap"><div align="center">Systolic / Diastolic</div></td>
          <?php } ?>
          <td><input type="submit" name="SubmitDel" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="DELETE" /></td>
        </tr>
        <tr>
          <td><table width="100%" border="0">
              <tr>
                <?php
//  $colname_schedate = date("Y-m-d H:i"); 
//  $mkmin = date('i',strtotime($colname_schedate)) ;
//  if($mkmin >=0 && $mkmin <=15 ){
//  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(15*60));  //show 15 minutes past hour 
//  }
//  elseif($mkmin >15 && $mkmin <=30 ){
//  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(30*60));
//  }
//  elseif($mkmin >30 && $mkmin <=45 ){
//  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(45*60));
//  }
//  elseif($mkmin >45 && $mkmin <=59 ){
//  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(60*60));
//  }
//
 
?>
                <td><?php echo date('d-m-Y h:i A',$row_VitalDelete['schedt']); ?></td>
              </tr>
          </table></td>
          <td><table width="100%" border="0">
              <tr>
                <?php if($row_VitalDelete['vital'] == 'temp'){ ?>
                <td><input name="temp" size="2" maxlength="4" value="<?php echo $row_VitalDelete['value']/10 ?>" /></td>
                <?php } elseif($row_VitalDelete['vital'] == 'pulse'){ ?>
                <td><input name="pulse" size="2" maxlength="3" value="<?php echo $row_VitalDelete['value'] ?>" /></td>
                <?php } elseif($row_VitalDelete['vital'] == 'resp'){ ?>
                <td><input name="resp" size="2" maxlength="3" value="<?php echo $row_VitalDelete['value'] ?>" /></td>
                <?php } elseif($row_VitalDelete['vital'] == 'bpsd'){ ?>
                <td><input name="bps" size="2" maxlength="3" value="<?php echo $row_VitalDelete['value'] ?>" /></td>
                <td><input name="bpd"  size="2" maxlength="4" value="<?php echo $row_VitalDelete['value2'] ?>" /></td>
                <?php } ?>
              </tr>
          </table>
              <input name="vital" type="hidden" id="vital" value=<?php echo $row_VitalDelete['vital']; ?> />
              <input name="id" type="hidden" id="id" value="<?php echo $row_VitalDelete['id']; ?>" />
              <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
              <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
              <input name="MM_Delete" type="hidden" value="formvitaldel" />          </td>
          <td>&nbsp;</td>
        </tr>
      </form>
    </table>
	</body>
</html>
