<?php
// previous pregnancy data
mysql_select_db($database_swmisconn, $swmisconn);
$query_prevpreg = sprintf("SELECT id, medrecnum, pregid, name, DATE_FORMAT(dob,'%%d-%%b-%%Y') dob, pregdur, plptext, birthweight, babystatus, gender, entryby, entrydt FROM anprevpregs WHERE pregid = '".$pregid."' order by dob");
$prevpreg = mysql_query($query_prevpreg, $swmisconn) or die(mysql_error());
$row_prevpreg = mysql_fetch_assoc($prevpreg);
$totalRows_prevpreg = mysql_num_rows($prevpreg);

// Pregnancy data
//$colname_preg = "-1";  //1317
//if (isset($_SESSION['mrn'])) {
//  $colname_preg = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
//}
mysql_select_db($database_swmisconn, $swmisconn);
$query_preg = sprintf("SELECT id, medrecnum, DATE_FORMAT(lmp,'%%d-%%b-%%Y') lmp, DATE_FORMAT(edd,'%%d-%%b-%%Y') edd, DATE_FORMAT(ussedd,'%%d-%%b-%%Y') ussedd, DATE_FORMAT(firstvisit,'%%d-%%b-%%Y') firstvisit, obg, obp, specpoints, HistPatHeart, HistPatHTN,HistPatChest, HistPatSCD, HistPatKidney, HistPatSeiz, HistPatBldTransf, HistPatDM, HistPatOperations, HistPatOther, HistFamHeart, HistFamHypertens, HistFamTb, HistFamDM, HistFamMultPreg, HistFamOther, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anpreg WHERE id = '".$pregid."' ORDER BY id ASC");
$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
$row_preg = mysql_fetch_assoc($preg);
$totalRows_preg = mysql_num_rows($preg);
 
//  Followup Data
//mysql_select_db($database_swmisconn, $swmisconn);
//$query_followup = sprintf("SELECT id, medrecnum, visitid, pregid, ega, hof, prespos, lie, fetalheart, bldpres, weight, oedema, foluptext, DATE_FORMAT(nextvisit,'%%d-%%b-%%Y') nextvisit, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anfollowup WHERE pregid = '".$pregid."' order by id");
//$followup = mysql_query($query_followup, $swmisconn) or die(mysql_error());
//$row_followup = mysql_fetch_assoc($followup);
//$totalRows_followup = mysql_num_rows($followup);
//
?>

<?php if($totalRows_prevpreg >= 1){ ?>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
 
  	<table width="1000px">
	  <tr bgcolor="#E8F0F4">
	    <td colspan="8" nowrap="nowrap" class="Black_14">
	      <div align="center" class="BlackBold_18">--------------------------------------------------------------- PreviousPregnancies&nbsp;&nbsp;&nbsp;(<?php echo $totalRows_preg ?>) --------------------------------------------------------------</div></td>
      </tr>
	  <tr bgcolor="#E8F0F4">
	    <td nowrap="nowrap" class="Black_14"><div align="center" class="BlackBold_10">Preg<br />Record</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Name:</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">DOB</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Preg.Duration</div></td>
		<td nowrap="NOWRAP" class="Black_14"><div align="center">Pregnancy, Labour, Pueperium</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Birth Weight </div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Baby Status </div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Gender</div></td>
	  </tr>

<?php do { ?>
	  <tr>
	    <td class="borderbottomthinblack" bgcolor="#E8F0F4"><div align="center"><?php echo $row_prevpreg['pregid']; ?></div></td>
		<td class="borderbottomthinblack" nowrap="nowrap" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['name']; ?></div></td>
		<td class="borderbottomthinblack" nowrap="nowrap" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['dob']; ?></div></td>
		<td class="borderbottomthinblack" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['pregdur']; ?></div></td>
		<td class="borderbottomthinblack" bgcolor="#FFFFFF"><div align="left"><?php echo $row_prevpreg['plptext']; ?></div></td>
		<td class="borderbottomthinblack" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['birthweight']; ?></div></td>
		<td class="borderbottomthinblack" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['babystatus']; ?></div></td>
		<td class="borderbottomthinblack" bgcolor="#FFFFFF"><div align="center"><?php echo $row_prevpreg['gender']; ?></div></td>
	  </tr>
    <?php } while ($row_prevpreg = mysql_fetch_assoc($prevpreg)); ?>
   <tr>
</table>
<?php } ?>
<table width="1000px">
   <tr>
  	<td Colspan="8"> <div align="center" class="BlackBold_18">-------------------------------------------------------- Pregnancy Record -------------------------------------------------------------------</div></td>
  </tr>
</table>

