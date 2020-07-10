<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php 
// http://web-tech.ga-usa.com/2012/05/creating-a-custom-hot-to-cold-temperature-color-gradient-for-use-with-rrdtool/
// Centigrade-Farenheit.xlsx
?>
<?php
$Temps2 = array(
array('Celcius', 'Fahrenheit', "#ffffff"), //$Temps2[0][0],$Temps2[0][1],$Temps2[0][2]
array(41.0, 105.8, "#FF00F0"),             //$Temps2[1][0],$Temps2[1][1],$Temps2[1][2]
array(40.8, 105.4, "#FF0050"),
array(40.6, 105.1, "#FF0030"),
array(40.4, 104.7, "#FF0010"),
array(40.2, 104.4, "#FF0a00"),
array(40.0, 104.0, "#FF1e00"), 
array(39.8, 103.6, "#FF3200"),
array(39.6, 103.3, "#FF4600"),
array(39.4, 102.9, "#FF5a00"),
array(39.2, 102.6, "#FF6e00"),
array(39.0, 102.2, "#FF8200"),
array(38.8, 101.8, "#FF9600"),
array(38.6, 101.5, "#FFaa00"),
array(38.4, 100.8, "#FFbe00"),
array(38.2, 100.8, "#FFd200"),
array(38.0, 100.4, "#FFe600"),
array(37.8, 100.0, "#FFfa00"),
array(37.6, 99.7, "#d7ff00"),
array(37.4, 99.3, "#8aff00"),
array(37.2, 99.0, "#3eff00"),
array(37.0, 98.6, "#00ff10"),
array(36.8, 98.2, "#00ff5c"),
array(36.6, 97.9, "#00ffa8"),
array(36.4, 97.5, "#00fff4"),
array(36.2, 97.2, "#00d4ff"),
array(36.0, 96.8, "#00b4ff"),
array(35.8, 96.4, "#0094ff"),
array(35.6, 96.1, "#0074ff"),
array(35.4, 95.7, "#0054ff"),
array(35.2, 95.4, "#0032ff"),
array(35.0, 95.0, "#0000ff"),
array('Edit', '', "#F3F3F3")
);
$Temp3 = array();

$pulse = array(
array('PULSE', "#ffffff"), //$Temps2[0][0],$Temps2[0][1],$Temps2[0][2]
array(165, "#FF00F0"),             //$Temps2[1][0],$Temps2[1][1],$Temps2[1][2]
array(160, "#FF0050"),
array(155, "#FF0030"),
array(150, "#FF0010"),
array(145, "#FF0a00"),
array(140, "#FF1e00"), 
array(135, "#FF3200"),
array(130, "#FF4600"),
array(125, "#FF5a00"),
array(120, "#FF6e00"),
array(115, "#FF8200"),
array(110, "#FF9600"),
array(105, "#FFaa00"),
array(100, "#FFbe00"),
array(95, "#FFd200"),
array(90, "#FFe600"),
array(85, "#FFfa00"),
array(80, "#d7ff00"),
array(75, "#8aff00"),
array(70, "#3eff00"),
array(65, "#00ff10"),
array(60, "#00ff5c"),
array(55, "#00ffa8"),
array(50, "#00fff4"),
array(45, "#00d4ff"),
array(40, "#00b4ff"),
array(35, "#0094ff"),
array(30, "#0074ff"),
array(25, "#0054ff"),
array(20, "#0032ff"),
array(15, "#0000ff"),
array('Edit', "#F3F3F3")
);
$pulse2 = array();
$pulse3 = array();

