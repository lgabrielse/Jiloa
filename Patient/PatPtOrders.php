<?php $pt = "PT Orders"; ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
  $colname_daysback = "60";
if (isset($_POST['daysback'])  && strlen($_POST['daysback'])>0 ) {   //&& ($_POST["MM_update"] == "form3")
  $colname_daysback = (get_magic_quotes_gpc()) ? $_POST['daysback'] : addslashes($_POST['daysback']);
}

$colname_pat_type = "%";
if (isset($_POST['pat_type'])  && strlen($_POST['pat_type'])>0) {
  $colname_pat_type = (get_magic_quotes_gpc()) ? $_POST['pat_type'] : addslashes($_POST['pat_type']);
}

$colname_ostatus = "%";
if (isset($_POST['ostatus'])  && strlen($_POST['ostatus'])>0) {
  $colname_ostatus = (get_magic_quotes_gpc()) ? $_POST['ostatus'] : addslashes($_POST['ostatus']);
}

$mydate = 220;
mysql_select_db($database_swmisconn, $swmisconn);
$query_Orders = "SELECT DISTINCT o.id ordid, o.medrecnum, o.visitid, p.lastname, p.firstname, p.othername, p.gender, ethnicgroup, p.dob, o.status, o.comments, DATE_FORMAT(o.entrydt,'%d %b %Y') oentrydt, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,dob)),'%y') AS age, p.ethnicgroup, p.active, v.pat_type, v.location, b.bed FROM `orders` o join `patperm` p on o.medrecnum = p.medrecnum join patvisit v on o.visitid = v.id join fee f on o.feeid = f.id LEFT OUTER JOIN patbed b ON p.medrecnum = b.medrecnum WHERE o.feeid = '182' and f.dept = 'Physiotherapy' and v.pat_type like '".$colname_pat_type."%' and o.status like '".$colname_ostatus."%' and o.entrydt >= SYSDATE() - INTERVAL " .$colname_daysback." DAY order by DATE(o.entrydt) DESC, lastname, firstname";
$Orders = mysql_query($query_Orders, $swmisconn) or die(mysql_error());
$row_Orders = mysql_fetch_assoc($Orders);
$totalRows_Orders = mysql_num_rows($Orders);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PT Orders</title>
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

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>

