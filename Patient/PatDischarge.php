<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>

<?php
if(!function_exists("GetSQLValueString")) {
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
}

if (isset($_GET["vid"])) {
  $updateSQL = sprintf("UPDATE patvisit SET discharge=%s WHERE id=%s",
                       GetSQLValueString(Date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($_GET['vid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

// check for discharged patients
mysql_select_db($database_swmisconn, $swmisconn);
$query_Pat_Disch = "SELECT b.id bid, b.medrecnum, v.discharge FROM patbed b JOIN patperm p ON b.medrecnum=p.medrecnum JOIN patvisit v ON p.medrecnum = v.medrecnum JOIN fee f ON b.feeid=f.id WHERE v.id = '".$_GET['vid']."' ORDER BY b.feeid, b.bed;";
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

	

  $updateGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= str_replace('&visit=PatDischarge.php','',$_SERVER['QUERY_STRING']); // replace function  $_SERVER['QUERY_STRING'];
  }
}


  header(sprintf("Location: %s", $updateGoTo));
?>


