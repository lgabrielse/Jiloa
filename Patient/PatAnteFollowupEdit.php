<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formpaea")) {
  $updateSQL = sprintf("UPDATE anfollowup SET medrecnum=%s, visitid=%s, pregid=%s, ega=%s, hof=%s, prespos=%s, lie=%s, fetalheart=%s, bldpres=%s, weight=%s, oedema=%s, foluptext=%s, seedoc=%s, nextvisit=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['pregid'], "int"),
                       GetSQLValueString($_POST['ega'], "int"),
                       GetSQLValueString($_POST['hof'], "text"),
                       GetSQLValueString($_POST['prespos'], "text"),
                       GetSQLValueString($_POST['lie'], "text"),
                       GetSQLValueString($_POST['fetalheart'], "text"),
                       GetSQLValueString($_POST['bldpres'], "text"),
                       GetSQLValueString($_POST['weight'], "text"),
                       GetSQLValueString($_POST['oedema'], "text"),
                       GetSQLValueString($_POST['foluptext'], "text"),
                       GetSQLValueString($_POST['seedoc'], "text"),
                       GetSQLValueString($_POST['nextvisit'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateSQL = sprintf("Update patvisit SET diagnosis = %s where id = %s",
                       GetSQLValueString($_POST['diag'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());


    $insertGoTo = "PatShow1.php";
      if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('&pge2=PatAnteFollowupEdit.php','',$_SERVER['QUERY_STRING']); // replace function takes &pge2=PatAnteFollowupEdit.php out of $_SERVER['QUERY_STRING'];     
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_folup = "-1";
if (isset($_GET['id'])) {
  $colname_folup = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_folup = sprintf("SELECT f.id, f.medrecnum, f.visitid, f.pregid, f.ega, f.hof, f.prespos, f.lie, f.fetalheart, f.bldpres, f.weight, f.oedema, f.foluptext, f.seedoc, f.nextvisit, f.entryby, f.entrydt, p.lmp, v.diagnosis diag FROM anfollowup f JOIN anpreg p on f.pregid = p.id JOIN patvisit v on f.visitid = v.id WHERE f.id = %s", $colname_folup);
$folup = mysql_query($query_folup, $swmisconn) or die(mysql_error());
$row_folup = mysql_fetch_assoc($folup);
$totalRows_folup = mysql_num_rows($folup);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
    <link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" />   
	<script src="../../jquery-1.11.1.js"></script>
    <script src="../../jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<script>
$(document).ready(function(){
    $.datepicker.setDefaults({ 
     dateFormat: 'yy-mm-dd'
    });
	 dateFormat: "yy-mm-dd";
          $( "#nextvisite" ).datepicker();
       });
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
  </script>
    <link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

</head>

<body>
<form name="formpaea" id="formpafe" method="POST" action="<?php echo $editFormAction; ?>">

<table cellpadding="0" cellspacing="0" bgcolor="#F8FDCE">
  <tr>
    <td class="BlackBold_12"><div align="center">Edit<br />
      PregID,<br />
    FIUD</div></td>
    <td bgcolor="#FFFF33" class="BlackBold_12"><div align="center">Date</div></td>
    <td class="BlackBold_12"><div align="center">EGA</div></td>
    <td bgcolor="#FFFF33" class="BlackBold_12"><div align="center"> Fundus<br />
    Height</div></td>
    <td class="BlackBold_12"><div align="center">Lie</div></td>
    <td bgcolor="#FFFF33" class="BlackBold_12"><div align="center">Presentation<br />
    and Position </div></td>
    <td nowrap="nowrap" class="BlackBold_12"><div align="center">Foetal<br />
    Heart Rate</div></td>
    <td bgcolor="#FFFF33" class="BlackBold_12"><div align="center">B/P</div></td>
    <td class="BlackBold_12"><div align="center">Weight</div></td>
    <td bgcolor="#FFFF33" class="BlackBold_12"><div align="center">Oedema</div></td>
    <td class="BlackBold_12">Put<br />
      Return<br />
      Date in</td>
    <td bgcolor="#FFFF33" class="BlackBold_12">Examiner</td>
    <td><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td Title="MRN: <?php echo $row_folup['medrecnum']; ?>&#10;VisitID: <?php echo $row_folup['visitid']; ?>&#10;PregID: <?php echo $row_folup['pregid']; ?>">
	      <input name="pregid" type="text" id="pregid" size="5" maxlength="9" class="BlackBold_10"  value="<?php echo $row_folup['pregid']; ?>, <?php echo $row_folup['id']; ?>" />
	      <input name="visitid" type="hidden" id="visitid" value="<?php echo $row_folup['visitid']; ?>" />
	      <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_folup['medrecnum']; ?>" />
		  <input name="id" type="hidden" id="id" value="<?php echo $row_folup['id']; ?>"/>		  </td>
      <td><div align="center">
        <input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" class="BlackBold_10" value="<?php echo date("Y-m-d H:i"); ?>" />
      </div></td>
      <?php $visitdt=date_create($_SESSION['visitdt']); ?>  <!--$_SESSION['visitdt'] from patvisitview.php-->
      <td nowrap="nowrap" title="Visitdate: <?php echo date_format($visitdt,"M-d-Y"); ?>&#10;EGA = visitdate minus lmp date in weeks"><div align="center">

	<?php //calculate ega from lmp
			if(isset($row_preg['lmp']) && !empty($row_preg['lmp'])) {
					$startDate = new datetime($row_preg['lmp']);
					$endDate = new datetime($_SESSION['visitdt']); //new date('Y-m-d') = today  <!--$_SESSION['visitdt'] from patvisitview.php-->
					$interval = $startDate->diff($endDate);
					$calcega = (int)(($interval->days) / 7);
			} else {
					$calcega = 0;				
	  	} ?> 
         <input name="ega" type="text" id="ega" size="2" maxlength="2" style="text-align:center;" class="BlackBold_10" readonly="readonly" value="<?php echo $calcega ?>" /><strong>Wks</strong>
      </div></td>
<!--      <td nowrap="nowrap">
        <div align="center"><input name="ega" type="text" class="BlackBold_10" id="ega" style="text-align:center;" onblur="MM_validateForm('ega','','RinRange0:50');return document.MM_returnValue" value="<?php //echo $row_folup['ega']; ?>" size="2" maxlength="2" />
       <strong>Wks</strong></div>
      </td>
-->
      <td bgcolor="#FFFF33" nowrap="nowrap"><div align="center">
        <input name="hof" type="text" id="hof" style="text-align:center;" onblur="MM_validateForm('hof','','RinRange1:50');return document.MM_returnValue" value="<?php echo $row_folup['hof']; ?>" size="2" maxlength="2" />
      <strong>Cm</strong></div></td>

      <td><div align="center">
        <select name="lie" id="lie">
          <option value="None">None</option>
          <option value="Longitudinal" <?php if (!(strcmp("Longitudinal", $row_folup['lie']))) {echo "selected=\"selected\"";} ?>>Longitudinal</option>
          <option value="Transverse" <?php if (!(strcmp("Transverse", $row_folup['lie']))) {echo "selected=\"selected\"";} ?>>Transverse</option>
          <option value="Oblique" <?php if (!(strcmp("Oblique", $row_folup['lie']))) {echo "selected=\"selected\"";} ?>>Oblique</option>
        </select>
        </div></td>
      <td title="a) Cephalic- LOA: Cephalic Left Occipito-Anterior&#10;b) Cephalic-ROA: Cephalic Right Occipito-Anterior&#10;c) Cephalic-LOT: Cephalic  Left Occipito-Transverse&#10;d) Cephalic-ROT: Cephalic Right Occipito-Transverse&#10;e) Cephalic-LOP: Cephalic Left Occipito-Posterior&#10;f) Cephalic-ROP: Cephalic Right Occipito-Posterior&#10;g) Breech-LSA: Breech Left Sacro-Anterior&#10;h) Breech-RSA: Breech Right Sacro-Anterior&#10;i) Transverse&#10;j) Oblique">
        <select name="prespos" id="prespos">
          <option value="" <?php if (!(strcmp("", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <option value="Cephalic-LOA" <?php if (!(strcmp("Cephalic-LOA", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-LOA</option>
          <option value="Cephalic-ROA"<?php if (!(strcmp("Cephalic-ROA", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-ROA</option>
          <option value="Cephalic-LOT"<?php if (!(strcmp("Cephalic-LOT", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-LOT</option>
          <option value="Cephalic-ROT"<?php if (!(strcmp("Cephalic-ROT", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-ROT</option>
          <option value="Cephalic-LOP"<?php if (!(strcmp("Cephalic-LOP", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-LOP</option>
          <option value="Cephalic-ROP"<?php if (!(strcmp("Cephalic-ROP", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Cephalic-ROP</option>
          <option value="Breech-LSA"<?php if (!(strcmp("Breech-LSA", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Breech-LSA</option>
          <option value="Breech-RSA"<?php if (!(strcmp("Breech-RSA", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Breech-RSA</option>
          <option value="Transverse"<?php if (!(strcmp("Transverse", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Transverse</option>
          <option value="Oblique"<?php if (!(strcmp("Oblique", $row_folup['prespos']))) {echo "selected=\"selected\"";} ?>>Oblique</option>
      </select></td>

      <td nowrap="nowrap"><div align="center">
        <input name="fetalheart" type="text" id="fetalheart" style="text-align:center;" onblur="MM_validateForm('fetalheart','','RinRange0:150');return document.MM_returnValue" value="<?php echo $row_folup['fetalheart']; ?>" size="2" maxlength="3" />
      <strong>bpm</strong></div></td>

      <td bgcolor="#FFFF33" nowrap="nowrap"><div align="center">
        <input name="bldpres" type="text" id="bldpres" style="text-align:center;" onblur="MM_validateForm('bldpres','','R');return document.MM_returnValue" value="<?php echo $row_folup['bldpres']; ?>" size="4" maxlength="9" />
      <strong>mmHg</strong></div></td>

      <td nowrap="nowrap"><div align="center">
        <input name="weight" type="text" id="weight" style="text-align:center;" onblur="MM_validateForm('weight','','RinRange20:150');return document.MM_returnValue" value="<?php echo $row_folup['weight']; ?>" size="2" maxlength="2" />
      <strong>Kg</strong></div></td>

      <td align="center" title="1+ = Barely detectable impression when finger is presssed into skin.&#10;2+ = Slight indentation. 15 seconds to rebound.&#10;3+ = Deeper indentation. 30 seconds to rebound.&#10;4+ = > 30 seconds to rebound.">
      <select name="oedema" id="oedema" value="<?php echo $row_folup['oedema']; ?>" >
        <option value="" <?php if (!(strcmp("", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="Nil" <?php if (!(strcmp("Nil", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>Nil</option>
        <option value="1+" <?php if (!(strcmp("1+", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>1+</option>
        <option value="2+" <?php if (!(strcmp("2+", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>2+</option>
        <option value="3+" <?php if (!(strcmp("3+", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>3+</option>
        <option value="4+" <?php if (!(strcmp("4+", $row_folup['oedema']))) {echo "selected=\"selected\"";} ?>>4+</option>
      </select>
      </td>

      <td><strong>Visit>Edit</strong></td>

      <td><div align="center">
        <input name="entryby" type="text" id="entryby" size="8" maxlength="12" class="BlackBold_10" readonly="readonly" value="<?php echo $_SESSION['user']; ?>" />
      </div></td>
      <td align="center">
        <input name="Submit" type="submit" onclick="MM_validateForm('hof','','RinRange1:50');MM_validateForm('fetalheart','','RinRange0:150');MM_validateForm('bldpres','','R');MM_validateForm('weight','','RinRange20:150');MM_validateForm('diag','','R');return document.MM_returnValue" style="background-color:aqua;  border-color:blue; color:black; text-align:center; border-radius:4px;" value = "Edit" />
      </td>
    </tr>
	<tr>
		<td align="right">Remarks: </td>
        <td colspan="5" bgcolor="#FFE4E1"><textarea name="foluptext" cols="60" rows="2"><?php echo $row_folup['foluptext']; ?></textarea></td>
		<td align="right">Diagnosis: </td>
        <td colspan="4" bgcolor="#FFE4E1"><textarea name="diag" cols="40" rows="1"><?php echo $row_folup['diag']; ?></textarea></td>
 	 <td align="center" colspan="3"  bgcolor="#FFFF33">See Doctor: 
   <input type="checkbox" name="seedoc" id="seedoc" style="text-align: center;" size="1" maxlength="1" <?php if ($row_folup['seedoc'] == "on") {echo "checked=\"checked\"";} ?>  /></td>
 
 	</tr>
	
    <?php } while ($row_folup = mysql_fetch_assoc($folup)); ?>
</table>
<input type="hidden" name="MM_update" value="formpaea">
</form>
</body>
</html>
<?php
mysql_free_result($folup);
?>
