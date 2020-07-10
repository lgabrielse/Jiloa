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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET userid=%s, login=%s, lastname=%s, firstname=%s, docflag=%s, ptflag=%s, anflag=%s, active=%s WHERE id=%s", // do not use when encrypted
//  $updateSQL = sprintf("UPDATE users SET userid=%s, login=%s, lastname=%s, firstname=%s, docflag=%s, active=%s WHERE id=%s",  
                       GetSQLValueString($_POST['userid'], "text"),
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['docflag'], "text"),
                       GetSQLValueString($_POST['ptflag'], "text"),
                       GetSQLValueString($_POST['anflag'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "UserMenu.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$MMID_EDIT = "-1";
if (isset($_GET["id"])) {
  $MMID_EDIT = (get_magic_quotes_gpc()) ? $_GET["id"] : addslashes($_GET["id"]);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_EDIT = sprintf("SELECT id, userid, login, firstname, lastname, password, docflag, ptflag, anflag, active FROM users WHERE id = %s", $MMID_EDIT);
$EDIT = mysql_query($query_EDIT, $swmisconn) or die(mysql_error());
$row_EDIT = mysql_fetch_assoc($EDIT);
$totalRows_EDIT = mysql_num_rows($EDIT);
?>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">


<table  width="50%" border="1" bgcolor="#F8FDCE">
  <tr>
 		<td valign="top">
      <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
		  <table  width="100%" bgcolor="#F8FDCE">
		  <caption class="subtitle">
			Edit User
			</caption>
			  <tr>
				<td align="right" nowrap>User ID</td>
				<td><input name="userid" type="text" id="userid" readonly="readonly" value="<?php echo $row_EDIT['userid']; ?>" size="12" /></td>
			  </tr>
			  <tr>
			    <td align="right" nowrap>Login</td>
			    <td><input name="login" type="text" id="login" value="<?php echo $row_EDIT['login']; ?>" /></td>
		    </tr>
			  <tr>
				<td align="right" nowrap>First Name </td>
				<td><input name="firstname" type="text" id="firstname" value="<?php echo $row_EDIT['firstname']; ?>" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap>Last Name </td>
				<td><input name="lastname" type="text" id="lastname" value="<?php echo $row_EDIT['lastname']; ?>" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap>&nbsp;</td>
				<td>Use Security --> Reset user Password<br />
				  to Edit Password </td>
				<!-- remove when encrypted -->
	 			<!-- <td>Use Security <br />  <strong>Reset User Password<br />
</strong> to update password.</td>   add when encrypted -->
			  </tr>
			  <tr>
		    <td align="right" nowrap>Is Doctor </td>
			    <td><select name="docflag" id="docflag">
			      <option value="N" <?php if (!(strcmp("N", $row_EDIT['docflag']))) {echo "selected=\"selected\"";} ?>>N</option>
			      <option value="Y" <?php if (!(strcmp("Y", $row_EDIT['docflag']))) {echo "selected=\"selected\"";} ?>>Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
		    <td align="right" nowrap>Is Physiotherapist</td>
			    <td><select name="ptflag" id="ptflag">
			      <option value="N" <?php if (!(strcmp("N", $row_EDIT['ptflag']))) {echo "selected=\"selected\"";} ?>>N</option>
			      <option value="Y" <?php if (!(strcmp("Y", $row_EDIT['ptflag']))) {echo "selected=\"selected\"";} ?>>Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
		    <td align="right" nowrap>Is Anesthetist</td>
			    <td><select name="anflag" id="anflag">
			      <option value="N" <?php if (!(strcmp("N", $row_EDIT['anflag']))) {echo "selected=\"selected\"";} ?>>N</option>
			      <option value="Y" <?php if (!(strcmp("Y", $row_EDIT['anflag']))) {echo "selected=\"selected\"";} ?>>Y</option>
			      </select>
			    </td>
		    </tr>
			  <tr>
			    <td align="right" nowrap>Active</td>
			    <td><select name="active" id="active">
			      <option value="Y" <?php if (!(strcmp("Y", $row_EDIT['active']))) {echo "selected=\"selected\"";} ?>>Y</option>
			      <option value="N" <?php if (!(strcmp("N", $row_EDIT['active']))) {echo "selected=\"selected\"";} ?>>N</option>
		          </select>
			    </td>
		    </tr>
			  <tr>
				<td align="left" nowrap><input type="hidden" name="id" value="<?php echo $row_EDIT['id']; ?>">
				    <input type="hidden" name="MM_update" value="form1">
			        <a href="UserMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit User" /></td>
			  </tr>
		</table>   
      </form>
	</td>
  </tr>
</table>

<br />
<?php
mysql_free_result($EDIT);
?><?php
ob_end_flush();
?>
