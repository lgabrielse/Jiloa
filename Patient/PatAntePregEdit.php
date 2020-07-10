<?php   $pt = "View Patient Info"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
if(!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	
	  switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
	  }
	  return $theValue;
	}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formpape")) {

$lmp = '';
if(isset($_POST["lmp"]) && $_POST["lmp"] != '') {
	      $lmp = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['lmp'])));
				$edd = date('Y-m-d',strtotime('+280 days',strtotime($lmp)));
}else{
				$edd = '';
	
}
//$edd = '';
//if(isset($_POST["edd"]) && $_POST["edd"] != '') {
//	      $edd = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['edd'])));
//}

$ussedd = '';
if(isset($_POST["ussedd"]) && $_POST["ussedd"] != '') {
	      $ussedd = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['ussedd'])));
}
if(isset($_POST["firstvisit"])) {
	      $_POST['firstvisit'] = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['firstvisit'])));
}
if(isset($_POST['HistPatHeart']) && $_POST['HistPatHeart'] == 'on'){$_POST['HistPatHeart'] = 'Y';} else {$_POST['HistPatHeart'] = 'N';}
if(isset($_POST['HistPatChest']) && $_POST['HistPatChest'] == 'on'){$_POST['HistPatChest'] = 'Y';} else {$_POST['HistPatChest'] = 'N';}
if(isset($_POST['HistPatKidney']) && $_POST['HistPatKidney'] == 'on'){$_POST['HistPatKidney'] = 'Y';} else {$_POST['HistPatKidney'] = 'N';}
if(isset($_POST['HistPatBldTransf']) && $_POST['HistPatBldTransf'] == 'on'){$_POST['HistPatBldTransf'] = 'Y';} else {$_POST['HistPatBldTransf'] = 'N';}
if(isset($_POST['HistPatPPH']) && $_POST['HistPatPPH'] == 'on'){$_POST['HistPatPPH'] = 'Y';} else {$_POST['HistPatPPH'] = 'N';}
if(isset($_POST['HistPatOperations']) && $_POST['HistPatOperations'] == 'on'){$_POST['HistPatOperations'] = 'Y';} else {$_POST['HistPatOperations'] = 'N';}
if(isset($_POST['HistPatHTN']) && $_POST['HistPatHTN'] == 'on'){$_POST['HistPatHTN'] = 'Y';} else {$_POST['HistPatHTN'] = 'N';}
if(isset($_POST['HistPatSCD']) && $_POST['HistPatSCD'] == 'on'){$_POST['HistPatSCD'] = 'Y';} else {$_POST['HistPatSCD'] = 'N';}
if(isset($_POST['HistPatSeiz']) && $_POST['HistPatSeiz'] == 'on'){$_POST['HistPatSeiz'] = 'Y';} else {$_POST['HistPatSeiz'] = 'N';}
if(isset($_POST['HistPatDM']) && $_POST['HistPatDM'] == 'on'){$_POST['HistPatDM'] = 'Y';} else {$_POST['HistPatDM'] = 'N';}
if(isset($_POST['HistFamHeart']) && $_POST['HistFamHeart'] == 'on'){$_POST['HistFamHeart'] = 'Y';} else {$_POST['HistFamHeart'] = 'N';}
if(isset($_POST['HistFamHypertens']) && $_POST['HistFamHypertens'] == 'on'){$_POST['HistFamHypertens'] = 'Y';} else {$_POST['HistFamHypertens'] = 'N';}
if(isset($_POST['HistFamTb']) && $_POST['HistFamTb'] == 'on'){$_POST['HistFamTb'] = 'Y';} else {$_POST['HistFamTb'] = 'N';}
if(isset($_POST['HistFamDM']) && $_POST['HistFamDM'] == 'on'){$_POST['HistFamDM'] = 'Y';} else {$_POST['HistFamDM'] = 'N';}
if(isset($_POST['HistFamMultPreg']) && $_POST['HistFamMultPreg'] == 'on'){$_POST['HistFamMultPreg'] = 'Y';} else {$_POST['HistFamMultPreg'] = 'N';}
//exit;
	
  $updateSQL = sprintf("UPDATE anpreg SET medrecnum=%s, lmp=%s, edd=%s, ussedd=%s, firstvisit=%s, obg=%s, obp=%s, specpoints=%s, HistPatHeart=%s, HistPatChest=%s, HistPatKidney=%s, HistPatBldTransf=%s, HistPatPPH=%s, HistPatOperations=%s, HistPatHTN=%s, HistPatSCD=%s, HistPatSeiz=%s, HistPatDM=%s, HistFamHeart=%s, HistFamHypertens=%s, HistFamTb=%s, HistFamDM=%s, HistFamMultPreg=%s, HistFamOther=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($lmp, "date"),
                       GetSQLValueString($edd, "date"),
                       GetSQLValueString($ussedd, "date"),
                       GetSQLValueString($_POST['firstvisit'], "date"),
                       GetSQLValueString($_POST['obg'], "text"),
                       GetSQLValueString($_POST['obp'], "text"),
                       GetSQLValueString($_POST['specpoints'], "text"),					                          
                       GetSQLValueString($_POST['HistPatHeart'], "text"),
                       GetSQLValueString($_POST['HistPatChest'], "text"),
                       GetSQLValueString($_POST['HistPatKidney'], "text"),
                       GetSQLValueString($_POST['HistPatBldTransf'], "text"),
                       GetSQLValueString($_POST['HistPatPPH'], "text"),
                       GetSQLValueString($_POST['HistPatOperations'], "text"),
                       GetSQLValueString($_POST['HistPatHTN'], "text"),
                       GetSQLValueString($_POST['HistPatSCD'], "text"),
                       GetSQLValueString($_POST['HistPatSeiz'], "text"),
                       GetSQLValueString($_POST['HistPatDM'], "text"),
                       GetSQLValueString($_POST['HistFamHeart'], "text"),
                       GetSQLValueString($_POST['HistFamHypertens'], "text"),
                       GetSQLValueString($_POST['HistFamTb'], "text"),
                       GetSQLValueString($_POST['HistFamDM'], "text"),
                       GetSQLValueString($_POST['HistFamMultPreg'], "text"),
                       GetSQLValueString($_POST['HistFamOther'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));


  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

// find followup record(s) for the current visit and set ega, entryby, entrydt for edited lmp 
mysql_select_db($database_swmisconn, $swmisconn);
$query_folup = sprintf("SELECT a.id, a.medrecnum, a.visitid, a.pregid, a.ega, hof, prespos, lie, fetalheart, bldpres, a.weight, oedema, foluptext, nextvisit, a.entryby, a.entrydt, v.visitdate FROM anfollowup a join patvisit v on a.visitid = v.id WHERE a.visitid = %s", $_SESSION['vid']);
$folup = mysql_query($query_folup, $swmisconn) or die(mysql_error());
$row_folup = mysql_fetch_assoc($folup);
$totalRows_folup = mysql_num_rows($folup);


	if(isset($_POST["lmp"]) && !empty($_POST["lmp"])){
			$startDate = new datetime($_POST["lmp"]);
			$endDate = new datetime($row_folup['visitdate']); //new DateTime('now');
			$interval = $startDate->diff($endDate);
			$calcega = (int)(($interval->days) / 7);
	} else {
			$calcega = 0;

	}
//echo 'lmp:'.$_POST["lmp"].'   ';
//echo 'visitdate:'.$row_folup['visitdate'];
//echo'rows:'. $totalRows_folup;
//echo 'ega: '.$calcega;
//echo 'visitid: '.$_POST['visitid'];
//exit;

if($totalRows_folup > 0) {
//do {
  $updateSQL = sprintf("UPDATE anfollowup SET ega=%s, entryby=%s, entrydt=%s WHERE visitid=%s",
                       GetSQLValueString($calcega, "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['visitid'], "int"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

//	 } while ($row_folup = mysql_fetch_assoc($folup));
}


  $updateGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= str_replace('pge=PatAntePregEdit.php','pge=PatAntePregView.php',$_SERVER['QUERY_STRING']); 
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>

<?php
$colname_mrn = "-1";
if (isset($_SESSION['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']);
}
$colname_preg = "-1";
if (isset($_GET['pregid'])) {
  $colname_preg = (get_magic_quotes_gpc()) ? $_GET['pregid'] : addslashes($_GET['pregid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_preg = sprintf("SELECT a.id, a.medrecnum, DATE_FORMAT(lmp,'%%b %%d, %%Y') lmp, DATE_FORMAT(DATE_ADD(lmp, INTERVAL 280 DAY),'%%b %%d, %%Y') edd, DATE_FORMAT(ussedd,'%%b %%d, %%Y') ussedd, DATE_FORMAT(firstvisit,'%%b %%d, %%Y') firstvisit, obg, obp, specpoints, HistPatHeart, HistPatChest, HistPatKidney, HistPatBldTransf, HistPatPPH, HistPatOperations,  HistPatHTN, HistPatSCD, HistPatSeiz, HistPatDM, HistFamHeart, HistFamHypertens, HistFamTb, HistFamDM, HistFamMultPreg, HistFamOther, a.entryby, DATE_FORMAT(v.visitdate,'%%b %%d, %%Y') visitdate  FROM anpreg a join patvisit v on a.visitid = v.id  WHERE a.medrecnum = %s AND a.id = %s ORDER BY a.id ASC", $colname_mrn, $colname_preg);
$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
$row_preg = mysql_fetch_assoc($preg);
$totalRows_preg = mysql_num_rows($preg);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" />
   <!--<script src="../../ckeditor/ckeditor.js"></script>-->
<!--	<style type="text/css">@import url(../../jscalendar-1.0/calendar-win2k-1.css);</style>
	<script type="text/javascript" src="../../jscalendar-1.0/calendar.js"></script>
	<script type="text/javascript" src="../../jscalendar-1.0/lang/calendar-en.js"></script>
	<script type="text/javascript" src="../../jscalendar-1.0/calendar-setup.js"></script>
    
    <link rel="stylesheet" href="runnable.css" />
-->    <link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
  <body>
  
 <form  id="formpape" name="formpape" method="POST">
   <table width="80%" bgcolor="#F8FDCE">
      <tr>
        <td nowrap="nowrap" Title="PregID:<?php echo $row_preg['id']; ?>">MRN:<br />
   	  <input name="medrecnum" type="text" value="<?php echo $_SESSION['mrn'] ?>" size="3" maxlength="9" /></td>

        <td nowrap="nowrap"  class="borderbottomthinblackBold14"  title="MRN: <?php echo $_SESSION['mrn'] ?>&#10;VISIT ID: <?php echo $_SESSION['vid'] ?>&#10;Visit Date: <?php echo $row_preg['visitdate']; ?>&#10;Preg Entryby: <?php echo $row_preg['entryby']; ?>">Pregnancy<br />
        <?php echo $row_preg['id']; ?></td>
        
 	    <td colspan="2" nowrap="nowrap" bgcolor="#F8FDCE5" class="borderbottomthinblack" >LMP:<br />
        		 <input name="lmp" type="text" style="font-size:12px;" id="lmp" size="12" maxlength="18" value="<?php echo $row_preg['lmp']; ?>"/></td>
	     <td colspan="2" nowrap="nowrap" bgcolor="#F8FDCE" class="borderbottomthinblack" >EDD:LMP+280 days<br />
           <input name="edd" type="text" id="edd" style="font-size:12px;" readonly="readonly" value="<?php echo $row_preg['edd']; ?>" size="12" maxlength="18" /></td>
        <td colspan="2" nowrap="nowrap" bgcolor="#F8FDCE" class="borderbottomthinblack" >&nbsp;</td>
        <td colspan="2" nowrap="nowrap" bgcolor="#F8FDCE" class="borderbottomthinblack" >USS EDD:<br />
        <input name="ussedd" type="text" id="ussedd" style="font-size:12px;" size="12" maxlength="18" value="<?php echo $row_preg['ussedd']; ?>"/></td>
        <td colspan="2" nowrap="nowrap"  bgcolor="#F8FDCE" class="borderbottomthinblack" >FirstVisit:<br />
        <input name="firstvisit" type="text" id="firstvisit" style="font-size:12px;" size="12" maxlength="18" value="<?php echo $row_preg['firstvisit']; ?>" /></td>


        <td colspan="2" nowrap="nowrap" class="borderbottomthinblack" >OB Hist:<br />
          G
      <input name="obg" type="text" id="obg" size="1" maxlength="3" value="<?php echo $row_preg['obg']; ?>" /> 
        P
      <input name="obp" type="text" id="obp" size="1" maxlength="3" value="<?php echo $row_preg['obp']; ?>" /> &nbsp;&nbsp;&nbsp;     <span class="BlueBold_24">Edit Pregnancy</span></td>
 
       <td></td>
      </tr>
 
  
  <tr>
    <td nowrap="nowrap" >&nbsp;</td>
    <td class="borderbottomthinblackBold14" nowrap="nowrap" ><div align="center">Patient<br />
      History:</div></td>
	  
	  <?php if($row_preg['HistPatHeart'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg1; ?> nowrap="nowrap" title="Heart Disease" >
      <input type="checkbox" name="HistPatHeart" id="HistPatHeart" <?php if ($row_preg['HistPatHeart'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HD</td>
	  
	  <?php if($row_preg['HistPatHTN'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" Title="Hypertension" >
      <input type="checkbox" name="HistPatHTN" id="HistPatHTN" <?php if ($row_preg['HistPatHTN'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HTN</td> 

	  <?php if($row_preg['HistPatChest'] == "Y") {$bkg2 = "#FFFDDA"; } else {$bkg2 = "#FFFFFF";} ?> 
	<td class="borderbottomthinblack" bgcolor=<?php echo $bkg2; ?> nowrap="nowrap" title="Chest Disease" >
      <input type="checkbox" name="HistPatChest" id="HistPatChest" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatChest'] == "Y") {echo "checked=\"checked\"";} ?> />
      CD</td>

	  <?php if($row_preg['HistPatSCD'] == "Y") {$bkg2 = "#FFFDDA"; } else {$bkg2 = "#FFFFFF";} ?> 
	<td nowrap="nowrap" bgcolor=<?php echo $bkg2; ?> class="borderbottomthinblack" title="Sickle Cell Disease" >
      <input type="checkbox" name="HistPatSCD" id="HistPatSCD" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatSCD'] == "Y") {echo "checked=\"checked\"";} ?> />
      SCD&nbsp;</td>
	    
	  <?php if($row_preg['HistPatKidney'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg3; ?> nowrap="nowrap" title="Kidney Disease" > 
      <input type="checkbox" name="HistPatKidney" id="HistPatKidney" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatKidney'] == "Y") {echo "checked=\"checked\"";} ?>  />
      K D</td>

	  <?php if($row_preg['HistPatSeiz'] == "Y") { $bkg3 = "#FFFDDA";} else {$bkg3 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg3; ?> class="borderbottomthinblack" title="Seizures" > 
      <input type="checkbox" name="HistPatSeiz" id="HistPatSeiz" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatSeiz'] == "Y") {echo "checked=\"checked\"";} ?>  />
      Seiz</td>

	  <?php if($row_preg['HistPatBldTransf'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="Blood Transfusions" >
      <input type="checkbox" name="HistPatBldTransf" id="HistPatBldTransf" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatBldTransf'] == "Y") {echo "checked=\"checked\"";} ?> />
      BT</td>

	  <?php if($row_preg['HistPatPPH'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg4; ?> nowrap="nowrap" title="Post Partum Haemorrhage" >
      <input type="checkbox" name="HistPatPPH" id="HistPatPPH" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatPPH'] == "Y") {echo "checked=\"checked\"";} ?> />
      PPH</td>
 
	  <?php if($row_preg['HistPatDM'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg4; ?> class="borderbottomthinblack" title="Diabetes" >
      <input type="checkbox" name="HistPatDM" id="HistPatDM" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatDM'] == "Y") {echo "checked=\"checked\"";} ?> />
      DM&nbsp; </td>

	  <?php if($row_preg['HistPatOperations'] == "Y") { $bkg5 = "#FFFDDA";} else {$bkg5 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg5; ?> nowrap="nowrap" title="Operations">
      <input type="checkbox" name="HistPatOperations" id="HistPatOperations" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistPatOperations'] == "Y") {echo "checked=\"checked\"";} ?>  />
      Oper</td>

        <td class="borderbottomthinblack" nowrap="nowrap" >Special<br />
        Points:</td>
      <td class="borderbottomthinblack" nowrap="nowrap" ><textarea name="specpoints" cols="20" rows="1" id="specpoints" ><?php echo $row_preg['specpoints']; ?></textarea></td>

    <td><input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />

<input name="Submit" type="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Edit" /></td>
  </tr>

<!--line 3-->



<!--line 4-->


  <tr>
    <td nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregDelete.php&pregid=<?php echo $row_preg['id']; ?>">Delete</a></td>
    <td class="borderbottomthinblackBold14" nowrap="nowrap" ><div align="center">Family<br />
      History:</div></td>

	  <?php if($row_preg['HistFamHeart'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" Title="Heart Disease" >
      <input type="checkbox" name="HistFamHeart" id="HistFamHeart" <?php if ($row_preg['HistFamHeart'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HD</td>
	  
	  <?php if($row_preg['HistFamHypertens'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" title="Hypertension" >
      <input type="checkbox" name="HistFamHypertens" id="HistFamHypertens" <?php if ($row_preg['HistFamHypertens'] == "Y") {echo "checked=\"checked\"";} ?>  />
      HTN</td>

	  <?php if($row_preg['HistFamTb'] == "Y") {$bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg1; ?> class="borderbottomthinblack" title = "Tuberculosis" >
      <input type="checkbox" name="HistFamTb" id="HistFamTb" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistFamTb'] == "Y") {echo "checked=\"checked\"";} ?> />
      TB</td>

	  <?php if($row_preg['HistFamDM'] == "Y") { $bkg4 = "#FFFDDA";} else {$bkg4 = "#FFFFFF";} ?> 
    <td nowrap="nowrap" bgcolor=<?php echo $bkg4; ?> class="borderbottomthinblack" title="Diabetes" >
      <input type="checkbox" name="HistFamDM" id="HistFamDM" style="text-align: center;" size="1" maxlength="1" <?php if ($row_preg['HistFamDM'] == "Y") {echo "checked=\"checked\"";} ?> />
      DM&nbsp; </td>

	
	  <?php if($row_preg['HistFamMultPreg'] == "Y") { $bkg1 = "#FFFDDA"; } else {$bkg1 = "#FFFFFF";} ?> 
    <td class="borderbottomthinblack" bgcolor=<?php echo $bkg1; ?> nowrap="nowrap" title = "Multiple Pregnacies" >
      <input type="checkbox" name="HistFamMultPreg" id="HistFamMultPreg" <?php if ($row_preg['HistFamMultPreg'] == "Y") {echo "checked=\"checked\"";} ?>  />
      MP</td>
       
    <td nowrap="nowrap"  bgcolor="#F8FDCE" class="borderbottomthinblack">&nbsp;</td> 
    <td nowrap="nowrap"  bgcolor="#F8FDCE" class="borderbottomthinblack">&nbsp;</td> 
    <td nowrap="nowrap" bgcolor="#F8FDCE" class="borderbottomthinblack">&nbsp;</td> 
    <td nowrap="nowrap"  bgcolor="#F8FDCE" class="borderbottomthinblack">&nbsp;</td> 
    <td nowrap="nowrap"  bgcolor="#F8FDCE" class="borderbottomthinblack">&nbsp;</td> 

    <td class="borderbottomthinblack" nowrap="nowrap" >Other:</td>
    <td class="borderbottomthinblack" nowrap="nowrap" ><textarea name="HistFamOther" cols="20" rows="1" id="HistFamOther"><?php echo $row_preg['HistFamOther']; ?></textarea></td>
    <td nowrap="nowrap"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div>
      <input type="hidden" name="MM_update" value="formpape" />
      <input type="hidden" name="id" id="id" value="<?php echo $row_preg['id']; ?>">
	  <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
	  <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />	</td>
    </tr>
  </table>
</form>
<!--Downloaded from  http://www.nogray.com/    in Len directory-->                    
<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">

ng.ready( function() {
	    var my_cal = new ng.Calendar({
        input:'lmp',
		start_date: 'month - 10',
		// start_date: '01-01-2018',
		//  display_date: new Date()   // the display date (default is start_date)
        num_months:10,
        num_col:4
      });
//  var my_cal = new ng.Calendar({
//       input:'edd',
//	    start_date: 'month - 1',
//	   //  display_date: new Date()   // the display date (default is start_date)
//      });
	   var my_cal = new ng.Calendar({
       input:'ussedd',
	   start_date: 'month - 1',
	   //  display_date: new Date()   // the display date (default is start_date)
      });
	   var my_cal = new ng.Calendar({
       input:'firstvisit',
	   //start_date: '2018-01-01',
	   display_date: new Date()   // the display date (default is start_date)
        //multi_selection:true,
        //max_selection: 5
      });

});
//ng.calendar.set_date_format ("Y-M-d");
</script>

  <p>&nbsp;</p>
    <script type="text/javascript">
//	     Calendar.setup({
//        inputField     :    "lmp",     // id of the input field
//        ifFormat       :    "%b %e,%Y",      // format of the input field
//        button         :    "f_trigger_lmp",  // trigger for the calendar (button ID)
//        align          :    "Rl",           // alignment (defaults to "Bl")
////        displayArea    :    "show_c",       // ID of the span where the date is to be shown
////        daFormat       :    "%b %e, %Y",// format of the displayed date
//        singleClick    :    true
//    });
//
//	     Calendar.setup({
//        inputField     :    "edd",     // id of the input field
//        ifFormat       :    "%b %e,%Y",      // format of the input field
//        button         :    "f_trigger_edd",  // trigger for the calendar (button ID)
//        align          :    "Tl",           // alignment (defaults to "Bl")
////        displayArea    :    "show_c",       // ID of the span where the date is to be shown
////        daFormat       :    "%b %e, %Y",// format of the displayed date
//        singleClick    :    true
//    });
//	 
//	     Calendar.setup({
//        inputField     :    "ussedd",     // id of the input field
//        ifFormat       :    "%b %e,%Y",      // format of the input field
//        button         :    "f_trigger_uss",  // trigger for the calendar (button ID)
//        align          :    "Tl",           // alignment (defaults to "Bl")
////        displayArea    :    "show_c",       // ID of the span where the date is to be shown
////        daFormat       :    "%b %e, %Y",// format of the displayed date
//        singleClick    :    true
//    });
//
//	     Calendar.setup({
//        inputField     :    "firstvisit",     // id of the input field
//        ifFormat       :    "%b %e,%Y",      // format of the input field
//        button         :    "f_trigger_fir",  // trigger for the calendar (button ID)
//        align          :    "Tl",           // alignment (defaults to "Bl")
////        displayArea    :    "show_c",       // ID of the span where the date is to be shown
////        daFormat       :    "%b %e, %Y",// format of the displayed date
//        singleClick    :    true
//    });
//
</script>

  </body>
</html>