<?php ob_start(); ?>
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
 ?>

<?php
  $deleteSQL = sprintf("DELETE token FROM token WHERE userid=%s",
                       GetSQLValueString($_SESSION['user'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

?>
<!--delete all session variables-->
<?php session_start();
      unset($_SESSION['user']);;
      unset($_SESSION['token']);;
      unset($_SESSION['sysconn']);;
      unset($_SESSION['sysdata']);;
 // triple action cleaning session variables
	  session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	  setcookie("mySession",$mysessioncookie,time()-100,"/");
		
		
		
	  $insertGoTo = '../Security/Login.php';
      header(sprintf("Location: %s", $insertGoTo));
?>
<?php
ob_end_flush();
?>
