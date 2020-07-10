<?php ob_start(); ?>
<?php  $pt = "User Roles "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$colsort = "role";
if (isset($_GET['sort'])) {
  $colsort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_UserRoles = "SELECT u.id u_id, u.userid, r.id r_id, r.role, ur.id ur_id FROM users u join user_role ur on u.id = ur.userid join roles r on ur.roleid = r.id Order By ". $colsort;
$UserRoles = mysql_query($query_UserRoles, $swmisconn) or die(mysql_error());
$row_UserRoles = mysql_fetch_assoc($UserRoles);
$totalRows_UserRoles = mysql_num_rows($UserRoles);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Menu</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<p>&nbsp;</p>
<table width="40%" align="center">
  <tr>
    <td valign="top">
	 <table width="70%">
      <tr>
      <tr>
        <td colspan="9"><div align="center">
			<a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
			<a href="UserMenu.php" class="navLink">User</a>&nbsp;-&nbsp;
			<a href="RoleMenu.php" class="navLink">Role</a>&nbsp;-&nbsp;
			<a href="PermitMenu.php" class="navLink">Permit</a>&nbsp;-&nbsp;
			<a href="RolePermitMenu.php" class="navLink">Role Permit</a>&nbsp;-&nbsp;
			<a href="PermitLinks.php" class="navLink">Links</a>
		</div></td>
      </tr>
      <tr>
	  <td><div align="center">
	  	<?php if (allow(7,3) == 1) { ?>
		<a href="UserRoleMenu.php?act=UserRoleAdd.php">Add</a>  <?php }?> </div></td>
	  <td colspan="2" class="subtitlebl"><div align="center" class="GreenBold_24">User Roles </div></td>
        <td>&nbsp;</td>
        <td class="subtitlebl">&nbsp;</td>
      </tr>

           <tr>
             <td class="big">&nbsp;</td>
             <td class="big">&nbsp;</td>
             <td class="big"><div align="center"><a href="UserRoleMenu.php?sort=userid">User</a></div></td>
             <td class="big">=</td>
             <td class="big"><div align="center"><a href="UserRoleMenu.php?sort=role">Role</a></div></td>
        </tr>
            <?php do { ?>
              <tr>
                <td align="center" bgcolor="#FFFFFF" class="navLink11">
				<?php if (allow(7,2) == 1) { ?>
				<a href="UserRoleMenu.php?act=UserRoleEdit.php&urid=<?php echo $row_UserRoles['ur_id']; ?>">Edit</a> <?php }?> </td>
                <td align="center" bgcolor="#FFFFFF" class="navLink11">
				<?php if (allow(7,4) == 1) { ?>
				<a href="UserRoleMenu.php?act=UserRoleDelete.php&urid=<?php echo $row_UserRoles['ur_id']; ?>">Delete</a> <?php }?> </td>
                <td align="right" nowrap="nowrap" bgcolor="#FFFFFF" class="Black_11" title="UserRecNum: <?php echo $row_UserRoles['u_id']; ?>&#10;RoleRecNum: <?php echo $row_UserRoles['r_id']; ?>&#10;UserRoleRecNum: <?php echo $row_UserRoles['ur_id']; ?>"><?php echo $row_UserRoles['userid']; ?></td>
                <td bgcolor="#FFFFFF" class="Black_11"><div align="center">=</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_11"><?php echo $row_UserRoles['role']; ?></td>
              </tr>
              <?php } while ($row_UserRoles = mysql_fetch_assoc($UserRoles)); ?>
      </table>
  </td>
  <td valign="top">
<?php if (isset($_GET['act'])) {
	$act = $_GET['act'];
 ?>	
		<table width="30%">
		  <tr>
			<td valign="top"><?php require_once($act); ?></td>
		  </tr>
		</table>
<?php } ?>
	</td>
  </tr>
</table>
</body>
</html>
<?php
ob_end_flush();
?>
