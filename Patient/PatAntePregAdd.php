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
 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formapa")) {
    $lmp = '';
	if(isset($_POST["lmp"]) && $_POST["lmp"] != '') {
	   $lmp = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['lmp'])));
		 $edd = date('Y-m-d',strtotime('+280 days',strtotime($lmp)));

	}
	 $ussedd = '';
	if(isset($_POST["ussedd"]) && $_POST["ussedd"] != '') {
	   $ussedd = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['ussedd'])));
	}
	if(isset($_POST["firstvisit"])) {
	   $_POST['firstvisit'] = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['firstvisit'])));
    }
	$insertSQL = sprintf("INSERT INTO anpreg (medrecnum, visitid, lmp, edd, ussedd, firstvisit, obg, obp, specpoints, HistPatHeart, HistPatChest, HistPatKidney, HistPatBldTransf, HistPatPPH, HistPatOperations, HistPatHTN, HistPatSCD, HistPatSeiz, HistPatDM, HistFamMultPreg, HistFamTb, HistFamHypertens, HistFamHeart, HistFamDM, HistFamOther, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($lmp, "date"),
                       GetSQLValueString($edd, "date"),  //$edd, "date"),
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
                       GetSQLValueString($_POST['HistFamMultPreg'], "text"),
                       GetSQLValueString($_POST['HistFamTb'], "text"),
                       GetSQLValueString($_POST['HistFamHypertens'], "text"),
                       GetSQLValueString($_POST['HistFamHeart'], "text"),
                       GetSQLValueString($_POST['HistFamDM'], "text"),
                       GetSQLValueString($_POST['HistFamOther'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

	   mysql_select_db($database_swmisconn, $swmisconn);
	   $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

	   $insertGoTo = "PatShow1.php";
	   if (isset($_SERVER['QUERY_STRING'])) {
	   $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
	   $insertGoTo .= str_replace('pge=PatAntePregAdd.php','pge=PatAntePregView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
	   }
   header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" /> <!--needed for calendar-->   
</head>
<body>
<div> 
<form  id="formapa" name="formapa" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="80%" cellpadding="2" cellspacing="2" bgcolor="#BCFACC">
    <!--<caption>ADD PREGNANCY</caption>-->
      <tr>
        <td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >LMP:<br />
               <input name="lmp" type="text" style="font-size:12px;" id="lmp" size="12" maxlength="18" /></td>
         <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >&nbsp;</td>
         <!--colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >EDD:<br />
             <input name="edd" type="text" id="edd" style="font-size:12px;" size="12" maxlength="18" />-->
          <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >&nbsp;</td>
         <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >&nbsp;</td>
<!--          <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" >USS EDD:<br />
          <input name="ussedd" type="text" id="ussedd" style="font-size:12px;" size="12" maxlength="18" /></td>
-->          <td colspan="2" nowrap="nowrap" class="borderbottomthinblackBold14" title="MRN: <?php echo $_SESSION['mrn'] ?>&#10;VISIT ID: <?php echo $_SESSION['vid'] ?>" > ADD Pregnancy*</td> 
          <td colspan="2" nowrap="nowrap"  bgcolor="#C0E8D5" class="borderbottomthinblack" >FirstVisit:<br />
          <input name="firstvisit" type="text" id="firstvisit" style="font-size:12px;" size="12" maxlength="18" value="<?php echo date('m-d-Y'); ?>" /></td>
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" >OB Hist:<br />G
          <input name="obg" type="text" id="obg" size="1" maxlength="3" />P
        <input name="obp" type="text" id="obp" size="1" maxlength="3" /></td>

        <td bgcolor="#C0E8D5"nowrap="nowrap" ><div align="right"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save" /></div></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><div align="center" class="BlackBold_14">Patient History:</div></td>

        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" Title="Heart Disease" ><div align="left">
          <input type="hidden" name="HistPatHeart" value="N" />
          <input type="checkbox" name="HistPatHeart" id="HistPatHeart" value="Y" >
HD</div></td>
          
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" Title="Hypertension" ><div align="left">
          <input type="hidden" name="HistPatHTN" id="HistPatHTN" value="N" />
          <input type="checkbox" name="HistPatHTN" id="HistPatHTN" value="Y" />
HTN</div></td>

        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Chest Disease"><div align="left">
          <input type="hidden" id="HistPatChest" name="HistPatChest" value="N">
          <input type="checkbox" id="HistPatChest" name="HistPatChest" value="Y">
CD</div>  </td>
        
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Sickle Cell Disease"><div align="left">
          <input type="hidden" id="HistPatSCD" name="HistPatSCD" value="N" />
          <input type="checkbox" id="HistPatSCD" name="HistPatSCD" value="Y" />
SCD</div></td>

        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Kidney Disease"><div align="left">
          <input type="hidden" id="HistPatKidney" name="HistPatKidney" value="N">
          <input type="checkbox" id="HistPatKidney" name="HistPatKidney" value="Y">
KD</div>  </td>
        
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Seizures"><div align="left">
          <input type="hidden" id="HistPatSeiz" name="HistPatSeiz" value="N" />
          <input type="checkbox" id="HistPatSeiz" name="HistPatSeiz" value="Y" />
Seiz</div></td>
 
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Blood Transfusions"><div align="left">
          <input type="hidden" id="HistPatBldTransf" name="HistPatBldTransf" value="N">
          <input type="checkbox" id="HistPatBldTransf" name="HistPatBldTransf" value="Y">
BT</div></td>
          
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Post Partum Haemorrhage"><div align="left">
          <input type="hidden" id="HistPatPPH" name="HistPatPPH" value="N">
          <input type="checkbox" id="HistPatPPH" name="HistPatPPH" value="Y">
PPH</div></td>
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Diabetes"><div align="left">
          <input type="hidden" id="HistPatDM" name="HistPatDM" value="N" />
          <input type="checkbox" id="HistPatDM" name="HistPatDM" value="Y" />
DM</div></td>
	
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Operations"><div align="left">
          <input type="hidden" id="HistPatOperations" name="HistPatOperations" value="N">
        <input type="checkbox" id="HistPatOperations" name="HistPatOperations" value="Y"> Ops</div></td
        >
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" title="Notes/Comments">Special Points:</td>
        <td class="borderbottomthinblack" nowrap="nowrap" ><textarea name="specpoints" cols="20" rows="1" id="specpoints"></textarea></td>
  
      </tr>
<!--third row-->

      <tr>
        <td class="borderbottomthinblackBold14" nowrap="nowrap" ><div align="center">Family History:</div></td>

        <td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Heart Disease"><div align="left">
          <input type="hidden" name="HistFamHeart" id="HistFamHeart" value="N" />
          <input type="checkbox" name="HistFamHeart" id="HistFamHeart" value="Y" >
HD</div></td>
        <td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" Title="Hypertension" ><div align="left">
          <input type="hidden" name="HistFamHypertens" id="HistFamHypertens" value="N" />
           <input type="checkbox" name="HistFamHypertens" id="HistFamHypertens" value="Y" />
HTN</div></td>

	
        <td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" title="Tuberculosis"><div align="left">
          <input type="hidden" name="HistFamTb" id="HistFamTb" value="N" />
          <input type="checkbox" name="HistFamTb" id="HistFamTb" value="Y" >
TB</div></td>
          
        <td nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" title="Diabetes"><div align="left">
          <input type="hidden" name="HistFamDM" id="HistFamDM" value="N" />
           <input type="checkbox" id="HistFamDM" name="HistFamDM" value="Y" />
DM</div></td>
		
        <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack" title="Multiple Pregnancies"><div align="left">
          <input type="hidden" id="HistFamMultPreg" name="HistFamMultPreg" value="N">
          <input type="checkbox" name="HistFamMultPreg" id="HistFamMultPreg" value="Y" />
MP</div></td>

        <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack">&nbsp;</td>
        <td colspan="2" nowrap="nowrap" bgcolor="#C0E8D5" class="borderbottomthinblack">&nbsp;</td>

        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" >Other:</td>
        <td bgcolor="#C0E8D5" class="borderbottomthinblack" nowrap="nowrap" ><textarea name="HistFamOther" cols="20" rows="1" id="HistFamOther"></textarea></td>
     
      </tr>
    </table>
          <p>
            <input name="MM_insert" type="hidden" value="formapa" />
            <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
            <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" /></td>
    </p>
</form>
</div> 
  
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
	   var my_cal = new ng.Calendar({
       input:'edd',
	    start_date: 'month - 1',
	   //  display_date: new Date()   // the display date (default is start_date)
      });
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

<!--<p>Notes:</p>
<p>This the new format for this page.<br />
- The current script  has features that I like:  The Icon location and size, the multiple months display for lmp selection, and simple calendar display.</p>
<p>The only problem is the date entry<br />
	- I don't know how to calculate EDD (280 days)  It does not need a selector if it is calculated
    - I don't know how to set the format - I want 'Feb 10, 2019'
</p>-->

</body>
</html>