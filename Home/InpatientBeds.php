<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php $pt = "InpatientBeds"; ?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
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
if(!isset($_SESSION['location'])){
$_SESSION['location'] = "%";
} elseif (isset($_POST['location'])  && strlen($_POST['location'])>0 && ($_POST["MM_update"] == "form3")) {
$_SESSION['location'] = (get_magic_quotes_gpc()) ? $_POST['location'] : addslashes($_POST['location']);	
}

// check for discharged patients
mysql_select_db($database_swmisconn, $swmisconn);
$query_Pat_Disch = "SELECT b.id bid, b.feeid, f.name, b.bed, b.medrecnum, p.lastname, p.firstname, p.othername, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, p.dob)),'%y') AS age, p.gender, v.id vid, v.discharge, b.active, b.entryby, b.entrydt FROM patbed b JOIN patperm p ON b.medrecnum=p.medrecnum JOIN patvisit v ON p.medrecnum = v.medrecnum JOIN fee f ON b.feeid=f.id WHERE v.id = (SELECT max(v2.id) FROM patvisit v2 WHERE v.medrecnum = v2.medrecnum) ORDER BY b.feeid, b.bed;";
$Pat_Disch = mysql_query($query_Pat_Disch, $swmisconn) or die(mysql_error());
$row_Pat_Disch = mysql_fetch_assoc($Pat_Disch);
$totalRows_Pat_Disch = mysql_num_rows($Pat_Disch);

