<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
	$colname_mrn = "-1";
if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
	}
	$colname_vid = "-1";
if (isset($_GET['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
	}
	$colname_ordid = "-1";
if (isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_medsched = "SELECT i.id, i.visitid, i.orderid, i.med, i.status, i.unit, i.nunits, i.schedt, i.comments, CASE WHEN i.givendt is NULL THEN 0 ELSE i.givendt END as givendt, i.givenby, i.entryby, i.entrydt, o.evperiod, o.status ostatus, o.billstatus,  p.lastname, p.firstname from ipmeds i join orders o on i.orderid = o.id join patperm p on o.medrecnum = p.medrecnum where i.orderid like '".$colname_ordid."%' and i.visitid ='". $colname_vid."' ORDER BY schedt ASC";
$medsched = mysql_query($query_medsched, $swmisconn) or die(mysql_error());
$row_medsched = mysql_fetch_assoc($medsched);
$totalRows_medsched = mysql_num_rows($medsched);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Scheduled Med View</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
	 <table border = "1" align="center" bgcolor="#F5F5F5">
      <tr bgcolor="#DCDCDC">
	  	<td>Order Status: :<span><?php echo $row_medsched['ostatus'].', <br />Billing Status:'.$row_medsched['billstatus'] ?></span></td>
		<td colspan="9"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="BlueBold_1414"><?php echo $row_medsched['lastname'].', '.$row_medsched['firstname'] ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="BlueBold_16">&nbsp;VIEW INPATIENT MED SCHEDULE</span></div></td>
		<td><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
	  </tr>
      <tr bgcolor="#DCDCDC">
        <td><div align="center">Order# <?php echo $colname_ordid ?></div></td>
        <td><div align="center">Date:Time</div></td>
        <td><div align="center">Med</div></td>
        <td><div align="center">#</div></td>
        <td><div align="center">Status</div></td>
        <td><div align="center">GivenDt</div></td>
        <td><div align="center">GivenBy</div></td>
        <td><div align="center">Comment</div></td>
      </tr>
	  <tr>
        <?php if($totalRows_medsched > 0){
	    do { ?>
    <?php //if(allow(41,2) == 1) { ?>	
        <!--<td><a href="javascript:void(0)" onclick="MM_openBrWindow('PatInPatMedStatus.php?mmid=<?php echo $row_medsched['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=1000,height=500')">Edit</a></td>-->
      <?php	// }?>
	    <!--id, visitid, orderid, med, status, unit, nunits, schedt, comments, givenby, entryby, entrydt-->
        <?php 		if(date('A',$row_medsched['schedt']) == 'AM') { // set color of time display
				$dtbkgd = '#d5effc';
			} else {
				$dtbkgd = '#DFDFDF';
			}
 		   if($row_medsched['status'] == 'ordered') { // set color of time display
				$stbkgd = '#F5F5F5';
			} else {
				$stbkgd = '#DCDCDC"';
			}
?>
<?php if(strpos("OD Nocte BD TDS QDS PRN STAT", $row_medsched['evperiod'])>0) {?>
        <td bgcolor="<?php echo $dtbkgd ?>" nowrap="nowrap"><?php echo date('D M-d',$row_medsched['schedt']) .'  '.$row_medsched['evperiod']; ?></td>
<?php } else {?>
        <td bgcolor="<?php echo $dtbkgd ?>" nowrap="nowrap"><?php echo date('D M-d H:i',$row_medsched['schedt']); ?>
<?php }?>
	    <td nowrap="nowrap" bgcolor="<?php echo $stbkgd ?>" title="ID: <?php echo $row_medsched['id']; ?>&#10;VisitID: <?php echo $row_medsched['visitid']; ?>&#10;OrderID: <?php echo $row_medsched['orderid']; ?>&#10;EntryDt: <?php echo $row_medsched['entrydt']; ?>&#10;EntryBy: <?php echo $row_medsched['entryby']; ?>&#10;Medication Comments: <?php echo $row_medsched['comments']; ?>"><?php echo $row_medsched['med']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['nunits']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['unit']; ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['status']; ?></td>
        <td bgcolor="<?php echo $stbkgd ?>" nowrap="nowrap"><?php if($row_medsched['givendt'] == 0) { echo ' --- ';} else { echo date('D h:i_A' ,$row_medsched['givendt']); } ?></td>
	    <td bgcolor="<?php echo $stbkgd ?>" title="<?php if($row_medsched['givendt'] == 0) { echo ' --- ';} else { echo date('D M-d_h:i_A' ,$row_medsched['givendt']); } ?>" nowrap="nowrap"><?php echo $row_medsched['givenby']; ?></td>
<?php if(strlen($row_medsched['comments'])>0){  ?>
	    <td bgcolor="<?php echo $stbkgd ?>"><?php echo $row_medsched['comments']; ?></td>  
<?php   } 		?>
       </tr>
      <?php } while ($row_medsched = mysql_fetch_assoc($medsched)); 
	 } else { ?>
      <?php	 }?>
</table>
	 </td>

</body>
</html>
<?php mysql_free_result($medsched);

ob_end_flush();

?>