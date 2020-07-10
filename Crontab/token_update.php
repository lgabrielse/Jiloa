<?php //ob_start(); ?>
<?php //if (session_status() == PHP_SESSION_NONE) {
    //session_start();
//} ?>
<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']);
      //require_once('../../Connections/swmisconn.php'); ?>
<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php  
$hostname_swmisconn = "localhost";
$database_swmisconn = "swmisbethany";
$username_swmisconn = "root";
$password_swmisconn = "jiloa7";
$swmisconn = mysql_pconnect($hostname_swmisconn, $username_swmisconn, $password_swmisconn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
		//$query_token = "SELECT id, userid, token, entrydt FROM token WHERE userid = '".$_SESSION['user']."'";
		$query_token = "SELECT id, userid, token, entrydt FROM token";
		$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
		$row_token = mysql_fetch_assoc($token);
		$totalRows_token = mysql_num_rows($token);

if($totalRows_token > 0) {
		
		do { // for each record in token table
			if(strtotime("now") - 2400 > $row_token['entrydt']){  //if current timestamp number minus 2400 seconds (40 min) is > token last activity 

			// keep a log of deleted tokens using this procedure	
			$insertSQL = "INSERT INTO token_out (tokenid, userid ,last_activity, timed_out) VALUES ('".$row_token['id']."', '".$row_token['userid']."', '".$row_token['entrydt']."', '".strtotime("now")."')";
			mysql_select_db($database_swmisconn, $swmisconn);
			$Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
		
			$deleteSQL = "DELETE FROM token WHERE id = '".$row_token['id']."'";  // delete token table record
			mysql_select_db($database_swmisconn, $swmisconn);
			$Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
		}
   } while ($row_token = mysql_fetch_assoc($token));
?>
<?php }  // if rows > 0 ?>
<?php
//ob_end_flush();
?>

