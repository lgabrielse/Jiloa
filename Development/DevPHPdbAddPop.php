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
?>

<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_folderselect = sprintf("SELECT distinct folder FROM devphpdb WHERE folder IS NOT NULL Order By folder");
$folderselect  = mysql_query($query_folderselect , $swmisconn) or die(mysql_error());
$row_folderselect  = mysql_fetch_assoc($folderselect );
$totalRows_folderselect  = mysql_num_rows($folderselect );

mysql_select_db($database_swmisconn, $swmisconn);
$query_phpselect = sprintf("SELECT distinct php FROM devphpdb WHERE php IS NOT NULL Order By php");
$phpselect  = mysql_query($query_phpselect , $swmisconn) or die(mysql_error());
$row_phpselect  = mysql_fetch_assoc($phpselect );
$totalRows_phpselect  = mysql_num_rows($phpselect );

mysql_select_db($database_swmisconn, $swmisconn);
$query_dbselect = sprintf("SELECT distinct db FROM devphpdb WHERE db IS NOT NULL Order By db");
$dbselect  = mysql_query($query_dbselect , $swmisconn) or die(mysql_error());
$row_dbselect  = mysql_fetch_assoc($dbselect );
$totalRows_dbselect  = mysql_num_rows($dbselect );

mysql_select_db($database_swmisconn, $swmisconn);
$query_tableselect = sprintf("SELECT distinct dbtable FROM devphpdb WHERE dbtable IS NOT NULL Order By dbtable");
$tableselect  = mysql_query($query_tableselect , $swmisconn) or die(mysql_error());
$row_tableselect  = mysql_fetch_assoc($tableselect );
$totalRows_tableselect  = mysql_num_rows($tableselect );

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
<title>PhpDb Add Popup</title>
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</head>
<body>

<table width="50%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="2">Dev Item:<?php echo $_GET['phpdb'].':  '. $row_summary['summary'];?></td>
  </tr>
  <tr>
    <td>Add folder and php file from previously entered list</td>
		<td align="center">Add db and table from previously entered list</td>
  </tr>
  <tr>
    <td>
      <table>
        <form name="formphp" id="formphp" method="post" action="DevPhpDbAddPop.php?phpdb=<?php echo $_GET['phpdb']; ?>">  
        <tr>
          <td>Folder:</td>
          <td>php file:</td>
          <td nowrap="nowrap">include '.php' in file name:</td>
        </tr>
          <tr>
            <td>
              <select name="folder">
              <option value="">Select</option>
								<?php
                  do {  
                  ?>
                <option value="<?php echo $row_folderselect['folder']?>"><?php echo $row_folderselect['folder']?></option>
                <?php
                    } while ($row_folderselect = mysql_fetch_assoc($folderselect));
                      $rows = mysql_num_rows($folderselect);
                      if($rows > 0) {
                          mysql_data_seek($folderselect, 0);
                        $row_folderselect = mysql_fetch_assoc($folderselect);
                } ?>      
               </select>
            </td>
            <td>
              <select name="php">
              <option value="">Select</option>
								<?php
                  do {  
                  ?>
                <option value="<?php echo $row_phpselect['php']?>"><?php echo $row_phpselect['php']?></option>
                <?php
                    } while ($row_phpselect = mysql_fetch_assoc($phpselect));
                      $rows = mysql_num_rows($phpselect);
                      if($rows > 0) {
                          mysql_data_seek($phpselect, 0);
                        $row_phpselect = mysql_fetch_assoc($phpselect);
                } ?>      
               </select>
            </td>
                <input name="devid" type="hidden" id="devid" value="<?php echo $_GET['phpdb']; ?>" />
                <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
                <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
                <input type="hidden" name="MM_Insert" value="formphp" />
            <td><input name="submit" type="submit" style="background-color:#ccffcc;" value="Add folder/php" />
            </td>
          </tr>
        </form>
      </table>
    </td>
    <td>
      <table>
        <form name="formdb" id="formdb" method="get" action="DevPHPdbAddPop.php">  
        <tr>
          <td>DB:</td>
          <td>Table:</td>
          <td>&nbsp;</td>
        </tr>
          <tr>
            <td>
              <select name="db">
              <option value="">Select</option>
								<?php
                  do {  
                  ?>
                <option value="<?php echo $row_dbselect['db']?>"><?php echo $row_dbselect['db']?></option>
                <?php
                    } while ($row_dbselect = mysql_fetch_assoc($dbselect));
                      $rows = mysql_num_rows($dbselect);
                      if($rows > 0) {
                          mysql_data_seek($dbselect, 0);
                        $row_dbselect = mysql_fetch_assoc($dbselect);
                } ?>      
               </select>
            </td>
            <td>
              <select name="dbtable">
              <option value="">Select</option>
								<?php
                  do {  
                  ?>
                <option value="<?php echo $row_tableselect['dbtable']?>"><?php echo $row_tableselect['dbtable']?></option>
                <?php
                    } while ($row_tableselect = mysql_fetch_assoc($tableselect));
                      $rows = mysql_num_rows($tableselect);
                      if($rows > 0) {
                          mysql_data_seek($tableselect, 0);
                        $row_tableselect = mysql_fetch_assoc($tableselect);
                } ?>      
               </select>

            </td>
                <input name="phpdb" type="hidden" id="phpdb" value="<?php echo $_GET['phpdb']; ?>" />
                <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
                <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
                <input type="hidden" name="MM_Insert" value="formdb" />
            <td><input name="submit" type="submit" style="background-color:#ccffcc;" value="Add db/table" />
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
     If not found on list, Close and Add to the list using Add (New):   <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
    </div></td>
  </tr>

</table>


</body>
</html>