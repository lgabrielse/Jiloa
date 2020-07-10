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
  $insertSQL = sprintf("INSERT INTO ipvitals (visitid, schedt, vital, value, comment, entryby, entrydt) VALUES (%s, %s, 'temp', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['temp']*10, "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }
  
 if (isset($_POST['pulse']) AND $_POST['pulse'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (visitid, schedt, vital, value, comment, entryby, entrydt) VALUES (%s, %s, 'pulse', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['pulse'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['resp']) AND $_POST['resp'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (visitid, schedt, vital, value, comment, entryby, entrydt) VALUES (%s, %s, 'resp', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['resp'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

 if (isset($_POST['bps']) AND $_POST['bps'] > 0 AND isset($_POST['bpd']) AND $_POST['bpd'] > 0) {
  $insertSQL = sprintf("INSERT INTO ipvitals (visitid, schedt, vital, value, value2, comment, entryby, entrydt) VALUES (%s, %s, 'bpsd', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['schedt'], "int"),
                       GetSQLValueString($_POST['bps'], "int"),
                       GetSQLValueString($_POST['bpd'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  }

	$saved = "true";

}
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
<table width="80%" border="1">
<caption class="BlueBold_20">ADD VITALS</caption>

 <form id="formvital" name="formvital" method="post" onsubmit="MM_validateForm('temp','','NinRange35:41','pulse','','NinRange15:165','resp','','NinRange1:60','bps','','NinRange50:300','bpd','','NinRange20:150');return document.MM_returnValue" action="<?php echo $editFormAction; ?>">
  <tr>
    <td><div align="center">Time</div></td>
    <td><div align="center">Temp<br />35-41</div></td>
    <td><div align="center">Pulse<br />20-200</div></td>
    <td><div align="center">Resp<br />
    10-120</div></td>
    <td><div align="center">Blood Pressure<br />
      50-300 / 
20-150</div></td>
    <td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" tabindex="0" /></td>
  </tr>
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
      <td><div align="center">
            <select name="schedt" tabindex="1"> 
              <!--size="32"-->
              <!--   <option value="0" selected="selected">Select</option>-->
              <?php for ($t = $onhour; $t >= $onhour - (24*60*60); $t-=(15*60)) { ?>  
              <!-- if 24*60*60 is changed, change $editbackto = strtotime(date("Y-m-d H:i") - (24*60*60); in PaInPatVitals.php -->
              <!--echo date('Y-m-d h:i A',$t);-->
                  <option value="<?php echo $t; ?>"><?php echo date('M-d h:i A',$t);?>
                  <?php }?>
              </select>	
            
          </div></td>
          <td nowrap="nowrap"><div align="center">
            <input name="temp" type="text" size="2" maxlength="4" tabindex="2"/>
          C</div></td>
          <td nowrap="nowrap"><div align="center">
            <input name="pulse" type="text" size="2" maxlength="3" tabindex="3"/>
          </div></td>
		  <td nowrap="nowrap"><div align="center">
            <input name="resp" type="text" size="2" maxlength="3" tabindex="4"/></div></td>
          <td nowrap="nowrap"><div align="center">
            <input name="bps" type="text" size="2" maxlength="3" tabindex="5"/>
            over
            <input name="bpd" type="text" size="2" maxlength="3" tabindex="6"/>
            
            <input name="visitid" type="hidden" value="<?php echo $_SESSION['vid']; ?>"/>
            <input name="comment" type="hidden" value="" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
            <input name="MM_insert" type="hidden" value="formvital" />
            </div></td>
 
      <td valign="top"><div align="center">VID:<?php echo $_SESSION['vid']; ?></div></td>
  </tr>
        </form>
</table>

</body>
</html>
