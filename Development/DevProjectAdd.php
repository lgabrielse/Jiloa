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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "projadd")) {
  $insertSQL = sprintf("INSERT INTO devproject (projname, projversion, projdescr, projpriority, projstatus, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['projname'], "text"),
                       GetSQLValueString($_POST['projversion'], "text"),
                       GetSQLValueString($_POST['projdescr'], "text"),
                       GetSQLValueString($_POST['projpriority'], "text"),
                       GetSQLValueString($_POST['projstatus'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "DevProjectView.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_evproject = "SELECT id, projname, projversion, projdescr, projpriority, projstatus, entryby, entrydt FROM devproject";
$evproject = mysql_query($query_evproject, $swmisconn) or die(mysql_error());
$row_evproject = mysql_fetch_assoc($evproject);
$totalRows_evproject = mysql_num_rows($evproject);
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

<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>
</p>
<table width="50%" border="1" align="center" cellpadding="1" cellspacing="1">
<form action="<?php echo $editFormAction; ?>" method="POST" name="projadd">  
  <tr>
    <td><a href="DevProjectView.php">View</a></th>
    <td align="center" class="BlueBold_24">ADD DEVELOPMENT PROJECT</td>
  </tr>
  <tr>
    <td align="right">Title:</td>
    <td><input name="projname" type="text" maxlength="40"/>
      </th>Max=40 char</tr>
  <tr>
    <td align="right">Priority:</td>
    <td><select name="projpriority">&nbsp;
      <option value="Select">Select</option>
      <?php
do {  
?>
      <option value="<?php echo $row_DevPriority['name']?>"><?php echo $row_DevPriority['name']?></option>
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
    <td><input name="projversion" type="text"  maxlength="40"/>
      </th>
  
      </th>
      Max=30 char</tr>
  <tr>
    <td title="Max=200 char" align="right">Description:</td>
    <td title="Max=200 char"><textarea name="projdescr" cols="100" rows="2">&nbsp;</textarea></td>
  </tr>
  <input name="id" type="hidden" value="" />
  <input name="entryby" type="hidden" value="" />
  <input name="entrydt" type="hidden" value="" />

	<tr>
    <td align="right">Status</td>
    <td><select name="projstatus">
      <option value="New">New</option>
      <option value="Design">Design</option>
      <option value="Coding">Coding</option>
      <option value="Testing">Testing</option>
      <option value="OnHold">OnHold</option>
      <option value="Complete">Complete</option>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</th>
    <td><input name="submit" type="submit" value="ADD" /></td>
  </tr>
	  <input type="hidden" name="entryby" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
	  <input type="hidden" name="entrydt" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />	</td>

  <input type="hidden" name="MM_insert" value="projadd">
</form>
</table>
</body>
</html>
<?php
mysql_free_result($evproject);
?>
