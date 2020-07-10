<?php  $pt = "Lab Result Entry Edit"; ?>
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
    $normflag = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

 $gmp = "";
 $gmn = "";
 $colname_ordid = -1;
if (isset($_POST['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_POST['ordid'] : addslashes($_POST['ordid']);

	// look for POS or NEG result of Culture test in order to select antibiotics for sensitivity
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_tests = "SELECT t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN )  join  results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and t.test = 'Culture' and o.id = '".$colname_ordid."'";
	$culturetests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
	$row_culturetests = mysql_fetch_assoc($culturetests);	
	$totalRows_culturetests = mysql_num_rows($culturetests); 
  
    if (strpos($row_culturetests['result'],'POS') > 0) {
	  $gmp = 'P';	
	} 
    if (strpos($row_culturetests['result'],'NEG') > 0) {
	  $gmn = 'N';	
	} 

	mysql_select_db($database_swmisconn, $swmisconn);
	$query_tests = "SELECT o.id ordid, o.feeid, t.id tid, t.test, t.description, t.formtype, t.units,t. reportseq, t.active, r.id rid FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN ) left outer join results r on (o.id = r.ordid and o.feeid = r.feeid and t.id = r.testid) WHERE t.active = 'Y' and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') and o.id ='".$colname_ordid."' ORDER BY reportseq ";
	$addtests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
	$row_addtests = mysql_fetch_assoc($addtests);
	$totalRows_addtests = mysql_num_rows($addtests); 
 
  $noresult = 0;  // count number of blank results to determine resulted status of order
 do { 
 
// check for a test result record ******************************************
// if none, insert a result record ***************************************** 
// check for a test result value
	if (strlen(strval($_POST['result'.$row_addtests['tid']])) == 0) {
		$noresult = $noresult + 1 ;
	}
  $normflag = "";
	if(is_numeric($_POST['result'.$row_addtests['tid']])) {  //verify test value is numeric
		if(is_numeric($_POST['NL'.$row_addtests['tid']]) AND  $_POST['result'.$row_addtests['tid']] < $_POST['NL'.$row_addtests['tid']]) {
			$normflag = "LO";
		}	
		if(is_numeric($_POST['PL'.$row_addtests['tid']]) AND $_POST['result'.$row_addtests['tid']] < $_POST['PL'.$row_addtests['tid']]) {
			$normflag = "PL";
		}	
		if(is_numeric($_POST['NH'.$row_addtests['tid']]) AND $_POST['result'.$row_addtests['tid']] > $_POST['NH'.$row_addtests['tid']]) {
			$normflag = "HI";
		}	
		if(is_numeric($_POST['PH'.$row_addtests['tid']]) AND $_POST['result'.$row_addtests['tid']] > $_POST['PH'.$row_addtests['tid']]) {
			$normflag = "PH";
		}	
      }
	if(!isset($row_addtests['rid'])){
		  $insertSQL = sprintf("INSERT INTO results (testid, feeid, ordid, `result`, normflag, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tid'.$row_addtests['tid']], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['ordid'], "int"),
                       GetSQLValueString($_POST['result'.$row_addtests['tid']], "text"),
                       GetSQLValueString($normflag, "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
		
	}

  $insertSQL = sprintf("UPDATE results SET testid=%s, feeid=%s, ordid=%s, `result`=%s, normflag=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['tid'.$row_addtests['tid']], "int"),
                       GetSQLValueString($_POST['feeid'], "int"),
                       GetSQLValueString($_POST['ordid'], "int"),
                       GetSQLValueString($_POST['result'.$row_addtests['tid']], "text"),
                       GetSQLValueString($normflag, "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
					   GetSQLValueString($_POST['resid'.$row_addtests['rid']], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

 } while ($row_addtests = mysql_fetch_assoc($addtests)); 
}
 if($noresult == 0) {
  $UpdateSQL = sprintf("UPDATE orders SET status=%s, comments=%s WHERE id=%s",
                       GetSQLValueString('Resulted', "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));
	} 
	else {
  $UpdateSQL = sprintf("UPDATE orders SET status=%s, comments=%s WHERE id=%s",
                       GetSQLValueString('InLab', "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));
	}
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "LabREtests.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('LabREedit.php&','LabREview.php&',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
  }
// echo $noresult;
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php
 $gmp = "";
 $gmn = "	";

 $colname_ordid = 6;
if (isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
	}
	// look for POS or NEG result of Culture test in order to select antibiotics for sensitivity
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_tests = "SELECT t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN )  join  results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and t.test = 'Culture' and o.id = '".$colname_ordid."'";
	$culturetests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
	$row_culturetests = mysql_fetch_assoc($culturetests);	
	$totalRows_culturetests = mysql_num_rows($culturetests); 
  
    if (strpos($row_culturetests['result'],'POS') > 0) {
	  $gmp = 'P';	
	} 
    if (strpos($row_culturetests['result'],'NEG') > 0) {
	  $gmn = 'N';	
	} 

mysql_select_db($database_swmisconn, $swmisconn);  //instr(flag1,'".$gmp."')>=0    ".$gmn."
$query_atests = "SELECT o.id ordid, o.feeid, o.doctor, o.comments, t.id, t.test, t.description, t.formtype, ddl, t.units,t.reportseq, t.active,r.id rid, r.result, r.normflag, p.gender, p.dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN ) join patperm p on o.medrecnum = p.medrecnum left outer join results r on (o.id = r.ordid and o.feeid = r.feeid and t.id = r.testid) WHERE t.active = 'Y' and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') AND o.id ='".$colname_ordid."' ORDER BY reportseq";
$atests = mysql_query($query_atests, $swmisconn) or die(mysql_error());
$row_atests = mysql_fetch_assoc($atests);
$totalRows_atests = mysql_num_rows($atests);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
option.ltgreen {background-color:#BCFACC}
</style>
</head>

<body>
<table width="100%">
  <tr>
  	<td colspan="6"><div align="center" class="BlueBold_24">Edit Lab Test Results</div></td>
  </tr>
  <tr>
    <td> 
	 <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
       <table bgcolor="#F8FDCE">
        <tr>

          <td><div align="center"><a href="LabRETests.php?app=LabREview.php&<?php echo str_replace('app=LabREedit.php&','',$_SERVER['QUERY_STRING']);?>">View</a></div></td>
          <td>&nbsp;<?php echo 'GMP:'.$gmp ?>&nbsp;<?php echo 'GMN:'.$gmn ?></td>
          <td class="BlackBold_12"><div align="right">&nbsp;DOB: </div></td>
          <td class="BlackBold_12"><?php echo $row_atests['dob']; ?></td>
          <td colspan="2" nowrap="nowrap" class="BlackBold_12"> Age: <?php echo $row_atests['age']; ?></td>
          <td colspan="3" class="BlackBold_12"> Gender: <?php echo $row_atests['gender']; ?></td>
        </tr>
        <tr>
          <td class="BlackBold_12">Ord#:<?php echo $_GET['ordid']; ?></td>
          <td class="BlackBold_12"><?php echo $_GET['name']; ?></td>
          <td class="BlackBold_12"><div align="right">Doctor:</div></td>
          <td colspan="2" class="BlackBold_12"><?php echo $row_atests['doctor']; ?></td>
          <td colspan="2" class="BlackBold_12"></td>
          <td class="BlackBold_12"></td>
        </tr>
        <tr>
          <td>test</td>
          <td>Result </td>
          <td>flag</td>
          <td>units</td>
          <td colspan="2"><div align="center">Normal</div></td>
          <td colspan="2"><div align="center">Panic</div></td>
          <td>Interpretation</td>
        </tr>
        <?php do { ?>
        <tr>
          <td nowrap="nowrap" title="Fee ID: <?php echo $row_atests['feeid']; ?>&#10;TestID: <?php echo $row_atests['id']; ?>&#10;Description: <?php echo $row_atests['description']; ?>&#10;Form Type: <?php echo $row_atests['formtype']; ?>&#10;Report Seq: <?php echo $row_atests['reportseq']; ?>"><?php echo $row_atests['test']; ?></td>

<?php if ($row_atests['formtype'] == 'TextField') { ?>
          <td><input name="<?php echo 'result'.$row_atests['id']; ?>" type="text" id="<?php echo 'result'.$row_atests['id']; ?>" value ="<?php echo $row_atests['result'] ?>" maxlength="30"  autocomplete="off" /></td>
<?php } else if($row_atests['formtype'] == 'DropDown') { 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_ddl = "SELECT list, name, seq FROM dropdownlist WHERE list = '".$row_atests['ddl']."' Order by seq";
	$ddl = mysql_query($query_ddl, $swmisconn) or die(mysql_error());
	$row_ddl = mysql_fetch_assoc($ddl);
	$totalRows_ddl = mysql_num_rows($ddl);

?>
<td><select type="text" name="<?php echo 'result'.$row_atests['id']; ?>" id="<?php echo 'result'.$row_atests['id']; ?>" >
  <?php if(isset($row_atests['ddl']) && $row_atests['ddl'] == 'Antibiotic') { ?>
  <option value="Not Tested" <?php if (!(strcmp("Not Tested", $row_atests['result']))) {echo "selected=\"selected\"";} ?>>Not Tested</option>
  <?php } else { ?>
  <option value="" <?php if (!(strcmp("", $row_atests['result']))) {echo "selected=\"selected\"";} ?>>Select</option>
  <?php } ?>

  <?php do { ?>
  <option class="ltgreen" value="<?php echo $row_ddl['name']?>"<?php if (!(strcmp($row_ddl['name'], $row_atests['result']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ddl['name']?></option>
  <?php
	} while ($row_ddl = mysql_fetch_assoc($ddl));
	  $rows = mysql_num_rows($ddl);
	  if($rows > 0) {
      mysql_data_seek($ddl, 0);
	  $row_ddl = mysql_fetch_assoc($ddl);
  }
?>
</select></td>
<?php 
	mysql_free_result($ddl);
   }?>
          <td><?php echo $row_atests['normflag']; ?></td>
          <td><?php echo $row_atests['units']; ?></td>

          <input type="hidden" id="<?php echo 'tid'.$row_atests['id']; ?>" name="<?php echo 'tid'.$row_atests['id']; ?>" value="<?php echo $row_atests['id']; ?>" />
          <input type="hidden" id="<?php echo 'resid'.$row_atests['rid']; ?>" name="<?php echo 'resid'.$row_atests['rid']; ?>" value="<?php echo $row_atests['rid']; ?>" />

<?php 
	mysql_select_db($database_swmisconn, $swmisconn); // look up normal ranges
	$query_norms = "Select normlow, normhigh, paniclow, panichigh, interpretation from testnormalvalues where testid = '".$row_atests['id']."' AND instr(gender,'".$row_atests['gender']."') > 0 AND '".$row_atests['age']."' > agemin AND '".$row_atests['age']."' < agemax";
	$norms = mysql_query($query_norms, $swmisconn) or die(mysql_error());
	$row_norms = mysql_fetch_assoc($norms);
	$totalRows_norms = mysql_num_rows($norms);
?>		  
          <td colspan="2" nowrap="nowrap" bgcolor="#80ff80"><?php echo $row_norms['normlow']; ?>-<?php echo $row_norms['normhigh']; ?></td>
          <td nowrap="nowrap" bgcolor="#ffcccccc">&lt;<?php echo $row_norms['paniclow']; ?></td>
          <td nowrap="nowrap" bgcolor="#ffcccccc">&gt;<?php echo $row_norms['panichigh']; ?></td>
          <td bgcolor="#ffffff"><?php echo $row_norms['interpretation']; ?></td>
		  <td><div align="center">
			   <input type="hidden" id="<?php echo 'PL'.$row_atests['id']; ?>" name="<?php echo 'PL'.$row_atests['id']; ?>"value="<?php echo $row_norms['paniclow']; ?>" />
			   <input type="hidden" id="<?php echo 'NL'.$row_atests['id']; ?>" name="<?php echo 'NL'.$row_atests['id']; ?>"value="<?php echo $row_norms['normlow']; ?>" />
			   <input type="hidden" id="<?php echo 'NH'.$row_atests['id']; ?>" name="<?php echo 'NH'.$row_atests['id']; ?>"value="<?php echo $row_norms['normhigh']; ?>" />
			   <input type="hidden" id="<?php echo 'PH'.$row_atests['id']; ?>" name="<?php echo 'PH'.$row_atests['id']; ?>"value="<?php echo $row_norms['panichigh']; ?>" />
			   <input name="age" type="hidden" value="<?php echo $row_atests['age']; ?>" />
			   <input name="gender" type="hidden" value="<?php echo $row_atests['gender']; ?>" />
			   <input name="ordid" type="hidden" value="<?php echo $row_atests['ordid']; ?>" />
			   <input name="feeid" type="hidden" value="<?php echo $row_atests['feeid']; ?>" />
			   <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
			   <input name="entrydt" type="hidden" id="entrydt" value="<?php echo Date('Y-m-d H:i:s'); ?>" />
			   <input type="hidden" name="MM_insert" value="form1">
		    </div></td>
        </tr>

<?php
			$ordcomments = $row_atests['comments'];

		 	} while ($row_atests = mysql_fetch_assoc($atests)); ?>
		<tr>
          <td>Order<br />
            Comments:</td>
          <td colspan="9"><textarea name="comments" cols="50" rows="2"><?php echo $ordcomments ?></textarea></td>
        </tr>
		<tr>
          <td colspan="9"><div align="center">
            <input name="submit" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="SAVE" />
          </div></td>
		</tr>
	   </table>
      </form>
	</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($atests);
?>
