<?php ob_start(); ?>
<?php  $pt = "Users "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

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

mysql_select_db($database_swmisconn, $swmisconn);
$query_users = "SELECT id, userid, login, lastname, firstname, password, docflag, ptflag, anflag, active FROM users WHERE active like '".$id_active."' ORDER BY ".$id_sort;
$users = mysql_query($query_users, $swmisconn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Menu</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="60%" align="center">
  <tr>
    <td valign="top">
	 <table width="60%">
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
        <td class="subtitlebl"><div align="center"></div></td>
        <td class="subtitlebl"><div align="center" class="GreenBold_24">Users </div></td>
        <td class="subtitlebl"><div align="center">
		</div></td>
    <form name="form1" id="form1" method="GET">
        <td >Active: 
          <select name="active" size="1" onchange="change()">
            <option value="Y" <?php if (!(strcmp("Y", $id_active))) {echo "selected=\"selected\"";} ?>>Yes</option>
            <option value="N" <?php if (!(strcmp("N", $id_active))) {echo "selected=\"selected\"";} ?>>No</option>
            <option value="%" <?php if (!(strcmp("%", $id_active))) {echo "selected=\"selected\"";} ?>>Both</option>
          </select>
          <input name="prevsort" type="hidden" value="<?php echo $id_sort ?>" />
          </td>
     </form>
        <td colspan="3" class="BlueBold_1414">Click header to re-sort  </td>
        <td class="subtitlebl">&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
      </tr>
      <tr>
        <td class="BlueBold_12"><?php echo $totalRows_users ?></td> 
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=id&active=<?php echo $id_active ?>">id</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=userid&active=<?php echo $id_active ?>">userid</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=login&active=<?php echo $id_active ?>">login</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=lastname&active=<?php echo $id_active ?>">lastname</a></div></td>
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=firstname&active=<?php echo $id_active ?>">firstname</a></div></td>
        <td class="subtitlebl"><div align="center">password</div></td>
        <td nowrap="nowrap" class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=docflag desc&active=<?php echo $id_active ?>">is doc</a> </div></td>
        <td nowrap="nowrap" class="subtitlebl"><a href="UserMenu.php?sort=ptflag desc&active=<?php echo $id_active ?>">is PT</a> </td>
        <td nowrap="nowrap" class="subtitlebl"><a href="UserMenu.php?sort=anflag desc&active=<?php echo $id_active ?>">is AN</a> </td>
        <td class="subtitlebl"><div align="center"><a href="UserMenu.php?sort=active desc&active=<?php echo $id_active ?>">active</a></div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF" class="navLink11"><div align="center">
	<?php if (allow(3,2) == 1) { ?>
		<a href="UserMenu.php?act=UserEdit.php&id=<?php echo $row_users['id']; ?>">Edit</a>
	<?php }?>	
		</div></td>
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
       </tr>
      <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
<?php
		mysql_free_result($users);
?>
    </table>
  </td>
  <td valign="top">
<?php if (isset($_GET['act'])) {
	$useract = $_GET['act'];
 ?>	
		<table width="100%">
		  <tr>
		  	<td class="RedBold_18"><br> Delete can only be <br>done by Programmer!</td>
		  </tr>
		  <tr>
			<td valign="top"><?php require_once($useract); ?></td>
		  </tr>
		</table>
<?php } ?>
	</td>
  </tr>
</table>

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
