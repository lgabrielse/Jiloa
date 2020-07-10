<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>


<?php $colname_Recordset1 = "-1";
if (isset($_GET['vid'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
?>
<?php //echo date('Y-m-d'); 
	mysql_select_db($database_swmisconn, $swmisconn);  
	$query_distinctdates = "SELECT Distinct FROM_UNIXTIME(dt_time,'%Y-%m-%d') as dtti, FROM_UNIXTIME(dt_time,' %d-%M-%Y %W') as dttidisp FROM ipfluids WHERE visitid = ".$colname_Recordset1. " ORDER BY dt_time DESC"; 
	$distinctdates = mysql_query($query_distinctdates, $swmisconn) or die(mysql_error());
	$row_distinctdates = mysql_fetch_assoc($distinctdates);
	$totalRows_distinctdates = mysql_num_rows($distinctdates);
?>
<?php //echo date('Y-m-d'); 
	mysql_select_db($database_swmisconn, $swmisconn);  
	$query_distinctdates2 = "SELECT Distinct FROM_UNIXTIME(dt_time,'%Y-%m-%d') as dtti2, FROM_UNIXTIME(dt_time,' %d-%M-%Y %W') as dttidisp FROM ipfluids WHERE visitid = ".$colname_Recordset1. " ORDER BY dt_time DESC"; 
	$distinctdates2 = mysql_query($query_distinctdates2, $swmisconn) or die(mysql_error());
	$row_distinctdates2 = mysql_fetch_assoc($distinctdates2);
	$totalRows_distinctdates2 = mysql_num_rows($distinctdates2);
?>
<?php //echo date('Y-m-d'); 
	mysql_select_db($database_swmisconn, $swmisconn);  
	$query_mindate = "SELECT MIN(FROM_UNIXTIME(dt_time,'%Y-%m-%d')) as mindt, FROM_UNIXTIME(dt_time,' %d-%M-%Y %W') as mindisp FROM ipfluids WHERE visitid = ".$colname_Recordset1. " ORDER BY dt_time DESC"; 
	$mindate = mysql_query($query_mindate, $swmisconn) or die(mysql_error());
	$row_mindate = mysql_fetch_assoc($mindate);
	$totalRows_mindate = mysql_num_rows($mindate);
?>

<?php

 	if(isset($_POST['dtti'])) { 
		$_SESSION['seldate'] = $_POST['dtti'];
	}
	if(!isset($_POST['dtti']) AND !isset($_SESSION['seldate'])) { 
		$_SESSION['seldate'] = date('Y-m-d');
	}?>
<?php
 	if(isset($_POST['dtti2'])) { 
		$_SESSION['seldate2'] = $_POST['dtti2'];
	}
	if(!isset($_POST['dtti2']) AND !isset($_SESSION['seldate2'])) { 
		$_SESSION['seldate2'] = $row_mindate['mindt'];  //date('Y-m-d');
	}
	?>
<?php //echo $_POST['dtti'].$_SESSION['seldate']. '---'.$_POST['dtti2']. $_SESSION['seldate2'] ?>
<?php //echo $row_mindate['mindt']?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>InPatient Fluids</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=350,screenX=550,screenY=200';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
</head>

<body>
<table width="30%" border="0" align="center">
  <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

</tr>
</table>
<?php 
	mysql_select_db($database_swmisconn, $swmisconn);  // and FROM_UNIXTIME(dt_time,'%Y-%m-%d') = date_format(".$seldate.",'%Y-%m-%d')AND STRCMP(FROM_UNIXTIME(dt_time,'%Y-%m-%d'),".$seldate.") = 0 
	$query_influid = "SELECT id, visitid, dt_time, fluid, amt, entryby, entrydt, comment, FROM_UNIXTIME(dt_time,'%Y-%m-%d') as dtti FROM ipfluids WHERE in_out = 'in' AND visitid = ".$colname_Recordset1. " ORDER BY dt_time ASC"; 
	$influid = mysql_query($query_influid, $swmisconn) or die(mysql_error());
	$row_influid = mysql_fetch_assoc($influid);
	$totalRows_influid = mysql_num_rows($influid);
?>

<table width="80%" border="0">
  <tr>
    <td valign="top">
	  <table border="1" bgcolor="#F3F3F3" cellpadding="1" cellspacing="1">
		<tr>
        <?php if(allow(42,3) == 1) { ?>
		    <td colspan="5" nowrap="nowrap" class="navLink">
      <div align="center">
        <a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsInAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">Add IN</a> 
        <?php  } else { ?>
        	<td colspan="5" nowrap="nowrap">&nbsp;</td>
        <?php  } ?>
    </div></td>

		</tr>
      <tr>
        <td bgcolor="#cccccc">&nbsp;</td>
        <td bgcolor="#cccccc">By time </td>
        <td colspan="2" bgcolor="#cccccc" class="BlueBold_18"><div align="center">IN</div></td>
        <td bgcolor="#cccccc"><div align="center">ml</div></td>
      </tr>
      <tr>
        <td bgcolor="#cccccc">&nbsp;</td>
        <td bgcolor="#cccccc">datetime</td>
        <td colspan="2" bgcolor="#cccccc">fluid</td>
        <td bgcolor="#cccccc"><div align="center">amount</div></td>
      </tr>
<?php 
		$intot = 0;
		$water = 0;
		$softdrink = 0;
		$iv = 0;
		$food = 0;
		$otherin = 0;
		 
		$vintot = 0;
		$vwater = 0;
		$vsoftdrink = 0;
		$viv = 0;
		$vfood = 0;
		$votherin = 0; 
 	if($totalRows_influid > 0){  ?>
<?php do { ?>
<?php   if($row_influid['dtti'] == $_SESSION['seldate']){ ?>  <!-- For Selected Date-->
      <tr>
        <td nowrap="nowrap" class="navLink">&nbsp;<?php if(allow(42,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsInEdit.php?id=<?php echo $row_influid['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">E</a><?php  } ?>&nbsp;&nbsp;<?php if(allow(42,4) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsInDel.php?id=<?php echo $row_influid['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">D</a><?php  } ?></td>
        <td nowrap="nowrap" title="<?php echo date('Y-m-d h:i A', $row_influid['dt_time']); ?>"><?php echo date('m-d h:i A',$row_influid['dt_time']); ?></td>
        <td nowrap="nowrap" ><?php echo $row_influid['fluid']; ?></td>
<?php 	if(isset($row_influid['comment'])) {?>
        <td nowrap="nowrap" bgcolor="#FFFF00" title="<?php echo $row_influid['comment']; ?>">C</td>
<?php	} else { ?>
    <td>&nbsp;</td>
<?php	}?>
        <td title="Entered: <?php echo date('Y-m-d h:i',strtotime($row_influid['entrydt'])); ?>:By: <?php echo $row_influid['entryby']; ?>"><div align="center" class="BlueBold_14"><?php echo $row_influid['amt']; ?></div></td>
		<!--<td><?php //echo $row_influid['dtti'] ?>
		  :<?php //echo $seldate ?></td>-->
	<?php
		if($row_influid['fluid'] == 'water'){$water = $water + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'soft drink'){$softdrink = $softdrink + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'IV'){$iv = $iv + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'food'){$food = $food + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'other'){$otherin = $otherin + $row_influid['amt'];} 
		   $intot = $intot + $row_influid['amt']; ?>
	  </tr>
<?php  } ?>

<?php   if($row_influid['dtti'] >= $_SESSION['seldate2']){ // For Selected greater than Date-->
		if($row_influid['fluid'] == 'water'){$vwater = $vwater + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'soft drink'){$vsoftdrink = $vsoftdrink + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'IV'){$viv = $viv + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'food'){$vfood = $vfood + $row_influid['amt'];} 
		if($row_influid['fluid'] == 'other'){$votherin = $votherin + $row_influid['amt'];} 
		   $vintot = $vintot + $row_influid['amt']; 
        ?>
<?php  } ?>
	<?php } while ($row_influid = mysql_fetch_assoc($influid)); ?>
	  <tr>
        <td>&nbsp;</td>
	   	<td class="BlueBold_16"><div align="right" class="BlackBold_14">TOTAL</div></td>
    	<td colspan="2" class="BlueBold_16"><div align="center">IN</div></td>
		<td><div align="center" class="BlueBold_16"><?php echo $intot; ?></div></td>	  
	  </tr>
<?php  } ?>
    </table>
   </td>

<?php                   
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_outfluid = "SELECT id, visitid, dt_time, fluid, amt, entryby, entrydt, comment, FROM_UNIXTIME(dt_time,'%Y-%m-%d') as dtto FROM ipfluids WHERE in_out = 'out' AND visitid = ".$colname_Recordset1. " ORDER BY dt_time ASC";                                  
	$outfluid = mysql_query($query_outfluid, $swmisconn) or die(mysql_error());
	$row_outfluid = mysql_fetch_assoc($outfluid);
	$totalRows_outfluid = mysql_num_rows($outfluid);
?>
	

    <td valign="top" nowrap="nowrap">
		<table border="1" bgcolor="#F3F3F3" cellpadding="1" cellspacing="1">
		   <tr>
      <?php if(allow(42,3) == 1) { ?>
		    <td colspan="5" nowrap="nowrap" class="navLink"><div align="center">
      <a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsOutAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&amp;vid=<?php echo $_SESSION['vid']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">Add OUT</a></div></td>
        <?php  } else { ?>
        	<td colspan="5" nowrap="nowrap">&nbsp;</td>
        <?php  } ?>
    </div></td>
		
		</tr>
      <tr>
        <td bgcolor="#cccccc">&nbsp;</td>
        <td bgcolor="#cccccc">by time </td>
        <td colspan="2" bgcolor="#cccccc" class="BlueBold_18"><div align="center">OUT</div></td>
        <td bgcolor="#cccccc"><div align="center">ml</div></td>
      </tr>
      <tr>
        <td bgcolor="#cccccc">&nbsp;</td>
        <td bgcolor="#cccccc">datetime</td>
        <td colspan="2" bgcolor="#cccccc">fluid</td>
        <td bgcolor="#cccccc"><div align="center">amount</div></td>
      </tr>
<?php	if($totalRows_outfluid > 0){
		$outtot = 0;
		$urine = 0;
		$drainage = 0;
		$vomit = 0;
		$stool = 0;
		$otherout = 0;
		
		$vouttot = 0;
		$vurine = 0;
		$vdrainage = 0;
		$vvomit = 0;
		$vstool = 0;
		$votherout = 0;		 ?> 
  <?php do { ?>
  <?php //echo $row_outfluid['dtto'] ?>
    <?php   if($row_outfluid['dtto'] == $_SESSION['seldate']){ ?>
      <tr>
        <td nowrap="nowrap" class="navLink">&nbsp;<?php if(allow(42,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsOutEdit.php?id=<?php echo $row_outfluid['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">E</a><?php	}?>&nbsp;&nbsp;<?php if(allow(42,2) == 1) { ?><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatFluidsOutDel.php?id=<?php echo $row_outfluid['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=550,height=200')">D</a><?php	}?></td>
        <td nowrap="nowrap" title="<?php echo date('Y-m-d h:i A',$row_outfluid['dt_time']); ?>"><?php echo date('m-d h:i A',$row_outfluid['dt_time']); ?></td>
        <td nowrap="nowrap"><?php echo $row_outfluid['fluid']; ?></td>
<?php 	if(isset($row_outfluid['comment'])) {?>
        <td nowrap="nowrap" bgcolor="#FFFF00" title="<?php echo $row_outfluid['comment']; ?>">C</td>
<?php	} else { ?>
    <td>&nbsp;</td>
<?php	}?>
        <td title="Entered: <?php echo date('Y-m-d h:i',strtotime($row_outfluid['entrydt'])); ?>:By: <?php echo $row_outfluid['entryby']; ?>"><div align="center" class="BlueBold_14"><?php echo $row_outfluid['amt']; ?></div></td>
      </tr>
<?php  // daily sum calculation
		if($row_outfluid['fluid'] == 'urine'){$urine = $urine + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'drainage'){$drainage = $drainage + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'vomit'){$vomit = $vomit + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'stool'){$stool = $stool + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'other'){$otherout = $otherout + $row_outfluid['amt'];} 
		$outtot = $outtot + $row_outfluid['amt']; 
		} ?>
	
<?php  // visit sum calculation
   if($row_outfluid['dtto'] >= $_SESSION['seldate2']){ // For Selected greater than Date-->
		if($row_outfluid['fluid'] == 'urine'){$vurine = $vurine + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'drainage'){$vdrainage = $vdrainage + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'vomit'){$vvomit = $vvomit + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'stool'){$vstool = $vstool + $row_outfluid['amt'];} 
		if($row_outfluid['fluid'] == 'other'){$votherout = $votherout + $row_outfluid['amt'];} 
		$vouttot = $vouttot + $row_outfluid['amt'];
        } ?>

  <?php } while ($row_outfluid = mysql_fetch_assoc($outfluid)); ?>
	  <tr>
        <td>&nbsp;</td>
    	<td class="BlackBold_14"><div align="right">TOTAL</div></td>
    	<td colspan="2" class="BlueBold_16"><div align="center">OUT</div></td>
		<td><div align="center" class="BlueBold_16"><?php echo $outtot; ?></div></td>	  
	  </tr>
    </table>
   </td>
   <td>
     <table>
	    <tr>
		   <td colspan="5">
			 <table>
			   <tr>	
				 <td class="BlackBold_16">FOR:</td>
		  <form id="form1" name="formdtti" method="post" action="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatFluids.php">
		
				<td colspan="2"><select name="dtti"  onchange="document.formdtti.submit();">
		<!--  <option value="<?php //echo date('Y-m-d');?>"><?php //echo date('Y-m-d');?></option>-->
		  <?php do {  ?>
		  <option value="<?php echo $row_distinctdates['dtti']?>" <?php if (!(strcmp($row_distinctdates['dtti'], $_SESSION['seldate']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distinctdates['dttidisp']?></option>
		
		  <?php
		} while ($row_distinctdates = mysql_fetch_assoc($distinctdates));
		  ?>
		</select></td>
		  </form>
		  
		  
		  <form id="form2" name="formdtti2" method="post" action="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatFluids.php">
		
				<td class="BlackBold_16">SINCE:</td>
				<td colspan="2"><select name="dtti2" onchange="document.formdtti2.submit();">
			<!--<option value="<?php //echo date('Y-m-d');?>"><?php //echo date('Y-m-d');?></option>-->
			<?php do {  ?>
			<option value="<?php echo $row_distinctdates2['dtti2']?>" <?php if (!(strcmp($row_distinctdates2['dtti2'], $_SESSION['seldate2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distinctdates2['dttidisp']?></option>
			<?php
		} while ($row_distinctdates2 = mysql_fetch_assoc($distinctdates2));
		  ?>
		  </select></td>
		  </form>
				</tr>
			 </table>
		  </td>
		  </tr>
		  <tr>
	
    <td valign="top">
	   <table width="100%" bgcolor="#F3F3F3" border="1">
		  <tr>
			<td bgcolor="#cccccc"><div align="center">IN</div></td>
			<td bgcolor="#cccccc"><div align="center"></div></td>
		  </tr>
		  <tr>
			<td bgcolor="#cccccc"><div align="center">fluids</div></td>
			<td bgcolor="#cccccc"><div align="center">ml</div></td>
		  </tr>
		  <tr>
			<td>Water</td>
			<td class="BlueBold_14"><div align="center"><?php echo $water ?></div></td>
		  </tr>
		  <tr>
			<td>SoftDrink</td>
			<td class="BlueBold_14"><div align="center"><?php echo $softdrink ?></div></td>
		  </tr>
		  <tr>
			<td>IV</td>
			<td class="BlueBold_14"><div align="center"><?php echo $iv ?></div></td>
		  </tr>
		  <tr>
			<td>Food</td>
			<td class="BlueBold_14"><div align="center"><?php echo $food ?></div></td>
		  </tr>
		  <tr>
			<td>Other</td>
			<td class="BlueBold_14"><div align="center"><?php echo $otherin ?></div></td>
		  </tr>
		  <tr>
	      	<td class="BlackBold_14"><div align="right">TOTAL</div></td>
			<td><div align="center" class="BlueBold_16"><?php echo $intot; ?></div></td>	  
		  </tr>
		</table>	</td>
    <td valign="top">
	 <table width="100%" bgcolor="#F3F3F3" border="1">
      <tr>
        <td bgcolor="#cccccc"><div align="center">OUT</div></td>
        <td bgcolor="#cccccc"><div align="center"></div></td>
      </tr>
      <tr>
        <td bgcolor="#cccccc"><div align="center">fluids</div></td>
        <td bgcolor="#cccccc"><div align="center">ml</div></td>
      </tr>
      <tr>
        <td>Urine</td>
        <td class="BlueBold_14"><div align="center"><?php echo $urine ?></div></td>
      </tr>
      <tr>
        <td>Drainage</td>
        <td class="BlueBold_14"><div align="center"><?php echo $drainage ?></div></td>
      </tr>
      <tr>
        <td>Vomit</td>
        <td class="BlueBold_14"><div align="center"><?php echo $vomit ?></div></td>
      </tr>
      <tr>
        <td>Stool</td>
        <td class="BlueBold_14"><div align="center"><?php echo $stool ?></div></td>
      </tr>
      <tr>
        <td>Other</td>
        <td class="BlueBold_14"><div align="center"><?php echo $otherout ?></div></td>
      </tr>
      <tr>
	    <td class="BlackBold_14"><div align="right">TOTAL</div></td>
		<td><div align="center" class="BlueBold_16"><?php echo $outtot; ?></div></td>	  
      </tr>
    </table></td>
    <td valign="top">&nbsp;</td>
    <td valign="top"><table width="100%" bgcolor="#F3F3F3" border="1">
        <tr>
          <td bgcolor="#cccccc"><div align="center">IN</div></td>
          <td bgcolor="#cccccc"><div align="center"></div></td>
        </tr>
        <tr>
          <td bgcolor="#cccccc"><div align="center">fluids</div></td>
          <td bgcolor="#cccccc"><div align="center">ml</div></td>
        </tr>
        <tr>
          <td>Water</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vwater ?></div></td>
        </tr>
        <tr>
          <td>SoftDrink</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vsoftdrink ?></div></td>
        </tr>
        <tr>
          <td>IV</td>
          <td class="BlueBold_14"><div align="center"><?php echo $viv ?></div></td>
        </tr>
        <tr>
          <td>Food</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vfood ?></div></td>
        </tr>
        <tr>
          <td>Other</td>
          <td class="BlueBold_14"><div align="center"><?php echo $votherin ?></div></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">TOTAL</div></td>
          <td><div align="center" class="BlueBold_16"><?php echo $vintot; ?></div></td>
        </tr>
    </table></td>
    <td valign="top"><table width="100%" bgcolor="#F3F3F3" border="1">
        <tr>
          <td bgcolor="#cccccc"><div align="center">OUT</div></td>
          <td bgcolor="#cccccc"><div align="center"></div></td>
        </tr>
        <tr>
          <td bgcolor="#cccccc"><div align="center">fluids</div></td>
          <td bgcolor="#cccccc"><div align="center">ml</div></td>
        </tr>
        <tr>
          <td>Urine</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vurine ?></div></td>
        </tr>
        <tr>
          <td>Drainage</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vdrainage ?></div></td>
        </tr>
        <tr>
          <td>Vomit</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vvomit ?></div></td>
        </tr>
        <tr>
          <td>Stool</td>
          <td class="BlueBold_14"><div align="center"><?php echo $vstool ?></div></td>
        </tr>
        <tr>
          <td>Other</td>
          <td class="BlueBold_14"><div align="center"><?php echo $votherout ?></div></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">TOTAL</div></td>
          <td><div align="center" class="BlueBold_16"><?php echo $vouttot; ?></div></td>
        </tr>
    </table></td>
  </tr>
</table>
</td>
  </tr>
</table>

 <?php } ?>
</body>
</html>
<?php
mysql_free_result($influid);
mysql_free_result($outfluid);
?>

</body>
</html>
