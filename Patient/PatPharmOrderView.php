<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']);

 $_SESSION['CurrDateTime'] =  date("Y-m-d H:i:s"); ?>

<?php
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
	}
if (isset($_SESSION['vid'])) {
  $colname_vid = (get_magic_quotes_gpc()) ? $_SESSION['vid'] : addslashes($_SESSION['vid']);
	}
mysql_select_db($database_swmisconn, $swmisconn);
$query_ordered = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.quant, o.ofee, o.rate, o.doctor, o.status, o.billstatus, o.comments, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee FROM orders o, fee f WHERE o.feeid = f.id and f.dept in ('pharm') and o.medrecnum ='". $colname_mrn."' and o.visitid ='". $colname_vid."' ORDER BY entrydt ASC";
$ordered = mysql_query($query_ordered, $swmisconn) or die(mysql_error());
$row_ordered = mysql_fetch_assoc($ordered);
$totalRows_ordered = mysql_num_rows($ordered);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=400,top=5,screenX=400,screenY=5';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
     <style type="text/css">
         input.center{
         text-align:center;
         }
      </style>

</head>

<body>

<table width="90%" align="center" bgcolor="#F5F5F5">
  <tr>
    <td>
      <table width="100%" align="center">
        <tr>
   		 <?php if(allow(45,3) == 1) { ?>
		  <td bgcolor="#F5F5F5" nowrap ="nowrap">
<!--		  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdTabAdd.php?mrn=<?php //echo $_SESSION['mrn']; ?>&user=<?php //echo $_SESSION['user']; ?>&patype=<?php //echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=950,height=400')">Add Tablets/Capsules</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdOtherAdd.php?mrn=<?php //echo $_SESSION['mrn']; ?>&user=<?php //echo $_SESSION['user']; ?>&patype=<?php //echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=950,height=400')">Add Other drugs </a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
-->
		&nbsp;&nbsp;<br />
		&nbsp;&nbsp;<br />
	  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdMultiAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&patype=<?php echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=800')">Prescriptions&nbsp;&nbsp;&nbsp;&nbsp;  </a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<!--	  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdMultiAddMid.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&patype=<?php echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=800')">&nbsp;&nbsp;Mid&nbsp;&nbsp;  </a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
