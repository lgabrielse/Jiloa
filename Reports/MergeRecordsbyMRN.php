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

<?php $colname_MRNA = "";
	if (isset($_GET['MRNA'])) {
	  $colname_MRNA = (get_magic_quotes_gpc()) ? $_GET['MRNA'] : addslashes($_GET['MRNA']);
	}
     $colname_MRNB = "";
if (isset($_GET['MRNB'])) {
  $colname_MRNB = (get_magic_quotes_gpc()) ? $_GET['MRNB'] : addslashes($_GET['MRNB']);
}
     $colname_INFO = "";
if (isset($_GET['INFO'])) {
  $colname_INFO = (get_magic_quotes_gpc()) ? $_GET['INFO'] : addslashes($_GET['INFO']);
}
  $colname_isform = "";
if (isset($_POST['isform'])) {
  $colname_isform = $_POST['isform'];
}
?>


<?php 

	 If(($colname_isform == "isform") and ($_POST['Submit'] == "Submit") and (!empty($_POST['mrndelete']) and !empty($_POST['mrnkeep']) and !empty($_POST['mrninfo']))) {
?>
<?php 
mysql_select_db($database_swmisconn, $swmisconn); 

//1. Delete (MRNA) mrndelete record

	if ((isset($_POST['mrndelete'])) && ($_POST['mrndelete'] != "")) {
	  $deleteSQL = sprintf("DELETE FROM patperm WHERE medrecnum=%s",
						   GetSQLValueString($_POST['mrndelete'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
	}

//2. If INFO = MRNKeep, Delete info record MRNDelete.
    if ((isset($_POST['mrninfo'])) && ($_POST['mrninfo'] != "") && 
	    (isset($_POST['mrnkeep'])) && ($_POST['mrnkeep'] != "") && 
	    (isset($_POST['mrndelete'])) && ($_POST['mrndelete'] != "") && 
		($_POST['mrninfo'] == $_POST['mrnkeep'])) {
	  $deleteSQL = sprintf("DELETE FROM patinfo WHERE medrecnum=%s",
						   GetSQLValueString($_POST['mrndelete'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
	}
	
//3  If INFO = MRNDelete, Delete info record having MRNkeep, Update Info record having MRNDelete with MRNKeep
    if ((isset($_POST['mrninfo'])) && ($_POST['mrninfo'] != "") && 
	    (isset($_POST['mrnkeep'])) && ($_POST['mrnkeep'] != "") && 
	    (isset($_POST['mrndelete'])) && ($_POST['mrndelete'] != "") && 
		($_POST['mrninfo'] == $_POST['mrndelete'])) {
	  $deleteSQL = sprintf("DELETE FROM patinfo WHERE medrecnum=%s",
						   GetSQLValueString($_POST['mrnkeep'], "int"));
	
	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());
	  
	  $updateSQL = sprintf("UPDATE patinfo SET medrecnum=%s WHERE medrecnum=%s",
                    GetSQLValueString($_POST['mrnkeep'], "int"),
					GetSQLValueString($_POST['mrninfo'], "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	}

//4. Update Vist record with MRNDelete to MRNkeep.

	  $updateSQL2 = sprintf("UPDATE patvisit SET medrecnum=%s WHERE medrecnum=%s",
                    GetSQLValueString($_POST['mrnkeep'], "int"),
					GetSQLValueString($_POST['mrndelete'], "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result2 = mysql_query($updateSQL2, $swmisconn) or die(mysql_error());

//5. Update Notes records with MRNDelete to MRNkeep

	  $updateSQL = sprintf("UPDATE patnotes SET medrecnum=%s WHERE medrecnum=%s",
                    GetSQLValueString($_POST['mrnkeep'], "int"),
					GetSQLValueString($_POST['mrndelete'], "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

//6. Update Orders records with MRNDelete to MRNkeep

	  $updateSQL = sprintf("UPDATE orders SET medrecnum=%s WHERE medrecnum=%s",
                    GetSQLValueString($_POST['mrnkeep'], "int"),
					GetSQLValueString($_POST['mrndelete'], "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

//7. Update Receipt records with MRNDelete to MRNkeep

	  $updateSQL = sprintf("UPDATE receipts SET medrecnum=%s WHERE medrecnum=%s",
                    GetSQLValueString($_POST['mrnkeep'], "int"),
					GetSQLValueString($_POST['mrndelete'], "int"));

	  mysql_select_db($database_swmisconn, $swmisconn);
	  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

?>

<?php  $insertGoTo = "MergeView.php?mrn1=".$_POST['mrnkeep']."&mrn2=".$_POST['mrndelete']."";   
  header(sprintf("Location: %s", $insertGoTo));
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
<div align="center" class="BlueBold_24">MERGE Patient Records </div>
<?php If(!empty($errmsg)) {?>
<div align="center" class="RedBold_24"><?php echo $errmsg ?> </div>
<?php }?>
<table width="30%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="1" align="center">
        <tr>
          <td nowrap="nowrap"><div align="center"><span class="navLink"><a href="MergeView.php">MergeView </a></span>&nbsp;&nbsp;&nbsp;</div></td>
		  <td>&nbsp;</td>
		  <td nowrap="nowrap"><div align="center"><span class="navLink"><a href="ReportsMenu.php">Report Menu </a></span>&nbsp;&nbsp;&nbsp;</div></td>
        </tr>
        <tr>
          <td align="right" class="BlackBold_14">Delete:
            <input name="mrndelete" type="text" id="mrndelete" value="<?php echo $colname_MRNA; ?>" size="5" maxlength="9" /> 
 & Keep           </td>
          <td>
            <input name="mrnkeep" type="text" id="mrnkeep" value="<?php echo $colname_MRNB; ?>" size="5" maxlength="9" />
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" class="BlackBold_14">& Keep Info </td>
          <td>
            <input name="mrninfo" type="text" id="mrninfo" value="<?php echo $colname_INFO; ?>" size="5" maxlength="9" />
          </td>
          <td><input name="isform" type="hidden" value="isform" />
            <input type="submit" name="Submit" value="Submit" />
          </td>
        </tr>
      </table>
        </form>
    </td>
  </tr>
</table>
</body>
</html>
