<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
// join tests t on d.list = t.ddl join fee f on t.feeid1 = f.id  and f.dept = 'LAB'
//$query_lists = "select d.name from dropdownlist d  where list = 'list' order by d.name";

mysql_select_db($database_swmisconn, $swmisconn); 
$query_lists = "Select distinct t.test, d.list from dropdownlist d join tests t on d.list = t.ddl  order by list";
$lists = mysql_query($query_lists, $swmisconn) or die(mysql_error());
$row_lists = mysql_fetch_assoc($lists);
$totalRows_lists = mysql_num_rows($lists);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DDL Summary Report</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td>test</td>
    <td>list</td>
  </tr>
  <?php do { ?>
    <tr>
      <td nowrap="nowrap"><?php echo $row_lists['test']; ?></td>
      <td nowrap="nowrap"><?php echo $row_lists['list']; ?></td>
	  
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_items = "select d.name from dropdownlist d where list = '".$row_lists['list']."' order by seq";
$items = mysql_query($query_items, $swmisconn) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);
?>	
  <?php do { ?>
      <td nowrap="nowrap"><?php echo $row_items['name']; ?></td>

    <?php } while ($row_items = mysql_fetch_assoc($items)); ?>
    </tr>
<?php } while ($row_lists = mysql_fetch_assoc($lists)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($lists);

mysql_free_result($items);
?>
