<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php // require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php 
//		$_SESSION['sysconn'] = '/Len/Connections/swmisconn.php'; 
		$_SESSION['sysconn'] = '/Len/Connections/bethanyconn.php'; 
//		$_SESSION['sysconn'] = '/Len/Connections/trainingconn.php'; 
// require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); 

//include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php');

//$mysalt='swmis';
define("SALT","swmis");
mysql_select_db($database_swmisconn, $swmisconn);
$query_users = "SELECT id, password FROM users"; 
$update = mysql_query($query_users);
if($update && mysql_affected_rows()>=1)
{
while($row = mysql_fetch_array($update))
{
$id= $row['id'];
$pass = $row['password'];
$encrypt_pass = crypt($pass,SALT);
$sqlquery ="update users set password ='$encrypt_pass' where id ='$id'";
$getdone =mysql_query($sqlquery);
	if($getdone && mysql_affected_rows() == 1)
		{
		echo("done"."$id");
		}
		else
		{
		echo("not done"."$id");
		}
}
}
else{}
?>
<?php
ob_end_flush();
?>
