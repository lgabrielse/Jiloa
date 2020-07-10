<?php  $pt = "Lab Result Print"; ?>
<?php session_start(); // 'Start new or resume existing session'  $_SESSION['sysconn'] seems to unavailable default?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, o.medrecnum, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.status, o.entryby, o.comments, f.name, p.lastname, p.firstname, p.othername, p.gender, p.ethnicgroup, p.dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, p.est,  p.active, p.hospital, s.name spec, sc.collby, DATE_FORMAT(sc.colldt,'%d-%b-%Y %H:%i') colldt, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, DATE_FORMAT(v.entrydt,'%d-%b-%Y %H:%i') ventrydt, v.entryby ventryby, v.id vid, v.visitdate, v.location, v.pat_type, v.urgency, v.discharge from orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum join patvisit v on o.visitid = v.id join specimens s on f.specid= s.id left outer join speccollected sc on sc.ordnum = o.id where f.dept = 'Laboratory' and visitid = '".$_GET['vid']."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script>
  function Print()
	 {
	  if (document.all) 
		 {
		   WebBrowser1.ExecWB(6, 6) //use 6, 1 to prompt the print dialog or 6, 6 to omit it; 
			WebBrowser1.outerHTML = "";
		 }
	 else
		{
		 window.print();
		 }
	 }
</script>
<script>
table
{
    table-layout: fixed;
    width: 1000px;
}
</script>
</head>
<object id="WebBrowser1" width="0" height="0" 
    classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2">
    </object>
<body onload="Print()">


<table width="1000px" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td> <!--1-->
      <table width="1000px" >
        <tr>
          <td width ="110px" colspan="2"> <a href="PatShow1.php?mrn=<?php echo $row_orders['medrecnum']; ?>&vid=<?php echo $_GET['vid']; ?>&visit=PatVisitView.php">Close</a>&nbsp;&nbsp;<a href="#" onclick="Print()">Print</a></td>
          <td width ="340px" colspan="3" nowrap="nowrap"> <h1 align="center">Laboratory Results page </h1></td>
          <td width ="550px">&nbsp;</td>
        </tr>
      </table>
    </td><!--1-->
  </tr>
  

<!-- Begin PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT - PATIENT -   -->
  <tr>
    <td>
  	  <table bgcolor="" width="75%">
	    <tr>
			<td nowrap="nowrap" class="BlueBold_16"><?php echo $row_orders['hospital']; ?></td>
			<td nowrap="nowrap" Title="Entry Date: <?php echo $row_orders['entrydt']; ?>&#10; Entry By: <?php echo $row_orders['entryby']; ?>&#10;Active: <?php echo $row_orders['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_orders['medrecnum']; ?></span></td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_16"><?php echo $row_orders['lastname']; ?></span>, <span class="BlueBold_16"><?php echo $row_orders['firstname']; ?></span> (<span class="BlueBold_16"><?php echo $row_orders['othername']; ?></span>) </td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_orders['gender']; ?></span></td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_orders['ethnicgroup']; ?></span></td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_orders['age']; ?></span></td>
			<td nowrap="nowrap">DOB:<span class="BlueBold_16"><?php echo $row_orders['dob']; ?></span>:<?php echo $row_orders['est']; ?></td>
	   </tr>
	
     </table>

