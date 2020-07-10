<?php $pt = "Exam Setup Menu"; ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php require_once('../../Connections/swmisconn.php'); ?>
<?php function GetBetween($content,$start,$end) // used to display other text 
{
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}
 ?>

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
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
/* echo '<script type="text/javascript">alert("'.$_POST['location'].'");<//script>';
 exit;*/
// put other_text into a string by finding the exam ids (which are other_text names) and concatinating ids and other_text and store in db:other_arr field
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_exami = "Select id, examname from exam_exam where active = 'Y' and locfeeid = ".$_POST['locfeeid']." order by seq ";
	$exami = mysql_query($query_exami, $swmisconn) or die(mysql_error());
	$row_exami = mysql_fetch_assoc($exami);
	$totalRows_exami = mysql_num_rows($exami);
	$vare = '';
	$other = 'x';
	do { 
 		$vare = $row_exami['id'];
 	    if(strlen($_POST["$vare"]) > 0) {
		  $other = $other  . $row_exami['id'] . ':'.$_POST["$vare"]. ',';  // $row_exami['id'] is exam number, $_POST["$vare"] is the text
        } 
    } while ($row_exami = mysql_fetch_assoc($exami));
        $other = substr($other,0,strlen($other)-1);
$chkexsy = "x";

if(isset($_POST['chk_ex_sy'])) {
           $chkexsy = $chkexsy.implode(',', $_POST['chk_ex_sy']).',';  //comma added to end of string to be used as a delimiter later

}
  $insertSQL = sprintf("INSERT INTO exam_result (medrecnum, visitid, exam_arr, other_arr, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($chkexsy, "text"),
					   GetSQLValueString($other, "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

mysql_select_db($database_swmisconn, $swmisconn);
$query_NewExam = sprintf("SELECT MAX(id) maxid FROM exam_result");
$NewExam = mysql_query($query_NewExam, $swmisconn) or die(mysql_error());
$row_NewExam = mysql_fetch_assoc($NewExam);
$totalRows_NewExam = mysql_num_rows($NewExam);

  $insertSQL = sprintf("INSERT INTO patnotes (medrecnum, visitid, examresultid, notetype, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($row_NewExam['maxid'], "text"),
					   GetSQLValueString('exam', "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());


  $saved = "true";
}
 ?> 
<?php 
if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "form2")) {
/* echo '<script type="text/javascript">alert("'.$_POST['location'].'");<//script>';
 exit;*/
// put other_text into a string by finding the exam ids (which are other_text names) and concatinating ids and other_text and store in db:other_arr field
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_exami = "Select id, examname from exam_exam where active = 'Y' and locfeeid = '".$_POST['locfeeid']."' order by seq ";
	$exami = mysql_query($query_exami, $swmisconn) or die(mysql_error());
	$row_exami = mysql_fetch_assoc($exami);
	$totalRows_exami = mysql_num_rows($exami);
	$vare = '';
	$other = 'x';
	do { 
 		$vare = $row_exami['id'];
 	    if(strlen($_POST["$vare"]) > 0) {
		  $other = $other  . $row_exami['id'] . ':'.$_POST["$vare"]. ',';  // $row_exami['id'] is exam number, $_POST["$vare"] is the text
        } 
    } while ($row_exami = mysql_fetch_assoc($exami));
        $other = substr($other,0,strlen($other)-1);
/*echo '<script type="text/javascript">alert("'.$other.'");</script>';
exit;*/
$chkexsy = "x";
// print_r($_POST['chk_ex_sy']);
// exit;

if(isset($_POST['chk_ex_sy'])) {
           $chkexsy = $chkexsy.implode(',', $_POST['chk_ex_sy']).',';  //comma added to end of string to be used as a delimiter later
}
// echo '<script type="text/javascript">alert("'.$_POST['chk_ex_sy'].'");<//script>';
 
  $insertSQL = sprintf("Update exam_result set medrecnum = %s, visitid = %s, exam_arr = %s, other_arr = %s, entryby = %s, entrydt = %s WHERE id=%s",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($chkexsy, "text"),
					   GetSQLValueString($other, "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
}
 ?> 

<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_medrecnum = "-1";
if (isset($_GET['mrn'])) {
  $colname_medrecnum = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}

$colname_visitid = "-1";
if (isset($_GET['visitid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
$_SESSION['vid'] = $colname_visitid;
}

if(!isset($_GET['EDIT'])){
	include('PatExamView.php');
}

$colname_locfeeid = "-1";
if (isset($_GET['locfeeid'])) {
  $colname_locfeeid = (get_magic_quotes_gpc()) ? $_GET['locfeeid'] : addslashes($_GET['locfeeid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_exam = "Select id, examname from exam_exam where locfeeid = ".$colname_locfeeid." order by seq ";
$exam = mysql_query($query_exam, $swmisconn) or die(mysql_error());
$row_exam = mysql_fetch_assoc($exam);
$totalRows_exam = mysql_num_rows($exam);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</head>
<?php if($saved == "true") { ?>
	<body onload="out()">
	<?php }?>
<p>&nbsp;</p>
<?php if(isset($_GET['ADD']) and $_GET['ADD']=='Y'){?>
<table border="0" align="center">
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">  <!--action="ExamData.php"-->
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="Save" /></td>
		<td>&nbsp;</td>
	</tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#DEEAFA" class="BlueBold_1414"><?php echo $row_exam['examname']; ?></td>
      <td bgcolor="#DEEAFA"><label>
        <input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'];?>:1" />Normal</label></td>
      <td Colspan="4" bgcolor="#DEEAFA"><label>
        <input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'];?>:0" />NA</label><?php echo $colname_locfeeid ?></td>
    </tr>
  <?php 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_sympt = "Select s.id sid, s.symptom, s.seq, s.active, e.id eid, e.examname from exam_sympt s join exam_exam e on e.id = s.examid where  e.active = 'Y' and s.active = 'Y' and examid = ".$row_exam['id']." order by s.seq";
	$sympt = mysql_query($query_sympt, $swmisconn) or die(mysql_error());
	$row_sympt = mysql_fetch_assoc($sympt);
	$totalRows_sympt = mysql_num_rows($sympt);
  ?>	
  <?php $s=0 ?>
	<tr>
  <?php do { 
  		$s = $s + 1 ;  
  ?>
<?php       if($s >0 & $s <13) {	?>
		<td bgcolor="#ffffee"><input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'].':'.$row_sympt['sid']; ?>"/>

	    <?php echo $row_sympt['symptom']; ?></td>
<?php } ?>
<?php       if($s == 6) {	// 6 items per row ?>
	</tr>
<?php } ?>
	   
  <?php } while ($row_sympt = mysql_fetch_assoc($sympt)); ?>
  		<!--<input name="chk_ex_sy[]" type="hidden" value="0:0" />-->
    <tr>
		<!--<td align="right">other_text:</td>-->
	<!--	<td bgcolor="#ffffee"><label><input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'].':2' ?>"/>Other ..........</label></td>-->
		    <td bgcolor="#ffffee"><label><input type="hidden" name="chk_ex_sy[]" value="<?php echo $row_exam['id'].':2' ?>"/>Other ..........</label></td>
		<td colspan="5"><input name="<?php echo $row_exam['id'] ?>" type="text" size="80" maxlength="80" autocomplete="off" /></td>
	</tr>
  <?php } while ($row_exam = mysql_fetch_assoc($exam)); ?>
  		<input name="medrecnum" type="hidden" value="<?php echo $colname_medrecnum ;?>" />
		<input name="visitid" type="hidden" value="<?php echo $colname_visitid ;?>" />
		<input name="locfeeid" type="hidden" value="<?php echo $colname_locfeeid ;?>" />
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		<input type="hidden" name="MM_insert" value="form1">
  </form>
</table>
<?php   }?>

<?php if(isset($_GET['EDIT']) and $_GET['EDIT']=='Y'){  // EDIT     EDIT     EDIT     EDIT     EDIT     EDIT     EDIT     EDIT     EDIT     ?>

<?php
$colname_medrecnum = "-1";
if (isset($_GET['mrn'])) {
  $colname_medrecnum = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}

$colname_visitid = "-1";
if (isset($_GET['visitid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['visitid'] : addslashes($_GET['visitid']);
}

$colname_locfeeid = "-1";
if (isset($_GET['locfeeid'])) {
  $colname_locfeeid = (get_magic_quotes_gpc()) ? $_GET['locfeeid'] : addslashes($_GET['locfeeid']);
}

$colname_id = "-1";
if (isset($_GET['id'])) {
  $colname_id = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_exam = "Select id, examname from exam_exam where locfeeid = '".$colname_locfeeid."' order by seq ";
$exam = mysql_query($query_exam, $swmisconn) or die(mysql_error());
$row_exam = mysql_fetch_assoc($exam);
$totalRows_exam = mysql_num_rows($exam);

mysql_select_db($database_swmisconn, $swmisconn);
//$query_result = "Select id, substr(exam_arr,2, CHAR_LENGTH(exam_arr)-2) exam_arr, substr(other_arr,2, CHAR_LENGTH(other_arr)-2) other_arr from exam_result where 
$query_result = "Select id, exam_arr, other_arr from exam_result where medrecnum = ".$colname_medrecnum." and visitid = ".$colname_visitid." and id = ".$colname_id."";
$result = mysql_query($query_result, $swmisconn) or die(mysql_error());
$row_result = mysql_fetch_assoc($result);
$totalRows_result = mysql_num_rows($result);
$row_result['exam_arr']
?>

<p>&nbsp;</p>

<table border="0" align="center">
<form id="form1" name="form2" method="POST" action="<?php echo $editFormAction; ?>">  <!--action="ExamData.php"-->
	<tr>
		<td><?php //echo $row_result['exam_arr'] ?></td>
		<td>&nbsp;</td>
		<td><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="Edit" /></td>
		<td>&nbsp;</td>
	</tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#DEEAFA" class="BlueBold_1414"><?php echo $row_exam['examname']; ?></td>
      <td bgcolor="#DEEAFA"><?php //echo $row_exam['id'] ?><label>
        <input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'];?>:1" <?php if (strpos($row_result['exam_arr'],$row_exam['id'].':1,') > 0) echo "checked='checked'"; ?> />Normal</label></td>
      <td Colspan="4" bgcolor="#DEEAFA"><label>
        <input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'];?>:0" <?php if (strpos($row_result['exam_arr'],$row_exam['id'].':0,') > 0) echo "checked='checked'"; ?>/>NA</label></td>
    </tr>
  <?php 
	mysql_select_db($database_swmisconn, $swmisconn);
	$query_sympt = "Select s.id sid, s.symptom, s.seq, s.active, e.id eid, e.examname from exam_sympt s join exam_exam e on e.id = s.examid where  e.active = 'Y' and s.active = 'Y' and examid = ".$row_exam['id']." order by s.seq";
	$sympt = mysql_query($query_sympt, $swmisconn) or die(mysql_error());
	$row_sympt = mysql_fetch_assoc($sympt);
	$totalRows_sympt = mysql_num_rows($sympt);
  ?>	
  <?php $s=0 ?>
	<tr>
  <?php do { 
  		$s = $s + 1 ;  
  ?>
<?php       if($s >0 & $s <13) {	?>
		<td bgcolor="#ffffee"><?php //echo $row_exam['id'].':'.$row_sympt['sid'] ?><input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id'].':'.$row_sympt['sid']; ?>"<?php if (strpos($row_result['exam_arr'], $row_exam['id'].':'.$row_sympt['sid'].',') > 0) echo "checked='checked'"; ?>/><?php echo $row_sympt['symptom']; ?></td>
<?php  } ?>
<?php    if($s == 6) {	// 6 items per row ?>
	</tr>
<?php  } ?>
	   
  <?php } while ($row_sympt = mysql_fetch_assoc($sympt)); ?>
     <tr>
		<!--<td align="right">other_text:</td>-->
		<td align="right"><label><input type="checkbox" name="chk_ex_sy[]" value="<?php echo $row_exam['id']; ?>:2" <?php if (strpos($row_result['exam_arr'],$row_exam['id'].':2,') > 0) echo "checked='checked'"; ?>/>Other</label></td>
		
		<td colspan="5"><input name="<?php echo $row_exam['id'] ?>" type="text" size="80" maxlength="80" autocomplete="off" value="<?php if (strpos($row_result['other_arr'],$row_exam['id'].':') > 0) echo GetBetween($row_result['other_arr'],$row_exam['id'].':',','); ?>"/></td>
	</tr>
  <?php } while ($row_exam = mysql_fetch_assoc($exam)); ?>

  		<input name="id" type="hidden" value="<?php echo $row_result['id'] ;?>" />
  		<input name="medrecnum" type="hidden" value="<?php echo $colname_medrecnum ;?>" />
		<input name="visitid" type="hidden" value="<?php echo $colname_visitid ;?>" />
		<input name="locfeeid" type="hidden" value="<?php echo $colname_locfeeid ;?>" />
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
		<input type="hidden" name="MM_Update" value="form2">
  </form>
</table>
<?php  } ?>

</body>
</html>
<?php
mysql_free_result($exam);
?>
