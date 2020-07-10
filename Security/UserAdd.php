<?php ob_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$userid = $_POST['userid'];
$login_name = $_POST['login'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
//$encrypt_pass = crypt($_POST['password'],swmis);
$docflag = $_POST['docflag'];
$ptflag = $_POST['ptflag'];
$anflag = $_POST['anflag'];
$active = $_POST['active'];
$insertSQL = sprintf("INSERT INTO users (userid, login, lastname, firstname, docflag, ptflag, anflag, active) VALUES ('$userid', '$login_name', '$lastname', '$firstname', '$docflag', '$ptflag', '$anflag', '$active')");
                       //GetSQLValueString($_POST['userid'], "text"),
                       //GetSQLValueString($_POST['login'], "text"),
                       //GetSQLValueString($_POST['lastname'], "text"),
                       //GetSQLValueString($_POST['firstname'], "text"),
                       //GetSQLValueString($_POST['docflag'], "text"),
                       //GetSQLValueString($_POST['ptflag'], "text"),
                       //GetSQLValueString($_POST['anflag'], "text"),
                       //GetSQLValueString($_POST['active'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "UserMenu.php";

  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_users = "SELECT id, userid, lastname, firstname, password, active FROM users";
$users = mysql_query($query_users, $swmisconn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">


<table width="50%" border="1">
  <tr>
    <td>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		  <table width="100%"  bgcolor="#BCFACC">
		  <caption class="subtitle">
			Add User
			</caption>
			  <tr>
				<td align="right" nowrap="nowrap">User ID</td>
				<td><input name="userid" type="text" id="userid" size="10" autocomplete="off" /></td>
			  </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Login Name </td>
			    <td><input name="login" type="text" id="login" size="10" autocomplete="off" /></td>
		    </tr>
			  <tr>
				<td align="right" nowrap="nowrap">First Name </td>
				<td><input name="firstname" type="text" id="firstname" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Last Name </td>
				<td><input name="lastname" type="text" id="lastname" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">&nbsp;</td>
				<td>Use Security --&gt; Reset user Password<br />to Add Password </td>
			  </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Is Doctor </td>
			    <td><select name="docflag" id="docflag">
			      <option value="N">N</option>
			      <option value="Y">Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Is Physiotherapist </td>
			    <td><select name="ptflag" id="ptflag">
			      <option value="N">N</option>
			      <option value="Y">Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Is Anesthetist </td>
			    <td><select name="anflag" id="anflag">
			      <option value="N">N</option>
			      <option value="Y">Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Active</td>
			    <td><select name="active" id="active">
			      <option value="Y">Y</option>
			      <option value="N">N</option>
	            </select>			      &nbsp;&nbsp;Y / N </td>
		    </tr>
			  <tr>
				<td><a href="UserMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add User" /></td>
			  </tr>
		</table>   
		  <input type="hidden" name="MM_insert" value="form1">
	  </form>
<?php
mysql_free_result($users);
?>
<?php ob_end_flush();?>