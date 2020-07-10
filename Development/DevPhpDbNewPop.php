<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();   }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
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
?>
<?php $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['MM_Insert']) AND $_POST['MM_Insert']  == 'formphp')  {
  $insertSQL = sprintf("INSERT INTO devphpdb (devid, folder, php, entrydt, entryby) VALUES (%s, %s, %s, %s, %s)",
											GetSQLValueString($_POST['devid'], "int"),
                      GetSQLValueString($_POST['folder'], "text"),
                      GetSQLValueString($_POST['php'], "text"),
                      GetSQLValueString($_POST['entrydt'], "date"),
											GetSQLValueString($_POST['entryby'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
}
if (isset($_POST['MM_Insert']) AND $_POST['MM_Insert']  == 'formdb')  {
  $insertSQL = sprintf("INSERT INTO devphpdb (devid, db, dbtable, entrydt, entryby) VALUES (%s, %s, %s, %s, %s)",
                      GetSQLValueString($_POST['devid'], "int"),
                      GetSQLValueString($_POST['db'], "text"),
                      GetSQLValueString($_POST['dbtable'], "text"),
                      GetSQLValueString($_POST['entrydt'], "date"),
											GetSQLValueString($_POST['entryby'], "text"));
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
}

mysql_select_db($database_swmisconn, $swmisconn);
$query_summary = sprintf("SELECT summary FROM development WHERE id = '".$_GET['phpdb']."'");
$summary  = mysql_query($query_summary , $swmisconn) or die(mysql_error());
$row_summary  = mysql_fetch_assoc($summary );
$totalRows_summary  = mysql_num_rows($summary );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PhpDb New Popup</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>
</head>

<body>

<table width="50%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2">Dev Item: <?php echo $_GET['phpdb'].':  '. $row_summary['summary'];?></td>
  </tr>
  <tr>
    <td>Add NEW folder and php file </td>
    <td>Add NEW databse and table</td>
  </tr>
  <tr>
    <td>
      <table>
        <form name="formphp" id="formphp" method="post" action="DevPhpDbNewPop.php?phpdb=<?php echo $_GET['phpdb']; ?>">  
          <tr>
            <td>Folder<input name="folder" id="folder" type="text" size ="10"/>
            </td>
            <td>php<input name="php" id="php" type="text" size ="10"/>
            </td>
                <input name="devid" type="hidden" id="devid" value="<?php echo $_GET['phpdb']; ?>" />
                <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
                <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
                <input type="hidden" name="MM_Insert" value="formphp" />
            <td><input name="submit" type="submit" style="background-color:#ccffcc;" value="Add folder/php to programs list" />
            </td>
          </tr>
        </form>
      </table>
    </td>
    <td>
      <table>
        <form name="formdb" id="formdb" method="post" action="DevPhpDbNewPop?phpdb=<?php echo $_GET['phpdb']; ?>.php">  
          <tr>
            <td>Database:<input type="text" name="db" id="db" size="10" />
            </td>
            <td>Table:<input type="text" name="dbtable" id="dbtable" size="10" />
            </td>
                <input name="devid" type="hidden" id="devid" value="<?php echo $_GET['phpdb']; ?>" />
                <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
                <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
                <input type="hidden" name="MM_Insert" value="formdb" />
            <td><input name="submit" type="submit" style="background-color:#ccffcc;" value="Add db/table to db/table list" />
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">Check if it is already on the list using ADD (Select) 
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></div></td>
	</tr>
</table>



</body>
</html>