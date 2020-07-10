<?php  $pt = "Lab Result Entry Add"; ?>
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

 if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $UpdateSQL = sprintf("UPDATE orders SET status=%s, revby=%s, revdt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString('Reviewed', "text"),
					   GetSQLValueString($_POST['revby'], "text"),
                       GetSQLValueString($_POST['revdt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());


  $insertGoTo = "LabREtests.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('LabREreview.php&','LabRErevDone.php&',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
  }
// echo $noresult;
  header(sprintf("Location: %s", $insertGoTo));
  }
?>
<?php
 $gmp = "";
 $gmn = "";

 $colname_ordid = 6;
if (isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
	}
	// look for POS or NEG result of Culture test in order to select antibiotics for sensitivity
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_tests = "SELECT t.id, t.test, r.result FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN)  join  results r on (r.testid = t.id and r.feeid = o.feeid and r.ordid = o.id) where t.active = 'Y' and t.test = 'Culture' and o.id = '".$colname_ordid."'";
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
$query_tests = "SELECT o.id ordid, o.feeid, o.status, o.doctor, o.comments, t.id, t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.test, t.description, t.formtype, t.units,t. reportseq, t.active, p.gender, p.dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age FROM tests t join orders o on o.feeid in (t.feeid1, t.feeid2, t.feeid3, t.feeid4, t.feeid5, t.feeid6, t.feeid7, t.feeid8, t.feeid9, t.feeid0, t.feeidA, t.feeidB, t.feeidC, t.feeidD, t.feeidE, t.feeidF, t.feeidG, t.feeidH, t.feeidI, t.feeidJ, t.feeidK, t.feeidL, t.feeidM, t.feeidN) join patperm p on o.medrecnum = p.medrecnum where t.active = 'Y' and (t.flag1 is null or trim(t.flag1) = '".$gmp."' or trim(t.flag1) = '".$gmn."') and o.id ='".$colname_ordid."' ORDER BY reportseq";
$tests = mysql_query($query_tests, $swmisconn) or die(mysql_error());
$row_tests = mysql_fetch_assoc($tests);
$totalRows_tests = mysql_num_rows($tests);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn); // check # of results to determine if add or edit screen should be displayed
$query_results = "Select result, normflag from results where testid = '".$row_tests['id']."' and feeid = '".$row_tests['feeid']."'and ordid = '".$row_tests['ordid']."'";
$vresults = mysql_query($query_results, $swmisconn) or die(mysql_error());
$row_vresults = mysql_fetch_assoc($vresults);
$totalRows_vresults = mysql_num_rows($vresults);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<?php
//   $REGoTo = "LabRETests.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $REGoTo .= (strpos($REGoTo, '?')) ? "&" : "?";
//    $REGoTo .= $_SERVER['QUERY_STRING']; // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
?>
<body>
<table width="100%">
  <tr>
  	<td colspan="6"><div align="center" class="BlueBold_24">Review Lab Test Results</div></td>
  </tr>
  <tr>
    <td> 
	 <form name="form1" id="form1" method="post" action="<?php echo $editFormAction; ?>">
       <table bgcolor="#F8FDCE">
        <tr>
	<?php if($totalRows_vresults == 0) { ?> 
          <td><div align="center"><a href="LabRETests.php?app=LabREadd.php&<?php echo str_replace('app=LabREreview.php&','',$_SERVER['QUERY_STRING']);?>">Add</a></div></td>
	<?php }?>

          <td><div align="center"><a href="LabRETests.php?app=LabREedit.php&<?php echo str_replace('app=LabREreview.php&','',$_SERVER['QUERY_STRING']);?>">Edit</a></div></td>
          <td class="BlackBold_12"><div align="right">DOB: </div></td>
          <td class="BlackBold_12"><?php echo $row_tests['dob']; ?></td>
          <td colspan="2" nowrap="nowrap" class="BlackBold_12"> Age: <?php echo $row_tests['age']; ?></td>
          <td colspan="3" class="BlackBold_12"> Gender: <?php echo $row_tests['gender']; ?></td>
        </tr>
        <tr>
          <td class="BlackBold_12">Ord#:<?php echo $_GET['ordid']; ?></td>
          <td class="BlackBold_12"><?php echo $_GET['name']; ?></td>
          <td class="BlackBold_12"><div align="right">Doctor:</div></td>
          <td colspan="2" class="BlackBold_12"><?php echo $row_tests['doctor']; ?></td>
          <td colspan="2" class="BlackBold_12"></td>
          <td class="BlackBold_12"></td>
        </tr>
        <tr>
          <td>test</td>
          <td>Result </td>
          <td>units</td>
          <td colspan="2"><div align="center">Normal</div></td>
          <td colspan="2"><div align="center">Panic</div></td>
          <td>Interpretation</td>
        </tr>
        <?php do { ?>
        <tr>
          <td title="TestID: <?php echo $row_tests['id']; ?>&#10;Description: <?php echo $row_tests['description']; ?>&#10;Form Type: <?php echo $row_tests['formtype']; ?>&#10;Report Seq: <?php echo $row_tests['reportseq']; ?>"><?php echo $row_tests['test']; ?></td>

<?php mysql_select_db($database_swmisconn, $swmisconn); // look up results
$query_results = "Select result, normflag from results where testid = '".$row_tests['id']."' and feeid = '".$row_tests['feeid']."'and ordid = '".$row_tests['ordid']."'";
$results = mysql_query($query_results, $swmisconn) or die(mysql_error());
$row_results = mysql_fetch_assoc($results);
$totalRows_results = mysql_num_rows($results);
?>
          <td><input style="background-color: #cccccc;" bgcolor="#cccccc" type="text" id="<?php echo $row_tests['id']; ?>" name="<?php echo $row_tests['id']; ?>" readonly="readonly" value="<?php echo $row_results['result'] ?>" /></td>
		  
          <td><?php echo $row_results['normflag']; ?></td>
          <td><?php echo $row_tests['units']; ?></td>
<?php 
	mysql_select_db($database_swmisconn, $swmisconn); // look up normal ranges
	$query_norms = "Select normlow, normhigh, paniclow, panichigh, interpretation from testnormalvalues where testid = '".$row_tests['id']."' AND instr(gender,'".$row_tests['gender']."') > 0 AND '".$row_tests['age']."' > agemin AND '".$row_tests['age']."' < agemax";
	$norms = mysql_query($query_norms, $swmisconn) or die(mysql_error());
	$row_norms = mysql_fetch_assoc($norms);
	$totalRows_norms = mysql_num_rows($norms);
?>		  
          <td colspan="2" nowrap="nowrap" bgcolor="#80ff80"><?php echo $row_norms['normlow']; ?>-<?php echo $row_norms['normhigh']; ?></td>
          <td nowrap="nowrap" bgcolor="#ffcccccc">&lt;<?php echo $row_norms['paniclow']; ?></td>
          <td nowrap="nowrap" bgcolor="#ffcccccc">&gt;<?php echo $row_norms['panichigh']; ?></td>
          <td bgcolor="#ffffff"><?php echo $row_norms['interpretation']; ?></td>
        </tr>
			   <input name="ordid" type="hidden" value="<?php echo $row_tests['ordid']; ?>" />
			   <input name="feeid" type="hidden" value="<?php echo $row_tests['feeid']; ?>" />
        <?php
			$ordcomments = $row_tests['comments'];
		 } while ($row_tests = mysql_fetch_assoc($tests)); ?>
		<tr>
          <td>Order<br />
            Comments:</td>
          <td colspan="10"><textarea name="comments" cols="50" rows="2"><?php echo $ordcomments ?></textarea></td>
		</tr>
		  <tr>
		   <td colspan="10">
				   <input name="revby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				   <input name="revdt" type="hidden" id="entrydt" value="<?php echo Date('Y-m-d H:i:s'); ?>" />
				   <input type="hidden" name="MM_update" value="form1">
				   <input name="submit" type="submit" value="REVIEWED" />
			</td>
		  </tr>
	   </table>
      </form>
	</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($results);
?>
