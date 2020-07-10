<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php // require_once('../../Connections/swmisconn.php'); ?>
<?php $pt = "Reset Pwd Select";  ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/len/Jiloa/Master/Header.php'); ?>
<?php 
if(isset($_GET['prevsort'])){
	$id_sort = $_GET['prevsort'];
	} else {
 $id_sort = "userid";
	}
if (isset($_GET['sort'])) {
  $id_sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}
 $id_active = "Y";
if (isset($_GET['active'])) {
  $id_active = (get_magic_quotes_gpc()) ? $_GET['active'] : addslashes($_GET['active']);
}
$user_search = "%";
if (isset($_GET['user'])  && strlen($_GET['user'])>1 && ($_GET["MM_update"] == "form1")) {
	$user_search = (get_magic_quotes_gpc()) ? $_GET['user'] : addslashes($_GET['user']);
  }
 $id_docflag = "%";
if (isset($_GET['docflag'])) {
  $id_docflag = (get_magic_quotes_gpc()) ? $_GET['docflag'] : addslashes($_GET['docflag']);
}
 $id_anflag = "%";
if (isset($_GET['anflag'])) {
  $id_anflag = (get_magic_quotes_gpc()) ? $_GET['anflag'] : addslashes($_GET['anflag']);
}
 $id_ptflag = "%";
