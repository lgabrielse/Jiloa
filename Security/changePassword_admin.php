<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php  $pt = "Home";  //Jiloa/home.index ?>
<?php //echo $_COOKIE['loggedin'] ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php 
  if(isset($_GET['error'])) {
    $error = $_GET['error'];
  }
  else {
     $error = $_GET['error']= "";
  }
mysql_select_db($database_swmisconn, $swmisconn);
$rowid = $_GET['myid'];
$select = "SELECT lastname, firstname,userid from users where id ='$rowid'";
$getrec = mysql_query($select,$swmisconn);
if($getrec && mysql_affected_rows()== 1)
{
	while($row = mysql_fetch_array($getrec))
	{
	$identity_id = $row['userid'];
	$lastname =$row['lastname'];
	$firstname = $row['firstname'];
	}
	mysql_free_result($getrec);
}
else
{
$msg = "Can not get infirmation";
}
// logic to update password
//function to sanitize values
    function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
    $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
    }
	//end of sanitizing function
	// hide the ID of the user in a hidden field
if(isset($_POST['updatepass']))
{
define("SALT","swmis"); 
$uniqueid = $_POST['userid'];
$password =$_POST['pass1'];
$confirm_pass= $_POST['pass2'];
mysql_select_db($database_swmisconn, $swmisconn);
	if(!empty($password) && !empty($confirm_pass))
	{
		//compare the password
		if($password !=$confirm_pass)
		{
		$error = "Password mismatch";
		header("location:changePassword_admin.php?error=$error&myid=$uniqueid");
		}
		else
		{
		//data base connection
		// get sanitize values from form
		$password= clean(strip_tags($password));
		$encrypted_pass = crypt($password,SALT);
		//$account = "SELECT * FROM users WHERE password ='$crpted_pass'";
		//$select = mysql_query($account);
				//if($select && mysql_affected_rows()>= 1)
				//{
				//mysql_close($dbcon);
				//$alert = "Please select a different password";
				//header("location:changePassword.php?error=$alert");
				//exit();
				//}
				//else
				//{
				//insert the values with the encrypted password
	    		 $account = "Update users set password='$encrypted_pass' where id = '$uniqueid'";
	     		$update = mysql_query($account);
			   			if($update && mysql_affected_rows()==1)
			   			{
						$alert = "Update Successful";
						header("location:UserResetPassword.php?error=$alert");
						exit();
			   			}
			        	else
				    	{
				     	$alert = "Update Failed";
				     	header("location:UserResetPassword.php?error=$alert");
				     	exit();
				     	}
	  			//}
	}
}
	else
	{
				$alert = "OOps: Please fill all the fields";
				header("location:UserResetPassword.php?error=$alert");
				exit();
	}
}
else
{}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
	<link href="/Len/css/Level3_1.css" rel="stylesheet" type="text/css" />
    <script type="text/JavaScript">
<!--
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

<body>
<form id="form1" name="form1" method="post" onSubmit="MM_validateForm('pass1','','R','pass2','','R');return document.MM_returnValue" maxlength="8" action="changePassword_admin.php">
<table width="600px" height="250" align="center">
  <tr>
    <td height="43" colspan="2" align="center">Password Reset for:  <span class="GreenBold_18"><?php echo ("$lastname, $firstname"); ?> </span><span>with user userid:  </span><span class="GreenBold_18"><?php echo ("$identity_id");  ?></span></td>
  </tr>
<?php if(isset($_GET['error']) and !empty($_GET['error'])) { ?>
  <tr>
    <td height="43" colspan="2" align="center" class="RedBold_24"><?php echo $_GET['error'];?></td>
  </tr>

<?php  } ?>
  <tr>
    <td height="43" colspan="2" align="center">Please provide your information to change your password </td>
  </tr>
  <tr>
    <td width="276" height="40" align="center">Enter New Password:</td>
    <td width="312" align="center"><input name="pass1" type="password" id="pass1" maxlength="15" /></td>
  </tr>
  <tr>
    <td height="40" align="center">Confirm New Password: </td>
    <td align="center"><input name="pass2" type="password" id="pass2" maxlength="15"/></td>
  </tr>
  <tr>
    <td height="25" align="center"><input type="hidden" name="userid"  value="<?php echo $_GET['myid'];?>" /></td>
  	<td align="center"><input type="submit" name="updatepass" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Change Password" /></td>
  </tr>
<?php if (allow(29,4) == 1) {	?>
<?php }?>
</table>
</form>

</body>
</html>
<?php
?>
<?php ob_end_flush();?>
