<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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
<?php //$mydate = Date('Y-m-d H:i:s');?>
<?php //echo Date('Y-m-d H:i:s');?>
<?php //Select visits where discharge is null and diagnosis is not null and current date is 2 days after visitdate. mysql_select_db($database_swmisconn, $swmisconn);  //DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE,p.dob)),'%y Years %m Months %d Days') AS age
$query_dischPat = "select v.id from patvisit v join patperm p ON p.medrecnum = v.medrecnum where discharge is null and diagnosis is not null and v.pat_type in ('OutPatient', 'Antenatal') and DATEDIFF(CURRENT_DATE,v.visitdate) > 2 ";
$dischPat = mysql_query($query_dischPat, $swmisconn) or die(mysql_error());
$row_dischPat = mysql_fetch_assoc($dischPat);
$totalRows_dischPat = mysql_num_rows($dischPat);

  do {
    echo '    '.$row_dischPat['id']; ?>	</ br>

	<?php 
  	$updateSQL = sprintf("UPDATE patvisit SET discharge=%s WHERE id=%s",
                       GetSQLValueString(Date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($row_dischPat['id'], "int"));

  	mysql_select_db($database_swmisconn, $swmisconn);
	$Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
		
  } while ($row_dischPat = mysql_fetch_assoc($dischPat));
		  
  
  $updateGoTo = "Dischargable.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
  }  
    header(sprintf("Location: %s", $updateGoTo));

//If diagnosis is blank, enter 'No Diagnosis  Documented' 

?>
