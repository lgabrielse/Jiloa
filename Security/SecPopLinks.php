<?php ob_start(); ?>
<?php  $pt = "Permit Links "; ?>
<?php session_start(); ?>

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
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</head>

<body>

<table width="60%" align="center">
  <tr>
    <td valign="top">
	 <table width="100%">
          <tr>
            <td class="BlueBold_12"><?php echo $totalRows_permitlinks ?></td>
            <td class="button12b"><div align="center"><strong>permit</strong></div></td>
            <td class="button12b"><div align="center">permitid</div></td>
            <td class="button12b"><div align="center">permit level</div></td>
            <td class="button12b"><div align="center"><strong>link</strong></div></td>
            <td class="button12b"><div align="center"><strong>file</strong></div></td>
          </tr>
        <?php do { ?>
          <tr>
			<td class="navLink11">&nbsp;</td>
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
<tr>
    <td><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></div></td>

</tr>

 </tr>
</table>

<form id="form1" name="form1" method="post" action="">
</form>
<p>&nbsp;</p>
</body>
</html>


<?php
ob_end_flush();
?>