-->
<!--	  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdMultiAdd4.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&patype=<?php echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=800')">&nbsp;&nbsp;4&nbsp;&nbsp;  </a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
-->
<!--	  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrdMultiAdd3.php?mrn=<?php //echo $_SESSION['mrn']; ?>&user=<?php //echo $_SESSION['user']; ?>&patype=<?php//echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=700')">Prescriptions3  </a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
-->
<!--	  <a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmDocOrdAdd.php?mrn=<?php //echo $_SESSION['mrn']; ?>&user=<?php //echo $_SESSION['user']; ?>&patype=<?php //echo $_GET['patype'];?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=750')">Orders2</a>
-->		  
		  </td>
		  <?php  }  else  {?>
		  <td bgcolor="#DCDCDC">&nbsp;</td>
		<?php  }?>
          <td nowrap="nowrap" bgcolor="#F5F5F5"><div class="BlackBold_16">Drug Orders&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Black_12">(<?php echo $_GET['patype'];?>)</span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div></td>
          <td bgcolor="#F5F5F5"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></div></td>
        </tr>
      </table>
 
  </td></tr>
  <tr>
          <td valign="top" bgcolor="#DCDCDC" class="subtitlebk"><div align="center">
            <table>
                  <tr>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">#Date/Time</td>
                    <td nowrap="nowrap" class="BlackBold_11">Ord#*</td>
                    <td nowrap="NOWRAP" class="BlackBold_11">Test*</td>
                    <td nowrap="nowrap" class="BlackBold_11">Prescription</td>
                    <td nowrap="nowrap" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" class="BlackBold_11">Quant</td>
                    <td nowrap="nowrap" class="BlackBold_11">Urg</td>
                    <td nowrap="nowrap" class="BlackBold_11">Status</td>
                    <td nowrap="nowrap" class="BlackBold_11">Amt</td>
                    <td nowrap="nowrap" class="BlackBold_11">Done</td>
                  </tr>
	<?php do { ?>
	   <?php if (!empty($row_ordered['id'])) { ?>
                  <tr>
		 <?php if ($row_ordered['amtpaid'] == 0 AND ($row_ordered['status'] == "RxOrdered" OR $row_ordered['status'] == "RxCosted")) {?>
			 <?php if(allow(45,4) == 1) { ?>
					<td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">					
					<a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrderDelete.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&ordid=<?php echo $row_ordered['id'] ?>','StatusView','scrollbars=yes,resizable=yes,width=800,height=350')">Del</a></td>
			 <?php } else { ?>
                    <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">&nbsp;</td>
			 <?php } ?>

			 <?php if(allow(45,2) == 1 AND $row_ordered['status'] == "RxOrdered" ) { //AND $_GET['patype'] == "InPatient"?>
                   <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">
<!--					<a href="javascript:void(0)" onclick="MM_openBrWindow('PatPharmOrderEdit.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&ordid=<?php echo $row_ordered['id'] ?>','StatusView','scrollbars=yes,resizable=yes,width=950,height=400')">Edit</a></td>
-->			 <?php } else { ?>
                    <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">&nbsp;</td>
			 <?php } ?>
		 <?php } 
				   else { ?>
                    <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">&nbsp;</td>
                    <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11">&nbsp;</td>
		<?php } ?>
                    <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11" title="VID: <?php echo $row_ordered['visitid']; ?> "><?php echo $row_ordered['entrydt']; ?></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11" title="Doctor: <?php echo $row_ordered['doctor']; ?>"><div align="center"><?php echo $row_ordered['id']; ?></div></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11" title="Dept:<?php echo $row_ordered['dept']; ?>&#10;Section:<?php echo $row_ordered['section']; ?>&#10;Descr:<?php echo $row_ordered['descr']; ?>&#10;Order Comments:<?php echo $row_ordered['comments']; ?>"><?php echo $row_ordered['name']; ?> </td>

		        <td nowrap="nowrap" bgcolor="#F5F5F5" title="Order Comments:<?php echo $row_ordered['comments']; ?>">
				<input name="item" type="text" id="item" size="30" disabled="disabled" value="<?php echo $row_ordered['item']; ?>" />		            </td>
		        <td nowrap="nowrap" bgcolor="#F5F5F5"><input name="nunits" type="text" id="nunits" size="1" disabled="disabled" class="center" value="<?php echo $row_ordered['nunits']; ?>" />
				  <input name="unit" type="text" size="6" value="<?php echo $row_ordered['unit']; ?>" />
		  Every
		  <input name="every" type="text" id="every" size="1" class="center" disabled="disabled" Value="<?php echo $row_ordered['every']; ?>" />
		  <input name="evperiod" type="text" id="evperiod" size="3" disabled="disabled" Value="<?php echo $row_ordered['evperiod']; ?>" />
		  for
		  <input name="fornum" type="text" id="fornum" size="1"  class="center" disabled="disabled" Value="<?php echo $row_ordered['fornum']; ?>" />
		  <input name="forperiod" type="text" id="forperiod" size="3" disabled="disabled" Value="<?php echo $row_ordered['forperiod']; ?>" /></td>


                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11" title="Order Comment: <?php echo $row_ordered['comments']; ?>"><div align="center"><?php echo $row_ordered['quant']; ?></div></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11"><?php echo $row_ordered['urg']; ?></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11" title="Billstatus: <?php echo $row_ordered['billstatus']; ?>"><?php echo $row_ordered['status']; ?></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11"><div align="right"><?php echo $row_ordered['ofee']; ?></div></td>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11"><div align="right"><?php echo $row_ordered['amtpaid']; ?></div></td>
						<?php if($row_ordered['billstatus'] == 'paylater'){ ?>
                <td nowrap="nowrap" bgcolor="#F5F5F5" class="BlackBold_11"><div align="right" class="flagBlackonOrange">PL</div></td>
						<?php 	}?>
              </tr>
		    <?php } //if row [id]?>
              <?php } while ($row_ordered = mysql_fetch_assoc($ordered)); ?>
            </table>     	  </div></td>
        </tr>
    </table>
   </td>
  </tr>
</table>
</body>
</html>
