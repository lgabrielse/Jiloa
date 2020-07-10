<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_nonorm = "Select t.id, t.test, n.testid from tests t left outer join testnormalvalues n on n.testid = t.id where n.testid IS NULL   order by test";
$nonorm = mysql_query($query_nonorm, $swmisconn) or die(mysql_error());
$row_nonorm = mysql_fetch_assoc($nonorm);
$totalRows_nonorm = mysql_num_rows($nonorm);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table>
  <tr>
    <td bgcolor="#eeeeee" colspan="2" class="BlueBold_12"><div align="center">Tests with<br /> 
    No Normal<br /> 
    Values</div></td>
  </tr>
  <tr>
    <td bgcolor="#eeeeee" class="BlueBold_12"><div align="center">id</div></td>
    <td bgcolor="#eeeeee" class="BlueBold_12"><div align="center">test</div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_nonorm['id']; ?></td>
      <td bgcolor="#FFFFFF" class="BlackBold_11"><?php echo $row_nonorm['test']; ?></td>
    </tr>
    <?php } while ($row_nonorm = mysql_fetch_assoc($nonorm)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($nonorm);
?>
