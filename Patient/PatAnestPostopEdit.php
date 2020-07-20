<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
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
$editFormAction = $_SERVER['PHP_SELF'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  }
  if ((isset($_POST["MM_UPDATE"])) && ($_POST["MM_UPDATE"] == "anestpostop")) {
	  $postopexamdtt = strtotime($_POST['postopexamdt'].' '.$_POST['postopexamdt_alt']);

  $UPDATESQL = sprintf("UPDATE anesthesia SET postopexamdt=%s, postopcomplications=%s, otherfindings=%s, status=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($postopexamdtt, "int"),
                       GetSQLValueString($_POST['postopcomplications'], "text"),
                       GetSQLValueString($_POST['otherfindings'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['aid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UPDATESQL, $swmisconn) or die(mysql_error());
	
	// keep surgery status same as ansthesia
  $updateSQL = sprintf("UPDATE surgery SET status=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['sid'], "int"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $UPDATEGoTo = "PatShow1.php";
    if (isset($_SERVER['QUERY_STRING'])) {
      $UPDATEGoTo .= (strpos($UPDATEGoTo, '?')) ? "&" : "?";
      $UPDATEGoTo .= $_SERVER['QUERY_STRING'];
    }
  header(sprintf("Location: %s", $UPDATEGoTo));
}

if (isset($_GET['mrn'])) {
  $colname_mrn = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
	}
if (isset($_GET['vid'])) {
  $colname_visitid = (get_magic_quotes_gpc()) ? $_GET['vid'] : addslashes($_GET['vid']);
	}
	if (isset($_GET['sid'])) {
  $colname_sid = (get_magic_quotes_gpc()) ? $_GET['sid'] : addslashes($_GET['sid']);
	}

mysql_select_db($database_swmisconn, $swmisconn);
$query_anestpostop = "SELECT id aid, medrecnum, visitid, surgid, postopexamdt, postopcomplications, otherfindings, status, entrydt, entryby FROM anesthesia WHERE surgid='".$colname_sid."'";
$anestpostop = mysql_query($query_anestpostop, $swmisconn) or die(mysql_error());
$row_anestpostop = mysql_fetch_assoc($anestpostop);
$totalRows_anestpostop = mysql_num_rows($anestpostop);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Postop Anesthesia</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=400,top=5,screenX=400,screenY=5';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<!--<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>-->
<script rel="text/javascript" src="../../jquery-1.11.1.js"></script>
<!-- for autosave-->
<script src="../../autosaveform.js">
//***********************************************
//* Auto Save Form script (c) Dynamic Drive (www.dynamicdrive.com)
//* This notice MUST stay intact for legal use
//* Visit http://www.dynamicdrive.com/ for this script and 100s more.
//**********************************************
//http://www.dynamicdrive.com/dynamicindex16/autosaveform.htm
</script>
<script>
		var formsave1=new autosaveform({
		formid: 'formpn1',
		pause: 1000 //<--no comma following last option!
    })
</script>

<!-- tinymce text editor-->
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({ 
  	//selector:'textarea#diagnosis', 
	mode : 'textareas',
	editor_selector : 'surgdata',   //textarea  must have class="notes"
	content_css : '../../CSS/content.css',
	min_height: 10,
	width: 500,
	autoresize_max_height: 400,
	autoresize_min_height: 10,
	autoresize_bottom_margin: 0,
	autoresize_top_margin: 0,
	menubar: false,
	statusbar: false,
	toolbar: false,
/*	toolbar_items_size : 'small',
	toolbar: 'bold italic underline | bullist  numlist | indent outdent | alignleft aligncenter alignright | superscript subscript | preview',
*/
    plugins: 'autoresize, autosave',     /*preview,*/
  	autosave_ask_before_unload: false,
		autosave_interval: "20s",
		autosave_restore_when_empty: true,
		autosave_retention: "30m"
	 });
</script>

<!-- for date & time picker-->
<link rel="stylesheet" href="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="../../js/jquery-ui-timepicker-addon.css">
<script src="../../js/jquery.js"></script>
<script src="../../js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="../../js/jquery-ui-timepicker-addon.js"></script>

<!-- postopexamdt-->
<script>
$( function() {
$('#postopexamdt').datetimepicker({
	altField: "#postopexamdt_alt",
	controlType: 'select',
	oneLine: true,
	dateFormat: 'D M d, yy',
	timeFormat: 'hh:mm tt', 
	buttonImage: "../../js/jquery-ui-1.12.1.custom/images/calendar.gif",
	showOn: "both", 
  buttonImageOnly: false
});
});
</script>
</head>

<body>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
		<td valign="top"><?php $patview = "PatPermView.php" ?>
		        		  <?php require_once($patview); ?></td>
  </tr>
</table>

<div align="center" class="BlueBold_24">Anesthesia POSTOP Update</div>
<table align="center" width="50%" border="1" style="border-collapse:collapse;">
<tr>
   <td nowrap="nowrap"><a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php" class="BlueBold_24" style="background-color:violet; border-color:blue; color:white; text-align:center; border-radius:5px;">Surg Update</a>&nbsp;</td>
   <td nowrap="nowrap"><div><a href="Patshow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&aid=<?php echo $row_anestpostop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestPreopEdit.php" title="Update Preop"  class="BlueBold_24"  style="background-color:green; border-color:blue; color:white; text-align:center; border-radius:5px;">Anest Preop</a>&nbsp;</div></td>
   <td nowrap="nowrap" title="Update Intraop"><a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&sid=<?php echo $colname_sid ?>&aid=<?php echo $row_anestpostop['aid'] ?>&visit=PatVisitView.php&act=lab&pge=PatAnestIntraopEdit.php" class="BlueBold_24" style="background-color:yellow; border-color:blue; color:black; text-align:center;border-radius:5px;">Anest Intraop</a></td>
</tr><p>&nbsp;</p> 
</table>
<table width="50%" align="center" border="1" >
 <form method="post" name="anestpostop" action="" >
  <tr>
    <td>
  		<table border="1" align="center" bgcolor="#ffedcc" cellpadding="1" cellspacing="1">
   			<tr>
            <td height="18" align="right" scope="row" title="MRN: <?php echo $row_anestpostop['medrecnum'] ?>&#10;VisitID: <?php echo $row_anestpostop['visitid'] ?>&#10;SurgID: <?php echo $row_anestpostop['surgid'] ?>&#10;AnestID: <?php echo $row_anestpostop['aid'] ?> ">postopexamdt:</td>
            <td  colspan="2" align="left" nowrap="nowrap">
<?php  if(isset($row_anestpostop['postopexamdt']) && $row_anestpostop['postopexamdt'] != NULL ) {?>
              <input id="postopexamdt" name="postopexamdt" type="text" size="12" maxlength="12" value="<?php echo  date('D M d,Y', $row_anestpostop['postopexamdt']) ;?>" />
              <input id="postopexamdt_alt" name="postopexamdt_alt" type="text" size="8" maxlength="10" value="<?php echo  date('h:i a', $row_anestpostop['postopexamdt']) ;?>" />
<?php } else {?>
              <input id="postopexamdt" name="postopexamdt" type="text" size="12" maxlength="15" value="" />
              <input id="postopexamdt_alt" name="postopexamdt_alt" type="text" size="8" maxlength="10" value="" />
<?php }?></td>
        </tr>
          <tr>
            <td align="right">Postop Complications:</td>
            <td colspan='4'><textarea name="postopcomplications" id="postopcomplications" class="surgdata" cols="80" rows="2" ><?php echo $row_anestpostop['postopcomplications']?></textarea></td>
          </tr>
          <tr>
            <td align="right">Other Findings:</td>
            <td colspan='4'><textarea name="otherfindings" id="otherfindings" class="surgdata" cols="80" rows="2" ><?php echo $row_anestpostop['otherfindings']?></textarea></td>
          </tr>
          <tr>
              <td>status</td>
              <td align="right" title="Anest Id: <?php echo $row_anestpostop['aid'] ?>&#10;Surg Id: <?php echo $row_anestpostop['surgid'] ?>&#10;MRN: <?php echo $row_anestpostop['medrecnum'] ?>&#10;Visit Id: <?php echo $row_anestpostop['visitid'] ?>"><select name="status" id="status">
        <option value="Ordered" <?php if (!(strcmp("Ordered", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
        <option value="Scheduled" <?php if (!(strcmp("Scheduled", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>Scheduled</option>
        <option value="In-Progress" <?php if (!(strcmp("In-Progress", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
        <option value="Recovery" <?php if (!(strcmp("Recovery", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>Recovery</option>
        <option value="Complete" <?php if (!(strcmp("Complete", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>Complete</option>
        <option value="Cancelled" <?php if (!(strcmp("Cancelled", $row_anestpostop['status']))) {echo "selected=\"selected\"";} ?>>Cancelled</option>
      </select>
      </td>
        
      <td align="right"><input type="submit" name="Add" id="Add" class="BlueBold_16" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Update POSTOP" /></td>
          
        <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="sid" id="sid" value="<?php echo $row_anestpostop['surgid'] ?>" />
        <input type="hidden" name="aid" id="aid" value="<?php echo $row_anestpostop['aid'] ?>" />
        <input type="hidden" name="MM_UPDATE" value="anestpostop" />
         </tr>
     </table>
      </td>     
 
  </form>            
  
        </td>
      </tr>
    </table>



</body>
</html> 
<?php
mysql_free_result($anestpostop);
?>
