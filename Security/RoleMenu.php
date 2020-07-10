<?php ob_start(); ?>
?php  $pt = "Roles "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_roles = "SELECT id, role, descr, active FROM roles";
$roles = mysql_query($query_roles, $swmisconn) or die(mysql_error());
$row_roles = mysql_fetch_assoc($roles);
$totalRows_roles = mysql_num_rows($roles);
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
	 <table width="50%">
      <tr>
      <tr>
        <td colspan="9"><div align="center">
			<a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
			<a href="UserMenu.php" class="navLink">User</a>&nbsp;-&nbsp;
			<a href="UserRoleMenu.php" class="navLink">User Role</a>&nbsp;-&nbsp;
			<a href="PermitMenu.php" class="navLink">Permit</a>&nbsp;-&nbsp;
			<a href="RolePermitMenu.php" class="navLink">Role Permit</a>&nbsp;-&nbsp;
			<a href="PermitLinks.php" class="navLink">Links</a>
		</div></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
		<?php if (allow(2,3) == 1) { ?>
			<a href="RoleMenu.php?act=RoleAdd.php">Add</a> <?php }?> </div></td>
        <td colspan="2" class="subtitlebl"><div align="center" class="GreenBold_30">Roles </div></td>
        <td colspan="2" class="subtitlebl">&nbsp;</td>
      </tr>
      <tr>
        <td class="subtitlebl">&nbsp;</td>
        <td class="BlueBold_12"><?php echo $totalRows_roles ?></td>
        <td class="subtitlebl"><div align="center">id</div></td>
        <td class="subtitlebl"><div align="center">Role</div></td>
        <td class="subtitlebl"><div align="center">Description</div></td>
        <td class="subtitlebl"><div align="center">active</div></td>
      </tr>
      <?php do { ?>
      <tr>
        <td bgcolor="#FFFFFF" class="navLink11"><div align="center">
		<?php if (allow(2,2) == 1) { ?>
			<a href="RoleMenu.php?act=RoleEdit.php&id=<?php echo $row_roles['id']; ?>">Edit</a>	<?php }?> </div></td>
        <td bgcolor="#FFFFFF" class="navLink11"><div align="center">
		<?php if (allow(2,4) == 1) { ?>
		<a href="RoleMenu.php?act=RoleDelete.php&id=<?php echo $row_roles['id']; ?>">Delete</a>	<?php }?> </div></td>
        <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_roles['id']; ?></td>
        <td nowrap="nowrap" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_roles['role']; ?></td>
        <td nowrap="nowrap" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_roles['descr']; ?></td>
        <td align="center" bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_roles['active']; ?></td>
       </tr>
      <?php } while ($row_roles = mysql_fetch_assoc($roles)); ?>
<?php
		mysql_free_result($roles);
?>
    </table>
  </td>
  <td>
<?php if (isset($_GET['act'])) {
	$roleact = $_GET['act'];
 ?>	
		<table width="100%">
		  <tr>
			<td valign="top"><?php require_once($roleact); ?></td>
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
