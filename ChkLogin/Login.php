<?php
 error_reporting(E_ALL);
 session_start(); 
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Len/Connections/bethanyconn.php');?>
<?php $_SESSION['sysconn'] = '/Len/Connections/bethanyconn.php'; ?>
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
?>

<?php 
//$_SESSION['token'] = 1234567890;
//$_SESSION['user'] = 'L.GABRIELSE ';
//	echo session_id().'<br>';

if(isset($_SESSION['user'])) {
	    header('Location: ../security/deadend.php');
			exit;
//	echo 'U'.$_SESSION['user'].'<br>';
//	echo 'T'.$_SESSION['token'].'<br>';
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_token = "SELECT id, userid, token, entrydt FROM token WHERE userid = '".$_SESSION['user']."'";
	$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
	$row_token = mysql_fetch_assoc($token);
	$totalRows_token = mysql_num_rows($token);

  $deleteSQL = sprintf("DELETE token FROM token WHERE userid=%s",
                       GetSQLValueString($_SESSION['user'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

	    unset($_SESSION['user']);;
      unset($_SESSION['token']);;

	//session_destroy();
}
//	echo 'B'.$_SESSION['user'].'<br>';
//	echo 'T'.$_SESSION['token'].'<br>';
//
//	echo 'S'.session_id().'<br>';
//
//  exit;
?> 


<!--settings:-->
<?php $_SESSION['sysdata'] = 'BETHANY'; ?>
<?php $_SESSION['PtInfoReq'] = 'Y'; ?> <!-- use 'N' to disable Patient Info Required, use 'Y' to require Patient Info-->
<?php $_SESSION['PtPicReq'] = 'Y'; ?> <!-- use 'N' to disable Patient Photo Required, use 'Y' to require Patient Photo-->
<?php $_SESSION['Outreach'] = 'N';  // use 'N' for routine operations, use 'Y' for outreach operation which disables ordering ?>
<?php $maint = 'TEST';  //use 'ON', 'TEST' or 'OFF':  Allows ADMIN to prevent system use?>
<?php define("SALT","swmis");   //comment to disable encryption?>
<?php $myerror = "";?>

<?php  // get URL address to display Connection
function curPageURL() {
$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $url;
}
?>

<?php // Userid, password from Form
	if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
		$col_user = "-1";
		$col_pwd = "-1";
		if (isset($_POST['userid']) && isset($_POST['password'])) {
				$col_user = (get_magic_quotes_gpc()) ? $_POST['userid'] : addslashes($_POST['userid']);
				$col_pwd = (get_magic_quotes_gpc()) ? $_POST['password'] : addslashes($_POST['password']);
				$encrypt_pass = crypt($col_pwd,SALT);  //comment to disable encryption
		} else {
				$myerror = "Either user id or password is wrong";
	  }

//echo $encrypt_pass;  // g  swfpR7sQ8re4M   easter swbUiLeeyGbew
//exit;
?>

<?php 
	$_SESSION['user'] = "";   // initialize user
//query users datatable for userid and password
//comment the below code to disable password encryption
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_loguser = sprintf("SELECT id, login, userid, lastname, firstname, password FROM users WHERE Active = 'Y' AND login = '%s' and password = '$encrypt_pass'", $col_user, $col_pwd);
//comment the query above and uncomment the code below for non encrypted password
//$query_loguser = sprintf("SELECT id, login, userid, lastname, firstname, password FROM users WHERE Active = 'Y' AND login = '%s' and password = '%s'", $col_user,$col_pwd);
	$loguser = mysql_query($query_loguser, $swmisconn) or die(mysql_error());
	$row_loguser = mysql_fetch_assoc($loguser);
  $totalRows_loguser = mysql_num_rows($loguser);
?>
<!--/ set user session-->
<?php $_SESSION['user'] = $row_loguser['userid']; ?>
<!--If user logs in at another browser or pc, a new token in token table will not match in the browser wher user first logged in which will disable the first  session.  This token will be checked in Home/index.php and maybe others.-->
<!--set a random number up to 1 billion -1 to store as token-->
<?php  $_SESSION['token'] = mt_rand(0,999999999);?> 
<!--query token table for record of user having previous token-->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_token = "SELECT id, userid, token, entrydt FROM token WHERE userid = '".$_SESSION['user']."'";
$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
$row_token = mysql_fetch_assoc($token);
$totalRows_token = mysql_num_rows($token);
?> 
<!--if user has previous token, update to a new token, else if user has no token record, insert new one into token table-->
<?php 
if(isset($_SESSION['user']) && $totalRows_token > 0) {
  $updateSQL = sprintf("UPDATE token SET token=%s, entrydt=%s WHERE userid=%s",
                       GetSQLValueString($_SESSION['token'], "int"),
                       GetSQLValueString(strtotime("now"), "int"),
                       GetSQLValueString($_SESSION['user'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	} else { 
  $insertSQL = sprintf("INSERT INTO token(userid, token, entrydt) VALUES (%s, %s, %s)",
                       GetSQLValueString($_SESSION['user'], "text"),
                       GetSQLValueString($_SESSION['token'], "int"),
                       GetSQLValueString(strtotime("now"), "date"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
	}
?>


<?php 
// query role_permit and user_role tables for list of user permissions
mysql_select_db($database_swmisconn, $swmisconn);
$query_permit = sprintf("Select permitid, level from role_permit where roleid in (Select roleid from user_role where userid = '%s')", $row_loguser['id']);
$permit = mysql_query($query_permit, $swmisconn) or die(mysql_error());
$row_permit = mysql_fetch_assoc($permit);
$totalRows_permit = mysql_num_rows($permit);
?>
	
<!--/ set swmis session-->
<?php $sessionStr = " " ;  // space is deliberate to make function work
	if ($totalRows_loguser > 0) {
//	$sessionStr = "user:" . $row_loguser['userid'] . ", ";  // removed
	 do {
		$sessionStr = $sessionStr . $row_permit['level'] . ":" . $row_permit['permitid'] . ", ";
        } while ($row_permit = mysql_fetch_assoc($permit));
		$_SESSION['swmis'] = $sessionStr;
}
?>

<?php 
$uid_menu = "0";
if (isset($row_loguser['id'])) {
  $uid_menu = (get_magic_quotes_gpc()) ? $row_loguser['id'] : addslashes($row_loguser['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_menu = sprintf("SELECT distinct main, sub1, sub2, sub3 FROM permits WHERE id in (Select permitid from role_permit where roleid in (Select roleid from user_role where userid = %s))", $uid_menu);
$menu = mysql_query($query_menu, $swmisconn) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>


<!-- set menu session-->
<?php
		$_SESSION['menuA'] = '';
		$_SESSION['menu1'] = '';
		$_SESSION['menu2'] = '';
		$_SESSION['menu3'] = '';
	  $sessionStr = "";
	  $sessionStrA = "" ;
      $sessionStr1 = "" ;
      $sessionStr2 = "" ;
      $sessionStr3 = "" ;
	if ($totalRows_menu > 0) {
	 do {
		$sessionStrA = $sessionStrA . ":" .  $row_menu['main'] . ", ";
		if (isset($row_menu['sub1'])) {$sessionStr1 = $sessionStr1 . ":" .  $row_menu['main'] . $row_menu['sub1'] . ", "; } 
		if (isset($row_menu['sub2'])) {$sessionStr2 = $sessionStr2 . ":" .  $row_menu['main'] . $row_menu['sub1'] . $row_menu['sub2'] . ", ";}
		if (isset($row_menu['sub3'])) {$sessionStr3 = $sessionStr3 . ":" .  $row_menu['main'] . $row_menu['sub1'] . $row_menu['sub2'] . $row_menu['sub3'] . ", "; }
        } while ($row_menu = mysql_fetch_assoc($menu));
		$_SESSION['menuA'] = $sessionStrA;
		$_SESSION['menu1'] = $sessionStr1;
		$_SESSION['menu2'] = $sessionStr2;
		$_SESSION['menu3'] = $sessionStr3;
}
?>

<?php echo $row_menu['main']; ?>
<?php // redirect to Home/Index page if user was found
if (isset($_SESSION['user']) ) {
	// print_r($_SESSION); die;
	$mysessioncookie = json_encode($_SESSION);
	setcookie("mySession",$mysessioncookie,0,"/");
    //$LoginGoTo = "../Home/index.php"; 
	//header(sprintf("Location: %s", $LoginGoTo));
// echo '<meta http-equiv="refresh" content="0; URL=../Home/index.php">';
header("location:../Home/index.php");
//echo '<meta http-equiv="Location" content="../Home/index.php">';	 
?>
<?php
    exit;
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>LOGIN 702x</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.style1 {font-size: 36px; height:40px; font-weight:bold}
</style> 
</head>

<body onLoad="document.forms.form1.userid.focus()">
<!--http://www.wrensoft.com/forum/showthread.php?42-Input-field-active-setting-the-focus-on-page-load -->
	<div>&nbsp;</div>
	<div align="center" class="style1">Softwise Medical Information System</div>
	<div align="center" class="style1">Bethany Hospital - Gboko, Nigeria</div>
<?php if($maint == 'ON'){ ?>
	<table width="600" border="1" align="center" bgcolor="#B1D5F8" class="promo">
		<tr>
			<td class="RedBold_36"><div align="center">System Off for Maintenance</div></td>
		</tr>
	</table>
<?php }	else {  //login enabled  ?>

<?php if($maint == 'TEST'){ ?>
	<table width="600" border="1" align="center" bgcolor="#B1D5F8" class="promo">
		<tr>
			<td class="RedBold_36"><div align="center">System on for testing only!</div></td>
		</tr>
	</table>
<?php } ?>
	<table width="300" border="1" align="center" bgcolor="#B1D5F8" class="promo">
     <form name="form1" action="Login.php" method="post">
	  <tr>
		<td nowrap="nowrap" scope="col"><strong>PLEASE LOGIN
		</strong></td>
		<td>
			<table width="300" border="1">
			  <tr>
				<td scope="col"><strong>USER ID</strong></td>
				<td scope="col">
				  <input type="text" name="userid" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td><strong>PASSWORD</strong></td>
				<td><input type="password" name="password" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="Submit" value="LOGIN" /></td>
			  </tr>
		<?php if(!empty($myerror)) {  session_destroy(); ?>
			  <tr>
				<td colspan="2"><span style="color:#f00; font-size:16px"><?=$myerror;?></span></td>
			  </tr>
		<?php } ?>
			  <tr>
				<td colspan="2" nowrap="nowrap" title="<?php  echo curPageURL(); ?>">
<?php	$myUrl= curPageURL();
		
		switch ($myUrl)
		{
		case "http://localhost/Len/Jiloa/Security/Login.php":
		  echo "Server = Current Computer";
		  break;
		case "http://www.swmis.org/Len/Jiloa/Security/Login.php":
		  echo "Server = Len@Home-BethanyServer on SWMIS domain";
		  break;
		case "http://swmis.org/Len/Jiloa/Security/Login.php":
		  echo "Server = Len@Home-BethanyServer on SWMIS domain";
		  break;
		case "http://bethanyserver/Len/Jiloa/Security/Login.php":
		  echo "Server = Len@Home-BethanyServer on Home Network";
		  break;
		case "http://www.jiloa.org/Len/Jiloa/Security/Login.php":
		  echo "Server = GODaddy Server on JILOA domain";
		  break;
		default:
		  echo "Source Server Not Identified";
}
?>				</td>
			  </tr>
			</table>
		</td>
 	  </tr>
     <input type="hidden" name="MM_update" value="form1"></td>
	 </form>
</table>
<?php }  // else login enabled ?>
</body>
</html>
<?php
if (isset($_POST['userid']) && isset($_POST['password'])) {

mysql_free_result($loguser);

mysql_free_result($permit);

mysql_free_result($menu);
}
?>
