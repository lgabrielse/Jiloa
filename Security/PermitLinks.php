<?php ob_start(); ?>
<?php  $pt = "Permit Links "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$mm_sort = 'main, permit';
if (isset($_GET['sort'])) {
  $mm_sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

$mm_pid = '0';
if (isset($_GET['pid'])) {
  $mm_pid = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
if(isset($mm_pid) AND $mm_pid > 0) {
$query_permitlinks = "Select l.id, permit, permitid, level, file, linkname from permitlinks l join permits p on permitid = p.id where permitid = ". $mm_pid . " order by ".$mm_sort;
}
else {
$query_permitlinks = "Select l.id, permit, permitid, level, file, linkname from permitlinks l join permits p on permitid = p.id order by ".
$mm_sort;
}
$permitlinks = mysql_query($query_permitlinks, $swmisconn) or die(mysql_error());
$row_permitlinks = mysql_fetch_assoc($permitlinks);
$totalRows_permitlinks = mysql_num_rows($permitlinks);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Permit Links</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="60%" align="center">
  <tr>S
    <td valign="top">
	 <table width="100%">
      <tr>
        <td colspan="9"><div align="center">
			<a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
			<a href="UserMenu.php" class="navLink">User</a>&nbsp;-&nbsp;
			<a href="RoleMenu.php" class="navLink">Role</a>&nbsp;-&nbsp;
			<a href="UserRoleMenu.php" class="navLink">User Role</a>&nbsp;-&nbsp;
			<a href="PermitMenu.php" class="navLink">Permit</a>&nbsp;-&nbsp;
			<a href="RolePermitMenu.php" class="navLink">Role Permit</a>
		</div></td>
      </tr>
      <tr>
			<td><a href="PermitMenu.php"><--Permit</a></td>
			<?php if (allow(7,3) == 1) { ?>
			<td><div align="center">
			<a href="PermitLinks.php?act=PermitLinksAdd.php&pid=<?php echo $mm_pid ?>">Add</a></div></td> 
			<?php }?> 
			<td colspan="3" class="subtitlebl"><div align="center" class="GreenBold_24">Permit Links </div></td>
			<td colspan="3" class="subtitlebl">&nbsp;</td>
      </tr>
          <tr>
            <td colspan="2" class="BlueBold_12"><?php echo $totalRows_permitlinks ?></td>
            <td class="big"><div align="center"><strong>permit</strong></div></td>
            <td class="big"><div align="center">permitid</div></td>
            <td class="big"><div align="center">permit level</div></td>
            <td class="big"><div align="center"><strong>link</strong></div></td>
            <td class="big"><div align="center"><strong>file</strong></div></td>
          </tr>
        <?php do { ?>
          <tr>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(7,2) == 1) { ?>
			<a href="PermitLinks.php?act=PermitLinkEdit.php&pid=<?php echo $mm_pid ?>&id=<?php echo $row_permitlinks['id']; ?>" class="nav11">Edit</a><?php }?></td>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(7,4) == 1) { ?>
			<a href="PermitLinks.php?act=PermitLinkDelete.php&pid=<?php echo $mm_pid ?>&id=<?php echo $row_permitlinks['id']; ?>">Delete</a> <?php }?> </td>
			<td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_12"><?php echo $row_permitlinks['permit']; ?></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permitlinks['permitid']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permitlinks['level']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center" Title="Permlink id: <?php echo $row_permitlinks['id']; ?>">
			  <div align="left"><?php echo $row_permitlinks['linkname']; ?></div>
			</div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center" Title="Permlink id: <?php echo $row_permitlinks['id']; ?>">
			  <div align="left"><?php echo $row_permitlinks['file']; ?></div>
			</div></td>
		  </tr>
              <?php } while ($row_permitlinks = mysql_fetch_assoc($permitlinks)); ?>
      </table>
    </td>
    <td valign="top">
<?php if (isset($_GET['act'])) {
	$roleact = $_GET['act'];
 ?>	
		<table width="100%">
		  <tr>
		  	<td class="RedBold_18"><br> <br> <br> <br> Used only by Programmer!</td>
		  </tr>
		  <tr>
			<td valign="top"><?php require_once($roleact); ?></td>
		  </tr>
		</table>
<?php } ?>
	</td>
  </tr>
</table>

<p>&nbsp;</p>
</body>
</html>
<?php
ob_end_flush();
?>