do {
	//echo $row_Pat_Disch['medrecnum'].'....   '. $row_Pat_Disch['vid'].'....   '. $row_Pat_Disch['discharge'].'....Bed:'.$row_Pat_Disch['bed'].'<br>';
	
	if(!empty($row_Pat_Disch['discharge'])){
    $insertSQL = sprintf("UPDATE patbed SET medrecnum=%s WHERE id=%s",
                       GetSQLValueString('', "text"),
                       GetSQLValueString($row_Pat_Disch['bid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
		}	
} while ($row_Pat_Disch = mysql_fetch_assoc($Pat_Disch));

//exit;
 
// set variable $show from $_GET['show'] in URL
$show='patinbed';
if(isset($_GET['show'])){
	$show = $_GET['show'];
} else {
if(isset($_POST['list'])) {
	$show = $_POST['list'];
	}
}

//echo $show;
//exit;

// list of patients in beds
if($show == 'patinbed'){
mysql_select_db($database_swmisconn, $swmisconn);
$query_Pat_Inbed = "SELECT b.id, b.feeid, f.name, b.bed, b.medrecnum, p.lastname, p.firstname, p.othername, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, p.dob)),'%y') AS age, p.gender, b.active, b.entryby, b.entrydt, f.section, f.name, v.visitdate FROM patbed b JOIN patperm p ON b.medrecnum=p.medrecnum JOIN fee f ON b.feeid=f.id JOIN patvisit v on b.medrecnum = v.medrecnum  WHERE v.id = (Select MAX(pv.id) FROM patvisit pv where pv.medrecnum = b.medrecnum) and f.name like '".$_SESSION['location']."%' ORDER BY b.feeid, b.bed;";
$Pat_Inbed = mysql_query($query_Pat_Inbed, $swmisconn) or die(mysql_error());
$row_Pat_Inbed = mysql_fetch_assoc($Pat_Inbed);
$totalRows_Pat_Inbed = mysql_num_rows($Pat_Inbed);
}
// list of inpatients not in beds
if($show == 'patnotinbed'){
mysql_select_db($database_swmisconn, $swmisconn);
$query_PatNotInbed = "SELECT DISTINCT p.medrecnum, p.lastname, p.firstname, p.othername, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, p.dob)),'%y') AS age, p.gender, f.name location, v.discharge, v.visitdate FROM patperm p JOIN patvisit v on p.medrecnum = v.medrecnum JOIN fee f on f.id = v.vfeeid WHERE f.section = 'InPatient' AND v.discharge IS NULL AND NOT EXISTS (SELECT b.medrecnum from patbed b where b.medrecnum =  p.medrecnum)  ORDER BY v.visitdate DESC";
$PatNotInbed = mysql_query($query_PatNotInbed, $swmisconn) or die(mysql_error());
$row_PatNotInbed = mysql_fetch_assoc($PatNotInbed);
$totalRows_PatNotInbed = mysql_num_rows($PatNotInbed);
}
// list of empty beds
if($show == 'emptybeds'){
mysql_select_db($database_swmisconn, $swmisconn);
$query_emptybeds = "SELECT f.name, b.bed, IFNULL(p.medrecnum,'EMPTY') as STATUS FROM patbed b JOIN fee f on b.feeid = f.id LEFT OUTER JOIN PatPerm p on b.medrecnum = p.medrecnum WHERE p.medrecnum IS NULL AND b.active = 'Y' AND f.name like '".$_SESSION['location']."%' ORDER BY f.name, b.bed";
$emptybeds = mysql_query($query_emptybeds, $swmisconn) or die(mysql_error());
$row_emptybeds = mysql_fetch_assoc($emptybeds);
$totalRows_emptybeds = mysql_num_rows($emptybeds);
}
// list of all beds empty and full
if($show == 'allbeds'){
mysql_select_db($database_swmisconn, $swmisconn);
$query_allbeds = "SELECT f.name, b.bed, IFNULL(p.medrecnum,'') as MRN, IFNULL(p.LastName,'') as LastName,	IFNULL(p.Firstname,' ') as FirstName FROM patbed b JOIN fee f on b.feeid = f.id LEFT OUTER  JOIN PatPerm p on b.medrecnum = p.medrecnum WHERE f.section = 'InPatient' AND b.active = 'Y' AND f.name like '".$_SESSION['location']."%' ORDER BY f.name, b.bed";
$allbeds = mysql_query($query_allbeds, $swmisconn) or die(mysql_error());
$row_allbeds = mysql_fetch_assoc($allbeds);
$totalRows_allbeds = mysql_num_rows($allbeds);
}
// list of inpatient locations for dropdown
mysql_select_db($database_swmisconn, $swmisconn);
$query_loc = "SELECT f.section, f.name FROM fee f Where f.active = 'Y' and section = 'InPatient' ORDER BY f.name";
$loc= mysql_query($query_loc, $swmisconn) or die(mysql_error());
$row_loc= mysql_fetch_assoc($loc);
$totalRows_loc= mysql_num_rows($loc);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../CSS/Level3_1.css"/>
<title>InPatient Beds</title>
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>

<body>
<h1 align="center">Select INPATIENTS BEDS List</h1>
<table align='center' width= '50%' bgcolor='#fffdda'>
  <form id="form3" name="form3" method="post" action="InpatientBeds.php" >
  <tr>
  	<td width='10%' nowrap><a href="InpatientBeds.php?show=patinbed" title="PATIENTS IN BEDS">PATIENTS IN BEDS</a></td>
  	<td width='10%' nowrap><a href="InpatientBeds.php?show=patnotinbed" title="PATIENTS NOT IN BEDS">PATIENTS NOT IN BEDS</a></td>
  	<td width='10%' nowrap><a href="InpatientBeds.php?show=emptybeds" title="EMPTY BEDS">EMPTY BEDS</a></td>
  	<td width='10%' nowrap><a href="InpatientBeds.php?show=allbeds" title="ALL BEDS">ALL BEDS</a></td>
  	<td width='10%' nowrap>
    		<select name="location" id="location" onChange="document.form3.submit();">
                 <option value="%">ALL</option>
              <?php do {  ?>
      					<option value="<?php echo $row_loc['name']?>"<?php if (!(strcmp($row_loc['name'], $_SESSION['location']))) {echo "selected=\"selected\"";} ?>><?php echo $row_loc['section']?> - <?php echo $row_loc['name']?></option>
      				<?php }
							 while ($row_loc = mysql_fetch_assoc($loc));
								$rows = mysql_num_rows($loc);
								if($rows > 0) {
										mysql_data_seek($loc, 0);
									$row_loc = mysql_fetch_assoc($loc);
								} ?>
				</select></td>
  </tr>
    <input type="hidden" name="MM_update" value="form3" />
  	<input id="list" name="list" type="hidden" value="<?php echo $show; ?>" />
  </form>
</table>
<p></p>
<p></p>
<?php if($show=='patinbed'){ ?>
<h1 align="center">LIST OF INPATIENTS IN BED </h1>
<table border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:Collapse">
   <tr bgcolor="#999999">
      <td class="BlackBold_18">MRN</td>
      <td class="BlackBold_18">Last Name</td>
      <td class="BlackBold_18">First Name</td>
      <td class="BlackBold_18">Other Name</td>
      <td class="BlackBold_18">Age</td>      
     <td class="BlackBold_18">Sex</td>
      <td class="BlackBold_18">Ward</td>
      <td class="BlackBold_18">Bed</td>
      <td class="BlackBold_18">VisitDate</td>
   </tr>
   <?php do { 
		if($row_Pat_Inbed['name'] == 'Postnatal Ward') {
			$bkg = "#DAEDFF";	
	} elseif($row_Pat_Inbed['name'] == 'Neonatal Ward') {
			$bkg = "#6699cc";	
	} elseif($row_Pat_Inbed['name'] == 'Male Ward') {
			$bkg = '#d3d6ff';	
	} elseif($row_Pat_Inbed['name'] == 'Female Ward') {
			$bkg = '#f4d531';	
	} elseif($row_Pat_Inbed['name'] == 'Amenity Ward') {
			$bkg = '#E0FF84';	
	} elseif($row_Pat_Inbed['name'] == 'Isolation Ward') {
			$bkg = '#F2BCD7';	
	} elseif($row_Pat_Inbed['name'] == 'Labour Ward') {
			$bkg = '#FFFF00';	
	} else {
		$bkg = '#FFFFFF';
	}
	 
	 
	 
	 ?>
      <tr bgcolor="#FF6633">
         <td bgcolor=<?php echo "$bkg"?>><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_Pat_Inbed['medrecnum']; ?>"><?php echo $row_Pat_Inbed['medrecnum']; ?></a></strong></td>
         <td bgcolor=<?php echo "$bkg"?>><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_Pat_Inbed['medrecnum']; ?>"><?php echo $row_Pat_Inbed['lastname']; ?></a></strong></td>
         <td bgcolor=<?php echo "$bkg"?>><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_Pat_Inbed['medrecnum']; ?>"><?php echo $row_Pat_Inbed['firstname']; ?></a></strong></td>         
         <td bgcolor=<?php echo "$bkg"?>><?php echo $row_Pat_Inbed['othername']; ?></td>              
         <td bgcolor=<?php echo "$bkg"?> align="center"><?php echo $row_Pat_Inbed['age']; ?></td>
        <td bgcolor=<?php echo "$bkg"?> align="center"><?php echo $row_Pat_Inbed['gender']; ?></td>
        <td bgcolor=<?php echo "$bkg"?> title="id=<?php echo $row_Pat_Inbed['id']; ?>&#10;feeid=<?php echo $row_Pat_Inbed['feeid']; ?>&#10;active=<?php echo $row_Pat_Inbed['active']; ?>&#10;entryby=<?php echo $row_Pat_Inbed['entryby']; ?>&#10;entrydt=<?php echo $row_Pat_Inbed['entrydt']; ?>"><?php echo $row_Pat_Inbed['name']; ?></td>
         <td bgcolor=<?php echo "$bkg"?> title="id=<?php echo $row_Pat_Inbed['id']; ?>&#10;feeid=<?php echo $row_Pat_Inbed['feeid']; ?>&#10;active=<?php echo $row_Pat_Inbed['active']; ?>&#10;entryby=<?php echo $row_Pat_Inbed['entryby']; ?>&#10;entrydt=<?php echo $row_Pat_Inbed['entrydt']; ?>"><?php echo $row_Pat_Inbed['bed']; ?></td>
         <td bgcolor=<?php echo "$bkg"?> title="id=<?php echo $row_Pat_Inbed['id']; ?>&#10;feeid=<?php echo $row_Pat_Inbed['feeid']; ?>&#10;active=<?php echo $row_Pat_Inbed['active']; ?>&#10;entryby=<?php echo $row_Pat_Inbed['entryby']; ?>&#10;entrydt=<?php echo $row_Pat_Inbed['entrydt']; ?>"><?php echo $row_Pat_Inbed['visitdate']; ?></td>

      </tr>
      <?php } while ($row_Pat_Inbed = mysql_fetch_assoc($Pat_Inbed)); ?>
</table>
<?php } ?>

<?php if($show=='patnotinbed'){ ?>
<h1 align="center"> Patient Not in Bed </h1>
<p align="center">&nbsp;</p>
<table align="center" border="1" cellpadding="1" cellspacing="1" style="border-collapse:Collapse">
  <tr>
    <td>medrecnum</td>
    <td>lastname</td>
    <td>firstname</td>
    <td>age</td>
    <td>gender</td>
    <td>location</td>
    <td>discharged</td>
    <td>visitdate</td>
  </tr>
  <?php do { ?> 
    <tr>  
      <td><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_PatNotInbed['medrecnum']; ?>"><?php echo $row_PatNotInbed['medrecnum']; ?></a></strong></td>
      <td><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_PatNotInbed['medrecnum']; ?>"><?php echo $row_PatNotInbed['lastname']; ?></a></strong></td>
      <td><strong><a href="../Patient/PatShow1.php?mrn=<?php echo $row_PatNotInbed['medrecnum']; ?>"><?php echo $row_PatNotInbed['firstname']; ?></a></strong></td>
      <td><?php echo $row_PatNotInbed['age']; ?></td>
      <td><?php echo $row_PatNotInbed['gender']; ?></td>
      <td><?php echo $row_PatNotInbed['location']; ?></td>
      <td><?php echo $row_PatNotInbed['discharge']; ?></td>
      <td><?php echo $row_PatNotInbed['visitdate']; ?></td>
    </tr>
    <?php } while ($row_PatNotInbed = mysql_fetch_assoc($PatNotInbed)); ?>
</table>
<?php } ?>

<?php if($show=='emptybeds'){ ?>
<h1 align="center"> EMPTY Beds </h1>
<p align="center">&nbsp;</p>
<table border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:Collapse">
  <tr>

    <td>name</td>
    <td>bed</td>
    <td>STATUS</td>
  </tr>
  <?php do { 
		if($row_emptybeds['name'] == 'Postnatal Ward') {
			$bkg = "#DAEDFF";	
	} elseif($row_emptybeds['name'] == 'Neonatal Ward') {
			$bkg = "#6699cc";	
	} elseif($row_emptybeds['name'] == 'Male Ward') {
			$bkg = '#d3d6ff';	
	} elseif($row_emptybeds['name'] == 'Female Ward') {
			$bkg = '#f4d531';	
	} elseif($row_emptybeds['name'] == 'Amenity Ward') {
			$bkg = '#E0FF84';	
	} elseif($row_emptybeds['name'] == 'Isolation Ward') {
			$bkg = '#F2BCD7';	
	} elseif($row_emptybeds['name'] == 'Labour Ward') {
			$bkg = '#FFFF00';	
	} else {
		$bkg = '#FFFFFF';
	}

	?>
    <tr>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_emptybeds['name']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_emptybeds['bed']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_emptybeds['STATUS']; ?></td>
    </tr>
    <?php } while ($row_emptybeds = mysql_fetch_assoc($emptybeds)); ?>
</table>
<?php } ?>

<?php if($show=='allbeds'){ ?>
<h1 align="center"> ALL Beds </h1>
<p align="center">&nbsp;</p>
<table border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:Collapse" >
  <tr>
    <td align="center">Location</td>
    <td align="center">Bed</td>
    <td align="center">MRN</td>
    <td align="center">Last Name</td>
    <td align="center">First Name</td>
  </tr>
  <?php do { 
			if($row_allbeds['name'] == 'Postnatal Ward') {
			$bkg = "#DAEDFF";	
	} elseif($row_allbeds['name'] == 'Neonatal Ward') {
			$bkg = "#6699cc";	
	} elseif($row_allbeds['name'] == 'Male Ward') {
			$bkg = '#d3d6ff';	
	} elseif($row_allbeds['name'] == 'Female Ward') {
			$bkg = '#f4d531';	
	} elseif($row_allbeds['name'] == 'Amenity Ward') {
			$bkg = '#E0FF84';	
	} elseif($row_allbeds['name'] == 'Isolation Ward') {
			$bkg = '#F2BCD7';	
	} elseif($row_allbeds['name'] == 'Labour Ward') {
			$bkg = '#FFFF00';	
	} else {
		$bkg = '#FFFFFF';
	}
	?>
    <tr>
      <td bgcolor=<?php echo "$bkg"?> ><?php echo $row_allbeds['name']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_allbeds['bed']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_allbeds['MRN']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_allbeds['LastName']; ?></td>
      <td bgcolor=<?php echo "$bkg"?>><?php echo $row_allbeds['FirstName']; ?></td>
    </tr>
    <?php } while ($row_allbeds = mysql_fetch_assoc($allbeds)); ?>
</table>
<p align="center">&nbsp;</p>
S

<?php } ?>

</body>
</html>
<?php
if(isset($show) && $show=='patinbed') {
	mysql_free_result($Pat_Inbed);
} elseif(isset($show) && $show=='patnotinbed') { 
  mysql_free_result($PatNotInbed);
} else if(isset($show) && $show=='emptybeds') { 
  mysql_free_result($emptybeds);
} else if(isset($show) && $show=='allbeds') { 
mysql_free_result($allbeds);
}
// elseif(isset($show) && $show='patnotinbed') { 
//  mysql_free_result($PatNotInbed);
//}


?>
