<?php  if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO patnotes (medrecnum, visitid, notetype, notes, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['notetype'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= str_replace('&pge=PatInPatDocOrdersAdd.php','&pge=PatInPatDocOrdersView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}
?> 
<?php // xml version="1.0" encoding="utf-8" ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script rel="text/javascript" src="../../jquery-1.11.1.js"></script>
<script src="../../autosaveform.js">

/***********************************************
* Auto Save Form script (c) Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
**********************************************
http://www.dynamicdrive.com/dynamicindex16/autosaveform.htm
*/

</script>

<script>

var formsave1=new autosaveform({
formid: 'formpn1',
pause: 1000 //<--no comma following last option!
})

</script>
</head>

<body>
<table width="60%" align="center">
<caption align="top" class="subtitlebl">
   Add InPatient Doctors Orders
  </caption>
<form  id="formpn1" name="formpn1" method="POST" action="<?php echo $editFormAction; ?>">  
  <tr>
    <td>
      <table width="100%" bgcolor="#BCFACC">
        <tr>
          <td valign="middle" nowrap="nowrap">Doctors<br />
          Orders:<br />
		    <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></td>
		  <td><textarea name="notes" cols="80" rows="3" id="notes"></textarea></td>
            <input name="notetype" type="hidden" id="notetype" value="InpatDocOrders" />
            <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
            <input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
            <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
         <td width="10%" nowrap="nowrap"><input type="submit" name="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save Orders" />           </td>
        </tr>
      </table>
	 </td>
        <input type="hidden" name="MM_insert" value="form1">

	   <script language="JavaScript" type="text/javascript"
		xml:space="preserve">//<![CDATA[
	//You should create the validator only after the definition of the HTML form
	  var frmvalidator  = new Validator("formpn1");
	  frmvalidator.EnableMsgsTogether();
	  
 	  frmvalidator.addValidation("notes","req","Please enter Notes");
      frmvalidator.addValidation("notes","minlen=3", "Minumun length for Notes is 3");


	  </script>
    </td>
  </tr>
    </form>
</table>

</body>
</html>
