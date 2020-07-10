<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
// set gender default and display 'All' if $gender = '%';
		if(!isset($_POST['gender'])) {
			$gender = '%'; 
			$genderdisplay = 'All';
			} else {
				if($_POST['gender'] == '%'){ 
				  $gender = $_POST['gender']; 
				  $genderdisplay = 'All'; 
				} else {
				  $gender = $_POST['gender']; 
				  $genderdisplay = $_POST['gender'];}
			}
//		echo $_POST['gender']."<br>";
//		echo $gender;
//		exit;

// set default sorting
		if(!isset($_POST['sort'])) {
				$sort = 'returndt';	
			} else {
				$sort = $_POST['sort'];
			}		
			
// Set $loc_list to list of all locations		
if(!isset($_POST['loc_list'])) {
$loc_list = "";
mysql_select_db($database_swmisconn, $swmisconn);
$query_location = "SELECT name, fee FROM fee WHERE Active = 'Y' and dept = 'Records' and section != 'Registration' ORDER BY name ASC";
$location = mysql_query($query_location, $swmisconn) or die(mysql_error());
$row_location = mysql_fetch_assoc($location);
$totalRows_location = mysql_num_rows($location);

 do { 
 // ad locations to list
 		$loc_list = $loc_list."'". $row_location['name']."'," ;
     } while ($row_location = mysql_fetch_assoc($location)); 
// remove ending comma		 
    $loc_list = rtrim($loc_list, ','); 
			} else { 
// create list from multiselect
			$loc_list = "'" . implode("', '", $_POST['loc_list']) . "'";
			}
//  test local list content			
//echo $loc_list;
//exit;

		if(isset($_POST['scheddt_0'])) {
		// get timestamp for posted variable - this takes unformattted input and creates a timestamp number
			$datebs = strtotime($_POST['scheddt_0']);
			$datees = strtotime($_POST['scheddt_1']);
		// put date in format for mysql and for display
			$date1 = date("Y-m-d", $datebs);
			$date2 = date("Y-m-d", $datees);
		} else {
			$date1 = date('Y-m-d'); // current date
			$date2 = strtotime('+3 month' , strtotime ( $date1 ) ) ; //date 3 months from nmow
			$date2 = date('Y-m-d' , $date2 );  //format date2
		}
//list of return dates
mysql_select_db($database_swmisconn, $swmisconn);
$query_returndt = "Select returndt, DATE_FORMAT(v.returndt, '%b %d, %Y, %a') returndtDISP, DATE_FORMAT(v.returndt, '%Y-%m-%d') returndtMYD, p.medrecnum, p.lastName, p.firstName, p.otherName, p.gender, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y') AS age, v.id, v.pat_type, v.location, v.discharge, v.diagnosis, v.returnloc, v.entryby, v.entrydt from patperm p join patvisit v on p.medrecnum = v.medrecnum where p.gender like '".$gender."' and  v.returndt BETWEEN '". $date1 . "' AND '" . $date2 ."' and v.returnloc in (".$loc_list.") order by ".$sort."";
$returndt = mysql_query($query_returndt, $swmisconn) or die(mysql_error());
$row_returndt = mysql_fetch_assoc($returndt);
$totalRows_returndt = mysql_num_rows($returndt);

// list of unique dates
mysql_select_db($database_swmisconn, $swmisconn);
$query_uniquedt = "Select DISTINCT v.returndt, DATE_FORMAT(v.returndt, '%b %d, %Y, %a') returndtDISP, DATE_FORMAT(v.returndt, '%Y-%m-%d') returndtYMD from patperm p join patvisit v on p.medrecnum = v.medrecnum where p.gender like '".$gender."' and  v.returndt BETWEEN '". $date1 . "' AND '" . $date2 ."' and v.returnloc in (".$loc_list.") order by ".$sort."";
$uniquedt = mysql_query($query_uniquedt, $swmisconn) or die(mysql_error());
$row_uniquedt = mysql_fetch_assoc($uniquedt);
$totalRows_uniquedt = mysql_num_rows($uniquedt);


?>
<?php
// list of locations for dropdown list 
mysql_select_db($database_swmisconn, $swmisconn);
$query_location2 = "SELECT name, fee FROM fee WHERE Active = 'Y' and dept = 'Records' and section != 'Registration' ORDER BY name ASC";
$location2 = mysql_query($query_location2, $swmisconn) or die(mysql_error());
$row_location2 = mysql_fetch_assoc($location2);
$totalRows_location2 = mysql_num_rows($location2);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../CSS/Level3_1.css" />
<title>Return Dates</title>
</head>

