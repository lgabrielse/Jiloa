<?php ob_start(); ?>
<?php  $pt = "RolePermit "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php

/*$colsort = "role";
if (isset($_GET['sort'])) {
  $colsort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}
*/
$myrole = '%';
if (isset($_SESSION['role'])) {
	$myrole = $_SESSION['role'];
    $myrole = $myrole.'%';
}
$mypermit = '%';
if (isset($_SESSION['permit'])) {
	$mypermit = $_SESSION['permit'];
    $mypermit = $mypermit.'%';
}
if (isset($_GET['role'])) {
  $myrole = (get_magic_quotes_gpc()) ? $_GET['role'] : addslashes($_GET['role']);
  $_SESSION['role'] = $myrole;
  $myrole = $myrole.'%';
}
if (isset($_GET['permit'])) {
  $mypermit = (get_magic_quotes_gpc()) ? $_GET['permit'] : addslashes($_GET['permit']);
  $_SESSION['permit'] = $mypermit;
  $mypermit = $mypermit.'%';
}

$mysort = 'permit, role';
if (isset($_GET['sort'])) {
  $mysort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
  $_SESSION['sort'] = $mysort;
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_RolePermit = "SELECT r.id r_id, r.role, p.id p_id, p.permit, rp.id rp_id, rp.level FROM permits p join role_permit rp on p.id = rp.permitid join roles r on rp.roleid = r.id where role like '". $myrole."' and permit like '". $mypermit."' order by ".$mysort."";   // role, permit
$RolePermit = mysql_query($query_RolePermit, $swmisconn) or die(mysql_error());
$row_RolePermit = mysql_fetch_assoc($RolePermit);
$totalRows_RolePermit = mysql_num_rows($RolePermit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Menu</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" /></head>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = 'left=100,top=100';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

<body>

<table width="50%" align="center">
  <tr>
    <td valign="top">
		<table width="50%" >
      <tr>
        <td colspan="10"><div align="center">
			<a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
			<a href="UserMenu.php" class="navLink">User</a>&nbsp;-&nbsp;
			<a href="RoleMenu.php" class="navLink">Role</a>&nbsp;-&nbsp;
			<a href="UserRoleMenu.php" class="navLink">User Role</a>&nbsp;-&nbsp;
			<a href="PermitMenu.php" class="navLink">Permit</a>&nbsp;-&nbsp;
			<a href="PermitLinks.php" class="navLink">Links</a>
		</div></td>
      </tr>
      <tr>
	    <td colspan="6" class="sidebarFooter">% = all. Click on role in list to select a role. Click on Permit in list to select permit. Click on * to select all. <?php echo $mysort ?> </td>
	  </tr>
	  <tr>
		<td><div align="right">
			<?php if (allow(8,3) == 1) { ?>
			<a href="RolePermitMenu.php?act=RolePermitAdd.php">Add</a> <?php }?> </div></td>
			<td colspan="2" nowrap="nowrap" class="subtitlebl"><div align="center" class="GreenBold_24"> Role Permits </div></td>
			<td  colspan="3" nowrap="nowrap" class="subtitlebl">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="footer"><?php echo 'Selected:'  ?></td>
		    <td class="footer"><?php echo ' '  ?></td>
		    <td class="footer">Role: <?php if(isset($_SESSION['role'])) { echo $_SESSION['role' ];} ?></td>
		    <td class="footer">&nbsp;</td>
		    <td class="footer">Permit:<?php if(isset($_SESSION['permit'])) { echo $_SESSION['permit' ];}  ?></td>
		    <td class="footer"><?php echo ' '  ?></td>
	      </tr>
		  <tr>
			<td><?php echo $totalRows_RolePermit ?></td>
			<td>&nbsp;</td>
			<td nowrap="nowrap" class="BlueBold_1414"><div align="center"><strong>ROLE</strong><a href="RolePermitMenu.php?role=%&sort=role,permit"> <strong>*</strong></a></div></td>
			<td nowrap="nowrap" class="BlueBold_1414"><a href="RolePermitMenu.php?sort=p_id"> <strong>ID</strong></a></td>
			<td nowrap="nowrap" class="BlueBold_1414"><div align="center"><strong>PERMIT</strong><a href="RolePermitMenu.php?permit=%&sort=permit,role"> <strong>*</strong></a></div></td>
			<td nowrap="nowrap" class="BlueBold_1414"><div align="center"><strong>Role<br />
			  LEVEL</strong></div></td>
		  </tr>
		  <?php do { ?>
		  <tr>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(8,2) == 1) { ?>
			<a href="RolePermitMenu.php?act=RolePermitEdit.php&id=<?php echo $row_RolePermit['rp_id']; ?>">Edit</a> <?php }?> </td>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(8,4) == 1) { ?>
			<a href="RolePermitMenu.php?act=RolePermitDelete.php&id=<?php echo $row_RolePermit['rp_id']; ?>">Delete</a> <?php }?> </td>
			<td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_11" title="Role ID:<?php echo $row_RolePermit['r_id']; ?>&#10;RolePermit ID:<?php echo $row_RolePermit['rp_id']; ?>&#10;Permit ID: <?php echo $row_RolePermit['p_id']; ?>::<?php echo $row_RolePermit['permit']; ?> "><a href="RolePermitMenu.php?role=<?php echo $row_RolePermit['role']; ?>&sort=permit"><?php echo $row_RolePermit['role']; ?></a></td>
			<td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_11"><?php echo $row_RolePermit['p_id']; ?></td>
			<td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_11"><a href="RolePermitMenu.php?permit=<?php echo $row_RolePermit['permit']; ?>&sort=role"><?php echo $row_RolePermit['permit']; ?></a></td>
			<td bgcolor="#FFFFFF" class="Black_11" title="Permit ID = <?php echo $row_RolePermit['p_id']; ?>"><div align="center"><?php echo $row_RolePermit['level']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center">
			<a href="javascript:void(0)" onclick="MM_openBrWindow('SecPopLinks.php?pid=<?php echo $row_RolePermit['p_id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=350')">Link</a></div></td>
		  </tr>
		  <?php } while ($row_RolePermit = mysql_fetch_assoc($RolePermit)); ?>
	  </table>
    </td>

<?php
mysql_free_result($RolePermit);
?>
  	<td valign="top"><?php if (isset($_GET['act'])) {
	$userroleact = $_GET['act'];
 ?>
    	<table width="50%">
		  <tr>
			<td nowrap="nowrap" class="subtitlebl"></td>
		  </tr>
		  <tr>
			<td nowrap="nowrap" class="Black_10"></td>
		  </tr>
		  <tr>
			<td nowrap="nowrap" class="Black_11">Permit ID shown here is assigned to user </td>
		  </tr>
		  <tr>
			<td nowrap="nowrap" class="Black_11">activity, e.g. patient visit, Result Entry. </td>
		  </tr>
		  <tr>
			<td nowrap="nowrap" class="Black_11">Level is R-E-A-D permission for a role.</td>
		  </tr>
		  <tr>
			<td nowrap="nowrap" class="Black_11">(1=Read, 2=Edit, 3=Add, 4= Delete)</td>
		  </tr>
		  <tr>
			<td valign="top"><?php require_once($userroleact); ?></td>
		  </tr>
	  </table>
<?php } ?>
	</td>
  </tr>
</table>
</body>
</html>
<?php ob_end_flush(); ?>