$resp = array(
array('RESP', "#ffffff"),
array(60, "#FF7800"),
array(58, "#FF8200"),
array(56, "#FF8c00"),
array(54, "#FF9600"),
array(52, "#FFa000"),
array(50, "#FFaa00"),
array(48, "#FFb400"),
array(46, "#FFbe00"),
array(44, "#FFc800"),
array(42, "#FFd200"),
array(40, "#FFdc00"),
array(38, "#FFe600"),
array(36, "#FFf000"),
array(34, "#FFfa00"),
array(32, "#fdff00"),
array(30, "#d7ff00"),
array(28, "#b0ff00"),
array(26, "#8aff00"),
array(24, "#65ff00"),
array(22, "#3eff00"),
array(20, "#17ff00"),
array(18, "#00ff10"),
array(16, "#00ff36"),
array(14, "#00ff5c"),
array(12, "#00ff83"),
array(10, "#00ffa8"),
array(8, "#00ffd0"),
array(6, "#00fff4"),
array(4, "#00d4ff"),
array(2, "#00b4ff"),
array(1, "#0074ff"),
array('Edit', "#F3F3F3")
);
$resp2 = array();
$resp3 = array();

?>
<?php 
$bpsd = array(
array('BP', "#ffffff"), //$Temps2[0][0],$Temps2[0][1],$Temps2[0][2]
array(165, "#FF00F0"),             //$Temps2[1][0],$Temps2[1][1],$Temps2[1][2]
array(160, "#FF0050"),
array(155, "#FF0030"),
array(150, "#FF0010"),
array(145, "#FF0a00"),
array(140, "#FF1e00"), 
array(135, "#FF3200"),
array(130, "#FF4600"),
array(125, "#FF5a00"),
array(120, "#FF6e00"),
array(115, "#FF8200"),
array(110, "#FF9600"),
array(105, "#FFaa00"),
array(100, "#FFbe00"),
array(95, "#FFd200"),
array(90, "#FFe600"),
array(85, "#FFfa00"),
array(80, "#d7ff00"),
array(75, "#8aff00"),
array(70, "#3eff00"),
array(65, "#00ff10"),
array(60, "#00ff5c"),
array(55, "#00ffa8"),
array(50, "#00fff4"),
array(45, "#00d4ff"),
array(40, "#00b4ff"),
array(35, "#0094ff"),
array(30, "#0074ff"),
array(25, "#0054ff"),
array(20, "#0032ff"),
array(15, "#0000ff"),
array('Edit', "#F3F3F3")
);
$bpsd2 = array();
$bpsd3 = array();

?><!--*******************************  TEMP  ***************************************************************-->
<?php

$Entry = array();  // use a second array to store Entryby and Entrydt for tooltip
	for ($zz = 0; $zz <33; $zz++){  // set values for first to columns
	 $Entry[$zz][0] = "Celcius";
	 $Entry[$zz][1] = "Farenheit";
	}
?>

