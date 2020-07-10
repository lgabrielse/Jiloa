<!--Begin LAB - LAB - LAB - LAB - LAB - LAB - LAB - LAB - LAB - LAB - LAB - LAB - -->
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_orders = "Select o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y %H:%i') entrydt, o.status, o.doctor, o.entryby, o.comments, o.revby, o.revdt, f.name, s.name spec, sc.collby, DATE_FORMAT(sc.colldt,'%d-%b-%Y %H:%i') colldt, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age from orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum join specimens s on f.specid= s.id left outer join speccollected sc on sc.ordnum = o.id where f.dept = 'Laboratory' and visitid = '".$visitid."'";
$orders = mysql_query($query_orders, $swmisconn) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
<?php if($totalRows_orders > 0) { ?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">

<table width="1000px" bgcolor="EBF4FA">
  <tr>
    <td colspan="2" >
       <table bgcolor="CAE5FF" width="1000px">
         <tr>
           <td Colspan="11"> <div align="center" class="BlackBold_18">        --------------------------------------------------------- Laboratory -------------------------------------------------------------------- </div></td>
        </tr>
        <tr>
          <td width ="30px">&nbsp;</td>
          <td width ="80px">Ordered*</td>
          <td width ="110px">Specimen*</td>
          <td width ="80px">Collected*</td>
          <td width ="150px">Order*</td>
          <td width ="125px">Test*</td>
          <td width ="125px">Result*</td>
          <td width ="30px">Flag</td>
          <td width ="70px">Normal</td>
          <td width ="50px">Units</td>
          <td width ="150px">Interpretation</td>
        </tr>
      </table>
    </td>
    <!--1-->
  </tr>
  <tr>
    <td><!--2-->
	  <table>
	     <tr>

			<?php do { ?>
			<td valign="top"><!--2a-->
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
					<td class="BlackBold_11" title="Doctor: <?php echo $row_orders['doctor']; ?>&#10;Entry By: <?php echo $row_orders['entryby']; ?>"><?php echo $row_orders['entrydt']; ?></td>
					<td class="BlackBold_11" title="Collected: <?php echo $row_orders['colldt']; ?>&#10; Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['spec']; ?></td>
					<td class="BlackBold_11" title="Collected By: <?php echo $row_orders['collby']; ?>"><?php echo $row_orders['colldt']; ?></td>
					<td class="BlackBold_14" title="Reviewed By: <?php echo $row_orders['revby']; ?>&#10;Reviewed Date: <?php echo $row_orders['revdt']; ?>"><?php echo $row_orders['name']; ?><br/>
						<span class="BlackBold_11"><?php echo $row_orders['status']; ?></span></td>
				  </tr>
				  <tr>
					<td colspan="5" class="BlackBold_14">Comments: <?php echo $row_orders['comments']; ?></td>
				  </tr>
			  </table>
			</td><!--2a-->
			<td><!--2b-->
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
					$query_tests = "SELECT t.id, t.test, t.description, t.units, r.id rid, r.result, r.normflag, r.entryby, r.entrydt, n.normlow, n.normhigh ,n.paniclow, n.panichigh, n.interpretation FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN) join results r on (o.id = r.ordid and o.feeid = r.feeid and t.id = r.testid) join testnormalvalues n on (n.testid = t.id and instr(gender,'".$row_orders['gender']."') > 0 AND '".$row_orders['age']."' >= agemin AND '".$row_orders['age']."' < agemax) WHERE t.active = 'Y' and o.id = '".$row_orders['ordid']."' ORDER BY reportseq";
					$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
					$row_tests = mysql_fetch_assoc($tests);
					$totalRows_tests = mysql_num_rows($tests);
			   
				do { ?>
				  <tr>
					<td nowrap="nowrap" class="BlackBold_11" title="Test ID: <?php echo $row_tests['id']; ?>&#10;Description: <?php echo $row_tests['description']; ?>"><?php echo $row_tests['test']; ?></td>
					<td nowrap="nowrap" class="BlackBold_14" title="Result ID: <?php echo $row_tests['rid']; ?> &#10;Entered By: <?php echo $row_tests['entryby']; ?>&#10;Entered Date: <?php echo $row_tests['entrydt']; ?>"><?php echo $row_tests['result']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normflag']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11" ><?php echo $row_tests['normlow']; ?> - <?php echo $row_tests['normhigh']; ?></td>
					<td nowrap="nowrap" class="BlackBold_11"><?php echo $row_tests['units']; ?></td>
					<td class="BlackBold_11"><?php echo $row_tests['interpretation']; ?></td>
				  </tr>
          <?php if($row_tests['test'] == 'Culture' && $row_tests['result'] == 'No growth'){ break; }?>
				  <?php } while ($row_tests = mysql_fetch_assoc($tests)); ?>
			  </table>
			</td><!--2b-->
	  	  </tr>
  <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
  	  </table>
	</td><!--2-->
  </tr>

</table>
<?php //} ?>
<!--<table width = "1000px" >
	<tr>
		<td><a href="#Top" class="BlueBold_10">Jump to Top</a></td>
		<td height="5px" bgcolor="#6699CC" class="legal"> <><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><a href="#Top" class="titlebar">Jump to Top</a> <><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>   </td>
	</tr>
</table>-->
<?php } while ($row_visits = mysql_fetch_assoc($visits)); ?>
</td>
</tr>
</table>
