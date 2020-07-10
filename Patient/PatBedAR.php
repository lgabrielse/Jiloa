<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); //used when login is used to connect to swmisbethany database using mysql ?>
<?php require_once('../../Connections/i_swmisconn.php'); // used to connect to wmisbethany database using mysqli ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php  //Protect PHP server behaviors from SQL injection vulnerability for insert, Edit, & Delete
if (!function_exists("GetSQLiValueString")) {
function GetSQLiValueString($theConnection, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theConnection, $theValue) : mysqli_escape_string($theConnection, $theValue);

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
//exit;
?>

<?php 
		$message = '';
		$bedid = 0;
		$user = '';
		$mrn = 0;
?>

<?php // check for assign values in URL
		if(isset($_GET['bedact']) && $_GET['bedact'] == 'assign' && isset($_GET['bedid']) && isset($_GET['mrn'])){
			if(isset($_GET['bedid'])&& isset($_GET['mrn']) && isset($_SESSION['user'])) {
				$bedid = $_GET['bedid'];
				$user = $_SESSION['user'];
				$mrn = $_GET['mrn'];
		} else {
		$message = "Data Error"; 
		}
	
		// look for patient being already assigned to a bed
		mysqli_select_db($swmisconn, $database_swmisconn);
		$mysqli_assign_query = "SELECT b.medrecnum, f.name FROM patbed b JOIN fee f on f.id=b.feeid where medrecnum = '".$_GET['mrn']."'";
		$assign = mysqli_query($swmisconn, $mysqli_assign_query) or die(mysqli_error($swmisconn));
		$row_assign = mysqli_fetch_assoc($assign);
		$totalRows_assign = mysqli_num_rows($assign);

		// if already assigned to a bed:
		if(isset($totalRows_assign) && $totalRows_assign > 0){
		$message = 'This Patient is already assigned to a bed';
		} else {
    $mysqliUpdateSQL = sprintf("UPDATE patbed SET medrecnum=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLiValueString($swmisconn, $mrn, "int"),
										   GetSQLiValueString($swmisconn, $user, "text"),
										   GetSQLiValueString($swmisconn, date("Y-m-d H:i"), "date"),
                       GetSQLiValueString($swmisconn, $bedid, "int"));

    mysqli_select_db($swmisconn, $database_swmisconn);
    $Result1 = mysqli_query($swmisconn, $mysqliUpdateSQL) or die(mysqli_error($mysqliUpdateSQL));
		}
		}
?>
<!--if the orders for this patient visit include a bed fee, make user discharge the patient.-->
<!--   query of fee tabkle to select Section = Bed Fees -->
<!--Display message...CLICK ON FULLVIEW AND DISCHARGE THIS PATIENT-->

<?php
// check for release values in URL
	if(isset($_GET['bedact']) && $_GET['bedact'] == 'release' && isset($_GET['bedid']) && isset($_GET['mrn'])){
		if(isset($_GET['bedid'])&& isset($_GET['mrn']) && isset($_SESSION['user'])) {
		$bedid = $_GET['bedid'];
		$user = $_SESSION['user'];
		$mrn = $_GET['mrn'];
		$vid = $_GET['vid'];
		} else {
		$message = "Data Error"; 
		}

  // check for bed fees order
		// look for patient being already assigned to a bed
		mysqli_select_db($swmisconn, $database_swmisconn);
		$mysqli_bedfees_query = "SELECT o.id, f.section FROM orders o JOIN fee f on f.id= o.feeid where f.section = 'Bed Fees' and o.visitid = '". $_GET['vid']."'";
		$bedfees = mysqli_query($swmisconn, $mysqli_bedfees_query) or die(mysqli_error($swmisconn));
		$row_bedfees = mysqli_fetch_assoc($bedfees);
		$totalRows_bedfees = mysqli_num_rows($bedfees);


	if($totalRows_bedfees > 0){
		$message = 'Click on FullView and discharge this patient.';
		} else {
		// update MRN in bed record
    $mysqliUpdateSQL = sprintf("UPDATE patbed SET medrecnum=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLiValueString($swmisconn, '', "int"),
										   GetSQLiValueString($swmisconn, $user, "text"),
										   GetSQLiValueString($swmisconn, date("Y-m-d H:i"), "date"),
                       GetSQLiValueString($swmisconn, $bedid, "int"));

    mysqli_select_db($swmisconn, $database_swmisconn);
    $Result1 = mysqli_query($swmisconn, $mysqliUpdateSQL) or die(mysqli_error($mysqliUpdateSQL));
}
}
if(isset($_GET['bedact']) && ($_GET['bedact'] == 'release' || $_GET['bedact'] == 'assign')){
		$updateGoTo = "PatShow1.php?mrn=".$_SESSION['mrn']."&vid=".$_SESSION['vid']."&visit=PatVisitView.php&act=inpat&pge=patbed.php&vfeeid=".$_GET['vfeeid']."&message=".$message."";
//		if (isset($_SERVER['QUERY_STRING'])) {
//			$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//			$updateGoTo .= $_SERVER['QUERY_STRING'];
//		}
		header(sprintf("Location: %s", $updateGoTo));

}
// ?mrn=".$_SESSION['mrn']."&vid=".$_SESSION['vid']."&visit=PatVisitView.php&act=inpat&pge=patbed.php&vfeeid='".$colname_id."'
?>


<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>-->