<?php
$colname_Recordset1 = "-1";
if (isset($_GET['vid'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Recordset1 = sprintf("SELECT id, visitid, schedt, vital, value, entryby, entrydt FROM ipvitals WHERE vital = 'temp' AND visitid = %s ORDER BY schedt ASC", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $swmisconn) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	$i = 3;
	
?>
  <?php do { 
		$Temps2[0][$i] = date('h:i',$row_Recordset1['schedt']); //$row_Recordset1['entryby']; $row_Recordset1['entrydt'];
		$Entry[0][$i] = date('D Y-M-d  h:i A',$row_Recordset1['schedt']); //$row_Recordset1['entryby']; $row_Recordset1['entrydt'];
		if(date('A',$row_Recordset1['schedt'])== 'AM') { // set color of time display
				$Temps3[0][$i] = '#d5effc';
			} else {
				$Temps3[0][$i] = '#ff9933';
			}
		for ($tt = 1; $tt <32; $tt++){
			$Temps2[$tt][$i] = ' ';
			$Entry[$tt][$i] = ' ';
			if($Temps2[$tt][0] == $row_Recordset1['value']/10 or $Temps2[$tt][0] == ($row_Recordset1['value']-1)/10) {
				$Temps2[$tt][$i] = $row_Recordset1['value']/10; 
				$Entry[$tt][$i] = $row_Recordset1['entryby'].' @ '.date('D Y-M-d  h:i A', strtotime($row_Recordset1['entrydt'])); 
			}
		}
		$Temps2[32][$i] = $row_Recordset1['id']; 
	 
  		$i =  $i + 1;
     } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); 
?>
<!--*******************************Pulse***************************************************************-->
<?php

$pulse2 = array();  // use a second array to store Entryby and Entrydt for tooltip
	for ($zz = 0; $zz <33; $zz++){  // set values for first to columns
	 $pulse2[$zz][0] = "Pulse";
	 //$pulse2[$zz][1] = "Farenheit";
	}
?>
<?php
$colname_rsPulse = "-1";
if (isset($_GET['vid'])) {
  $colname_rsPulse = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_rsPulse = sprintf("SELECT id, visitid, schedt, vital, value, entryby, entrydt FROM ipvitals WHERE vital = 'pulse' AND visitid = %s ORDER BY schedt ASC", $colname_rsPulse);
$rsPulse = mysql_query($query_rsPulse, $swmisconn) or die(mysql_error());
$row_rsPulse = mysql_fetch_assoc($rsPulse);
$totalRows_rsPulse = mysql_num_rows($rsPulse);
	$j = 2;
	
?>
  <?php do { 
		$pulse[0][$j] = date('h:i',$row_rsPulse['schedt']); //$row_rsPulse['entryby']; $row_rsPulse['entrydt'];
		$pulse2[0][$j] = date('D Y-M-d  h:i A',$row_rsPulse['schedt']); //$row_rsPulse['entryby']; $row_rsPulse['entrydt'];
		if(date('A',$row_rsPulse['schedt'])== 'AM') { // set color of time display
				$pulse3[0][$j] = '#d5effc';
			} else {
				$pulse3[0][$j] = '#ff9933';
			}
		for ($tt = 1; $tt <32; $tt++){
			$pulse[$tt][$j] = ' ';  // set blank by default for display of no value
			$pulse2[$tt][$j] = ' '; // set blank by default for display of no value
			if($pulse[$tt][0] >= $row_rsPulse['value']-2 and $pulse[$tt][0] <= ($row_rsPulse['value']+2)) {  // if value of color array matches db value
				$pulse[$tt][$j] = $row_rsPulse['value']; 
				$pulse2[$tt][$j] = $row_rsPulse['entryby'].' @ '.date('D Y-M-d  h:i A', strtotime($row_rsPulse['entrydt'])); 
			}
		}
		$pulse[32][$j] = $row_rsPulse['id']; 
	 
  		$j =  $j + 1;
     } while ($row_rsPulse = mysql_fetch_assoc($rsPulse)); 
?>
<!--*******************************   RESP   ***************************************************************-->
<?php

$resp2 = array();  // use a second array to store Entryby and Entrydt for tooltip
	for ($zz = 0; $zz <33; $zz++){  // set values for first to columns
	 $resp2[$zz][0] = "RESP";
	 //$resp2[$zz][1] = "Farenheit";
	}
?>
<?php
$colname_rsResp = "-1";
if (isset($_GET['vid'])) {
  $colname_rsResp = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_rsResp = sprintf("SELECT id, visitid, schedt, vital, value, entryby, entrydt FROM ipvitals WHERE vital = 'resp' AND visitid = %s ORDER BY schedt ASC", $colname_rsResp);
$rsResp = mysql_query($query_rsResp, $swmisconn) or die(mysql_error());
$row_rsResp = mysql_fetch_assoc($rsResp);
$totalRows_rsResp = mysql_num_rows($rsResp);
	$k = 2;
	
?>
  <?php do { 
		$resp[0][$k] = date('h:i',$row_rsResp['schedt']); //$row_rsResp['entryby']; $row_rsResp['entrydt'];
		$resp2[0][$k] = date('D Y-M-d  h:i A',$row_rsResp['schedt']); //$row_rsResp['entryby']; $row_rsResp['entrydt'];
		if(date('A',$row_rsResp['schedt'])== 'AM') { // set color of time display
				$resp3[0][$k] = '#d5effc';
			} else {
				$resp3[0][$k] = '#ff9933';
			}
		for ($tt = 1; $tt <32; $tt++){
			$resp[$tt][$k] = ' ';  // set blank by default for display of no value
			$resp2[$tt][$k] = ' '; // set blank by default for display of no value
			if($resp[$tt][0] >= $row_rsResp['value']-1 and $resp[$tt][0] <= ($row_rsResp['value'])) {  // if value of color array matches db value
				$resp[$tt][$k] = $row_rsResp['value']; 
				$resp2[$tt][$k] = $row_rsResp['entryby'].' @ '.date('D Y-M-d  h:i A', strtotime($row_rsResp['entrydt'])); 
			}
		}
		$resp[32][$k] = $row_rsResp['id']; 
	 
  		$k =  $k + 1;
     } while ($row_rsResp = mysql_fetch_assoc($rsResp)); 
?>
<!--*******************************   BP S/D   ***************************************************************-->
<?php

$bpsd2 = array();  // use a second array to store Entryby and Entrydt for tooltip
	for ($zz = 0; $zz <33; $zz++){  // set values for first to columns
	 $bpsd2[$zz][0] = "bpsd";
	 //$bpsd2[$zz][1] = "Farenheit";
	}
?>
<?php
$colname_rsbpsd = "-1";
if (isset($_GET['vid'])) {
  $colname_rsbpsd = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_rsbpsd = sprintf("SELECT id, visitid, schedt, vital, value, value2, entryby, entrydt FROM ipvitals WHERE vital = 'bpsd' AND visitid = %s ORDER BY schedt ASC", $colname_rsbpsd);
$rsbpsd = mysql_query($query_rsbpsd, $swmisconn) or die(mysql_error());
$row_rsbpsd = mysql_fetch_assoc($rsbpsd);
$totalRows_rsbpsd = mysql_num_rows($rsbpsd);
	$bp = 2;
	
?>
  <?php do { 
		$bpsd[0][$bp] = date('h:i',$row_rsbpsd['schedt']); //$row_rsbpsd['entryby']; $row_rsbpsd['entrydt'];
		$bpsd2[0][$bp] = date('D Y-M-d  h:i A',$row_rsbpsd['schedt']); //$row_rsbpsd['entryby']; $row_rsbpsd['entrydt'];
		if(date('A',$row_rsbpsd['schedt'])== 'AM') { // set color of time display
				$bpsd3[0][$bp] = '#d5effc';
			} else {
				$bpsd3[0][$bp] = '#ff9933';
			}
		for ($tt = 1; $tt <32; $tt++){
			$bpsd[$tt][$bp] = ' ';  // set blank by default for display of no value
			$bpsd2[$tt][$bp] = ' '; // set blank by default for display of no value
			if($bpsd[$tt][0] >= $row_rsbpsd['value2']-2 and $bpsd[$tt][0] <= ($row_rsbpsd['value2']+2)) {  // if value of color array matches db value
				$bpsd[$tt][$bp] = $row_rsbpsd['value'].'/'.$row_rsbpsd['value2']; 
				$bpsd2[$tt][$bp] = $row_rsbpsd['entryby'].' @ '.date('D Y-M-d  h:i A', strtotime($row_rsbpsd['entrydt'])); 
			}
		}
		$bpsd[32][$bp] = $row_rsbpsd['id']; 
	 
  		$bp =  $bp + 1;
     } while ($row_rsbpsd = mysql_fetch_assoc($rsbpsd)); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>InPatient Vitals</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=400,screenX=300,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
</head>

<body>


<table align="center">
	<tr>
    <?php if(allow(40,3) == 1) { ?>
		<td class="navLink">
	        <?php //if($totalRows_patinfoview <= 0 and allow(21,3) == 1) { ?>
					<a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsAddKey.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=250')">Add</a> 
        <?php  } ?>		</td>
		<td class="BlackBold_18">&nbsp;</td>
		<td colspan="2" class="BlackBold_18"><div align="center">View Vitals</div></td>
		<td class="BlackBold_18"><?php // echo date('h:i') ?></td>
		<td class="BlackBold_18"><?php //echo strtotime($Entry[0][11]).' -- '. (strtotime(date("Y-m-d H:i")) - (24*60*60)) ;?></td>
	</tr>
</table>
<!--  *************************************************** TEMP *****************************************************	-->
<?php 	if($totalRows_Recordset1 > 0){?>

<table width="80%" border="0" align="center">
  <tr>
    <td>
	<table>
<?php
for ($row = 0; $row <33; $row++) {
?>
 <tr>
<?php
  $editbackto = (strtotime(date("Y-m-d H:i")) - (24*60*60)); // edit back 24 hours 
  for ($col = 0; $col <$i; $col++) {
  	if($col > 2 and $row == 0){ ?>
        <td align="center" bgcolor="<?php echo $Temps3[0][$col] ?>" class="Black_1011" title="<?php echo $Entry[$row][$col] ?>"><?php echo $Temps2[$row][$col] ?></td>
<?php } elseif($col <> 2 and $row <> 32){  // eliminate color column
	  if($Temps2[$row][$col] == ' ') { // different background ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011"><?php echo $Temps2[$row][$col] ?></td>
<?php } else { ?>
        <td align="center" bgcolor="<?php echo $Temps2[$row][2] ?>" class="Black_1011" title="<?php echo $Entry[$row][$col] ?>"><?php echo $Temps2[$row][$col] ?></td>
<?php }		
  	}   if ($row == 32 and $col <> 0 and $col <> 1 and $col <> 2 and strtotime($Entry[0][$col]) > $editbackto){ //  edit back 24 hours ?>
  	<td>&nbsp;<?php if(allow(40,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsEditKey.php?id=<?php echo $Temps2[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">E</a>&nbsp;&nbsp;<?php } ?><?php if(allow(40,4) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsDelete.php?id=<?php echo $Temps2[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">D</a><?php } ?>
	</td>
<?php   } elseif ($col == 0 and $row == 32) { ?>
        <td align="center" bgcolor="<?php echo $Temps2[$row][2] ?>" class="Black_1011" title="<?php echo $Entry[0][0] ?>"><?php echo $Temps2[0][0] ?></td>
<?php   } elseif ($col == 1 and $row == 32) { ?>
        <td align="center" bgcolor="<?php echo $Temps2[$row][2] ?>" class="Black_1011" title="<?php echo $Entry[0][0] ?>"><?php echo $Temps2[0][1] ?></td>
<?php   } elseif ($col <> 2 and $row == 32) { ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011" title="Cannot edit if more than 24 hours ago">&nbsp;</td>
<?php	
	}
 } ?>
        </tr>

<?php } ?>
      </table>

	</td>
	<?php 	} // if($totalRows_Recordset1 > 0
	else { ?>
	<td valign="top" nowrap="nowrap"> No Temp data </td>
<?php 	} ?>

<!--  *************************************************** PULSE *****************************************************	-->
<?php 	if($totalRows_rsPulse > 0){?>

	<td valign="top">
	  <table>
<?php
for ($row = 0; $row <33; $row++) {
?>
 <tr>
<?php
  $editbackto = (strtotime(date("Y-m-d H:i")) - (24*60*60)); // edit back 24 hours 
  for ($col = 0; $col <$j; $col++) {
  	if($col > 1 and $row == 0){ ?>
        <td align="center" bgcolor="<?php echo $pulse3[0][$col] ?>" class="Black_1011" title="<?php echo $pulse2[$row][$col] ?>"><?php echo $pulse[$row][$col] ?></td>
<?php } elseif($col <> 1 and $row <> 32){  // eliminate color column
	  if($pulse[$row][$col] == ' ') { // different background ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011"><?php echo $pulse[$row][$col] ?></td>
<?php } else { ?>
        <td align="center" bgcolor="<?php echo $pulse[$row][1] ?>" class="Black_1011" title="<?php echo $pulse2[$row][$col] ?>"><?php echo $pulse[$row][$col] ?></td>
<?php }		
  	}   if ($row == 32 and $col <> 0 and $col <> 1 and strtotime($pulse2[0][$col]) > $editbackto){ //  edit back 24 hours ?>
  	<td>&nbsp;<?php if(allow(40,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsEditKey.php?id=<?php echo $pulse[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">E</a>&nbsp;&nbsp;<?php } ?><?php if(allow(40,4) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsDelete.php?id=<?php echo $pulse[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">D</a><?php } ?></td>
<?php   } elseif ($col == 0 and $row == 32) { ?>
        <td align="center" bgcolor="<?php echo $pulse[$row][1] ?>" class="Black_1011" title="<?php echo $pulse2[0][0] ?>"><?php echo $pulse[0][0] ?></td>
<?php   } elseif ($col <> 1 and $row == 32) { ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011" title="Cannot edit if more than 24 hours ago">&nbsp;</td>
<?php	
	}

 } ?>
        </tr>

<?php } ?>
	  </table>
	</td>
<?php 	} // if($totalRows_rsPulse > 0
	else { ?>
	<td valign="top" nowrap="nowrap"> No Pulse data </td>
<?php 	} ?>


<!--  *************************************************** RESP *****************************************************	-->
<?php 	if($totalRows_rsResp > 0){?>
	<td valign="top">
	  <table>
<?php
for ($row = 0; $row <33; $row++) {
?>
 <tr>
<?php
  $editbackto = (strtotime(date("Y-m-d H:i")) - (24*60*60)); // edit back 24 hours 
  for ($col = 0; $col <$k; $col++) {
  	if($col > 1 and $row == 0){ ?>  <!-- set time color-->
        <td align="center" bgcolor="<?php echo $resp3[0][$col] ?>" class="Black_1011" title="<?php echo $resp2[$row][$col] ?>"><?php echo $resp[$row][$col] ?></td>
<?php } elseif($col <> 1 and $row <> 32){  // eliminate color column
	  if($resp[$row][$col] == ' ') { // different background ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011"><?php echo $resp[$row][$col] ?></td>
<?php } else { ?>
        <td align="center" bgcolor="<?php echo $resp[$row][1] ?>" class="Black_1011" title="<?php echo $resp2[$row][$col] ?>"><?php echo $resp[$row][$col] ?></td>
<?php }		
  	}   if ($row == 32 and $col <> 0 and $col <> 1 and strtotime($resp2[0][$col]) > $editbackto){ //  edit back 24 hours ?>
  	<td>&nbsp;<?php if(allow(40,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsEditKey.php?id=<?php echo $resp[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">E</a>&nbsp;&nbsp;<?php } ?><?php if(allow(40,4) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsDelete.php?id=<?php echo $resp[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">D</a><?php } ?>
	</td>
	
<?php   } elseif ($col == 0 and $row == 32) { ?>
        <td align="center" bgcolor="<?php echo $resp[$row][1] ?>" class="Black_1011" title="<?php echo $resp2[0][0] ?>"><?php echo $resp[0][0] ?></td>
<?php   } elseif ($col <> 1 and $row == 32) { ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011" title="Cannot edit if more than 24 hours ago">&nbsp;</td>
<?php	
	}

 } ?>
        </tr>

<?php } ?>
	  </table>
	</td>
<?php 	} // if($totalRows_rsResp > 0
	else { ?>
	<td valign="top" nowrap="nowrap"> No RESP data </td>
<?php 	} ?>

<!--  *************************************************** BP S/D *****************************************************	-->

<?php 	if($totalRows_rsbpsd > 0){?>

	<td valign="top">
	  <table>
<?php
for ($row = 0; $row <33; $row++) {
?>
 <tr>
<?php
  $editbackto = (strtotime(date("Y-m-d H:i")) - (24*60*60)); // edit back 24 hours 
  for ($col = 0; $col <$bp; $col++) {
  	if($col > 1 and $row == 0){ ?>
        <td align="center" bgcolor="<?php echo $bpsd3[0][$col] ?>" class="Black_1011" title="<?php echo $bpsd2[$row][$col] ?>"><?php echo $bpsd[$row][$col] ?></td>
<?php } elseif($col <> 1 and $row <> 32){  // eliminate color column
	  if($bpsd[$row][$col] == ' ') { // different background ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011"><?php echo $bpsd[$row][$col] ?></td>
<?php } else { ?>
        <td align="center" bgcolor="<?php echo $bpsd[$row][1] ?>" class="Black_1011" title="<?php echo $bpsd2[$row][$col] ?>"><?php echo $bpsd[$row][$col] ?></td>
<?php }		
  	}   if ($row == 32 and $col <> 0 and $col <> 1 and strtotime($bpsd2[0][$col]) > $editbackto){ //  edit back 24 hours ?>
  	<td>&nbsp;<?php if(allow(40,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsEditKey.php?id=<?php echo $bpsd[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">E</a>&nbsp;&nbsp;<?php } ?><?php if(allow(40,4) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatVitalsDelete.php?id=<?php echo $bpsd[$row][$col] ?>','StatusView','scrollbars=yes,resizable=yes,width=500,height=150')">D</a><?php } ?>
	</td>
<?php   } elseif ($col == 0 and $row == 32) { ?>
        <td align="center" bgcolor="<?php echo $bpsd[$row][1] ?>" class="Black_1011" title="<?php echo $bpsd2[0][0] ?>"><?php echo $bpsd[0][0] ?></td>
<?php   } elseif ($col <> 1 and $row == 32) { ?>
    	<td align="center" bgcolor="#F3F3F3" class="Black_1011" title="Cannot delete if more than 24 hours ago">&nbsp;</td>
<?php	
	}

 } ?>
        </tr>

<?php } ?>
	  </table>
  	</td>
<?php 	} // if($totalRows_rsbpsd > 0
	else { ?>
	<td valign="top" nowrap="nowrap"> No B/P data </td>
<?php 	} ?>

  </tr>
</table>



<?php
	$doit = 'No';
	if($doit == 'Yes'){
		mysql_select_db($database_swmisconn, $swmisconn);
		$query_Recordset2 = sprintf("SELECT id, visitid, schedt, vital, value, entryby, entrydt FROM ipvitals WHERE vital = 'temp' AND visitid = %s ORDER BY schedt ASC", $colname_Recordset1);
		$Recordset2 = mysql_query($query_Recordset2, $swmisconn) or die(mysql_error());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
	
		if($totalRows_Recordset2 > 0){?>

 <table border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td>id</td>
    <td>visitid</td>
    <td>schedt</td>
    <td>vital</td>
    <td>value</td>
    <td>entryby</td>
    <td>entrydt</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Recordset2['id']; ?></td>
      <td><?php echo $row_Recordset2['visitid']; ?></td>
      <td title="<?php echo $row_Recordset2['schedt']?>"><?php echo date('m-d h:i A',$row_Recordset2['schedt']); ?></td>
      <td><?php echo $row_Recordset2['vital']; ?></td>
      <td><?php echo $row_Recordset2['value']/10; ?></td>
      <td><?php echo $row_Recordset2['entryby']; ?></td>
      <td><?php echo date('Y-m-d h:i',strtotime($row_Recordset2['entrydt'])); ?></td>
    </tr>
    <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
</table>
 <?php } // if rows
   mysql_free_result($Recordset2);
 } // if doit
  ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
