<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>

<?php

if (isset($_SESSION['user']) && isset($_SESSION['token'])) { ?>
<!--query token table for record of user having previous token-->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_token = "SELECT id, userid, token, entrydt FROM token WHERE userid = '".$_SESSION['user']."'";
$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
$row_token = mysql_fetch_assoc($token);
$totalRows_token = mysql_num_rows($token);
?> 
 
<?php
   		if($_SESSION['token'] != $row_token['token']){
    header('Location: ../security/stop.php');
   }
// if ($totalRows_token > 0) {
//   			$token = $row_token['token'];
//
//   		if($_SESSION['token'] != $token){
////      unset($_SESSION['token']);;
//
//  }
}
?>