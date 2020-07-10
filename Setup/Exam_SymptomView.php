<?php $pt = "Exam Setup Menu"; ?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php //($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_examid = "-1";
if (isset($_GET['examid'])) {
  $colname_examid = (get_magic_quotes_gpc()) ? $_GET['examid'] : addslashes($_GET['examid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_sympt = "Select s.id sid, s.symptom, s.seq, s.active, e.id eid, e.examname from exam_sympt s join exam_exam e on e.id = s.examid Where s.examid = '".$colname_examid."' order by s.seq";
$sympt = mysql_query($query_sympt, $swmisconn) or die(mysql_error());
$row_sympt = mysql_fetch_assoc($sympt);
$totalRows_sympt = mysql_num_rows($sympt);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exam Symtom ViewS</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p align="center">&nbsp;</p>
<table border="0" align="center" class="tablebc">
  <tr>
    <td valign="top">
	  <table border="1" class="tablebc">
        <tr>
          <td colspan="2"><a href="Exam_ExamView.php?act=Exam_SymptomView.php&act2=Exam_SymptomAdd.php&examid=<?php echo $colname_examid ?>">Add Symptom</a></td>
          <td colspan="3" nowrap="nowrap"><div align="center"><span class="GreenBold_30">Symptom View</span></div></td>
          <td>Close</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>exam</td>
          <td>sid</td>
          <td>symptom</td>
          <td>seq</td>
          <td>active</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="Exam_ExamView.php?act=Exam_SymptomView.php&act2=Exam_SymptEdit.php&examid=<?php echo $colname_examid ?>&symptid=<?php echo $row_sympt['sid']; ?>">Edit</a></td>
            <td title="ExamID: <?php echo $row_sympt['eid']; ?>"><?php echo $row_sympt['examname']; ?></td>
            <td><div align="center"><?php echo $row_sympt['sid']; ?></div></td>
            <td><?php echo $row_sympt['symptom']; ?></td>
            <td><div align="center"><?php echo $row_sympt['seq']; ?></div></td>
            <td><div align="center"><?php echo $row_sympt['active']; ?></div></td>
          </tr>
          <?php } while ($row_sympt = mysql_fetch_assoc($sympt)); ?>
	  </table>
    </td>
	<td valign="top" >
	<?php if (isset($_GET['act2'])) {
		$symptact = $_GET['act2'];
	?>
		<table>
			  <tr>
				<td valign="top"><?php require_once($symptact); ?></td>
			  </tr>
	  </table>
	<?php }
		else {
	?>		
		<table>
			  <tr>
				<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			  </tr>
	  </table>
	
	<?php	} ?>
	</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($sympt);
?>
