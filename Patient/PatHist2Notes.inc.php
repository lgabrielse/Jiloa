<?php 
	mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, notetype, notes, temp, pulse, bp_sys, bp_dia, entryby, DATE_FORMAT(entrydt,'%a,%b %d %Y') entrydt, DATE_FORMAT(entrydt,'%H:%i') entrytime FROM patnotes WHERE visitid = '".$visitid."'";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);

if($totalRows_notes > 0) {
?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

<table>  
  <tr>
  	<td Colspan="11"> <div align="center" class="BlackBold_18">------------------------------------------------------------------------- Notes  -------------------------------------------------------------- </div></td>
  </tr>
  <tr>
    <td><?php echo str_repeat("&nbsp;", 80); ?></td>
    <td>&nbsp;</td>
  </tr>
</table>

<!--Begin NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - NOTES - -->
<table width="1000" style="background-color:#CAE5FF">
  <?php do { ?>
  <tr>
    <td width="110px" nowrap="nowrap"><?php echo $row_notes['entrydt']; ?><br />
Notes:<?php echo $row_notes['entrytime']; ?></td>
    <td width="110px" nowrap="nowrap" bgcolor="#FFFFFF"><?php echo $row_notes['notetype']; ?></td>
    <td width="500px" bgcolor="#FFFFFF"><?php echo $row_notes['notes']; ?></td>
    <td><div align="right">Temp:</div></td>
    <td width="30px" bgcolor="#FFFFFF"><?php echo $row_notes['temp']; ?></td>
    <td align="right">Pulse:</td>
    <td width="30px" bgcolor="#FFFFFF"><?php echo $row_notes['pulse']; ?></td>
    <td align="right">Sys:</td>
    <td width="30px" bgcolor="#FFFFFF"><?php echo $row_notes['bp_sys']; ?></td>
    <td align="right">Dia:</td>
    <td width="30px" bgcolor="#FFFFFF"><?php echo $row_notes['bp_dia']; ?></td>
	</tr>
    <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
</table>	
<?php  }?>