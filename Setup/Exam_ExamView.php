<?php $pt = "Exam Setup Menu"; ?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_exams = "SELECT e.id eid, e.examname, e.seq, e.locfeeid, e.active, f.name locfeename from exam_exam e join fee f on e.locfeeid = f.id ORDER BY f.name, e.seq";
$exams = mysql_query($query_exams, $swmisconn) or die(mysql_error());
$row_exams = mysql_fetch_assoc($exams);
$totalRows_exams = mysql_num_rows($exams);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exam Exam View</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p></p>
<p align="center"> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>

<p>&nbsp;</p>

<table align="center">
	<tr>
		<td valign="top">
			<table border="1" align="center" class="tablebc">
			  <tr>
			    <td><a href="Exam_ExamView.php?act=Exam_ExamAdd.php">Add Exam</a> </td>
			    <td>&nbsp;</td>
			    <td colspan="3"><div align="center"><span class="GreenBold_30">Exam View</span></div></td>
			    <td colspan="2" align="center"><a href="SetupMenu.php">Setup Menu</a> </td>
		      </tr>
			  <tr>
			    <td colspan="7">&nbsp;</td>
		      </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>eid</td>
				<td>examname</td>
				<td>seq</td>
				<td>location</td>
				<td>active</td>
			  </tr>
			<?php do { ?>
			  <tr>
			    <td bgcolor="#FFFDDA"><a href="Exam_ExamView.php?act=Exam_SymptomView.php&examid=<?php echo $row_exams['eid']; ?>">ViewSympt</a></td>
				  <td bgcolor="#FFFDDA"><a href="Exam_ExamView.php?act=Exam_ExamEdit.php&examid=<?php echo $row_exams['eid']; ?>">Edit</a></td>
				  <td bgcolor="#FFFFFF"><?php echo $row_exams['eid']; ?></td>
				  <td bgcolor="#FFFFFF"><?php echo $row_exams['examname']; ?></td>
				  <td bgcolor="#FFFFFF"><?php echo $row_exams['seq']; ?></td>
				  <td bgcolor="#FFFFFF"><?php echo $row_exams['locfeename']; ?></td>
				  <td bgcolor="#FFFFFF"><?php echo $row_exams['active']; ?></td>
			 </tr>
		  <?php } while ($row_exams = mysql_fetch_assoc($exams)); ?>
			</table>
		</td>
		
		
		<td valign="top" >
		<?php if (isset($_GET['act'])) {
			$examact = $_GET['act'];
		?>
			<table width="400px">
				  <tr>
					<td valign="top"><?php require_once($examact); ?></td>
				  </tr>
		  </table>
		<?php }
			else {
		?>		
			<table width="400px">
				  <tr>
					<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				  </tr>
		  </table>
		
		<?php	} ?>
		</td>
		<td>
			<table>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>

		<td>
			<table>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mysql_free_result($exams);
?>
