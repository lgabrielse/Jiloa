<?php  $pt = "Receipts By Dept Report"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
   session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>

<?php  $datebm = date("m");
 $datebd = date("d");
?> 
<?php if ((!isset($_POST["MM_update"])) OR ($_POST["MM_update"] <> "form1")) {  // if form data is not posted
	$_POST['B_YYYY'] = Date('Y');
	$_POST['B_MM'] = Date('m');
	$_POST['B_DD'] = Date('d');
	$_POST['B_HH'] = "00"; //Date('H');
	$_POST['B_MIN'] = "00"; //Date('i');
	$_POST['E_YYYY'] = Date('Y');
	$_POST['E_MM'] = Date('m');
	$_POST['E_DD'] = Date('d');
	$_POST['E_HH'] = "23"; //Date('H');
	$_POST['E_MIN'] = "59"; //Date('H');
	}
?>

<?php if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {  // if form data is posted

//change posted values to a variable
	$dateb = $_POST['B_YYYY']."-".$_POST['B_MM']."-".$_POST['B_DD']." ".$_POST['B_HH'].":".$_POST['B_MIN'];
	$datee = $_POST['E_YYYY']."-".$_POST['E_MM']."-".$_POST['E_DD']." ".$_POST['E_HH'].":".$_POST['E_MIN'];
//strtotime explained: http://www.electrictoolbox.com/using-strtotime-with-php/
// get timestamp for posted variable - this takes unformattted input and creates a timestamp number
	$datebs = strtotime($dateb);
	$datees = strtotime($datee);
// put date in format for mysql and for display
	$date1 = date("Y-m-d H:i:s", $datebs);
	$date2 = date("Y-m-d H:i:s", $datees);
?>






<?php
mysql_select_db($database_swmisconn, $swmisconn);  //WHERE r.entrydt BETWEEN '". $date1 . "' AND '" . $date2 ."'
$query_dept = "SELECT  f.dept, f.section, f.name, sum(o.amtpaid) amtpaid FROM orders o join fee f on o.feeid = f.id join receipts r on r.medrecnum = o.medrecnum WHERE r.entrydt BETWEEN '". $date1 . "' AND '" . $date2 ."' group by f.dept, f.section, f.name order by f.dept, f.section, f.name ";
$dept = mysql_query($query_dept, $swmisconn) or die(mysql_error());
$row_dept = mysql_fetch_assoc($dept);
$totalRows_dept = mysql_num_rows($dept);
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<div>
  <div align="center" class="BlackBold_30">Cash Received by Department and Section</div>
</div>
<table width="40%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" align="center">
        <tr>
          <td nowrap="nowrap" bgcolor="#FFFFFF" class="BlackBold_11"><p>Select Beginning and </p>            </td>
          <td>Begin:</td>
          <td nowrap="nowrap">YYYY
            <select name="B_YYYY">
                <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
<?php 			$range = range(2012,2020);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['B_YYYY']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?>
              </select>
            MM
 		<select name="B_MM">
		  <option value="<?php echo date("m"); ?>"><?php echo date("m"); ?></option>
<?php 			$range = range(0,12);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['B_MM']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php }?> 
  		</select>
		
            DD
		<select name="B_DD">
		  <option value="<?php echo date("d"); ?>"><?php echo date("d"); ?></option>

<?php 			$range = range(0,31);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['B_DD']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php }?> 
  		</select>
            HH
		<select name="B_HH">
    		<option value="00">00</option>
<?php 			$range = range(0,24);
				foreach ($range as $cm) {?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['B_HH']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php }?> 
  		</select>
            MIN
		<select name="B_MIN">
			<option value="00">00</option>
<?php 			$range = range(0,60,5);
				foreach ($range as $cm) {?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['B_MIN']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php }?> 
		</select>          </td>
          <td>&nbsp;</td>
          <td><input type="submit" name="Submit" value="Submit" /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" bgcolor="#FFFFFF" class="BlackBold_11">ending date and time. </td>
          <td>End:</td>
          <td nowrap="nowrap">YYYY
		<select name="E_YYYY">
              <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
<?php 			$range = range(2012,2020);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['E_YYYY']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?>
        </select> 
            MM
            <select name="E_MM">
              <option value="<?php echo date("m"); ?>"><?php echo date("m"); ?></option>
<?php 			$range = range(1,12);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['E_MM']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?> </select>
            DD
            <select name="E_DD">
              <option value="<?php echo date("d"); ?>"><?php echo date("d"); ?></option>
<?php 			$range = range(0,31);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['E_DD']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?></select>
           HH
            <select name="E_HH">
              <option value="23">23</option>
<?php 			$range = range(0,23);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['E_HH']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?></select>
            MIN
            <select name="E_MIN">
              <option value="59">59</option>
<?php 			$range = range(0,59,5);
				foreach ($range as $cm) { ?>
 		  		<option value='<?php echo $cm ?>'  <?php if (!(strcmp($cm, $_POST['E_MIN']))) {echo "selected=\"selected\"";} ?>><?php echo $cm ?></option>";
<?php } ?></select>            </td>

          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
     </form>
    </td>
  </tr>
</table>

<?php if(isset($date1)) {?>
<table align="center">
  <tr>
    <td>dept</td>
    <td>section</td>
    <td>name</td>
    <td>sum(amtpaid)</td>
    <td>Sum(Section)</td>
    <td>Sum(Department)</td>
  </tr>
  <?php
  		$dep = 0;
		$sec = 0;
  		$deptm = "";
		$sectn = "";
		
  	 	do {
			if($row_dept['section'] <> $sectn AND $sectn <> "" ){ ?>
    <tr>
		<td>&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;</td>
		<td>Section</td>
		<td><?php echo $sectn .":"; ?></td>
		<td><?php echo " N " . $sec; ?></td>
		<td>&nbsp;&nbsp;</td>
	</tr>						
		<?php $sec = 0;	} 
			if($row_dept['dept'] <> $deptm AND $deptm <> "" ){ ?>
    <tr>
		<td>&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;</td>
		<td>Dept</td>
		<td><?php echo  $deptm .":" ?></td>
		<td>&nbsp;&nbsp;</td>
		<td><?php echo  " N " . $dep; ?></td>
	</tr>						
		<?php $dep = 0;	} 
?>	
		
    <tr>
      <td><?php echo $row_dept['dept']; ?></td>
	  		<?php $dep = $dep + $row_dept['amtpaid'];?>
	  
      <td><?php echo $row_dept['section']; ?></td>
	  		<?php $sec = $sec + $row_dept['amtpaid'];?>
			
      <td><?php echo $row_dept['name']; ?></td>
      <td><?php echo $row_dept['amtpaid']; ?> </td>
	  		<?php $deptm = $row_dept['dept'];
				  $sectn = $row_dept['section'];?>
			
    </tr>
    <?php } while ($row_dept = mysql_fetch_assoc($dept)); ?>
    <tr>
		<td>&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;</td>
		<td>Section</td>
		<td><?php echo $sectn .":"; ?></td>
		<td><?php echo " N " . $sec; ?></td>
		<td>&nbsp;&nbsp;</td>
	</tr>						
    <tr>
		<td>&nbsp;&nbsp;</td>
		<td>&nbsp;&nbsp;</td>
		<td>Dept</td>
		<td><?php echo  $deptm .":" ?></td>
		<td>&nbsp;&nbsp;</td>
		<td><?php echo  " N " . $dep; ?></td>
	</tr>						
</table>
<?php
mysql_free_result($dept);
 }?>
</body>
</html>