<!--Begin VISIT - VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT -  VISIT - -->  
     <table bgcolor="#FFEEDD">
       <tr>
         <td nowrap="nowrap" Title="Entry Date: <?php echo $row_orders['ventrydt']; ?>&#10; Entry By: <?php echo $row_orders['ventryby']; ?>"><span class="BlueBold_16">Visit</span> #: <span class="BlueBold_16"><?php echo $row_orders['vid']; ?></span></td>
         <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</td>
         <td nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_orders['visitdate']; ?></span></td>
         <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type-Location:<span class="BlueBold_16"><?php echo $row_orders['location']; ?><?php echo $row_orders['pat_type']; ?>-</span></td>
         <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Urgency:</td>
         <td nowrap="nowrap"><span class="BlueBold_16"><?php echo $row_orders['urgency']; ?></span></td>
         <td colspan="2" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discharged:<span class="BlueBold_16"><?php echo $row_orders['discharge']; ?></span></td>
      </tr>
    </table>
  </td>
  </tr>
  
     <tr>
  	   <td colspan="2" >
	 	 <table width="1000px">
	      <tr>
		    <td width ="30px">ord#</td>
		    <td width ="80px">Ordered*</td>
			<td width ="110px">Specimen*</td>
			<td width ="80px">Collected*</td>
			<td width ="150px">Order</td>
			<td width ="125px">Test</td>
			<td width ="125px">Result</td>
			<td width ="30px">Flag</td>
			<td width ="70px">Normal</td>
			<td width ="50px">Units</td>
			<td width ="150px">Interpretation</td>
        </tr>
      </table>
    </td><!--1-->
  </tr>
  <tr>
      <?php do { ?>
  	<td valign="top"><!--2-->
	  <table width="450">
			<tr>
				<td width="30px" ></td>
				<td width="80px" ></td>
				<td width="110px" ></td>
				<td width="80px" ></td>
				<td width="150px" ></td>
			</tr>
        <tr>
          <td class="BlackBold_11"><?php echo $row_orders['ordid']; ?></td>
          <td class="BlackBold_11" title="<?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
          <td class="BlackBold_11" title="Collected: <?php echo $row_orders['colldt']; ?>&#10; Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['spec']; ?></td>
          <td class="BlackBold_11" title="Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['colldt']; ?></td>
          <td class="BlackBold_14"><?php echo $row_orders['name']; ?><br/><span class="BlackBold_11"><?php echo $row_orders['status']; ?></span></td>
		</tr>
		<tr>
          <td colspan="5" class="BlackBold_14">Comments: <?php echo $row_orders['comments']; ?></td>
		</tr>
	 </table>
  </td><!--2-->
  <td><!--3-->
	<table width="550">
		<tr>
			<td width="125px" ></td>
			<td width="125px" ></td>
			<td width="30px" ></td>
			<td width="50px" ></td>
			<td width="70px" ></td>
			<td width="150px" ></td>
		</tr>
	   <?php
			mysql_select_db($database_swmisconn, $swmisconn);
			$query_tests = "SELECT t.id, t.test, t.description, t.units, r.id rid, r.result, r.normflag, n.normlow, n.normhigh ,n.paniclow, n.panichigh, n.interpretation FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3) join results r on (o.id = r.ordid and o.feeid = r.feeid and t.id = r.testid) join testnormalvalues n on (n.testid = t.id and instr(gender,'".$row_orders['gender']."') > 0 AND '".$row_orders['age']."' >= agemin AND '".$row_orders['age']."' < agemax) WHERE t.active = 'Y' and o.id = '".$row_orders['ordid']."' ORDER BY reportseq";
			$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
			$row_tests = mysql_fetch_assoc($tests);
			$totalRows_tests = mysql_num_rows($tests);
	   
	    do { ?>
		  <tr>
			<td nowrap="nowrap" class="BlackBold_11" title="Test ID: <?php echo $row_tests['id']; ?>&#10;Description: <?php echo $row_tests['description']; ?>"><?php echo $row_tests['test']; ?></td>
			<td nowrap="nowrap" class="BlackBold_14" title="Result ID: <?php echo $row_tests['rid']; ?>"><?php echo $row_tests['result']; ?></td>
			<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normflag']; ?></td>
			<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normlow']; ?> - <?php echo $row_tests['normhigh']; ?></td>
			<td nowrap="nowrap" class="BlackBold_11"><?php echo $row_tests['units']; ?></td>
			<td class="BlackBold_11"><?php echo $row_tests['interpretation']; ?></td>
		  </tr>
		<?php } while ($row_tests = mysql_fetch_assoc($tests)); ?>
	</table>
  </td><!--3-->
 </tr>
         <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($orders);

mysql_free_result($tests);
?>