<body>
<p>&nbsp;</p>

<div align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../Home/index.php"><span class="navLink">Home</span> </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span class="GreenBold_24">RETURN VISIT LIST</span></div></div>

<table width="40%" border="1" cellpadding="2" cellspacing="2" align="center">
  <tr>
    <td>
       <form id="form1" name="form1" action="" method="POST">  <!-- form for selection criteria-->
       <table cellpadding="2" cellspacing="2">
		  <tr>
		    <td valign="top">Begin:</td> <!-- Go to 'http://www.nogray.com/api/calendar.php' for parameters -->

          <td valign="top"><input name="scheddt_0" type="text" class="BlackBold_12" id="scheddt_0" size="14" maxlength="18" value="<?php echo $date1 ?>"/><br><span class="BlackBold_10">(Default = Today)</span></td>
         
          <td align="right" valign="top" nowrap="nowrap">End:</td>

		    <td valign="top"><input name="scheddt_1" type="text" class="BlackBold_12" id="scheddt_1" size="14" maxlength="18" value="<?php echo $date2 ?>"/><br><span class="BlackBold_10">(Default = Today + 3 months</span>)</td>

			<td valign="top">Location:<br>Multiple<br>Select</td>
         
         <td valign="top"> <label for "loc_list"></label>
         	<select name="loc_list[]" size="6"  multiple="multiple">
         	  <?php						
							do {  
						?>
         	  <option value="<?php echo $row_location2['name']?>"<?php if (!(strcmp($loc_list, $row_location2['name']))) {echo "selected=\"selected\"";} ?>><?php echo $row_location2['name']?></option>
         	  <?php
					} while ($row_location2 = mysql_fetch_assoc($location2));
					  $rows = mysql_num_rows($location2);
					  if($rows > 0) {
							mysql_data_seek($location2, 0);
						  $row_location2 = mysql_fetch_assoc($location2);
					  }
					?>
            </select>

		    <td valign="bottom">Gender:</td>
		    <td valign="bottom"><label for="gender"></label>
		      <select name="gender" id="gender">
		        <option value="%" <?php if (!(strcmp("%", $gender))) {echo "selected=\"selected\"";} ?>>All</option>
		        <option value="F" <?php if (!(strcmp("F", $gender))) {echo "selected=\"selected\"";} ?>>F</option>
		        <option value="M" <?php if (!(strcmp("M", $gender))) {echo "selected=\"selected\"";} ?>>M</option>
	         </select></td>
		    <td valign="bottom">Sort<br>Lower<br>Table<br>By:</td>
          <td valign="bottom"><label for="sort"></label>
		      <select name="sort" id="sort">
		        <option value="returndt" <?php if (!(strcmp("sortdt", $sort))) {echo "selected=\"selected\"";} ?>>Return Date</option>
		        <option value="v.medrecnum" <?php if (!(strcmp("medrecnum", $sort))) {echo "selected=\"selected\"";} ?>>MRN</option>
		        <option value="lastname" <?php if (!(strcmp("lastName", $sort))) {echo "selected=\"selected\"";} ?>>Last Name</option>
		        <option value="gender" <?php if (!(strcmp("gender", $sort))) {echo "selected=\"selected\"";} ?>>Gender</option>
		        <option value="pat_type, location" <?php if (!(strcmp("location", $sort))) {echo "selected=\"selected\"";} ?>>Location</option>
	         </select>
          </td>
		    <td valign="bottom"><input type="submit" name="GO" id="GO" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="GO" /></td>
		  </tr>
   </table>

       
       
       </form>
    </td>
  </tr>
</table>
<!--Begin Summary Chart-->

<!--Section Title-->
<div style="font-weight: bold; text-align: center;">
<h1> Number of return vists by date and location <?php echo date('M-d-Y') ?></h1>
</div>
<table border="3" align="center" style="border-collapse:collapse;">
	<tr bgcolor="#CCFF33"> <!--display header row-->
   	<td style="text-align: center">Return<br>Date</td>
      <td style="text-align: center">Ante<br>Follow</td>
      <td style="text-align: center">Ante<br>Booking</td>
      <td style="text-align: center">OutPat<br>Clinic</td>
      <td style="text-align: center">OutPat<br>Dental</td>
      <td style="text-align: center">OutPat<br>Spec</td>
      <td style="text-align: center">OutPat<br>Eye</td>
      <td style="text-align: center">OutPat<br>Circum</td>
      <td style="text-align: center">InPat<br>Female</td>
      <td style="text-align: center">InPat<br>Male</td>
      <td style="text-align: center">InPat<br>Amenity</td>
      <td style="text-align: center">InPat<br>Labour</td>
      <td style="text-align: center">InPat<br>Postnat</td>
      <td style="text-align: center">InPat<br>Neonat</td>
      <td style="text-align: center">InPat<br>Isolat</td>
      <td style="text-align: center">Surg</td>
      <td style="text-align: center">Lab</td>
      <td style="text-align: center">PT</td>
      <td style="text-align: center">Med<br>Cnslt</td>
      <td style="text-align: center">Spirit<br>Cnslt</td>
   </tr>
	<tr>
