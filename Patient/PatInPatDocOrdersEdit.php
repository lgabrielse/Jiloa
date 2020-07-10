<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
 <?php //echo $_SESSION['CurrDateTime'] =  date("Y-m-d H:i:s"); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formpne")) {
  $updateSQL = sprintf("UPDATE patnotes SET medrecnum=%s, visitid=%s, notetype= %s, notes=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString($_POST['notetype'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= str_replace('&pge=PatInPatDocOrdersEdit.php','&pge=PatInPatDocOrdersView.php',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesEdit.php out of $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
$colname_notes = "-1";
if (isset($_GET['mrn'])) {
  $colname_notes = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
$colname_notes2 = "-1";
if (isset($_GET['vid'])) {
  $colname_notes2 = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
}
$colname_notes3 = "-1";
if (isset($_GET['noteid'])) {
  $colname_notes3 = (get_magic_quotes_gpc()) ? $_GET['noteid'] : addslashes($_GET['noteid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_notes = "SELECT id, medrecnum, visitid, notetype, notes, entryby, DATE_FORMAT(entrydt,'%d-%b-%Y     %H:%i') entrydt FROM patnotes WHERE notetype = 'InpatDocOrders' and medrecnum = $colname_notes and visitid = $colname_notes2 and id = $colname_notes3";
$notes = mysql_query($query_notes, $swmisconn) or die(mysql_error());
$row_notes = mysql_fetch_assoc($notes);
$totalRows_notes = mysql_num_rows($notes);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>

<body>
<table width="100%">
  <caption align="top" class="subtitlebl">
    Edit InPatient Doctors Orders
  </caption>
  <tr>
    <td><form id="formpne" name="formpne" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#F8FDCE">
        <tr>
          <td>&nbsp;</td>
          <td>DateTime -- User</td>
          <td>*Notes </td>
          <td>&nbsp;</td>
        </tr>
        <?php do { ?>
        <tr>
          <td><?php if(allow(39,4) == 1) { ?>
                <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=inpat&pge=PatInPatDocOrdersDelete.php&noteid=<?php echo $row_notes['id']; ?>">Delete</a><br />
                <?php } ?>
              <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></td>
          <td title="NoteID: <?php echo $row_notes['id']; ?>&#10;MedRecNum: <?php echo $row_notes['medrecnum']; ?>&#10;VisitID:<?php echo $row_notes['visitid']; ?>"><input type="text" name="entrydt2" readonly="readonly" value="<?php echo $row_notes['entrydt']; ?>"/>
                <br />
                <input name="entryby" type="text" readonly="readonly" value="<?php echo $row_notes['entryby']; ?>" size="15"/></td>
          <td><textarea name="notes" cols="80" rows="3" value="<?php echo $row_notes['notes']; ?>" data-validation="required"><?php echo $row_notes['notes']; ?> </textarea></td>
          <td><input name="medrecnum" type="hidden" value="<?php echo $row_notes['medrecnum']; ?>" />
                <input name="notetype" type="hidden" id="notetype" value="InpatDocOrders" />
                <input name="visitid" type="hidden" value="<?php echo $row_notes['visitid']; ?>" />
                <input name="entrydt" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                <input name="entryby" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
                <input name="id" type="hidden" value="<?php echo $row_notes['id']; ?>" />
                <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Orders" /></td>
        </tr>
        <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>
      </table>
      <input type="hidden" name="MM_update" value="formpne" />
    </form></td>
  </tr>
</table>
<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>
  $.validate({
  validateOnBlur : false, // disable validation when input looses focus
    errorMessagePosition : 'top', // Instead of 'element' which is default
    scrollToTopOnError : false // Set this property to true if you have a long form
  });
</script>
	   <script language="JavaScript" type="text/javascript"
		xml:space="preserve">//<![CDATA[
	//You should create the validator only after the definition of the HTML form
	  var frmvalidator  = new Validator("formpne");
	  frmvalidator.EnableMsgsTogether();
	  
 	  frmvalidator.addValidation("notes","req","Please enter Notes");
      frmvalidator.addValidation("notes","minlen=3", "Minumun length for Notes is 3");


	  </script>


</body>
</html>
<?php
mysql_free_result($notes);
?>