if (isset($_GET['ptflag'])) {
  $id_ptflag = (get_magic_quotes_gpc()) ? $_GET['ptflag'] : addslashes($_GET['ptflag']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_users = "SELECT id, userid, login, lastname, firstname, password, docflag, ptflag, anflag, active FROM users WHERE (concat(lastname, ' ',firstname, ' ', userid)) like '%".$user_search."%' and active like '".$id_active."' and docflag like '".$id_docflag."' and anflag like '".$id_anflag."' and ptflag like '".$id_ptflag."' ORDER BY ".$id_sort;
$users = mysql_query($query_users, $swmisconn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>::. Admin Reset User</title>
	<link href="/Len/css/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if (allow(3,4) == 1) {	?>

<table width="60%" align="center">
  <tr>
    <td colspan="10"><div align="center">
  <a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
  <a href="RoleMenu.php" class="navLink">Role</a>&nbsp;-&nbsp;
  <a href="UserRoleMenu.php" class="navLink">User Role</a>&nbsp;-&nbsp;
  <a href="PermitMenu.php" class="navLink">Permit</a>&nbsp;-&nbsp;
  <a href="RolePermitMenu.php" class="navLink">Role Permit</a>&nbsp;-&nbsp;
  <a href="PermitLinks.php" class="navLink">Links</a>
</div></td>
  </tr>
  <tr>
<?php if (allow(3,3) == 1) { ?>
    <td colspan="3"><div align="center"><a href="UserMenu.php?act=UserAdd.php">Add</a></div></td>
<?php }?>
    <td colspan="3" nowrap="nowrap" class="subtitlebl"><div align="center"></div>          <div align="center" class="GreenBold_24">Reset User Password</div>
      <div align="center">
      </div></td>
  </tr>
<form name="form1" id="form1" method="GET">
  <tr>
    <td>&nbsp;#</td>
    <td>&nbsp;</td>
    <td bgcolor="#00FFFF">Select By:</td>
    <td colspan="2" bgcolor="#00FFFF">Name: <input name="user" type="text" size="20" maxlength="30" onchange="change()" /></td>
    <td bgcolor="#00FFFF" >Active: 
      <br>
      <select name="active" onchange="change()" size="1" > 
        <option value="Y" <?php if (!(strcmp("Y", $id_active))) {echo "selected=\"selected\"";} ?>>Yes</option>
        <option value="N" <?php if (!(strcmp("N", $id_active))) {echo "selected=\"selected\"";} ?>>No</option>
        <option value="%" <?php if (!(strcmp("%", $id_active))) {echo "selected=\"selected\"";} ?>>Both</option>
      </select>
    </td>
    <td bgcolor="#00FFFF" >docflag:<br>
<select name="docflag" onchange="change()" size="1" > 
<option value="Y" <?php if (!(strcmp("Y", $id_docflag))) {echo "selected=\"selected\"";} ?>>Yes</option>
        <option value="N" <?php if (!(strcmp("N", $id_docflag))) {echo "selected=\"selected\"";} ?>>No</option>
        <option value="%" <?php if (!(strcmp("%", $id_docflag))) {echo "selected=\"selected\"";} ?>>Both</option>
    </select>
    </td>
    <td bgcolor="#00FFFF" >anflag:<br>
<select name="anflag" onchange="change()" size="1" > 
<option value="Y" <?php if (!(strcmp("Y", $id_anflag))) {echo "selected=\"selected\"";} ?>>Yes</option>
        <option value="N" <?php if (!(strcmp("N", $id_anflag))) {echo "selected=\"selected\"";} ?>>No</option>
        <option value="%" <?php if (!(strcmp("%", $id_anflag))) {echo "selected=\"selected\"";} ?>>Both</option>
    </select>
    </td>
    <td bgcolor="#00FFFF" >ptflag: 
      <br>
      <select name="ptflag" onchange="change()" size="1" > 
        <option value="Y" <?php if (!(strcmp("Y", $id_ptflag))) {echo "selected=\"selected\"";} ?>>Yes</option>
        <option value="N" <?php if (!(strcmp("N", $id_ptflag))) {echo "selected=\"selected\"";} ?>>No</option>
        <option value="%" <?php if (!(strcmp("%", $id_ptflag))) {echo "selected=\"selected\"";} ?>>Both</option>
      </select>
      <input name="prevsort" type="hidden" value="<?php echo $id_sort ?>" />
      <input type="hidden" name="MM_update" value="form1" />
    </td>

<!--<td><input name="submit" type="button" value="Select"></td>-->

    <td colspan="3" bgcolor="#00FFFF" class="BlueBold_1414">Click header<br>
to re-sort </td>
    <td class="subtitlebl">&nbsp;</td>
    <td class="subtitlebl">&nbsp;</td>
  </tr>
 </form>
  <tr>
    <td class="BlueBold_12"><?php echo $totalRows_users ?></td> 
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=id&active=<?php echo $id_active ?>">id</a></div></td>
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=userid&active=<?php echo $id_active ?>">userid</a></div></td>
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=login&active=<?php echo $id_active ?>">login</a></div></td>
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=lastname&active=<?php echo $id_active ?>">lastname</a></div></td>
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=firstname&active=<?php echo $id_active ?>">firstname</a></div></td>
    <td class="subtitlebl"><div align="center">password</div></td>
    <td nowrap="nowrap" class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=docflag desc&active=<?php echo $id_active ?>">is doc</a> </div></td>
    <td nowrap="nowrap" class="subtitlebl"><a href="UserResetPassword.php?sort=ptflag desc&active=<?php echo $id_active ?>">is PT</a> </td>
    <td nowrap="nowrap" class="subtitlebl"><a href="UserResetPassword.php?sort=anflag desc&active=<?php echo $id_active ?>">is AN</a> </td>
    <td class="subtitlebl"><div align="center"><a href="UserResetPassword.php?sort=active desc&active=<?php echo $id_active ?>">active</a></div></td>
  </tr>
      <?php do { ?>
	<tr>
    <?php if (allow(3,2) == 1) { ?>
		<td><a href=changePassword_admin.php?myid=<?php echo $row_users['id'];?> title='Edit User'><img src=../Home/b_edit.png></a>  <!--by clicking on this link, the ID should be echoed in a hiiden field in the changePass_Admin_Link  Form GUI-->
    <?php }?>	
  </td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['id']; ?></td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['userid']; ?></td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['login']; ?></td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['lastname']; ?></td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['firstname']; ?></td>
    <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['password']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['docflag']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['ptflag']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['anflag']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_users['active']; ?></td>
    <td><a href=changePassword_admin.php?myid=<?php echo $row_users['id'];?> title='Edit User'><img src=../Home/b_edit.png></a>  <!--by clicking on this link, the ID should be echoed in a hiiden field in the changePass_Admin_Link  Form GUI-->
    </td>
  </tr>
      <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
<?php
		mysql_free_result($users);
?>
</table>
<?php } else {
	echo   'NO PERMISSION';
}?>

<script>
function change(){
    document.getElementById("form1").submit();
}
</script>

</body>
</html>
<?php
ob_end_flush();
?>

