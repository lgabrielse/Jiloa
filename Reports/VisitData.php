<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_VisitCnt = "SELECT IFNULL(Count( id ),0) cnt , DATE_FORMAT( `visitdate` , '%m-%d-%y %W' ) vdate, DAYOFWEEK(`visitdate`) dow, `pat_type` FROM `patvisit`  WHERE STR_TO_DATE(visitdate, '%Y-%m-%d') > STR_TO_DATE('2014-10-15', '%Y-%m-%d') GROUP BY DATE_FORMAT( `visitdate` , '%m-%d-%y' ) , `pat_type`  ORDER BY DATE_FORMAT( `visitdate` , '%Y-%m-%d' ) desc"; 
$VisitCnt = mysql_query($query_VisitCnt, $swmisconn) or die(mysql_error());
$row_VisitCnt = mysql_fetch_assoc($VisitCnt);
$totalRows_VisitCnt = mysql_num_rows($VisitCnt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {color: #FF5FAA}
.style3 {color: #0066FF}
.style4 {color: #66AA44}
-->
</style>
</head>

<body>
<table border="1" cellpadding="1" cellspacing="1" class="tabletc">
  <tr>
    <td bgcolor="#FFFFFF"><div align="center">Date</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Out-Patient</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Antenatal</div></td>
    <td bgcolor="#FFFFFF"><div align="center">In Patient</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Visits</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Out-Patient</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Antenatal</div></td>
    <td bgcolor="#FFFFFF"><div align="center">In Patient</div></td>
    <td bgcolor="#FFFFFF"><div align="center">Visits</div></td>
  </tr>
  <?php $visitarr = array();
  		$vdate = "";
		$dow = 0;
		$OPAT = "";  		 
		$ANTE = "";   		 
		$IPAT = "";
		$i = 0;   		 
  	do {  
		if($row_VisitCnt['vdate'] <> $vdate and $vdate <> '') {  
?>
<?php		if($row_VisitCnt['dow'] < $dow) {
				$brdr = "borderbottomthinblack";
			 } else {
				$brdr = "noborders";	} 
			?>
<!--		<tr>
			<td>---</td>
		</tr>
-->
	<tr>
	    <td><?php echo $vdate.'  '.$dow; ?></td>
		<td><?php echo $OPAT; ?></td>   		 
		<td><?php echo $ANTE; ?></td>   		 
		<td><?php echo $IPAT; ?></td>
		<td><?php echo $OPAT + $ANTE + $IPAT; ?></td>   		 
<?php If($OPAT == 0){?>
	<td class="<?php echo $brdr; ?>"> </td>	
<?php 	} else {?>
		<td class="<?php echo $brdr; ?>"><?php echo str_repeat('*',$OPAT)?></span></td>  
	    <!--<td class="noborders"><?php //echo str_repeat('*',$OPAT)?></span></td> --> 
	    <!-- long dash='&#8212;'-->
<?php }?>

<?php If($ANTE == 0){?>
	<td class="<?php echo $brdr; ?>"> </td>
<?php 	} else {?>
   <td class="<?php echo $brdr; ?>"><span class="style2"><?php echo str_repeat('*',$ANTE)?></td>  <!-- long dash='&#8212;'-->
<?php }?>

<?php If($IPAT == 0){?>
	<td class="<?php echo $brdr; ?>"> </td>
<?php 	} else {?>
	    <td class="<?php echo $brdr; ?>"><span class="style3"><?php echo str_repeat('*',$IPAT)?></span></td>  <!-- long dash='&#8212;'-->
<?php }?>

<?php If($OPAT + $ANTE + $IPAT == 0){?>
	<td class="<?php echo $brdr; ?>"> </td>
<?php 	} else {?>
	    <td class="<?php echo $brdr; ?>"><span class="style4"><?php echo str_repeat('*',$OPAT + $ANTE + $IPAT )?></span></td>  <!-- long dash='&#8212;'-->
<?php }?>
	<tr>
<?php 	$visitarr[$i][0] = $row_VisitCnt['vdate'];
		$visitarr[$i][1] = $OPAT;
		$visitarr[$i][2] = $ANTE;
		$visitarr[$i][3] = $IPAT;
		$i = $i + 1;
		$vdate = "";
		$OPAT = "";  		 
		$ANTE = "";   		 
		$IPAT = "";
?>
	  <?php
			if ($row_VisitCnt['pat_type'] == 'OutPatient'){ 
				$OPAT = $row_VisitCnt['cnt']; }
			if ($row_VisitCnt['pat_type'] == 'Antenatal'){ 
				$ANTE = $row_VisitCnt['cnt']; }
			if ($row_VisitCnt['pat_type'] == 'InPatient'){ 
				$IPAT = $row_VisitCnt['cnt']; }
				
				
		} else {
			if ($row_VisitCnt['pat_type'] == 'OutPatient'){ 
				$OPAT = $row_VisitCnt['cnt']; }
			if ($row_VisitCnt['pat_type'] == 'Antenatal'){ 
				$ANTE = $row_VisitCnt['cnt']; }
			if ($row_VisitCnt['pat_type'] == 'InPatient'){ 
				$IPAT = $row_VisitCnt['cnt']; }
		}
		$dow = $row_VisitCnt['dow'] ; 
      	$vdate = $row_VisitCnt['vdate']; 
	 } while ($row_VisitCnt = mysql_fetch_assoc($VisitCnt)); 
?>
</table >

</body>
</html>
<?php
mysql_free_result($VisitCnt);
?>
