<?php ob_start(); ?>
<?php  $pt = "Permits "; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
$mm_sort = 'main,sub1';
if (isset($_GET['sort'])) {
  $mm_sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_permit = "Select id, permit, main, sub1, sub2, sub3, descr, active from permits order by ".$mm_sort;
$permit = mysql_query($query_permit, $swmisconn) or die(mysql_error());
$row_permit = mysql_fetch_assoc($permit);
$totalRows_permit = mysql_num_rows($permit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Menu</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="50%" align="center">
  <tr>
    <td valign="top">
	 <table width="50%">
		  <tr>
        <td colspan="9"><div align="center">
			<a href="SecurityMenu.php" class="navLink">Menu</a>&nbsp;-&nbsp;
			<a href="UserMenu.php" class="navLink">User</a>&nbsp;-&nbsp;
			<a href="RoleMenu.php" class="navLink">Role</a>&nbsp;-&nbsp;
			<a href="UserRoleMenu.php" class="navLink">User Role</a>&nbsp;-&nbsp;
			<a href="RolePermitMenu.php" class="navLink">Role Permit</a>&nbsp;-&nbsp;
			<a href="PermitLinks.php" class="navLink">Links</a>
		</div></td>
      </tr>
      <tr>
			<td colspan="2"><div align="center">
			<?php if (allow(6,3) == 1) { ?>
			<a href="PermitMenu.php?act=PermitAdd.php">Add</a></div></td>
			<td colspan="3" class="subtitlebl"><div align="center" class="GreenBold_24">Permits </div></td>
			<td colspan="3" class="subtitlebl">&nbsp;</td>
      </tr>
          <tr>
            <td colspan="2" class="BlueBold_12"><?php echo $totalRows_permit ?></td>
            <td class="big"><div align="center"><a href="PermitMenu.php?sort=id"><strong>id</strong></a> <?php }?> </div></td>
            <td class="big"><div align="center"><a href="PermitMenu.php?sort=permit">permit</a></div></td>
            <td class="big"><div align="center"><a href="PermitMenu.php?sort=main,sub1">main</a></div></td>
            <td class="big"><div align="center"><strong>sub1</strong></div></td>
            <td class="big"><div align="center"><strong>sub2</strong></div></td>
            <td class="big"><div align="center"><strong>active</strong></div></td>
            <td class="big"><div align="center"><strong>link</strong></div></td>
         </tr>
        <?php do { ?>
          <tr>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(6,2) == 1) { ?>
			<a href="PermitMenu.php?act=PermitEdit.php&id=<?php echo $row_permit['id']; ?>" class="nav11">Edit</a> <?php }?> </td>
			<td bgcolor="#FFFFFF" class="navLink11">
			<?php if (allow(6,4) == 1) { ?>
			<a href="PermitMenu.php?act=PermitDelete.php&id=<?php echo $row_permit['id']; ?>">Delete</a> <?php }?> </td>
			<td bgcolor="#FFFFFF" class="Black_12"><?php echo $row_permit['id']; ?></td>
			<td nowrap="nowrap" bgcolor="#FFFFFF" class="Black_12" title="<?php echo $row_permit['descr']; ?>"><?php echo $row_permit['permit']; ?></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permit['main']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permit['sub1']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permit['sub2']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><?php echo $row_permit['active']; ?></div></td>
			<td bgcolor="#FFFFFF" class="Black_12"><div align="center"><a href="PermitLinks.php?pid=<?php echo $row_permit['id']; ?>">Link</a></div></td>
		  </tr>
              <?php } while ($row_permit = mysql_fetch_assoc($permit)); ?>
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

<p>Programmer: Add a permit/max levelfor the functionality</p>
<p>In Code add<br />
 "php" "if (allow(n1,n2) == 1) { "  //n1=Permit Id Number, n2 = min level user must have.<br />
  Link to functionality<br />
"php }" </p>
</body>
</html>
<?php
ob_end_flush();
?>