<?php do { // for each unique return date  ?>
	 <?php //initialize variables
    $OutClin = 0;
    $OutDent = 0;
    $OutSpec = 0;
    $OutEye = 0;
    $OutCircum = 0;
    $AntFoll = 0;
    $AntBook = 0;
    $InWdFe = 0;
    $InWdMa = 0;
    $InAmen = 0;
    $InLabour = 0;
    $InPostnatal = 0;
    $InNeonat = 0;
    $InIsol = 0;
    $Surgery = 0;
    $Laboratory = 0;
    $PhysioTher = 0;
    $MedConsult = 0;
    $SpiritConsult = 0;
  ?>
<!--Display unique date-->
   	<td><?php echo $row_uniquedt['returndtYMD']; ?></td>
      
<?php // list of patient data, location and return dates for unique date so they can be counted
mysql_select_db($database_swmisconn, $swmisconn);
$query_sortdt = "Select v.returndt,  DATE_FORMAT(v.returndt, '%b %d, %Y, %a') returndtDISP, DATE_FORMAT(v.returndt, '%Y-%m-%d') returndtYMD, v.pat_type, v.location, v.returnloc FROM patperm p join patvisit v on p.medrecnum = v.medrecnum where p.gender like '".$gender."' and  v.returndt BETWEEN '". $date1 . "' AND '" . $date2 ."' and v.returnloc in (".$loc_list.") and DATE_FORMAT(v.returndt, '%Y-%m-%d') = '".$row_uniquedt['returndtYMD']."' order by returndt";
$sortdt = mysql_query($query_sortdt, $swmisconn) or die(mysql_error());
$row_sortdt = mysql_fetch_assoc($sortdt);
$totalRows_sortdt = mysql_num_rows($sortdt);

?>      
		  <?php do {  //sum of patients for a return date ?>
                  <?php if($row_sortdt['returnloc'] == "AnteFollowUp"){ $AntFoll = $AntFoll + 1;} ?>
									<?php if($row_sortdt['returnloc'] == "Booking "){ $AntBook = $AntBook + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "OPD Clinic"){ $OutClin = $OutClin + 1;}?>  <!--note OdpClinic + space-->
                  <?php if($row_sortdt['returnloc'] == "Dental Clinic"){ $OutDent = $OutDent + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Specialist Clinic"){ $OutSpec = $OutSpec + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Eye Clinic"){ $OutEye = $OutEye + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Circumcision "){ $OutCircum = $OutCircum + 1;}?><!--note Circumcision + space-->
                  <?php if($row_sortdt['returnloc'] == "Female Ward"){ $InWdFe = $InWdFe + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Male Ward"){ $InWdMa = $InWdMa + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Amenity Ward"){ $InAmen = $InAmen + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Labour Ward"){ $InLabour = $InLabour + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Postnatal Ward"){ $InPostnatal = $InPostnatal + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Neonatal Ward"){ $InNeonat = $InNeonat + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Isolation Ward"){ $InIsol = $InIsol + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Surgery"){ $Surgery = $Surgery + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "Laboratory"){ $Laboratory = $Laboratory + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "PhysioTherapy"){ $PhysioTher = $PhysioTher + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "MedicalConsult"){ $MedConsult = $MedConsult + 1;}?>
                  <?php if($row_sortdt['returnloc'] == "SpiritualConsult"){ $SpiritConsult = $SpiritConsult + 1;}?>
    		<?php } while ($row_sortdt = mysql_fetch_assoc($sortdt)); ?>

<!--Display sums for each location and unique date-->
		<td style="text-align: center"><?php if($AntFoll != 0){ echo $AntFoll ;}?></td>	
		<td style="text-align: center"><?php if($AntBook != 0){ echo $AntBook ;}?></td>	
		<td style="text-align: center"><?php if($OutClin != 0){ echo $OutClin ;}?></td>	
		<td style="text-align: center"><?php if($OutDent != 0){ echo $OutDent ;}?></td>	
		<td style="text-align: center"><?php if($OutSpec != 0){ echo $OutSpec ;}?></td>	
		<td style="text-align: center"><?php if($OutEye != 0){ echo $OutEye ;}?></td>	
		<td style="text-align: center"><?php if($OutCircum != 0){ echo $OutCircum ;}?></td>	
		<td style="text-align: center"><?php if($InWdFe != 0){ echo $InWdFe ;}?></td>	
		<td style="text-align: center"><?php if($InWdMa != 0){ echo $InWdMa ;}?></td>	
		<td style="text-align: center"><?php if($InAmen != 0){ echo $InAmen ;}?></td>	
		<td style="text-align: center"><?php if($InLabour != 0){ echo $InLabour ;}?></td>	
		<td style="text-align: center"><?php if($InPostnatal != 0){ echo $InPostnatal ;}?></td>	
		<td style="text-align: center"><?php if($InNeonat != 0){ echo $InNeonat ;}?></td>	
		<td style="text-align: center"><?php if($InIsol != 0){ echo $InIsol ;}?></td>	
		<td style="text-align: center"><?php if($Surgery){ echo $Surgery ;}?></td>	
		<td style="text-align: center"><?php if($Laboratory){ echo $Laboratory ;}?></td>	
		<td style="text-align: center"><?php if($PhysioTher){ echo $PhysioTher ;}?></td>	
		<td style="text-align: center"><?php if($MedConsult){ echo $MedConsult ;}?></td>	
		<td style="text-align: center"><?php if($SpiritConsult){ echo $SpiritConsult ;}?></td>	
  </tr>		
    <?php } while ($row_uniquedt = mysql_fetch_assoc($uniquedt)); ?>
</table>   









<?php if($gender = '%'){$showgender = 'All'; } else{ $showgender = $row_returndt['gender'];} ?>
<?php if($sort = 'sortdt'){$showsort = 'Return Date'; } else { $showsort = $row_returndt['sort'];} ?>
<p>&nbsp;</p>
<table border="1" cellpadding="4" cellspacing="1" style="border-collapse:collapse;" align="center">
	<caption align="center"><h1> Return Dates</h1>
	<p>&nbsp;</p>
	</caption>
<caption style="font-size:12px; font-weight: bold;" align="center"> <?php  print_r(" Selected Locations= ".$loc_list."<br> Selected Gender= ".$showgender."...... Selected Sort By= ".$showsort); ?></caption>
  <tr bgcolor="#CCFF33" align="center">
    <td>Return Date</td>
    <td>Return Location</td>
    <td>MRN</td>
    <td>Name</td>
    <td>Gender</td>
    <td>Age</td>
    <td>Discharged</td>
    <td>Patient Type</td>
    <td>From Location</td>
    <td>Diagnosis</td>
    <td>Entry By</td>
    <td>Entry Date</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_returndt['returndt']; ?><?php //echo $row_returndt['sortdt']; ?></td>
      <td><?php echo $row_returndt['returnloc']; ?></td>
      <td align="center" title ="Returndt ID: <?php echo $row_returndt['id']; ?>"><?php echo $row_returndt['medrecnum']; ?></td>
      <td><?php echo $row_returndt['lastName'].", ".$row_returndt['firstName']." ".$row_returndt['otherName']; ?></td>
      <td align="center"><?php echo $row_returndt['gender']; ?></td>
      <td><?php echo $row_returndt['age']; ?></td>
      <td><?php echo $row_returndt['discharge']; ?></td>
      <td><?php echo $row_returndt['pat_type']; ?></td>
      <td><?php echo $row_returndt['location']; ?></td>
      <td><?php echo $row_returndt['diagnosis']; ?></td>
      <td><?php echo $row_returndt['entryby']; ?></td>
      <td><?php echo $row_returndt['entrydt']; ?></td>
    </tr>

    <?php } while ($row_returndt = mysql_fetch_assoc($returndt)); ?>
</table>
<script type="text/javascript" src="../../nogray_js/1.2.2/ng_all.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/calendar.js"></script>
<script type="text/javascript" src="../../nogray_js/1.2.2/components/timepicker.js"></script>
<script type="text/javascript">

//ng.Calendar.set_date_format ('Y-m-d');

ng.ready( function() {
	    var my_cal = new ng.Calendar({
        input:'scheddt_0',
		  start_date: '01-01-2019',
//		  display_date: new Date()   // the display date (default is start_date)
    });
  
	    var my_cal = new ng.Calendar({
        input:'scheddt_1',
//		  start_date: '01-01-2014',
//		  display_date: new Date()   // the display date (default is start_date)
    });
});

</script>

</body>

</html>
<?php
mysql_free_result($returndt);
?>