<?php do { ?>
   <table width="1000px" bgcolor="#EDEDED">
     <form  id="formapv" name="formapv" method="POST">
      <tr>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblackBold14" Title="Entryby: <?php echo $row_preg['entryby']; ?>&#10;Entrydt: <?php echo $row_preg['entrydt']; ?>">Pregnancy<br /> 
      ID: <?php echo $row_preg['id']; ?> </td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >lmp:<br />
      <input name="lmp" type="text" id="lmp" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['lmp']; ?>"/></td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >EDD:<br />
      <input name="edd" type="text" id="edd" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['edd']; ?>" /></td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >USS EDD:<br />
      <input name="ussedd" type="text" id="ussedd" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['ussedd']; ?>" /></td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >FirstVisit:<br />
      <input name="firstvisit" type="text" id="firstvisit" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['firstvisit']; ?>" /></td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >OB Hist:<br />
          G
        <input name="obg" type="text" id="obg" size="1" maxlength="3" readonly="readonly" value="<?php echo $row_preg['obg']; ?>" /> 
        P
      <input name="obp" type="text" id="obp" size="1" maxlength="3" readonly="readonly" value="<?php echo $row_preg['obp']; ?>" /></td>
        <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >Special<br />
        Points:</td>
        <td valign="top" bgcolor="#FFFFFF" class="borderbottomthinblack"><?php echo $row_preg['specpoints']; ?></td>
      </tr>
  <tr>                  
    <td valign="top" nowrap="nowrap" class="borderbottomthinblackBold14"><div align="center">Patient<br />
      History:</div></td>
	  <?php if($row_preg['HistPatHeart'] == "Y") { $bkg1 = "#FFFDDA";} else {$bkg1 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg1; ?> nowrap="nowrap" >Heart<br />
      Disease 
      <input name="HistPatHeart" type="text" id="HistPatHeart" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatHeart']; ?>" /></td>

	    <?php if($row_preg['HistPatHTN'] == "Y") { $bkg2 = "#FFFDDA";} else {$bkg2 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg2; ?> nowrap="nowrap" >HTN 
      <input name="HistPatHTN" type="text" id="HistPatHTN" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatHTN']; ?>" /></td>
	    <?php if($row_preg['HistPatChest'] == "Y") { $bkg2 = "#FFFDDA";} else {$bkg2 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg2; ?> nowrap="nowrap" >Chest<br />
      Disease 
      <input name="HistPatChest" type="text" id="HistPatChest" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatChest']; ?>" /></td>

	  <?php if($row_preg['HistPatSCD'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap" >SCD 
      <input name="HistPatSCD" type="text" id="HistPatSCD" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatSCD']; ?>" /></td>
      
	  <?php if($row_preg['HistPatKidney'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap" >Kidney<br />
      Disease 
      <input name="HistPatKidney" type="text" id="HistPatKidney" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatKidney']; ?>" /></td>

	  <?php if($row_preg['HistPatSeiz'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap" >Seiz
      <input name="HistPatSeiz" type="text" id="HistPatSeiz" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatSeiz']; ?>" /></td>
      
	  <?php if($row_preg['HistPatBldTransf'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" >Blood<br />
      Transf'ns 
      <input name="HistPatBldTransf" type="text" id="HistPatBldTransf" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatBldTransf']; ?>" 
/></td>

	  <?php if($row_preg['HistPatDM'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" >DM
      <input name="HistPatDM" type="text" id="HistPatDM" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatDM']; ?>" 
/></td>
	  <?php if($row_preg['HistPatOperations'] == "Y") { $bkg5 = "#FFFDDA";} else {$bkg5 = "#DCDCDC";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg5; ?> nowrap="nowrap" >Oper-<br />
      ations 
      <input name="HistPatOperations" type="text" id="HistPatOperations" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatOperations']; ?>" /></td>

    <td valign="top" nowrap="nowrap" class="borderbottomthinblack">Other:</td>
    <td valign="top" bgcolor="#FFFFFF" class="borderbottomthinblack"><?php echo $row_preg['HistPatOther']; ?></td>
  </tr>
  <tr>
    <td valign="top" nowrap="nowrap"  class="BlackBold_14"><div align="center">Family<br />
      History:</div></td>


	  <?php if($row_preg['HistFamHeart'] == "Y") { $bkg9 = "#FFFDDA";} else {$bkg9 = "#DCDCDC";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg9; ?> >Heart<br />
      Disease      
      <input name="HistFamHeart" type="text" id="HistFamHeart" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamHeart']; ?>" /></td>

	  <?php if($row_preg['HistFamHypertens'] == "Y") { $bkg8 = "#FFFDDA";} else {$bkg8 = "#DCDCDC";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg8; ?> >Hyper-<br />
      Tension 
      <input name="HistFamHypertens" type="text" id="HistFamHypertens" size="2" readonly="readonly" value="<?php echo $row_preg['HistFamHypertens']; ?>" maxlength="3" /></td>

	  <?php if($row_preg['HistFamTb'] == "Y") { $bkg7 = "#FFFDDA";} else {$bkg7 = "#DCDCDC";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg7; ?> >Tuberc-<br />
      ulosis
      <input name="HistFamTb" type="text" id="HistFamTb" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamTb']; ?>" /></td>

	  <?php if($row_preg['HistFamDM'] == "Y") { $bkg7 = "#FFFDDA";} else {$bkg7 = "#DCDCDC";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg7; ?> >DM
      <input name="HistFamDM" type="text" id="HistFamDM" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamDM']; ?>" /></td>

	  <?php if($row_preg['HistFamMultPreg'] == "Y") { $bkg6 = "#FFFDDA";} else {$bkg6 = "#DCDCDC";} ?> 
    <td bgcolor=<?php echo $bkg6; ?> nowrap="nowrap" >Multiple<br />
      Preg's
        <input name="HistFamMultPreg" type="text" id="HistFamMultPreg" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamMultPreg']; ?>" /></td>
        
    <td valign="top" nowrap="nowrap" class="borderbottomthinblack" ></td>
    <td valign="top" nowrap="nowrap" class="borderbottomthinblack" >Other:</td>
    <td valign="top" bgcolor="#FFFFFF" class="borderbottomthinblack" ><?php echo $row_preg['HistFamOther']; ?></td>
    </tr>
   </form>
  </table>
<?php  } while ($row_preg = mysql_fetch_assoc($preg));?>




<!--<table width="1000px" cellpadding="0" cellspacing="0">
  <tr>
  	<td Colspan="11"> <div align="center" class="BlackBold_14">--------------------------------------------------------------- Follow Up Visits -------------------------------------------------------------- </div></td>
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
  
  
-->  
<!--
<?php  //do { ?>
    <tr>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack" Title="VisitID: <?php //echo $row_followup['visitid']; ?>&#10;PregID: <?php //echo $row_followup['pregid']; ?>&#10;FollowupID: <?php ////echo $row_followup['id']; ?>&#10;EntryBY: <?php //echo $row_followup['entryby']; ?>&#10;EntryDt: <?php //echo $row_followup['entrydt']; ?>">
	    <div align="center">
	      <input name="medrecnum" type="text" id="medrecnum" value="<?php //echo $row_followup['pregid']; ?>,<?php //echo $row_followup['id']; ?>,<?php //echo $row_followup['visitid']; ?>" size="8" maxlength="12" style="text-align:center;" class="Black_11" readonly="readonly" />
      </div></td>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" style="text-align:center;" class="Black_11" readonly="readonly" value="<?php //echo $row_followup['entrydt']; ?>" />
      </div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="ega" type="text" id="ega" size="2" maxlength="2" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['ega']; ?>" />
      <strong>Wks</strong></div></td>
      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="hof" type="text" id="hof" size="2" maxlength="2" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['hof']; ?>" />
      <strong>Cm</strong></div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="lie" type="text" id="lie" size="8" maxlength="12" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['lie']; ?>" />
      </div></td>
      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="prespos" type="text" id="prespos" size="12" maxlength="12" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['prespos']; ?>" />
      </div></td>
      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="fetalheart" type="text" id="fetalheart" size="2" maxlength="2" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['fetalheart']; ?>" />
      <strong>bpm</strong></div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="bldpres" type="text" id="bldpres" size="3" maxlength="6"style="text-align:center;"  readonly="readonly" value="<?php //echo $row_followup['bldpres']; ?>" />
      <strong>mmHg</strong></div></td>

      <td valign="top" nowrap="nowrap"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="weight" type="text" id="weight" size="2" maxlength="3" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['weight']; ?>" />
      <strong>Kg</strong></div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="oedema" type="text" id="oedema" size="4" maxlength="6" style="text-align:center;" readonly="readonly" value="<?php //echo $row_followup['oedema']; ?>" />
      </div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="nextvisit" type="text" id="nextvisit" size="8" maxlength="12" class="Black_11" readonly="readonly" value="<?php //echo $row_followup['nextvisit']; ?>" />
      </div></td>

      <td valign="top"  bgcolor="#FFE4E1" class="borderbottomthinblack"><div align="center">
        <input name="entryby" type="text" id="entryby" size="10" maxlength="20" class="Black_11" readonly="readonly" value="<?php //echo $row_followup['entryby']; ?>" />
      </div></td>
    </tr>
	<tr>
		<td>Remarks</td>
		<td colspan="10" bgcolor="#FFFFFF"><?php //echo $row_followup['foluptext']; ?></td>
	</tr>
<?php // } while ($row_followup = mysql_fetch_assoc($followup));
?>
</table>
-->