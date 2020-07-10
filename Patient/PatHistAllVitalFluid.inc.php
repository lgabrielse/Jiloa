
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_dtti = "SELECT schedt FROM ipvitals where visitid = '".$visitid."' UNION SELECT dt_time FROM ipfluids where visitid = '".$visitid."' UNION select schedt from ipmeds where visitid = '".$visitid."' Order by schedt ";
$dtti = mysql_query($query_dtti, $swmisconn) or die(mysql_error());
$row_dtti = mysql_fetch_assoc($dtti);
$totalRows_dtti = mysql_num_rows($dtti);

if($totalRows_dtti > 0){
?>
<table border="1">
  <tr>
  	<td Colspan="16"> <div align="center" class="BlackBold_18">------------------------------------------------------- Vitals/Fluids/Meds Scheds  ------------------------------------------- </div></td>
  </tr>
	<tr>
		<td>Date/Time</td>
		<td bgcolor="#FFDEDE">Temp</td>
		<td bgcolor="#FFE4B2">Pulse</td>
		<td bgcolor="#FFFFB2">Resp</td>
		<td bgcolor="#B2D9B2">BP</td>
		<td>&nbsp;</td>
		<td bgcolor="#B2B2FF">Fluid In</td>
		<td bgcolor="#B2B2FF">ML</td>
		<td>&nbsp;</td>
		<td bgcolor="#C9B2DA">Fluid Out</td>
		<td bgcolor="#C9B2DA">ML</td>
		<td>&nbsp;</td>
		<td bgcolor="#FADAFA">Medication</td>
		<td bgcolor="#FADAFA">#</td>
		<td bgcolor="#FADAFA">Unit</td>
		<td bgcolor="#FADAFA">Status</td>
	</tr>

<?php do { //check to se if it is scheduled 
		if(date('A',$row_dtti['schedt']) == 'AM') { // set color of time display
				$dtbkgd = '#d5effc';
			} else {
				$dtbkgd = '#DFDFDF';
			}
?>
	<tr>
		<td bgcolor="<?php echo $dtbkgd ?>"><?php echo date('D M-d_h:i_A', $row_dtti['schedt']); ?></td>
<?php
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_vitals = "SELECT schedt, vital, value, value2, comment FROM ipvitals where visitid = '".$visitid."' and schedt = '".$row_dtti['schedt']."' ";
	$vitals = mysql_query($query_vitals, $swmisconn) or die(mysql_error());
	$row_vitals = mysql_fetch_assoc($vitals);
	$totalRows_vitals = mysql_num_rows($vitals);
	$temp = "";
	$pulse = "";
	$resp = "";
	$bp = "";
	do {
 			if($row_vitals['vital'] == 'temp'){
				if($row_vitals['value'] > 0){ 
				   $temp = ($row_vitals['value']/10);
		   		}
		   }
			if($row_vitals['vital'] == 'pulse'){ 
				if($row_vitals['value'] > 0){ 
 					$pulse = $row_vitals['value'];
			   }
		   }
		   
			if($row_vitals['vital'] == 'resp'){ 
				if($row_vitals['value'] > 0){ 
 					$resp = $row_vitals['value'];
		   	   }
		   }
	   
			if($row_vitals['vital'] == 'bpsd'){ 
				if($row_vitals['value'] > 0){ 
					$bp = $row_vitals['value'].'/'.$row_vitals['value2']. '</td>';
				}
			}	   
	   	} while ($row_vitals = mysql_fetch_assoc($vitals));
?>
<?php
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_fluids = "SELECT dt_time, fluid, in_out, amt, comment FROM ipfluids where visitid = '".$visitid."' and dt_time = '".$row_dtti['schedt']."' ";
	$fluids = mysql_query($query_fluids, $swmisconn) or die(mysql_error());
	$row_fluids = mysql_fetch_assoc($fluids);
	$totalRows_fluids = mysql_num_rows($fluids);

	$fluidin = "";
	$in = "";
	$fluidout = "";
	$out = "";
	do { 
 	//echo $row_fluids['fluid']; 
			if($row_fluids['in_out'] == 'in'){
				if($row_fluids['amt'] > 0){ 
				   $fluidin = $row_fluids['fluid'];
				   $in = $row_fluids['amt'];
		   		}
		   }

 			if($row_fluids['in_out'] == 'out'){
				if($row_fluids['amt'] > 0){ 
				   $fluidout = $row_fluids['fluid'];
				   $out = $row_fluids['amt'];
		   		}
		   }

	} while ($row_fluids = mysql_fetch_assoc($fluids));
?>

<?php
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_meds = "SELECT schedt, med, nunits, unit, status, comments FROM ipmeds where visitid = '".$visitid."' and schedt = '".$row_dtti['schedt']."' ";
	$meds = mysql_query($query_meds, $swmisconn) or die(mysql_error());
	$row_meds = mysql_fetch_assoc($meds);
	$totalRows_meds = mysql_num_rows($meds);

	$med = "";
	$nunits = "";
	$unit = "";
	$status = "";
	do { 
 	//echo $row_meds['med']; 
			if(!empty($row_meds['med'] )){
				if($row_meds['nunits'] > 0){ 
					$med = $row_meds['med'];
				    $nunits = $row_meds['nunits'];
					$unit = $row_meds['unit'];
					$status = $row_meds['status'];
		   		}
		   }
	} while ($row_meds = mysql_fetch_assoc($meds));
?>
	
	
		<td bgcolor="#FFB2B2"><?php echo $temp ?></td> <!-- Red-->
		<td bgcolor="#FFE4B2"><?php echo $pulse ?></td> <!--Orange-->
		<td bgcolor="#FFFFB2"><?php echo $resp ?></td>  <!--yellow-->
		<td bgcolor="#B2D9B2"><?php echo $bp ?></td>  <!--green-->
		<td>&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#B2B2FF"><?php echo $fluidin ?></td>  
		<!--blue-->
		<td bgcolor="#B2B2FF"><?php echo $in ?></td>
		<td>&nbsp;</td>    
		<td bgcolor="#C9B2DA"><?php echo $fluidout ?></td> <!--indigo-->
		<td bgcolor="#C9B2DA"><?php echo $out ?></td>
		<td>&nbsp;</td>
		<td nowrap="nowrap" bgcolor="#FADAFA"><?php echo $med ?></td> 
		<!--violot-->
		<td bgcolor="#FADAFA"><?php echo $nunits ?></td>
		<td bgcolor="#FADAFA"><?php echo $unit ?></td>
		<td bgcolor="#FADAFA"><?php echo $status ?></td>


	</tr>

<?php } while ($row_dtti = mysql_fetch_assoc($dtti)); ?>
</table>
<?php }?>
<?php
mysql_free_result($dtti);
?>
