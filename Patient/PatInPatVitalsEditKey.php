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
 
if (isset($_POST['SubmitEd']) AND $_POST['SubmitEd']  == 'EDIT' AND $_POST['MM_update'] == 'formvitaled')  {
    if(isset($_POST['vital']) AND $_POST['vital']  == 'temp') {
       $updateSQL = sprintf("UPDATE ipvitals SET schedt=%s, value=%s WHERE id=%s",
					   GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['temp']*10, "int"),
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital']  == 'bpsd') { 
       $updateSQL = sprintf("UPDATE ipvitals SET schedt=%s, value=%s, value2=%s WHERE id=%s",
					   GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['bps'], "int"),
                       GetSQLValueString($_POST['bpd'], "int"),
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital'] == 'pulse') { 
       $updateSQL = sprintf("UPDATE ipvitals SET schedt=%s, value=%s WHERE id=%s",
					   GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['pulse'], "int"),
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
	   
   } elseif(isset($_POST['vital']) AND $_POST['vital']  == 'resp') { 
       $updateSQL = sprintf("UPDATE ipvitals SET schedt=%s, value=%s WHERE id=%s",
					   GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['resp'], "int"),
                       GetSQLValueString($_POST['id'], "int"));
       mysql_select_db($database_swmisconn, $swmisconn);
       $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
       $saved = "true";
    }
}
?>
<?php
$colname_Vitaledit = "-1";
if (isset($_GET['id'])) {
  $colname_Vitaledit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Vitaledit = sprintf("SELECT id, visitid, schedt, vital, value, value2, entryby, entrydt FROM ipvitals WHERE id = %s ORDER BY schedt ASC", $colname_Vitaledit);
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
<title>Untitled Document</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
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
	    EDIT VITALS
      </caption>
	  <form id="formvitaled" name="formvitaled" method="post" onsubmit="MM_validateForm('temp','','NinRange35:41','pulse','','NinRange15:165','resp','','NinRange1:60','bps','','NinRange50:350','bpd','','NinRange20:165');return document.MM_returnValue" action="<?php echo $editFormAction; ?>">
        <tr>
          <td><div align="center">Time</div></td>
          <?php if($row_Vitaledit['vital'] == 'temp'){?>
          <td><div align="center">Temp</div></td>
          <?php } ?>
          <?php if($row_Vitaledit['vital'] == 'pulse'){?>
          <td><div align="center">Pulse</div></td>
          <?php } ?>
          <?php if($row_Vitaledit['vital'] == 'resp'){?>
          <td><div align="center">Resp</div></td>
          <?php } ?>
          <?php if($row_Vitaledit['vital'] == 'bpsd'){?>
          <td nowrap="nowrap"><div align="center">Systolic / Diastolic</div></td>
          <?php } ?>
          <td><input type="submit" name="SubmitEd" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="EDIT" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="100%" border="0">
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
                <td><select name="schedt">
                    <!--size="29"-->
                    <?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>
                    <option value="<?php echo $t; ?>"<?php if(!(strcmp($t, $row_Vitaledit['schedt']))) {echo "selected=\"selected\"";} ?>><?php echo date('M-d h:i A',$t);?></option>
                    <!--			<option value="<?php //echo $t; ?>"><?php //echo date('M-d h:i A',$t);?>-->
                    <?php }?>
                </select></td>
              </tr>
          </table></td>
          <td><table width="100%" border="0">
              <tr>
                <?php if($row_Vitaledit['vital'] == 'temp'){ ?>
                <td><input name="temp" size="2" maxlength="4" value="<?php echo $row_Vitaledit['value']/10 ?>" /></td>
                <?php } elseif($row_Vitaledit['vital'] == 'pulse'){ ?>
                <td><input name="pulse" size="2" maxlength="3" value="<?php echo $row_Vitaledit['value'] ?>" /></td>
                <?php } elseif($row_Vitaledit['vital'] == 'resp'){ ?>
                <td><input name="resp" size="2" maxlength="3" value="<?php echo $row_Vitaledit['value'] ?>" /></td>
                <?php } elseif($row_Vitaledit['vital'] == 'bpsd'){ ?>
                <td><input name="bps" size="2" maxlength="3" value="<?php echo $row_Vitaledit['value'] ?>" /></td>
                <td><input name="bpd"  size="2" maxlength="4" value="<?php echo $row_Vitaledit['value2'] ?>" /></td>
                <?php } ?>
              </tr>
          </table>
              <input name="vital" type="hidden" id="vital" value=<?php echo $row_Vitaledit['vital']; ?> />
              <input name="id" type="hidden" id="id" value="<?php echo $row_Vitaledit['id']; ?>" />
              <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
              <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
              <input name="MM_update" type="hidden" value="formvitaled" />          </td>
          <td>&nbsp;</td>
          <td>Current:</td>
          <td nowrap="nowrap"><?php if($row_Vitaledit['vital'] == 'temp'){
	  			echo number_format($row_Vitaledit['value']/10,1);
	  		} 	elseif($row_Vitaledit['vital'] == 'bpsd') {
	  				echo number_format($row_Vitaledit['value']).' / '.number_format($row_Vitaledit['value2']);
	  		} 	else {
	  				echo number_format($row_Vitaledit['value']);
	   			}?></td>
        </tr>
      </form>
    </table>
	</body>
</html>
