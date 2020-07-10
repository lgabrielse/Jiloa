<?php ob_start(); ?>
<?php session_start();
// clicking reset in html below will run the following code
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "reset")) {
	 
  $updateGoTo = "Logout.php";  // logout will delete the token record and session variables and send user back to login
  header(sprintf("Location: %s", $updateGoTo));
}

//      unset($_SESSION['user']);;
//      unset($_SESSION['token']);;
//      unset($_SESSION['sysconn']);;
//      unset($_SESSION['sysdata']);;
 
//	  session_unset();     // unset $_SESSION variable for the run-time 
//      session_destroy();   // destroy session data in storage
?>
<?php
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>2ND LOGIN</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="" method="post" name="reset">
<p></p>
<p></p>
<p></p>
<div align="center" class="RedBold_24">Only one login is allowed per user or workstation</div>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<div align="center" class="RedBold_24">Click on x on the 2ND LOGIN tab above to close it. </div>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<div align="center" class="RedBold_24">Use the currently open session.</div>
		<table align="center">
			<tr>
				    <input type="hidden" name="MM_update" value="reset">
				<td align="center">If problem arises the current session and login are not available, click on 'Reset'.<input type="submit" name="Submit" value="Reset" />to go to a new login</td>
      </tr>
      	<td align="center">
      WARNING: If there is a session already open in another tab, it will be disabled when reset is clicked.
      </td>
		</table>   

</form>

</body>
</html>