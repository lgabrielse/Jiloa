<!--AutoDischarge patients using microspft scheduler
This program is initiated by AutoDisch_Bethany.bat which is run by Win 7u Scheduler task AutoDischBethany
Step 1: Discharge undischarged patents that have diagnosis and lab tests are done
Step 2: Discharge undischarged patients that have diagnosis and VisitDate is > 2 days from current date
Step 3: Discharge patients where discharge is null and diagnosis is null and current date is > 7 days after visitdate.
           If diagnosis is blank, enter 'No Diagnosis  Documented'

  > 2 days from current date, when run in early morning hours means; if autodischarge run is the friday, the 5th of the month, 
     if visit date is before Wednesday, the 3rd, i.e Tuesday, the 2nd or before, the discharge will be done

-->
<?php if(!function_exists("GetSQLValueString")) {
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
}?>
<?php   //Open a new connection to the MySQL server
$mysqli = new mysqli('localhost','root','jiloa7','swmisbethany');

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>

<!--Step 1: Discharge undischarged patents that have diagnosis and lab tests are done-->
<?php
///MySqli Select Query
$results1 = $mysqli->query("select v.id from patvisit v where discharge is null and diagnosis is not null and v.pat_type in ('OutPatient', 'Antenatal') and (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id) = (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) and DATEDIFF(CURRENT_DATE,v.visitdate) > 14");

while($row1 = $results1->fetch_assoc()) {
//MySqli Update Query
	$resultsupd1 = $mysqli->query("UPDATE patvisit SET discharge = '". Date('Y-m-d H:i:s')."' WHERE id = '".$row1['id']."'");
	if($resultsupd1){
		$myvisitid = $row1['id'];
		print 'Success! record updated   1: '.$row1['id'];
	}else{
		print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
	}  //end $resultsupd1
	
$resultsq1 = $mysqli->query("select v.id vid, v.visitdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge discharged, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id) labcnt, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone from patvisit v join patperm p ON p.medrecnum = v.medrecnum where v.id = '".$myvisitid."'");
	
while($rowq1 = $resultsq1->fetch_assoc()) {
//values to be inserted in database table
	$vid = '"'.$mysqli->real_escape_string($rowq1['vid']).'"';
	$visitdate = '"'.$mysqli->real_escape_string($rowq1['visitdate']).'"';
	$status = '"'.$mysqli->real_escape_string($rowq1['status']).'"';
	$pat_type = '"'.$mysqli->real_escape_string($rowq1['pat_type']).'"';
	$location = '"'.$mysqli->real_escape_string($rowq1['location']).'"';
	$urgency = '"'.$mysqli->real_escape_string($rowq1['urgency']).'"';
	$visitreason = '"'.$mysqli->real_escape_string($rowq1['visitreason']).'"';
	$diagnosis = '"'.$mysqli->real_escape_string($rowq1['diagnosis']).'"';
	$discharged = '"'.$mysqli->real_escape_string($rowq1['discharged']).'"';
	$labcnt = '"'.$mysqli->real_escape_string($rowq1['labcnt']).'"';
	$labdone = '"'.$mysqli->real_escape_string($rowq1['labdone']).'"';
	$qry = '"'.$mysqli->real_escape_string(1).'"';

//MySqli Insert Query
$insert_row = $mysqli->query("INSERT INTO autodischarged (visitid, visitdate, status, pat_type, location, urgency, visitreason, diagnosis, discharged, labcnt, labdone, qry) VALUES ($vid, $visitdate, $status, $pat_type, $location, $urgency, $visitreason, $diagnosis, $discharged, $labcnt, $labdone, $qry)");

	if($insert_row){
		print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />';
	}else{
		die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}
  }	//end $resultsq1		
  $resultsq1->free(); // Frees the memory associated with a result
}  //end $results1
$results1->free(); // Frees the memory associated with a result
//$mysqli->close();// close connection
?>


<!--Step 2: Discharge undischarged patients that have diagnosis and VisitDate is > 2 days from current date-->

<?php
$results2 = $mysqli->query("select v.id from patvisit v join patperm p ON p.medrecnum = v.medrecnum where discharge is null and diagnosis is not null and v.pat_type in ('OutPatient', 'Antenatal') and DATEDIFF(CURRENT_DATE,v.visitdate) > 14 ");

