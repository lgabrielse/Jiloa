<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formpafa")) {
  $insertSQL = sprintf("INSERT INTO anfollowup (medrecnum, visitid, pregid, ega, hof, prespos, lie, fetalheart, bldpres, weight, oedema, foluptext, seedoc, nextvisit, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['pregid'], "int"),
                       GetSQLValueString($_POST['ega'], "int"),
                       GetSQLValueString($_POST['hof'], "text"),
                       GetSQLValueString($_POST['prespos'], "text"),
                       GetSQLValueString($_POST['lie'], "text"),
                       GetSQLValueString($_POST['fetalheart'], "text"),
                       GetSQLValueString($_POST['bldpres'], "text"),
                       GetSQLValueString($_POST['weight'], "int"),
                       GetSQLValueString($_POST['oedema'], "text"),
                       GetSQLValueString($_POST['foluptext'], "text"),
                       GetSQLValueString($_POST['seedoc'], "text"),
                       GetSQLValueString($_POST['nextvisita'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  
  $updateSQL = sprintf("Update patvisit SET diagnosis = %s where id = %s",
                       GetSQLValueString($_POST['diag'], "text"),
                       GetSQLValueString($_POST['visitid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

     $insertGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('&pge2=PatAnteFollowupAdd.php','',$_SERVER['QUERY_STRING']); // replace function takes &pge2=PatAnteFollowupAdd.php out of $_SERVER['QUERY_STRING'];     
  }
  header(sprintf("Location: %s", $insertGoTo));

}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
          $( "#nextvisita" ).datepicker();
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
<form name="formpafa" id="formpafa" method="POST" action="<?php echo $editFormAction; ?>"><table width="80%">
<table cellpadding="0" cellspacing="0" bgcolor="#BCFACC">
  <tr>
    <td class="BlackBold_12"><div align="center">FOLLOW-<br />
      UP for<br />
      Preg.
      ID</div></td>
    <td bgcolor="#66FF99" class="BlackBold_12"><div align="center">Follow-<br />
      Up 
      Date</div></td>
      <?php $visitdt=date_create($_SESSION['visitdt']); ?>  <!--$_SESSION['visitdt'] from patvisitview.php-->
    <td class="BlackBold_12" title="Visitdate: <?php echo date_format($visitdt,"M-d-Y"); ?>&#10;EGA = visitdate minus lmp date in weeks"><div align="center">EGA</div></td>
    <td bgcolor="#66FF99" class="BlackBold_12"><div align="center"> Fundus<br />
    Height</div></td>
    <td class="BlackBold_12"><div align="center">Lie</div></td>
    <td bgcolor="#66FF99" class="BlackBold_12"><div align="center">Presentation<br />
    and Position </div></td>
    <td nowrap="nowrap" class="BlackBold_12"><div align="center">Foetal<br />
    Heart Rate</div></td>
    <td bgcolor="#66FF99" class="BlackBold_12"><div align="center">Blood<br />Pressure</div></td>
    <td class="BlackBold_12"><div align="center">Weight</div></td>
    <td bgcolor="#66FF99" class="BlackBold_12"><div align="center" title="Alternate method:&#10;
Press on skin over tibia.&#10; Then run pads of fingers over the area pressed &#10;and note if there is an indentation. &#10;If indentation is noted,&#10; repeat further up the tibia.&#10;Document the point at which swelling&#10; is no longer present &#10;(distance above malleolus).">Oedema</div></td>
    <td class="BlackBold_12">Put<br />
      Return<br />
      Date in</td>
    <td bgcolor="#66FF99" class="BlackBold_12">Examiner</td>
    <td><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div></td>
  </tr>
    <tr>
      <td Title="MRN: <?php echo $_SESSION['mrn']; ?>&#10;VisitID: <?php echo $_SESSION['vid']; ?>&#10;PregID: <?php echo $row_preg['id']; ?>">
	      <input name="pregid" type="text" id="pregid" size="5" maxlength="9" style="text-align:center;" class="BlackBold_10"  value="<?php echo $row_preg['id']; ?>" />
	      <input name="visitid" type="hidden" id="visitid" size="5" maxlength="9" style="text-align:center;" value="<?php echo $_SESSION['vid']; ?>" />
	      <input name="medrecnum" type="hidden" id="medrecnum" size="3" maxlength="9" style="text-align:center;" value=" <?php echo $row_preg['medrecnum']; ?>" />		  </td>
      <td><input name="entrydt" type="text" id="entrydt" size="10" maxlength="12" style="text-align:center;" class="BlackBold_10" value="<?php echo date("Y-m-d H:i"); ?>" /></td>
	<?php //calculate ega from lmp
			if(isset($row_preg['lmp']) && !empty($row_preg['lmp'])) {
					$startDate = new datetime($row_preg['lmp']);
					$endDate = new datetime($_SESSION['visitdt']); //new date('Y-m-d') = today  <!--$_SESSION['visitdt'] from patvisitview.php-->
					$interval = $startDate->diff($endDate);
					$calcega = (int)(($interval->days) / 7);
			} else {
					$calcega = 0;				
			}
  ?> 
      <td nowrap="nowrap"><input name="ega" type="text" class="BlackBold_10" id="ega" style="text-align:center;" readonly="readonly"  value="<?php echo $calcega; ?>" size="2" maxlength="2" />          <strong>Wks</strong></td>
<!--      <td nowrap="nowrap"><input name="ega" type="text" class="BlackBold_10" id="ega" style="text-align:center;" onblur="MM_validateForm('ega','','RinRange1:50');return document.MM_returnValue" value="<?php //echo $row_preg['ega']; ?>" size="2" maxlength="2" />
        <strong>Wks</strong>
      </td>
-->
      <td bgcolor="#66FF99"><input name="hof" type="text" id="hof" style="text-align:center;" onblur="MM_validateForm('hof','','RinRange1:50');return document.MM_returnValue" size="2" maxlength="2" />
     <strong>Cm</strong></td>

      <td><select name="lie" id="lie">
        <option value="None">None</option>
        <option value="Longitudinal">Longitudinal</option>
        <option value="Transverse">Transverse</option>
        <option value="Oblique">Oblique</option>
      </select>
      </td>
      <td title="a) Cephalic- LOA: Cephalic Left Occipito-Anterior&#10;b) Cephalic-ROA: Cephalic Right Occipito-Anterior&#10;c) Cephalic-LOT: Cephalic  Left Occipito-Transverse&#10;d) Cephalic-ROT: Cephalic Right Occipito-Transverse&#10;e) Cephalic-LOP: Cephalic Left Occipito-Posterior&#10;f) Cephalic-ROP: Cephalic Right Occipito-Posterior&#10;g) Breech-LSA: Breech Left Sacro-Anterior&#10;h) Breech-RSA: Breech Right Sacro-Anterior&#10;i) Transverse&#10;j) Oblique">
        <select name="prespos" id="prespos">
          <option>Select</option>
          <option value="Cephalic-LOA">Cephalic-LOA</option>
          <option value="Cephalic-ROA">Cephalic-ROA</option>
          <option value="Cephalic-LOT">Cephalic-LOT</option>
          <option value="Cephalic-ROT">Cephalic-ROT</option>
          <option value="Cephalic-LOP">Cephalic-LOP</option>
          <option value="Cephalic-ROP">Cephalic-ROP</option>
          <option value="Breech-LSA">Breech-LSA</option>
          <option value="Breech-RSA">Breech-RSA</option>
          <option value="Transverse">Transverse</option>
          <option value="Oblique">Oblique</option>
      </select></td>


      <td><input name="fetalheart" type="text" id="fetalheart" style="text-align:center;" onblur="MM_validateForm('fetalheart','','RinRange0:150');return document.MM_returnValue" size="2" maxlength="3"/><strong>bpm</strong></td>

      <td bgcolor="#66FF99"><input name="bldpres" type="text" id="bldpres" style="text-align:center;" size="4" onblur="MM_validateForm('bldpres','','R');return document.MM_returnValue" maxlength="9"/><strong>mmHg</strong></td>

      <td><input name="weight" type="text" id="weight" style="text-align:center;" onblur="MM_validateForm('weight','','RinRange20:150');return document.MM_returnValue" size="2" maxlength="3"/><strong>Kg</strong></td>
			<td title="1+ = Barely detectable impression when finger is presssed into skin.&#10;2+ = Slight indentation. 15 seconds to rebound.&#10;3+ = Deeper indentation. 30 seconds to rebound.&#10;4+ = > 30 seconds to rebound.">
        <select name="oedema">
           <option value="">Select</option>
           <option value="Nil">Nil</option>
           <option value="1+">1+</option>
           <option value="2+">2+</option>
           <option value="3+">3+</option>
           <option value="4+">4+</option>
        </select>
			</td>
      <td bgcolor="#F8FDCE"><strong>Visit&gt;Edit</strong></td>

      <td><input name="entryby" type="text" class="BlackBold_10" id="entryby" value="<?php echo $_SESSION['user']; ?>" size="10" maxlength="30" style="text-align:center;"/></td>
      <td><input name="Submit" type="submit" onclick="MM_validateForm('fetalheart','','RinRange0:150');MM_validateForm('hof','','RinRange1:50');MM_validateForm('weight','','RinRange20:150');MM_validateForm('bldpres','','R');MM_validateForm('diag','','R');return document.MM_returnValue" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add" /></td>
    </tr>
	<tr>
      <td class="BlackBold_12"><div align="right">Remarks: </div></td>
      <td colspan="5"><textarea name="foluptext" cols="60" rows="1"></textarea></td>
      <td class="BlackBold_12"><div align="right">Diagnosis: </div></td>
      <td colspan="4"><textarea name="diag" cols="40" rows="1" id="diag"></textarea> </td>
      <td  align="center" colspan="2" class="BlackBold_12">See Doctor:       
         <input type="hidden" id="seedoc" name="seedoc" value="N">
         <input type="checkbox" id="seedoc" name="seedoc" value="Y">
      </td>
	</tr>
</table>
<input type="hidden" name="MM_insert" value="formpafa">
</form>


</body>
</html>
