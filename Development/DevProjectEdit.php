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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "projedit")) {
//echo $_POST['projpriority'] ;
//exit;
  $updateSQL = sprintf("UPDATE devproject SET projname=%s, projversion=%s, projdescr=%s, projpriority=%s, projstatus=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['projname'], "text"),
                       GetSQLValueString($_POST['projversion'], "text"),
                       GetSQLValueString($_POST['projdescr'], "text"),
                       GetSQLValueString($_POST['projpriority'], "text"),
                       GetSQLValueString($_POST['projstatus'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "DevProjectView.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_projid = "-1";
if (isset($_GET['projid'])) {
  $colname_projid = (get_magic_quotes_gpc()) ? $_GET['projid'] : addslashes($_GET['projid']);
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_devproject = "SELECT id, projname, projversion, projdescr, projpriority, projstatus, entryby, entrydt FROM devproject WHERE id = '".$colname_projid."'";
$devproject = mysql_query($query_devproject, $swmisconn) or die(mysql_error());
$row_devproject = mysql_fetch_assoc($devproject);
$totalRows_devproject = mysql_num_rows($devproject);
?>

<?php mysql_select_db($database_swmisconn, $swmisconn);
$query_DevPriority = "SELECT name FROM dropdownlist WHERE list = 'DevPriority' ORDER BY seq ASC";
$DevPriority = mysql_query($query_DevPriority, $swmisconn) or die(mysql_error());
$row_DevPriority = mysql_fetch_assoc($DevPriority);
$totalRows_DevPriority = mysql_num_rows($DevPriority);
?>

<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DevProEdit</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>
</p>
<table width="50%" border="1" align="center" cellpadding="1" cellspacing="1">
<form method="POST" name="projedit" id="projedit" action="<?php echo $editFormAction; ?>">  
  <tr>
    <td><a href="DevProjectView.php">View</a></th>
    <td align="center" class="BlueBold_24">EDIT DEVELOPMENT PROJECT</td>
  </tr>
  <tr>
    <td align="right">Title:</td>
    <td><input name="projname" type="text" maxlength="40" value="<?php echo $row_devproject['projname'] ?>"/>
      </th>Max=40 char</tr>
  <tr>
    <td align="right">Priority:</td>
    <td><select name="projpriority">&nbsp;
      <!--<option value="Select" <?php if (!(strcmp("Select", $row_devproject['projpriority']))) {echo "selected=\"selected\"";} ?>>Select</option>-->
      <?php
do {  
?>
      <option value="<?php echo $row_DevPriority['name']?> <?php if (!(strcmp($row_DevPriority['name'], $row_devproject['projpriority']))) {echo "selected=\"selected\"";} ?> "><?php echo $row_DevPriority['name']?></option>
      <?php
} while ($row_DevPriority = mysql_fetch_assoc($DevPriority));
  $rows = mysql_num_rows($DevPriority);
  if($rows > 0) {
      mysql_data_seek($DevPriority, 0);
	  $row_DevPriority = mysql_fetch_assoc($DevPriority);
  }
?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">Version:</td>
    <td><input name="projversion" type="text"  maxlength="40" value="<?php echo $row_devproject['projversion'] ?>"/>
      </th>
  
      </th>
      Max=30 char</tr>
  <tr>
    <td title="Max=200 char" align="right">Description:</td>
    <td title="Max=200 char"><textarea name="projdescr" cols="100" rows="2"><?php echo $row_devproject['projdescr'] ?></textarea></td>
  </tr>
	<tr>
    <td><div align="right">Status</div></td>
    <td><select name="projstatus">
      <option value="New" <?php if (!(strcmp("New", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>New</option>
      <option value="Design" <?php if (!(strcmp("Design", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>Design</option>
      <option value="Coding" <?php if (!(strcmp("Coding", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>Coding</option>
      <option value="Testing" <?php if (!(strcmp("Testing", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>Testing</option>
      <option value="OnHold" <?php if (!(strcmp("OnHold", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>OnHold</option>
      <option value="Complete" <?php if (!(strcmp("Complete", $row_devproject['projstatus']))) {echo "selected=\"selected\"";} ?>>Complete</option>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</th>
    <td><input name="submit" type="submit" value="Update Project" /></td>
  </tr>
    <input type="hidden" name="id" id="id" value="<?php echo $row_devproject['id'] ?>">
	  <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
	  <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />	</td>
    <input type="hidden" name="MM_update" value="projedit">
</form>
</table>
</body>
</html>
<?php
mysql_free_result($devproject);
?>