while($row2 = $results2->fetch_assoc()) {
//MySqli Update Query
	$resultsupd2 = $mysqli->query("UPDATE patvisit SET discharge = '". Date('Y-m-d H:i:s')."' WHERE id = '".$row2['id']."'");
	if($resultsupd2){
		$myvisitid2 = $row2['id'];
		print 'Success! record updated   2: '.$row2['id'];
	}else{
		print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
	}  //end $resultsupd2

$resultsq2 = $mysqli->query("select v.id vid, v.visitdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge discharged, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id) labcnt, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone from patvisit v join patperm p ON p.medrecnum = v.medrecnum where v.id = '".$myvisitid2."'");

while($rowq2 = $resultsq2->fetch_assoc()) {
//values to be inserted in database table
	$vid = '"'.$mysqli->real_escape_string($rowq2['vid']).'"';
	$visitdate = '"'.$mysqli->real_escape_string($rowq2['visitdate']).'"';
	$status = '"'.$mysqli->real_escape_string($rowq2['status']).'"';
	$pat_type = '"'.$mysqli->real_escape_string($rowq2['pat_type']).'"';
	$location = '"'.$mysqli->real_escape_string($rowq2['location']).'"';
	$urgency = '"'.$mysqli->real_escape_string($rowq2['urgency']).'"';
	$visitreason = '"'.$mysqli->real_escape_string($rowq2['visitreason']).'"';
	$diagnosis = '"'.$mysqli->real_escape_string($rowq2['diagnosis']).'"';
	$discharged = '"'.$mysqli->real_escape_string($rowq2['discharged']).'"';
	$labcnt = '"'.$mysqli->real_escape_string($rowq2['labcnt']).'"';
	$labdone = '"'.$mysqli->real_escape_string($rowq2['labdone']).'"';
	$qry = '"'.$mysqli->real_escape_string(2).'"';

//MySqli Insert Query
$insert_row = $mysqli->query("INSERT INTO autodischarged (visitid, visitdate, status, pat_type, location, urgency, visitreason, diagnosis, discharged, labcnt, labdone, qry) VALUES ($vid, $visitdate, $status, $pat_type, $location, $urgency, $visitreason, $diagnosis, $discharged, $labcnt, $labdone, $qry)");

	if($insert_row){
		print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />';
	}else{
		die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}
  }	//end $resultsq2		
  $resultsq2->free(); // Frees the memory associated with a result
}  //end $results2
$results2->free(); // Frees the memory associated with a result
//$mysqli->close();// close connection
?>

<!--Step3: discharge patients where discharge is null and diagnosis is null and current date is > 7 days after visitdate.
           If diagnosis is blank, enter 'No Diagnosis  Documented' --> 
<?php  
$results3 = $mysqli->query("select v.id from patvisit v join patperm p ON p.medrecnum = v.medrecnum where discharge is null and diagnosis is null and v.pat_type in ('OutPatient', 'Antenatal') and DATEDIFF(CURRENT_DATE,v.visitdate) > 14");
 
while($row3 = $results3->fetch_assoc()) {
//MySqli Update Query
	$resultsupd3 = $mysqli->query("UPDATE patvisit SET discharge = '". Date('Y-m-d H:i:s')."', diagnosis = 'No Diagnosis Documented' WHERE id = '".$row3['id']."'");
	if($resultsupd3){
		$myvisitid3 = $row3['id'];
		print 'Success! record updated   3: '.$row3['id'];
	}else{
		print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
	}  //end $resultsupd3

$resultsq3 = $mysqli->query("select v.id vid, v.visitdate, v.status, v.medrecnum, v.pat_type, v.location, v.urgency, substr(v.urgency,1,2) urg, v.visitreason, v.discharge discharged, v.diagnosis, v.entrydt ventrydt, v.entryby ventryby, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id) labcnt, (Select count(o.id) FROM orders o join fee f on o.feeid = f.id where f.dept = 'Laboratory' and o.visitid = v.id and o.status in ('Resulted', 'Refunded')) labdone from patvisit v join patperm p ON p.medrecnum = v.medrecnum where v.id = '".$myvisitid3."'");

while($rowq3 = $resultsq3->fetch_assoc()) {
//values to be inserted in database table
	$vid = '"'.$mysqli->real_escape_string($rowq3['vid']).'"';
	$visitdate = '"'.$mysqli->real_escape_string($rowq3['visitdate']).'"';
	$status = '"'.$mysqli->real_escape_string($rowq3['status']).'"';
	$pat_type = '"'.$mysqli->real_escape_string($rowq3['pat_type']).'"';
	$location = '"'.$mysqli->real_escape_string($rowq3['location']).'"';
	$urgency = '"'.$mysqli->real_escape_string($rowq3['urgency']).'"';
	$visitreason = '"'.$mysqli->real_escape_string($rowq3['visitreason']).'"';
	$diagnosis = '"'.$mysqli->real_escape_string($rowq3['diagnosis']).'"';
	$discharged = '"'.$mysqli->real_escape_string($rowq3['discharged']).'"';
	$labcnt = '"'.$mysqli->real_escape_string($rowq3['labcnt']).'"';
	$labdone = '"'.$mysqli->real_escape_string($rowq3['labdone']).'"';
	$qry = '"'.$mysqli->real_escape_string(3).'"';

//MySqli Insert Query
$insert_row = $mysqli->query("INSERT INTO autodischarged (visitid, visitdate, status, pat_type, location, urgency, visitreason, diagnosis, discharged, labcnt, labdone, qry) VALUES ($vid, $visitdate, $status, $pat_type, $location, $urgency, $visitreason, $diagnosis, $discharged, $labcnt, $labdone, $qry)");

	if($insert_row){
		print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />';
	}else{
		die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}
  }	//end $resultsq3		
    $resultsq3->free(); // Frees the memory associated with a result
}  //end $results3
$results3->free(); // Frees the memory associated with a result
$mysqli->close();// close connection		  
?>