<body><table width="800px" align="center">
        <form method="post" name="form2" id="form2" enctype="multipart/form-data">
  <tr>
    <td>
	  <table width="50%" align="center">
        <tr>
          <td nowrap="nowrap"><div align="center" class="subtitlebl">PHYSIOTHERAPY CURRENT PATIENT ORDERS </div></td>
          <td nowrap="nowrap"><div align="right">Patient Type:</div></td>
          <td><select name="pat_type" id="pat_type" onChange="document.form2.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>All</option>
			<option value="OutPatient" <?php if (!(strcmp("OutPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>OutPatient</option>
            <option value="InPatient" <?php if (!(strcmp("InPatient", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>InPatient</option>
            <option value="Antenatal" <?php if (!(strcmp("Antenatal", $colname_pat_type))) {echo "selected=\"selected\"";} ?>>Antenatal</option>
          </select>          </td>

          <td><select name="ostatus" id="ostatus" onChange="document.form2.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_ostatus))) {echo "selected=\"selected\"";} ?>>All</option>
			<option value="Ordered" <?php if (!(strcmp("Ordered", $colname_ostatus))) {echo "selected=\"selected\"";} ?>>Ordered</option>
            <option value="In-Progress" <?php if (!(strcmp("In-Progress", $colname_ostatus))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
            <option value="Complete" <?php if (!(strcmp("Complete", $colname_ostatus))) {echo "selected=\"selected\"";} ?>>Complete</option>
          </select>          </td>

        <td nowrap="nowrap">Days Back</td>
        <td>
          <select name="daysback" id="daysback" size="1" onChange="document.form2.submit();">    <!--onChange="document.form3.submit();"-->
            <option value="1" <?php if (!(strcmp(1, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1</option>
            <option value="2" <?php if (!(strcmp(2, $colname_daysback))) {echo "selected=\"selected\"";} ?>>2</option>
            <option value="3" <?php if (!(strcmp(3, $colname_daysback))) {echo "selected=\"selected\"";} ?>>3</option>
            <option value="4" <?php if (!(strcmp(4, $colname_daysback))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="5" <?php if (!(strcmp(5, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5</option>
            <option value="6" <?php if (!(strcmp(6, $colname_daysback))) {echo "selected=\"selected\"";} ?>>6</option>
            <option value="7" <?php if (!(strcmp(7, $colname_daysback))) {echo "selected=\"selected\"";} ?>>7</option>
            <option value="10" <?php if (!(strcmp(10, $colname_daysback))) {echo "selected=\"selected\"";} ?>>10</option>
            <option value="15" <?php if (!(strcmp(15, $colname_daysback))) {echo "selected=\"selected\"";} ?>>15</option>
            <option value="20" <?php if (!(strcmp(20, $colname_daysback))) {echo "selected=\"selected\"";} ?>>20</option>
            <option value="25" <?php if (!(strcmp(25, $colname_daysback))) {echo "selected=\"selected\"";} ?>>25</option>
            <option value="30" <?php if (!(strcmp(30, $colname_daysback))) {echo "selected=\"selected\"";} ?>>30</option>
            <option value="60" <?php if (!(strcmp(60, $colname_daysback))) {echo "selected=\"selected\"";} ?>>60</option>
            <option value="90" <?php if (!(strcmp(90, $colname_daysback))) {echo "selected=\"selected\"";} ?>>90</option>
            <option value="120" <?php if (!(strcmp(120, $colname_daysback))) {echo "selected=\"selected\"";} ?>>120</option>
            <option value="180" <?php if (!(strcmp(180, $colname_daysback))) {echo "selected=\"selected\"";} ?>>180</option>
            <option value="365" <?php if (!(strcmp(365, $colname_daysback))) {echo "selected=\"selected\"";} ?>>1 yr</option>
            <option value="1825" <?php if (!(strcmp(1825, $colname_daysback))) {echo "selected=\"selected\"";} ?>>5 yr</option>
          </select>
			</td>
        </tr>
      </table>
	</td>
  </tr>
  <tr>
    <td>  
          <table>
            <tr>
              <td><strong>MRN*</strong></td>
              <td><strong>lastname</strong></td>
              <td><strong>firstname</strong></td>
              <td><strong>othername</strong></td>
              <td><div align="center"><strong>gen<br />
              der</strong></div></td>
              <td><div align="center"><strong>ethnic<br />
              group</strong></div></td>
              <td><div align="center"><strong>age</strong></div></td>
              <td><div align="center"><strong>Location</strong></div></td>
              <td><strong>Bed</strong></td>
              <td><strong>order status</strong></td>
              <td><strong>order Comments</strong></td>
            </tr>
            <?php
			$ordnum = 0;
			$visita = "";
			$visitb = "";
		    $rowdate = "";
			 do { //bgcolor="#BCFACC" 
				if($row_Orders['status'] == 'Refund'){
					$bkgd = "#DDEEFF";
				} else {
					$bkgd = "#FFFFFF";
				}	
			$ordnum = $ordnum + 1;
			$visita = $row_Orders['visitid'];
			?>  
<?php	if($rowdate <> $row_Orders['oentrydt']) {?>	
		   <tr>
			  <td colspan="10"><strong><?php echo $row_Orders['oentrydt'] ?></strong></td>
		  </tr>
			<?php }	?>  


			
            <tr>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Visit: <?php echo $row_Orders['visitid']; ?>"><a href="PatShow1.php?mrn=<?php echo $row_Orders['medrecnum']; ?>&vid=<?php echo $row_Orders['visitid']; ?>&visit=PatVisitView.php&act=lab&pge=PatPtView.php"><?php echo $row_Orders['medrecnum']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="nav11" title="Status: <?php echo $row_Orders['status']; ?>">
			  <a href="PatShow1.php?mrn=<?php echo $row_Orders['medrecnum']; ?>&vid=<?php echo $row_Orders['visitid']; ?>&visit=PatVisitView.php&act=lab&pge=PatPtView.php"><?php echo $row_Orders['lastname']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><a href="PatShow1.php?mrn=<?php echo $row_Orders['medrecnum']; ?>&vid=<?php echo $row_Orders['visitid']; ?>&visit=PatVisitView.php&act=lab&pge=PatPtView.php"><?php echo $row_Orders['firstname']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><a href="PatShow1.php?mrn=<?php echo $row_Orders['medrecnum']; ?>&vid=<?php echo $row_Orders['visitid']; ?>&visit=PatVisitView.php&act=lab&pge=PatPtView.php"><?php echo $row_Orders['othername']; ?></a></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><div align="center"><?php echo $row_Orders['gender']; ?></div></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_Orders['ethnicgroup']; ?></td>
              <?php  //calculate Age
?>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><div align="center"><?php echo $row_Orders['age']; ?></div></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><div align="center"><?php echo $row_Orders['location']; ?></div></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><div align="center"><?php echo $row_Orders['bed']; ?></div></td>
              <td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><a href="javascript:void(0)" onclick="MM_openBrWindow('PatPtStatusAdd.php?mrn=<?php echo $row_Orders['medrecnum'] ?>&amp;user=<?php echo $_SESSION['user']; ?>&amp;ordid=<?php echo $row_Orders['ordid'] ?>','StatusView','menubar=yes,scrollbars=yes,resizable=yes,width=850,height=200')"><?php echo $row_Orders['status']; ?></a></td>
              <!--<td bgcolor="<?php echo $bkgd; ?>" class="BlackBold_11"><?php echo $row_Orders['comments']; ?></td>-->
            </tr>

<!--  this will display the visit notes after the first PT order for the visit -->
<?php // if($visita <> $visitb ){?>
<?php //mysql_select_db($database_swmisconn, $swmisconn);
		//$query_notes = "SELECT id, medrecnum, visitid, notes, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y     %H:%i') entrydt FROM patnotes WHERE notetype = 'PhysioTherapy' and medrecnum = '".$row_Orders['medrecnum']."' and visitid = '".$row_Orders['visitid']."'";
		//$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
		//$row_notes = mysql_fetch_assoc($notes);
		//$totalRows_notes = mysql_num_rows($notes);			
?>			
<?php //if($totalRows_notes > 0) {
      //	do { ?>			
	<!--		<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td class="BlackBold_10"><?php echo $row_notes['entrydt']; ?></td>
				<td class="BlackBold_10"><?php echo $row_notes['entryby']; ?></td>
				<td class="BlackBold_11">Note: <?php echo $row_notes['notes']; ?></td>		
			</tr>

-->			<?php //$visitb = $row_Orders['visitid'];?>
            <?php //} while ($row_notes = mysql_fetch_assoc($notes)); 
	// } ?>   			
<?php //}?>

<!-- end of notes display -->
            <?php $rowdate = $row_Orders['oentrydt']; ?>

            <?php } while ($row_Orders = mysql_fetch_assoc($Orders)); ?>
          </table>
	  </td>
  
   </tr></form>
  
</table>




</body>
</html>
<?php
mysql_free_result($Orders);
?>
