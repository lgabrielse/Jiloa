<!--Begin ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - ORDERS - -->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.status, o.urgency, o.doctor, o.entryby, o.comments, f.name, f.dept from orders o join fee f on o.feeid = f.id where f.dept in ('Physiotherapy', 'Radiology', 'Surgery') and visitid = '".$visitid."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
<?php if($totalRows_orders > 0) { ?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<table>
  <tr>
  	<td Colspan="11"> <div align="center" class="BlackBold_18">----------------------------------------------------------------------- Orders  ------------------------------------------------------ </div></td>
  </tr>
	<tr>
		<td bgcolor="#c2f4bd"><span class="subtitlegr">&nbsp;</span> (Surgery, Radiology, Physiotherapy)</td>	
<!--		<td bgcolor="#c2f4bd">Order</td>	
-->		<td bgcolor="#c2f4bd">Dept</td>	
		<td bgcolor="#c2f4bd">Urgency</td>	
		<td bgcolor="#c2f4bd">Status</td>	
		<td bgcolor="#c2f4bd">Doctor</td>	
		<td bgcolor="#c2f4bd">Comments</td>	
		<td bgcolor="#c2f4bd">Entrydt</td>	
	
	</tr>
	<?php do { ?>
	<tr>
<!--		<td bgcolor="#e2ffe2"><?php echo $row_orders['ordid'] ?></td>	
-->		<td bgcolor="#e2ffe2" class="BlackBold_14"><?php echo $row_orders['name'] ?></td>	
	  <td bgcolor="#e2ffe2"><?php echo $row_orders['dept'] ?></td>	
		<td bgcolor="#e2ffe2"><?php echo $row_orders['urgency'] ?></td>	
		<td bgcolor="#e2ffe2"><?php echo $row_orders['status'] ?></td>	
		<td bgcolor="#e2ffe2"><?php echo $row_orders['doctor'] ?></td>	
		<td bgcolor="#e2ffe2"><?php echo $row_orders['comments'] ?></td>	
		<td bgcolor="#e2ffe2"><?php echo $row_orders['entrydt'] ?></td>	
	
	</tr>
    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
  	
</table><?php } ?>
