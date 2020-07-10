<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
//$colname_preg = "1317";
if (isset($_GET['pregid'])) {  // link from prev preg i.e. pregnanccy outcome
  $colname_preg = (get_magic_quotes_gpc()) ? $_GET['pregid'] : addslashes($_GET['pregid']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_preg = sprintf("SELECT id, medrecnum, DATE_FORMAT(lmp,'%%b %%d, %%Y') lmp, DATE_FORMAT(edd,'%%b %%d, %%Y') edd, ega, DATE_FORMAT(ussedd,'%%b %%d, %%Y') ussedd, DATE_FORMAT(firstvisit,'%%b %%d, %%Y') firstvisit, obg, obp, specpoints, HistPatHeart, HistPatChest, HistPatKidney, HistPatBldTransf, HistPatPPH, HistPatOperations, HistPatHTN, HistPatSCD, HistPatSeiz, HistPatDM, HistPatOther, HistFamMultPreg, HistFamTb, HistFamHypertens, HistFamHeart, HistFamDM,  HistFamOther, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anpreg WHERE id = '".$colname_preg."' ORDER BY id ASC");
$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
$row_preg = mysql_fetch_assoc($preg);
$totalRows_preg = mysql_num_rows($preg);

} else {

//$colname_preg = "1317";
if (isset($_SESSION['mrn'])) {  // link from current pregnancy
  $colname_preg = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_preg = sprintf("SELECT id, medrecnum, DATE_FORMAT(lmp,'%%b %%d, %%Y') lmp, DATE_FORMAT(edd,'%%b %%d, %%Y') edd, ega, DATE_FORMAT(ussedd,'%%b %%d, %%Y') ussedd, DATE_FORMAT(firstvisit,'%%b %%d, %%Y') firstvisit, obg, obp, specpoints, HistPatHeart, HistPatChest, HistPatKidney, HistPatBldTransf, HistPatPPH, HistPatOperations, HistPatHTN, HistPatSCD, HistPatSeiz, HistPatDM, HistPatOther, HistFamMultPreg, HistFamTb, HistFamHypertens, HistFamHeart, HistFamDM,  HistFamOther, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anpreg WHERE id = (Select Max(id) from anpreg where medrecnum = '".$colname_preg."') and medrecnum = %s ORDER BY id ASC", $colname_preg);
$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
$row_preg = mysql_fetch_assoc($preg);
$totalRows_preg = mysql_num_rows($preg);
}
$colname_followup = "-1";
if (isset($row_preg['id'])) {
  $colname_followup = (get_magic_quotes_gpc()) ? $row_preg['id'] : addslashes($row_preg['id']);
  $_SESSION['pregid'] = $colname_followup;
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_followup = sprintf("SELECT a.id, a.medrecnum, a.visitid, a.pregid, a.ega, a.hof, a.prespos, a.lie, a.fetalheart, a.bldpres, a.weight, a.oedema, a.foluptext, a.seedoc, a.nextvisit, a.entryby, a.entrydt, v.diagnosis diag FROM anfollowup a JOIN patvisit v on a.visitid = v.id WHERE pregid = %s", $colname_followup);
$followup = mysql_query($query_followup, $swmisconn) or die(mysql_error());
$row_followup = mysql_fetch_assoc($followup);
$totalRows_followup = mysql_num_rows($followup);

mysql_select_db($database_swmisconn, $swmisconn);
$query_prevpreg = sprintf("SELECT id, medrecnum, pregid, entryby, entrydt FROM anprevpregs WHERE pregid = %s", $colname_followup);
$prevpreg = mysql_query($query_prevpreg, $swmisconn) or die(mysql_error());
$row_prevpreg = mysql_fetch_assoc($prevpreg);
$totalRows_prevpreg = mysql_num_rows($prevpreg);

?>

  <?php $AddFolup = "True";?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
  <body>
 <?php if($totalRows_preg > 0 && $totalRows_prevpreg == 0 ){?> 
 <form  id="formapv" name="formapv" method="POST">
   <table width="80%">
      <tr>
        <td nowrap="nowrap" Title="Entryby: <?php echo $row_preg['entryby']; ?>&#10;Entrydt: <?php echo $row_preg['entrydt']; ?>">MRN<br />
   	  <input name="medrecnum" type="text" value="<?php echo $_SESSION['mrn'] ?>" size="3" maxlength="9" readonly="readonly" /></td>
        <td class="borderbottomthinblackBold14" nowrap="nowrap">Pregnancy<br /> 
      ID: <?php echo $row_preg['id']; ?> </td>
        <td colspan="2" nowrap="nowrap" class="borderbottomthinblack" >LMP:<br />
        <input name="lmp" type="text" id="lmp" size="8" maxlength="10" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['lmp']; ?>"/></td>
        <td colspan="2" nowrap="nowrap" class="borderbottomthinblack" >EDD:<br />
        <input name="edd" type="text" id="edd" size="8" maxlength="10" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['edd']; ?>" /></td>
        <td colspan="2" nowrap="nowrap" class="borderbottomthinblack" >&nbsp;</td>
        <td colspan="2" nowrap="nowrap" class="borderbottomthinblack" >USS EDD:<br />
        <input name="ussedd" type="text" id="ussedd" size="8" maxlength="10" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['ussedd']; ?>" /></td>
        <td class="borderbottomthinblack" nowrap="nowrap" >FirstVisit:<br />
        <input name="firstvisit" type="text" id="firstvisit" size="8" maxlength="10" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['firstvisit']; ?>" /></td>
        <td class="borderbottomthinblack" nowrap="nowrap" >OB Hist:<br />
          G
        <input name="obg" type="text" id="obg" size="1" maxlength="3" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['obg']; ?>" /> 
        P
      <input name="obp" type="text" id="obp" size="1" maxlength="3" class="BlackBold_12" readonly="readonly" value="<?php echo $row_preg['obp']; ?>" /></td>
   <?php if(isset($_GET['pge2']) && $_GET['pge2']=='PatAnteFollowupDelete.php'){?>
         <td class="BlueBold_24" nowrap="nowrap" ><div align="center">Delete Followup</div></td>    
   <?php } elseif(isset($_GET['pge2']) && $_GET['pge2']=='PatAnteFollowupEdit.php'){?> 
        <td class="BlueBold_24" nowrap="nowrap" ><div align="center">Edit Followup</div></td>
  <?php } else { ?>
        <td class="BlueBold_24" nowrap="nowrap" ><div align="center">View Pregnancy</div></td>
  <?php } ?>
       </tr>
<!--line 2 -->
  <tr>                  
    <td nowrap="nowrap" ><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregEdit.php&pregid=<?php echo $row_preg['id']; ?>">Edit</a><br />
      <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregDelete.php&pregid=<?php echo $row_preg['id']; ?>">Delete</a></td> 
	<td class="borderbottomthinblackBold14" nowrap="nowrap"><div align="center">Patient<br />
      History:</div></td>
	
	  <?php if($row_preg['HistPatHeart'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg1; ?> nowrap="nowrap"  title="Heart Disease"  >
      <input type="checkbox" name="HistPatHeart" id="HistPatHeart" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatHeart'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HD</td>
      
	  <?php if($row_preg['HistPatHTN'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg1; ?> nowrap="nowrap" Title="Hypertension"  >
    <input type="checkbox" name="HistPatHTN" id="HistPatHTN" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatHTN'] == "Y") {echo "checked=\"checked\"";} ?>  />
HTN</td> 

	  <?php if($row_preg['HistPatChest'] == "Y") {$bkg2 = "#FFFDDA"; } else {$bkg2 = "#FFFFFF";} ?> 
	<td class="borderbottomthinblack" bgcolor=<?php echo $bkg2; ?> nowrap="nowrap" title="Chest Disease">
      <input type="checkbox" name="HistPatChest" id="HistPatChest" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatChest'] == "Y") {echo "checked=\"checked\"";} ?> />
      CD</td>
      
	  <?php if($row_preg['HistPatSCD'] == "Y") {$bkg2 = "#FFFDDA"; } else {$bkg2 = "#FFFFFF";} ?> 
	<td class="borderbottomthinblack" bgcolor=<?php echo $bkg2; ?> nowrap="nowrap" title="Sickle Cell Disease"><input type="checkbox" name="HistPatSCD" id="HistPatSCD" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatSCD'] == "Y") {echo "checked=\"checked\"";} ?> />
SCD</td>

     
	  <?php if($row_preg['HistPatKidney'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap"  title="Kidney Disease"> 
      <input type="checkbox" name="HistPatKidney" id="HistPatKidney" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatKidney'] == "Y") {echo "checked=\"checked\"";} ?>  />
      KD</td>
      
	  <?php if($row_preg['HistPatSeiz'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap" title="Seizures" ><input type="checkbox" name="HistPatSeiz" id="HistPatSeiz" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatSeiz'] == "Y") {echo "checked=\"checked\"";} ?>  />
Seiz</td>

	  <?php if($row_preg['HistPatBldTransf'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="Blood Transfusions" >
      <input type="checkbox" name="HistPatBldTransf" id="HistPatBldTransf" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatBldTransf'] == "Y") {echo "checked=\"checked\"";} ?> />
      BT</td>
      
	  <?php if($row_preg['HistPatPPH'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="Post Partum Haemorrhage" >
      <input type="checkbox" name="HistPatPPH" id="HistPatPPH" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatPPH'] == "Y") {echo "checked=\"checked\"";} ?> />
      PPH</td>
      
	  <?php if($row_preg['HistPatDM'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="Diabetes" ><input type="checkbox" name="HistPatDM" id="HistPatDM" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistPatDM'] == "Y") {echo "checked=\"checked\"";} ?> />
DM</td>

	  <?php if($row_preg['HistPatOperations'] == "Y") { $bkg5 = "#FFFDDA";} else {$bkg5 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg5; ?> nowrap="nowrap" title="Operations" >
      <input type="checkbox" name="HistPatOperations" id="HistPatOperations" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly"  <?php if ($row_preg['HistPatOperations'] == "Y") {echo "checked=\"checked\"";} ?>  />
      Ops </td>

    <td align="right" nowrap="nowrap" class="borderbottomthinblack" >Special Points:</td>
    <td class="borderbottomthinblack" nowrap="nowrap" ><textarea name="specpoints2" cols="20" rows="1" id="specpoints2" onclick="return false;" readonly="readonly" ><?php echo $row_preg['specpoints']; ?></textarea></td>
 
  </tr>
<!--line3-->

<!--line 4-->
  <tr>
    <td nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePrevPregAdd.php&pregid=<?php echo $row_preg['id']; ?>">Pregnancy<br />
      outcome</a></td>
    <td nowrap="nowrap"  class="BlackBold_14"><div align="center">Family<br />
      History:</div></td>

	  <?php if($row_preg['HistFamHeart'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" Title="Heart Disease" >
      <input type="checkbox" name="HistFamHeart" id="HistFamHeart" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistFamHeart'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HD</td>

	  <?php if($row_preg['HistFamHypertens'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" title="Hypertension"><input type="checkbox" name="HistFamHypertens" id="HistFamHypertens" style="checkbox-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistFamHypertens'] == "Y") {echo "checked=\"checked\"";} ?>    />
HTN</td> 

	  <?php if($row_preg['HistFamTb'] == "Y") { $bkg8 = "#FFFDDA";} else {$bkg8 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg8; ?> class="borderbottomthinblack" title = "Tuberculosis" ><input type="checkbox" name="HistFamTb" id="HistFamTb" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistFamTb'] == "Y") {echo "checked=\"checked\"";} ?>  />
TB</td>

	  <?php if($row_preg['HistFamDM'] == "Y") { $bkg8 = "#FFFDDA";} else {$bkg8 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg8; ?> class="borderbottomthinblack" title="Diabetes"><input type="checkbox" name="HistFamDM" id="HistFamDM" style="text-align: center;" size="1" maxlength="1"  onclick="return false;" readonly="readonly" <?php if ($row_preg['HistFamDM'] == "Y") {echo "checked=\"checked\"";} ?>  />
DM</td>
      
	  <?php if($row_preg['HistFamMultPreg'] == "Y") { $bkg7 = "#FFFDDA";} else {$bkg7 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg7; ?> class="borderbottomthinblack" title = "Multiple Pregnacies" ><input type="checkbox" name="HistFamMultPreg" id="HistFamMultPreg" style="text-align: center;" size="1" maxlength="1" onclick="return false;" readonly="readonly" <?php if ($row_preg['HistFamMultPreg'] == "Y") {echo "checked=\"checked\"";} ?>  />
MP</td>


    <td nowrap="nowrap"  class="borderbottomthinblack">&nbsp;</td>
    <td nowrap="nowrap"  class="borderbottomthinblack">&nbsp;</td>
    <td nowrap="nowrap"  class="borderbottomthinblack">&nbsp;</td>
    <td nowrap="nowrap"  class="borderbottomthinblack">&nbsp;</td>
    <td nowrap="nowrap"  class="borderbottomthinblack">&nbsp;</td>


    <td align="right" nowrap="nowrap" class="borderbottomthinblack" >Other:</td>
    <td nowrap="nowrap" class="borderbottomthinblack" ><textarea name="HistFamOther" cols="20" rows="1" id="HistFamOther" onclick="return false;" readonly="readonly" ><?php echo $row_preg['HistFamOther']; ?></textarea></td>

    </tr>
  </table>
</form>
<?php } else { ?>
	<div align="center"><span class="dingbat">No Current Pregnacy record: </span> <span class="BlueBold_16"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregAdd.php"> Add Pregnancy</a></span></div>

<?php }?>
<!--     **********************************FOLLOW-UP **********************************    -->
	<!--<div>totrows<?php //echo $totalRows_preg; ?></div>-->
<?php  If($totalRows_followup > 0 && $totalRows_prevpreg == 0 && !isset($_GET['pge2'])) { ?> <!--// If there are any records to display and is a current pregnancy-->

<table cellpadding="0" cellspacing="0">
  <tr>
   <!--Add rule for when 'Add' is displayed-->


    <td bgcolor="#ffa07a"></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Follow-Up<br />PregID,FID</div></td>    
    <td bgcolor="#FFE4E1" class="BlackBold_12"><div align="center">Date</div></td>
      <?php $visitdt=date_create($_SESSION['visitdt']); ?>  <!--$_SESSION['visitdt'] from patvisitview.php-->
    <td bgcolor="#ffa07a" class="BlackBold_12" title="Visitdate: <?php echo date_format($visitdt,"M-d-Y"); ?>&#10;EGA = visitdate minus lmp date in weeks"><div align="center">EGA</div></td>
  <!--  <td bgcolor="#ffa07a" class="BlackBold_12" title="EGA = visitdate minus lmp date in weeks"><div align="center">EGA</div></td>-->
    <td bgcolor="#FFE4E1" class="BlackBold_12"><div align="center"> Fundus<br />
    Height</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Lie</div></td>
    <td bgcolor="#FFE4E1" class="BlackBold_12"><div align="center">Presentation<br />
    and Position </div></td>
    <td  bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Foetal<br />
    Heart Rate</div></td>
    <td bgcolor="#FFE4E1" class="BlackBold_12"><div align="center">Blood<br />Pressure</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12"><div align="center">Weight</div></td>
    <td bgcolor="#FFE4E1" class="BlackBold_12"><div align="center">Oedema</div></td>
    <td bgcolor="#ffa07a" class="BlackBold_12">Examiner</td>
  </tr>
  
  
  
  <?php
         do { ?>
    <tr>
<!--If followup visitid = current visitid, display EDIT link, else display ----  -->
<?php if (isset($row_followup['visitid']) &&  isset($_SESSION['vid']) && $row_followup['visitid'] == $_SESSION['vid']) {?>
      <td bgcolor="#FFE4E1" Title="VisitID: <?php echo $row_followup['visitid'];?>&#10;A_Session vid <?php echo $_SESSION['vid']?>&#10;PregID: <?php echo $row_followup['pregid']; ?>&#10;FollowupID: <?php echo $row_followup['id']; ?>&#10;EntryBY: <?php echo $row_followup['entryby']; ?>&#10;EntryDt: <?php echo $row_followup['entrydt']; ?>"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php&pge2=PatAnteFollowupEdit.php&id=<?php echo $row_followup['id']; ?>">Edit </a><br />
      <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php&pge2=PatAnteFollowupDelete.php&id=<?php echo $row_followup['id']; ?>">Delete </a></td>

<?php } else {  ?>

      <td bgcolor="#FFE4E1" Title="VisitID: <?php echo $row_followup['visitid'];?>&#10; 'B_Session vid <?php echo $_SESSION['vid']?>&#10;PregID: <?php echo $row_followup['pregid']; ?>&#10;FollowupID: <?php echo $row_followup['id']; ?>&#10;EntryBY: <?php echo $row_followup['entryby']; ?>&#10;EntryDt: <?php echo $row_followup['entrydt']; ?>"><div align="center">----</div></td>
<?php }?>

      <td bgcolor="#ffa07a" Title="VisitID: <?php echo $row_followup['visitid']; ?>&#10;PregID: <?php echo $row_followup['pregid']; ?>&#10;FollowupID: <?php echo $row_followup['id']; ?>&#10;EntryBY: <?php echo $row_followup['entryby']; ?>&#10;EntryDt: <?php echo $row_followup['entrydt']; ?>">
	    <div align="center">
	      <input name="medrecnum2" type="text" id="medrecnum" size="7" maxlength="12" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['pregid']; ?>, <?php echo $row_followup['id']; ?>" />
	    </div></td>
      <td bgcolor="#FFE4E1" Title="VisitID: <?php echo $row_followup['visitid']; ?>&#10;PregID: <?php echo $row_followup['pregid']; ?>&#10;FollowupID: <?php echo $row_followup['id']; ?>&#10;EntryBY: <?php echo $row_followup['entryby']; ?>&#10;EntryDt: <?php echo $row_followup['entrydt']; ?>"><div align="center"><input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" class="BlackBold_10" readonly="readonly" value="<?php echo $row_followup['entrydt']; ?>" /></td>
      <td nowrap="nowrap" bgcolor="#ffa07a" title="EGA = visitdate minus lmp date in weeks"><div align="center">
        <input name="ega" type="text" id="ega" size="2" maxlength="2" style="text-align:center;" class="BlackBold_10" readonly="readonly" value="<?php echo $row_followup['ega']; ?>" /><strong>Wks</strong>
      </div></td>

      <td nowrap="nowrap" bgcolor="#FFE4E1"><div align="center">
        <input name="hof" type="text" id="hof" size="2" maxlength="2" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['hof']; ?>" /><strong>Cm</strong>
      </div></td>

      <td bgcolor="#ffa07a"><div align="center">
        <input name="lie" type="text" id="lie" size="7" maxlength="13" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['lie']; ?>" />
      </div></td>

      <td nowrap="nowrap" bgcolor="#FFE4E1"><div align="center">
          <input name="prespos" type="text" id="prespos" size="8" maxlength="12"style="text-align:center;"  readonly="readonly" value="<?php echo $row_followup['prespos']; ?>" />
      </div></td>
      <td nowrap="nowrap" bgcolor="#ffa07a"><div align="center">
        <input name="fetalheart" type="text" id="fetalheart" size="2" maxlength="3" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['fetalheart']; ?>" />
      <strong>bpm</strong></div></td>

      <td nowrap="nowrap" bgcolor="#FFE4E1"><div align="center">
        <input name="hgb" type="text" id="hgb" size="4" maxlength="9" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['bldpres']; ?>" />
        <strong>mmHg</strong></div></td>
      <td bgcolor="#ffa07a"><div align="center">
        <input name="hgb" type="text" id="hgb" size="2" maxlength="2" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['weight']; ?>" /><strong>Kg</strong>
      </div></td>

      <td bgcolor="#FFE4E1"><div align="center">
        <input name="oedema" type="text" id="oedema" size="3" maxlength="9" style="text-align:center;" readonly="readonly" value="<?php echo $row_followup['oedema']; ?>" />
      </div></td>

      <td bgcolor="#ffa07a"><div align="center">
        <input name="entryby" type="text" id="entryby" size="6" maxlength="12" class="BlackBold_10" readonly="readonly" value="<?php echo $row_followup['entryby']; ?>" />
      </div></td>
    </tr>
	<tr>
		<td align="right" bgcolor="#FFE4E1">Remarks: </td>
        <td colspan="5" bgcolor="#FFE4E1"><textarea name="foluptext" cols="60" rows="1" readonly="readonly"><?php echo $row_followup['foluptext']; ?></textarea></td>
		<td align="right" bgcolor="#FFE4E1">Diagnosis: </td>
    <td colspan="4" bgcolor="#FFE4E1"><textarea name="diag" cols="40" rows="1" readonly="readonly"><?php echo $row_followup['diag']; ?></textarea></td>
          <?php if($row_followup['seedoc'] == "on") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?>
       <td  bgcolor="#FFE4E1" align="center">See Doc:  
     <span class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="seedoc" >
     <input type="checkbox" name="seedoc" id="seedoc" style="text-align: center;" size="1" maxlength="1" readonly="readonly" <?php if ($row_followup['seedoc'] == "on") {echo "checked=\"checked\"";} ?> /></span></td>
	</tr>
    <?php
	 	if(isset($row_followup['visitid']) &&  isset($_SESSION['vid']) && $row_followup['visitid'] == $_SESSION['vid']) {
		   $AddFolup = "False";
		}
	 } while ($row_followup = mysql_fetch_assoc($followup));
	 
	
   }  ?>

  <!--logic for which screen to display...Add, edit, or Delete-->
  
<?php  if(isset($_GET['pge2'])) {
						 if($_GET['pge2'] == 'PatAnteFollowupAdd.php'){ 
						 $actante = $_GET['pge2'];
					 } 
						 if($_GET['pge2'] == 'PatAnteFollowupEdit.php') { 
						 $actante = $_GET['pge2'];
					}
						 if($_GET['pge2'] == 'PatAnteFollowupDelete.php') { 
						 $actante = $_GET['pge2'];
					}
						 ?>
					<table align="center">
						<tr>
							<td valign="top"><?php require_once($actante); ?></td>
						<tr>
					</table>	
<?php } ?>


<?php if(!isset($_GET['pge2']) && $totalRows_preg > 0 && $totalRows_prevpreg == 0) { ?>  <!--put an Add link here-->
	<tr>
		<td><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php&pge2=PatAnteFollowupAdd.php">Add Followup</a></td>
	</tr>

<?php }?>
</table>

  </body>
</html>
<?php
mysql_free_result($preg);

mysql_free_result($followup);
?>