<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO announce (entrydt, entryby, expires, acontent, active) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(date('Y-m-d',strtotime($_POST['entrydt'])), "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString(date('Y-m-d',strtotime($_POST['expires'])), "date"),
                       GetSQLValueString($_POST['acontent'], "text"),
                       GetSQLValueString($_POST['active'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "AnnouncAdd.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
    <script src="../../ckeditor/ckeditor.js"></script>
	<style type="text/css">@import url(../../jscalendar-1.0/calendar-win2k-1.css);</style>
	<script type="text/javascript" src="../../jscalendar-1.0/calendar.js"></script>
	<script type="text/javascript" src="../../jscalendar-1.0/lang/calendar-en.js"></script>
	<script type="text/javascript" src="../../jscalendar-1.0/calendar-setup.js"></script>
</head>

<body>
<table width="70%" border="0" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>EntryDate</td>
          <td><input name="entrydt" type="text" id="entrydt" size="12" />
		  <button id="trigger1">...</button>
		  </td>
        </tr>
        <tr>
          <td>EntryBy</td>
          <td><input name="entryby" type="text" id="entryby" /></td>
        </tr>
        <tr>
          <td>Expires</td>
          <td><input name="expires" type="text" id="expires" size="12"/> 
		  <button id="trigger2">...</button>
		  </td>
        </tr>
        <tr>
          <td>Active</td>
          <td><select name="active" id="active">
            <option value="Y">Y</option>
            <option value="N">N</option>
          </select>
          </td>
        </tr>
        <tr>
          <td>Announcement</td>
          <td><textarea name="acontent" cols="80" rows="2" id="acontent"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="center">
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" />
          </div></td>
        </tr>
      </table>
        <input type="hidden" name="MM_insert" value="form1">
    </form>
    </td>
  </tr>
</table>
	<script type="text/javascript">
	Calendar.setup(
	{
	inputField : "entrydt", // ID of the input field
	ifFormat : "%m/%d/%Y", // the date format
	button : "trigger1" // ID of the button
	}
	);
	Calendar.setup(
	{
	inputField : "expires", // ID of the input field
	ifFormat : "%m-%d-%Y", // the date format
	button : "trigger2" // ID of the button
	}
	);
	</script>
	<script>
		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace( 'acontent' );
	</script>

</body>
</html>
