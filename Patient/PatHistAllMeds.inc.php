<!--Begin MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS - MEDS -  -->
<?php 
//echo 'MEDS: '.$visitid;
mysql_select_db($database_swmisconn, $swmisconn);
$query_medord = "SELECT o.id, o.visitid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.doctor, o.status, o.comments, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, o.amtpaid FROM orders o WHERE o.visitid ='".$visitid."' and feeid = '30' ORDER BY o.id ASC";  //".$visitid."
$medord = mysql_query($query_medord, $swmisconn) or die(mysql_error());
$row_medord = mysql_fetch_assoc($medord);
$totalRows_medord = mysql_num_rows($medord);


/*mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.status, o.urgency, o.doctor, o.entryby, o.comments, f.name, f.dept from orders o join fee f on o.feeid = f.id where f.dept in ('Physiotherapy', 'Radiology', 'Surgery') and visitid = '".$visitid."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
*/
?>
<?php if($totalRows_medord > 0) { ?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<table>
  <tr>
  	<td Colspan="11"> <div align="center" class="BlackBold_18">-------------------------------------------------------------------- Medications  --------------------------------------------------------- </div></td>
  </tr>
	<tr>
		<td nowrap bgcolor="#c2f4bd"><span class="subtitlegr">&nbsp;</span> Ordered</td>	
		<td bgcolor="#c2f4bd">Status</td>	
		<td bgcolor="#c2f4bd">Doctor</td>	
		<td bgcolor="#c2f4bd">Medication</td>	
		<td bgcolor="#c2f4bd">Prescription</td>	
    </tr> 
       <?php do { //check to se if it is scheduled
		    $bkds="btngradgrn";
			//$bkds="btngradblu100";
			$bkgd="#F5F5F5";
			//$bkgd="#32ff32";
	   ?>
	     <tr>
		  <td bgcolor="<?php echo $bkgd ?>"><?php echo $row_medord['entrydt']; ?></td>			 
		  <td bgcolor="<?php echo $bkgd ?>"><?php echo $row_medord['status']; ?></td>			 
		  <td bgcolor="<?php echo $bkgd ?>"><?php echo $row_medord['doctor']; ?></td>			 
		  <td bgcolor="<?php echo $bkgd ?>" title="Order#: <?php echo $row_medord['id']; ?>&#10; Doctor: <?php echo $row_medord['doctor']; ?>&#10; EntryDt: <?php echo $row_medord['entrydt']; ?>&#10; EntryBy: <?php echo $row_medord['entryby']; ?>&#10; Order Comments: <?php echo $row_medord['comments']; ?>">
		  <input name="item2" id="item2" size="20" class="" value="<?php echo $row_medord['item']; ?>" /></td>  
		  <!-- adding type="button" centers the text -->
		<td nowrap="nowrap" bgcolor="<?php echo $bkgd ?>"><input name="nunits" type="text" id="nunits" size="1" disabled="disabled" class="center" Value="<?php echo $row_medord['nunits']; ?>" />
		  <input name="unit" type="text" id="unit" size="5" disabled="disabled" Value="<?php echo $row_medord['unit']; ?>" />
		  Every
		  <input name="every" type="text" id="every" size="1" class="center" disabled="disabled" Value="<?php echo $row_medord['every']; ?>" />
		  <input name="every" type="text" id="every" size="3" disabled="disabled" Value="<?php echo $row_medord['evperiod']; ?>" />
		  for
		  <input name="fornum" type="text" id="fornum" size="1"  class="center" disabled="disabled" Value="<?php echo $row_medord['fornum']; ?>" />
		  <input name="forperiod" type="text" id="forperiod" size="3" disabled="disabled" Value="<?php echo $row_medord['forperiod']; ?>" /></td>
		 </tr>
     <?php } while ($row_medord = mysql_fetch_assoc($medord)); ?>
    </table>




<?php } ?>
