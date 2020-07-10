<?php require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE development SET projid=%s, DevType=%s, EstHrs=%s, ActHrs=%s, Priority=%s, Status=%s, AssignedTo=%s, devsys=%s, train=%s ,test=%s, live=%s,Summary=%s, entryby=%s, entrydt=%s, Description=%s WHERE id=%s" ,
                       GetSQLValueString($_POST['projid'], "int"),
                       GetSQLValueString($_POST['devtype'], "text"),
                       GetSQLValueString($_POST['esthrs'], "int"),
                       GetSQLValueString($_POST['acthrs'], "int"),
                       GetSQLValueString($_POST['priority'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['assignto'], "text"),
                       GetSQLValueString($_POST['devsys'], "text"),
                       GetSQLValueString($_POST['train'], "text"),
                       GetSQLValueString($_POST['test'], "text"),
                       GetSQLValueString($_POST['live'], "text"),
                       GetSQLValueString($_POST['summary'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['descr'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "DevTrackSum.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
 $id_DEVLIST = "-1";
if (isset($_GET['id'])) {
  $id_DEVLIST = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_DEVLIST = sprintf("SELECT id, projid, DevType, EstHrs, ActHrs, Priority, Status, AssignedTo, devsys, train, test, live, Summary, Description, entryby, entrydt, comments FROM development WHERE id=%s", $id_DEVLIST);
$DEVLIST = mysql_query($query_DEVLIST, $swmisconn) or die(mysql_error());
$row_DEVLIST = mysql_fetch_assoc($DEVLIST);
$totalRows_DEVLIST = mysql_num_rows($DEVLIST);

?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DevType = "SELECT name FROM dropdownlist WHERE list = 'DevType' ORDER BY seq ASC";
$DevType = mysql_query($query_DevType, $swmisconn) or die(mysql_error());
$row_DevType = mysql_fetch_assoc($DevType);
$totalRows_DevType = mysql_num_rows($DevType);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DevPriority = "SELECT name FROM dropdownlist WHERE list = 'DevPriority' ORDER BY seq ASC";
$DevPriority = mysql_query($query_DevPriority, $swmisconn) or die(mysql_error());
$row_DevPriority = mysql_fetch_assoc($DevPriority);
$totalRows_DevPriority = mysql_num_rows($DevPriority);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DevStatus = "SELECT name FROM dropdownlist WHERE list = 'DevStatus' ORDER BY seq ASC";
$DevStatus = mysql_query($query_DevStatus, $swmisconn) or die(mysql_error());
$row_DevStatus = mysql_fetch_assoc($DevStatus);
$totalRows_DevStatus = mysql_num_rows($DevStatus);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
//$query_User = "SELECT u.userid FROM users u join user_role ur on u.id = ur.userid join roles r on r.id = ur.roleid and r.descr like '%Admin%' where u.active = 'Y' ORDER BY u.userid ASC";
$query_User = "SELECT u.userid FROM users u where u.id IN('1','72')";
$User = mysql_query($query_User, $swmisconn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_projid = "SELECT id, projname, projversion FROM devproject ORDER BY id ASC";
$projid = mysql_query($query_projid, $swmisconn) or die(mysql_error());
$row_projid = mysql_fetch_assoc($projid);
$totalRows_projid = mysql_num_rows($projid);
?>
<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_folderphp = "SELECT id, folder, php, entryby, entrydt FROM devphpdb WHERE folder IS NOT NULL && devid = '".$id_DEVLIST."' ORDER BY folder,php ASC";
$folderphp = mysql_query($query_folderphp, $swmisconn) or die(mysql_error());
$row_folderphp = mysql_fetch_assoc($folderphp);
$totalRows_folderphp = mysql_num_rows($folderphp);
?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_dbtable = "SELECT id, db, dbtable, entryby, entrydt FROM devphpdb WHERE db IS NOT NULL && devid = '".$id_DEVLIST."' ORDER BY db,dbtable ASC";
$dbtable = mysql_query($query_dbtable, $swmisconn) or die(mysql_error());
$row_dbtable = mysql_fetch_assoc($dbtable);
$totalRows_dbtable = mysql_num_rows($dbtable);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Dev Tracking</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=600,screenX=300,screenY=600';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
</script>
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script>tinymce.init({ 
  	selector:'textarea',
	content_css : '../../CSS/content.css',
	//inline: true, //this causes not to save updates
	//readonly: true, //use readonly: true and Toolbar:false in reports?
	//toolbar: false,
	menubar: false,
	statusbar: false,
	toolbar_items_size : 'small',
	toolbar: 'undo redo | fontselect  fontsizeselect | bold italic underline | bullist  numlist | alignleft aligncenter alignright'
	 });
</script>

</head>

<body>
<table width="50%" border="1" align="center" cellpadding="1" cellspacing="1">
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST">  <tr>
    <td align="center"> Project:
      <select name="projid" id="projid">
      <option value="0" <?php if (!(strcmp("0", $row_DEVLIST['projid']))) {echo "selected=\"selected\"";} ?>>0</option>
        <?php
do {  
?>
        <option value="<?php echo $row_projid['id']?>"<?php if (!(strcmp($row_projid['id'], $row_DEVLIST['projid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_projid['id'].': version: '.$row_projid['projversion'];?></option>
        <?php
} while ($row_projid = mysql_fetch_assoc($projid));
  $rows = mysql_num_rows($projid);
  if($rows > 0) {
      mysql_data_seek($projid, 0);
	  $row_projid = mysql_fetch_assoc($projid);
  }
?>
        </select>
      
    </td>

    <td colspan="8"><div align="center" class="BlueBold_14">
    
    <span class="BlueBold_20">Edit SWMIS Development Task Tracking </span>-------- Task:<?php echo $row_DEVLIST['id']; ?> </div></td>
    </tr>
  <tr>
    <td align="right"><div align="right">Summary</div>
      Max =<br />
      256 Char</td>
    <td colspan="8"><textarea name="summary" cols="100" rows="3" id="summary" maxlength="255"><?php echo $row_DEVLIST['Summary']; ?></textarea>
Required</td>
  </tr>
  <tr>
    <td><div align="right">AssignTo</div></td>
    <td colspan="4"><select name="assignto">
<!--      <option value="" <?php //if (!(strcmp("", $row_DEVLIST['AssignedTo']))) {echo "selected=\"selected\"";} ?>>Select</option>-->
      <?php
do {  
?>
      <option value="<?php echo $row_User['userid']?>"<?php if (!(strcmp($row_User['userid'], $row_DEVLIST['AssignedTo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_User['userid']?></option>
      <?php
} while ($row_User = mysql_fetch_assoc($User));
  $rows = mysql_num_rows($User);
  if($rows > 0) {
      mysql_data_seek($User, 0);
	  $row_User = mysql_fetch_assoc($User);
  }
?>
    </select> 
      Required</td>
    <td>&nbsp;</td>
    <td><div align="right">Status</div></td>
    <td colspan="2"><select name="status">
      <option value="" <?php if (!(strcmp("", $row_DEVLIST['Status']))) {echo "selected=\"selected\"";} ?>>Select</option>
      <?php
do {  
?>
      <option value="<?php echo $row_DevStatus['name']?>"<?php if (!(strcmp($row_DevStatus['name'], $row_DEVLIST['Status']))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevStatus['name']?></option>
      <?php
} while ($row_DevStatus = mysql_fetch_assoc($DevStatus));
  $rows = mysql_num_rows($DevStatus);
  if($rows > 0) {
      mysql_data_seek($DevStatus, 0);
	  $row_DevStatus = mysql_fetch_assoc($DevStatus);
  }
?>
    </select>
Required</td>
  </tr>
  <tr>
    <td><div align="right">Dev Type</div></td>
    <td colspan="4"><select name="devtype">
      <option value="" <?php if (!(strcmp("", $row_DEVLIST['DevType']))) {echo "selected=\"selected\"";} ?>>Select</option>
      <?php
do {  
?>
      <option value="<?php echo $row_DevType['name']?>"<?php if (!(strcmp($row_DevType['name'], $row_DEVLIST['DevType']))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevType['name']?></option>
      <?php
} while ($row_DevType = mysql_fetch_assoc($DevType));
  $rows = mysql_num_rows($DevType);
  if($rows > 0) {
      mysql_data_seek($DevType, 0);
	  $row_DevType = mysql_fetch_assoc($DevType);
  }
?>
    </select>
Required</td>
    <td>Priority: 
          <select name="priority">
            <option value="" <?php if (!(strcmp("", $row_DEVLIST['Priority']))) {echo "selected=\"selected\"";} ?>>Select</option>
            <?php
      do {  
      ?>
            <option value="<?php echo $row_DevPriority['name']?>"<?php if (!(strcmp($row_DevPriority['name'], $row_DEVLIST['Priority']))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevPriority['name']?></option>
            <?php
      } while ($row_DevPriority = mysql_fetch_assoc($DevPriority));
        $rows = mysql_num_rows($DevPriority);
        if($rows > 0) {
            mysql_data_seek($DevPriority, 0);
          $row_DevPriority = mysql_fetch_assoc($DevPriority);
        }
      ?>
    </select>
Required    </td>
    <td><div align="right">EstHrs</div></td>
    <td><input name="esthrs" type="text" id="esthrs" value="<?php echo $row_DEVLIST['EstHrs']; ?>" size="5" maxlength="10" /></td>
    <td>ActHrs<input name="acthrs" type="text" id="acthrs" value="<?php echo $row_DEVLIST['ActHrs']; ?>" size="5" maxlength="10" /></td>
  </tr>
  <tr>
  	<td rowspan="2">Changed on<br />
  	  Servers:</td>
  	<td rowspan="2" align="center">SWMIS
      <select name="devsys" id="devsys">
  	    <option value="N" <?php if (!(strcmp("N", $row_DEVLIST['devsys']))) {echo "selected=\"selected\"";} ?>>N</option>
  	    <option value="Y" <?php if (!(strcmp("Y", $row_DEVLIST['devsys']))) {echo "selected=\"selected\"";} ?>>Y</option>      
	    </select>
  	  <br />
  	  DevSys</td>
  	<td rowspan="2" align="center">BMC
      <select name="train" id="train">
  	    <option value="N" <?php if (!(strcmp("N", $row_DEVLIST['train']))) {echo "selected=\"selected\"";} ?>>N</option>
  	    <option value="Y" <?php if (!(strcmp("Y", $row_DEVLIST['train']))) {echo "selected=\"selected\"";} ?>>Y</option>      
	    </select>
			<br />Train</td>
  	<td rowspan="2" align="center">BMC
      <select name="test" id="test">
  	    <option value="N" <?php if (!(strcmp("N", $row_DEVLIST['test']))) {echo "selected=\"selected\"";} ?>>N</option>
  	    <option value="Y" <?php if (!(strcmp("Y", $row_DEVLIST['test']))) {echo "selected=\"selected\"";} ?>>Y</option>      
	    </select>
			<br />Test</td>
  	<td rowspan="2" align="center">BMC
      <select name="live" id="live">
  	    <option value="N" <?php if (!(strcmp("N", $row_DEVLIST['live']))) {echo "selected=\"selected\"";} ?>>N</option>
  	    <option value="Y" <?php if (!(strcmp("Y", $row_DEVLIST['live']))) {echo "selected=\"selected\"";} ?>>Y</option>      
	    </select>
    <br />Live</td>
		<td title="Select from a list of previously entered folder-phpfile&#10;If desired file is not on the list, use Add New below"><a href="javascript:void(0)" onclick="MM_openBrWindow('DevPhpDbAddPop.php?phpdb=<?php echo $row_DEVLIST['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=900,height=250')">Add(Select) Folder/File or db/table</a></td>
  <td rowspan="2" valign="top">
    <table border="1" cellpadding="1" cellspacing="1"style="border-collapse:collapse;">
      <tr>
        <td>&nbsp;</td>
        <td align="center" bgcolor="#66FFFF">folder</td>
        <td align="center" bgcolor="#66FFFF">php</td>
      </tr>
      <?php do { ?>
        <tr>
        	<td title="Delete is IMMEDIATE!!&#10; No confirmation is provided!"><a href="DevPhpdbDelete.php?delid=<?php echo $row_folderphp['id']; ?>&devid=<?php echo $row_DEVLIST['id']; ?>">Del</a></td>
          <td title="Entrydt: <?php echo $row_folderphp['entrydt']; ?>&#10;Entryby: <?php echo $row_folderphp['entryby']; ?>"bgcolor="#fffdda"><?php echo $row_folderphp['folder']; ?></td>
          <td bgcolor="#fffdda"><?php echo $row_folderphp['php']; ?></td>
        </tr>
        <?php } while ($row_folderphp = mysql_fetch_assoc($folderphp)); ?>
    </table></td>
  <td colspan="2" rowspan="2" valign="top">
    <table border="1" cellpadding="1" cellspacing="1">
      <tr>
        <td>&nbsp;</td>
        <td align="center" bgcolor="#66FFFF">db</td>
        <td align="center" bgcolor="#66FFFF">table</td>
      </tr>
      <?php do { ?>
        <tr>
        	<td title="Delete is IMMEDIATE!!&#10; No confirmation is provided!"><a href="DevPhpdbDelete.php?delid=<?php echo $row_dbtable['id']; ?>&devid=<?php echo $row_DEVLIST['id']; ?>">Del</a></td>
          <td bgcolor="#fffdda"><?php echo $row_dbtable['db']; ?></td>
          <td bgcolor="#fffdda"><?php echo $row_dbtable['dbtable']; ?></td>
        </tr>
        <?php } while ($row_dbtable = mysql_fetch_assoc($dbtable)); ?>
    </table></td>  
  </tr>
  <tr>
		<td title="Add a NEW folder/file OR db/table to &#10;selection list and for this dev item"><a href="javascript:void(0)" onclick="MM_openBrWindow('DevPhpDbNewPop.php?phpdb=<?php echo $row_DEVLIST['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=900,height=250')">Add (New)  Folder/File or db/table</a></td>
  </tr>

  <tr>
    <td><div align="right">Description</div></td>
    <td colspan="8"><textarea name="descr" cols="100" rows="10" id="descr"><?php echo $row_DEVLIST['Description']; ?></textarea></td>
  </tr>
  <tr>
    <td nowrap="nowrap">ID: <?php echo $row_DEVLIST['id']; ?></td>
    <td colspan="4" nowrap="nowrap"> <a href="DevTrackSum.php">Close </a></td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2" align="right"><input type="submit" name="Submit" value="Save Changes" />
      <input type="hidden" name="id" value="<?php echo $row_DEVLIST['id']; ?>" />
      <input type="hidden" name="entryby" Value = "<?php echo $_SESSION['user']; ?>"/>
      <input type="hidden" name="entrydt" Value = "<?php echo date("Y-m-d H:i"); ?>" /></td>
  </tr>
  <input type="hidden" name="MM_update" value="form1">
</form>
<!--************ Comments *********************************************-->
<tr>
    <td valign="top"><div align="right"><strong>Comments</strong></div>
    				<div align="center" class="navLink"><a href="javascript:void(0)" onclick="MM_openBrWindow('DevCommAdd.php?id=<?php echo $row_DEVLIST['id']; ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=300')">ADD</a></div>
	</td>

<td colspan="8">
 
     <table>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_source = "Select id, comments, entryby, entrydt from develcomnts where devdocid =  '".$row_DEVLIST['id']."'";
$source = mysql_query($query_source, $swmisconn) or die(mysql_error());
$row_source = mysql_fetch_assoc($source);
$totalRows_source = mysql_num_rows($source);
?>
<?php do {   ?>
        <tr>
          <td><a href="DevCommAdd.php"><a href="javascript:void(0)" onclick="MM_openBrWindow('DevCommEdit.php?id=<?php echo $row_source['id'] ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=300')"><?php echo $row_source['entrydt'].':'.$row_source['entryby']; ?></a></td>
          <td><?php echo $row_source['comments']; ?></td>
        </tr>
        <?php } while ($row_source = mysql_fetch_assoc($source)); ?>
      </table>
    </div></td>
  </tr>
  <?php //}?>
  <?php //} while ($row_DevSumList = mysql_fetch_assoc($DevSumList)); ?>
</table>


</td>
</tr>
<!--************ End Comments *********************************************-->

</table>

</body>
</html>
<?php
mysql_free_result($DEVLIST);

mysql_free_result($folderphp);

mysql_free_result($dbtable);
?>
