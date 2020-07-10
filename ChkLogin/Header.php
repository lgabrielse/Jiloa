<?php ob_start(); ?>
<?php  date_default_timezone_set('AMERICA/DETROIT')?>
<?php //date_default_timezone_set('AFRICA/LAGOS')?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<?php
// if(!isset($_SESSION['sysconn'])) {
//    header('Location: ../security/stop.php');
//	} else { 
 require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']);
//	}
 ?>
<?php mysql_select_db($database_swmisconn, $swmisconn); ?>

<?php // query db for token to display in show sessioin variables
mysql_select_db($database_swmisconn, $swmisconn);
$query_token = "SELECT id, userid, token, entrydt FROM token WHERE userid = '".$_SESSION['user']."'";
$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
$row_token = mysql_fetch_assoc($token);
$totalRows_token = mysql_num_rows($token);
?> 

<!--include function for security permit checks-->
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/functions.php'); ?>
<!--check whether db token number is same as session token number-->
<?php  include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/check_token.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta charset="utf-8" />
	<title>MIS Header</title>
<script language="JavaScript" type="text/JavaScript">
   var mins = 30;  //Set the number of minutes you need
    var secs = mins * 60;
    var currentSeconds = 0;
    var currentMinutes = 0;
    setTimeout('Decrement()',1000);

    function Decrement() {
        currentMinutes = Math.floor(secs / 60);
        currentSeconds = secs % 60;
        if(currentSeconds <= 9) currentSeconds = "0" + currentSeconds;
        secs--;
        document.getElementById("timerText").innerHTML = currentMinutes + ":" + currentSeconds; //Set the element id you need the time put into.
        if(secs !== -1) setTimeout('Decrement()',1000);
    }
</script>
<script type="text/javascript">
var timer;
var wait=30;
document.onkeypress=resetTimer;
document.onmousemove=resetTimer;
function resetTimer()
{
    clearTimeout(timer);
    timer=setTimeout("logout()", 60000*wait);
}

function logout()
{
    window.location.href='../Security/Logout.php';
}
</script>
<style type="text/css">

.style_header24 {
	color: #deeafa;
	font-size: 24px;
	font-weight: bold;
}
.style_header16 {
	color: #deeafa;
	font-size: 16px;
	font-weight: bold;
}
.style_date {
	color: #b7f3b8;
	font-size: 12px;
	font-weight: bold;
}
.style_login {
	color: #FFFFFF;
	font-size: 16px;
	font-weight: bold;
}

    </style>
	<link href="/Len/CSS/menustyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <table width="950" height="100px" border="1" align="center">
    <tr>
 <?php if ($_SESSION['sysdata'] == 'BETHANY') {
 		$bkgd = "#577fae";
	 } else {
		$bkgd = "#ff9933";
     } 
     $mysiteurl = $_SERVER['REQUEST_URI'];
     ?>
      <td bgcolor=<?php echo $bkgd ?> width="1000px" height="40px">
       <div id="div-header"><span class="style_header16"><?php echo $_SESSION['sysdata']; ?></span><span class="style_header16"> MEDICAL CENTER </span> <span class="style_date">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php	echo strtoupper(date('l  F d, Y   H:i')); ?> &nbsp; &nbsp; &nbsp;</span>
<?php if(isset($_SESSION['user'])) {  ?>
	     <span class="style_header16">User: &nbsp; 
	     <?php	echo strtoupper($_SESSION['user']); ?>
	     </span> &nbsp; &nbsp; &nbsp; &nbsp;<a href="../Security/Logout.php" class="style_login">Logout </a>
	    &nbsp; &nbsp; &nbsp;<span id= "timerText" class="style_date"></span></div> 
      
      </td>
<?php }
 	  else
	 {
	  $insertGoTo = "../Security/login.php";
	   header(sprintf("Location: %s", $insertGoTo));
?>
	  <!--  <a href="../Security/login.php" class="style_login"> Please Login </a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div> -->
	       </td>
<?php } ?>
   </tr>
   
<?php $showsessionvars = "Y" ?>
<?php if($showsessionvars == "Y") {
?> 
  <tr>
		<td class="sidebarFooter">variables: &nbsp;&nbsp;&nbsp;&nbsp;     Session Token:<?php	//echo($_SESSION['token']); ?>
dbtoken <?php echo $row_token['token']; ?>
<?php 
//echo $showsessionvars;
 
?>
**
<?php 
echo allow('11','4');
 
?>

<?php if (isset($_SESSION["user"])) {  ?>
		<?php echo 'session user = ' . $_SESSION['user']."<br/>"; ?>
		<?php echo 'session menuA = ' . $_SESSION['menuA']."<br/>"; ?>
		<?php echo 'session menu1 = ' . $_SESSION['menu1']."<br/>"; ?>
		<?php echo 'session menu2 = ' . $_SESSION['menu2']; ?>
		<?php echo 'session menu3 = ' . $_SESSION['menu3']."<br/>"; ?>
		<?php echo 'session swmis:  level : permit = ' . $_SESSION['swmis']; ?>
		
<?php } ?>		</td>
   </tr>
<?php } ?>
   
   <tr>
   <td>
<div>
<ul>
  <?php
// Creating query to fetch main information from mysql database table.
	$main_query = "select * from menu_main order by m_seq";
	$main_result = mysql_query($main_query);
	while($r = mysql_fetch_array($main_result)){
	if (isset($_SESSION["menuA"])) {
		$STPA = stripos($_SESSION["menuA"],':' . $r['m_permit']);
			If ($STPA > -1) {
				$url_link = "";
				if(strpos($mysiteurl,"Jiloa/medical/home/"))
				{
					$url_link = str_replace("../","../../",$r['m_link']);
				}
				else{
					$url_link = $r['m_link'];
				}
				?>
  <li><a href=<?php echo $url_link; ?>><?php echo $r['m_name'];?></a>
    <?php }}
?>
      <ul>
        <?php
	$sub1_query = "select * from menu_sub1 where m_id=".$r['id'];
	$sub1_result = mysql_query($sub1_query);
	while($r1 = mysql_fetch_array($sub1_result)){ 
		if (isset($_SESSION["menu1"])) {
			$STP1 = stripos($_SESSION["menu1"],':' . $r1['s1_permit']);
			If ($STP1 > -1) {
				$url_link = "";
				// echo $mysiteurl;
				if(strpos($mysiteurl,"Jiloa/medical/home"))
				{
					$url_link = str_replace("../","../../",$r1["s1_link"]);
				}
				else{
					$url_link = $r1['s1_link'];
				}
				?>
        <li><a href=<?php echo $url_link; ?>><?php echo $r1['s1_seq'] . "--" . $r1['s1_name']  ;?></a></li> 
		<!-- . "PM: " . $r1['s1_permit'] . "STRPOS: " . $STP1 -->
        <?php }}} ?>
      </ul>
  </li>
  <?php } ?>
</ul>
</div>	 </td>
   </tr>
</table>
<?php
mysql_free_result($main_result);
mysql_free_result($sub1_result);
//ob_end_flush();  // different from testing server to make it work 
?></body></html>