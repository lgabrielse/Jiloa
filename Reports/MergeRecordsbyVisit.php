<?php //require_once('../../Connections/swmisconn.php'); ?>
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

<?php $colname_MRN = "";
	if (isset($_GET['MRN'])) {
	  $colname_MRN = (get_magic_quotes_gpc()) ? $_GET['MRN'] : addslashes($_GET['MRN']);
	}
     $colname_VisitDelete = "";
if (isset($_GET['VisitDelete'])) {
  $colname_VisitDelete = (get_magic_quotes_gpc()) ? $_GET['VisitDelete'] : addslashes($_GET['VisitDelete']);
}
     $colname_VisitKeep = "";
if (isset($_GET['VisitKeep'])) {
  $colname_VisitKeep = (get_magic_quotes_gpc()) ? $_GET['VisitKeep'] : addslashes($_GET['VisitKeep']);
}
?>

<?php 	if ((isset($colname_MRN)) && ($colname_MRN != "") && 
	    (isset($colname_VisitDelete)) && ($colname_VisitDelete != "") && 
	    (isset($colname_VisitKeep)) && ($colname_VisitKeep != "")) {
	  $deleteSQL = sprintf("DELETE FROM patvisit WHERE id=%s",
						   GetSQLValueString($colname_VisitDelete, "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
	  
// Update Notes records with "to" visitid

	  	  $updateSQL = sprintf("UPDATE patnotes SET visitid=%s WHERE visitid=%s",
                    GetSQLValueString($colname_VisitKeep, "int"),
					GetSQLValueString($colname_VisitDelete, "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

// Update Orders records with "to" visitid

	  $updateSQL = sprintf("UPDATE orders SET visitid=%s WHERE visitid=%s",
                    GetSQLValueString($colname_VisitKeep, "int"),
					GetSQLValueString($colname_VisitDelete, "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "MergeView.php?mrn1=".$colname_MRN."&mrn2=0";   
  header(sprintf("Location: %s", $insertGoTo));
}
	?>