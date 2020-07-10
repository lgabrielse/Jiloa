<?php //  Followup Data
mysql_select_db($database_swmisconn, $swmisconn);
$query_followup = "SELECT id, medrecnum, visitid, pregid, ega, hof, prespos, lie, fetalheart, bldpres, weight, oedema, foluptext, DATE_FORMAT(nextvisit,'%%d-%%b-%%Y') nextvisit, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anfollowup WHERE visitid = '".$visitid."' order by id";
$followup = mysql_query($query_followup, $swmisconn) or die(mysql_error());
$row_followup = mysql_fetch_assoc($followup);
$totalRows_followup = mysql_num_rows($followup);

?>
<?php if($totalRows_followup > 0) { ?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">

<table width="1000px" cellpadding="0" cellspacing="0">
  <tr>
  	<td Colspan="11"> <div align="center" class="BlackBold_18">--------------------------------------------------------------- Follow Up  -------------------------------------------------------------- </div></td>
  </tr>
  <tr>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Follow-Up<br />
    PregID, FID,<br />VisitID</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Date</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">EGA</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center"> Fundus<br />
    Height</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Lie</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Presentation<br />
    and Position </div></td>
    <td nowrap="nowrap" bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Foetal<br />Heart Rate</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Blood<br />Pressure</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Weight</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Oedema</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Return</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12">Examiner</td>
  </tr>
  
  
  
<?php
         do { ?>
    <tr>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack" Title="VisitID: <?php echo $row_followup['visitid']; ?>&#10;PregID: <?php echo $row_followup['pregid']; ?>&#10;FollowupID: <?php echo $row_followup['id']; ?>&#10;EntryBY: <?php echo $row_followup['entryby']; ?>&#10;EntryDt: <?php echo $row_followup['entrydt']; ?>">
	    <div align="center">
	      <input name="medrecnum" type="text" id="medrecnum" value="<?php echo $row_followup['pregid']; ?>,<?php echo $row_followup['id']; ?>,<?php echo $row_followup['visitid']; ?>" size="8" maxlength="12" style="text-align:center;" class="Black_11" readonly />
      </div></td>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" style="text-align:center;" class="Black_11" readonly value="<?php echo $row_followup['entrydt']; ?>" />
      </div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="ega" type="text" id="ega" size="2" maxlength="2" style="text-align:center;" readonly value="<?php echo $row_followup['ega']; ?>" />
      <strong>Wks</strong></div></td>
      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="hof" type="text" id="hof" size="2" maxlength="2" style="text-align:center;" readonly value="<?php echo $row_followup['hof']; ?>" />
      <strong>Cm</strong></div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="lie" type="text" id="lie" size="8" maxlength="12" style="text-align:center;" readonly value="<?php echo $row_followup['lie']; ?>" />
      </div></td>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="prespos" type="text" id="prespos" size="12" maxlength="12" style="text-align:center;" readonly value="<?php echo $row_followup['prespos']; ?>" />
      </div></td>
      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="fetalheart" type="text" id="fetalheart" size="2" maxlength="2" style="text-align:center;" readonly value="<?php echo $row_followup['fetalheart']; ?>" />
      <strong>bpm</strong></div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="bldpres" type="text" id="bldpres" size="3" maxlength="6"style="text-align:center;"  readonly="readonly" value="<?php echo $row_followup['bldpres']; ?>" />
      <strong>mmHg</strong></div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="weight" type="text" id="weight" size="2" maxlength="3" style="text-align:center;" readonly value="<?php echo $row_followup['weight']; ?>" />
      <strong>Kg</strong></div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="oedema" type="text" id="oedema" size="4" maxlength="6" style="text-align:center;" readonly value="<?php echo $row_followup['oedema']; ?>" />
      </div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="nextvisit" type="text" id="nextvisit" size="8" maxlength="12" class="Black_11" readonly value="<?php echo $row_followup['nextvisit']; ?>" />
      </div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="entryby" type="text" id="entryby" size="10" maxlength="20" class="Black_11" readonly value="<?php echo $row_followup['entryby']; ?>" />
      </div></td>
    </tr>
	<tr>
		<td>Remarks</td>
		<td colspan="10" bgcolor="#FFFFFF"><?php echo $row_followup['foluptext']; ?></td>
	</tr>
<?php 	 } while ($row_followup = mysql_fetch_assoc($followup)); ?>
<?php }?>
</table>

</body>
</html>