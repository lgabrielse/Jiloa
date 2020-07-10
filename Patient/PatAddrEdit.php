<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //session_start()?>
<?php //session_destroy();?>
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
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 
if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Edit Patient Address')  {
  $updateSQL = sprintf("UPDATE patinfo SET medrecnum=%s, title=%s, occup=%s, married=%s, phone1=%s, phone2=%s, phone3=%s, street=%s, city=%s, locgovt=%s, `state`=%s, country=%s, em_rel=%s, em_fname=%s, em_lname=%s, em_phone1=%s, em_phone2=%s, comments=%s, entrydt=%s, entryby=%s WHERE id=%s",
					   GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['occup'], "text"),
                       GetSQLValueString($_POST['married'], "text"),
                       GetSQLValueString($_POST['phone1'], "text"),
                       GetSQLValueString($_POST['phone2'], "text"),
                       GetSQLValueString($_POST['phone3'], "text"),
                       GetSQLValueString($_POST['street'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['locgovt'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['em_rel'], "text"),
                       GetSQLValueString($_POST['em_fname'], "text"),
                       GetSQLValueString($_POST['em_lname'], "text"),
                       GetSQLValueString($_POST['em_phone1'], "text"),
                       GetSQLValueString($_POST['em_phone2'], "text"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  
	if(!isset($_SESSION['countryid']))  {$_SESSION['countryid'] = '0';}
	if(!isset($_SESSION['stateid']))    {$_SESSION['stateid'] = '0';}
	if(!isset($_SESSION['locgovtid']))  {$_SESSION['locgovtid'] = '0';}
	if(!isset($_SESSION['cityid']))     {$_SESSION['cityid'] = '0';}
  
  $saved = "true";
 // $insertGoTo = "PatShow1.php?show=PatInfoView.php&mrn=".$_SESSION['mrn'];  
 // header(sprintf("Location: %s", $insertGoTo));
}

$colname_info = "-1";
if (isset($_GET['id'])) {
  $colname_info = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_info = sprintf("SELECT id, medrecnum, title, occup, married, street, city, locgovt, `state`, country, phone1, phone2, phone3, em_rel, em_fname, em_lname, em_phone1, em_phone2, entrydt, entryby, comments FROM patinfo WHERE id = %s", $colname_info);
$info = mysql_query($query_info, $swmisconn) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
$totalRows_info = mysql_num_rows($info);



//If Add Country is clicked	
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpie2') {
//echo $_POST['MM_update'].$_POST['country'];	
$insertSQL = sprintf("INSERT INTO country (country) VALUES (%s)", GetSQLValueString($_POST['country2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add State is clicked
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpie3') {
//echo $_POST['MM_update'].'   '.$_POST['state'];
$insertSQL = sprintf("INSERT INTO state (countryid, state) VALUES (%s, %s)", GetSQLValueString($_SESSION['countryid'], "int"), GetSQLValueString($_POST['state2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add Locgovt is clicked
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpie4') {
echo $_POST['MM_update'].'   '.$_POST['locgovt2'];
$insertSQL = sprintf("INSERT INTO locgovt (stateid, locgovt) VALUES (%s, %s)", GetSQLValueString($_SESSION['stateid'], "int"), GetSQLValueString($_POST['locgovt2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add City is clicked	
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpie5') {
echo $_POST['MM_update'].'   '.$_POST['city2'];
$insertSQL = sprintf("INSERT INTO city (locgovtid, city) VALUES (%s, %s)", GetSQLValueString($_SESSION['locgovtid'], "int"), GetSQLValueString($_POST['city2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}
	
	
//List of countries for Drop down list
mysql_select_db($database_swmisconn, $swmisconn);
$query_countries = "SELECT id, country FROM country ORDER BY country ASC";
$countries = mysql_query($query_countries, $swmisconn) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
$totalRows_countries = mysql_num_rows($countries);

//List of States for Drop down list
if (isset($_POST['country'])) {
	$_SESSION['countryid'] = (get_magic_quotes_gpc()) ? $_POST['country'] : addslashes($_POST['country']);
}
else {
	if (isset($_GET['country'])) {
	$_SESSION['countryid'] = (get_magic_quotes_gpc()) ? $_GET['country'] : addslashes($_GET['country']);
	}
} 
$MM_states = $_SESSION['countryid'];
mysql_select_db($database_swmisconn, $swmisconn);
$query_states = sprintf("SELECT id, countryid, state FROM state WHERE countryid = %s ORDER BY state", $MM_states);
$states = mysql_query($query_states, $swmisconn) or die(mysql_error());
$row_states = mysql_fetch_assoc($states);
$totalRows_states = mysql_num_rows($states);

//List of Local Govts for Drop down list

if (isset($_POST['state'])) {
	$_SESSION['stateid'] = (get_magic_quotes_gpc()) ? $_POST['state'] : addslashes($_POST['state']);
}
else {
	if (isset($_GET['state'])) {
	$_SESSION['stateid'] = (get_magic_quotes_gpc()) ? $_GET['state'] : addslashes($_GET['state']);
	}
} 

if($_SESSION['countryid'] == '0') {
	$_SESSION['stateid'] = '0';
	$_SESSION['locgovtid'] = '0';
	$_SESSION['cityid'] = '0';
}
$MM_locgovts = $_SESSION['stateid'];
mysql_select_db($database_swmisconn, $swmisconn);
$query_locgovts = sprintf("SELECT id, locgovt, stateid FROM locgovt WHERE stateid = %s ORDER BY locgovt", $MM_locgovts);
$locgovts = mysql_query($query_locgovts, $swmisconn) or die(mysql_error());
$row_locgovts = mysql_fetch_assoc($locgovts);
$totalRows_locgovts = mysql_num_rows($locgovts);

//List of Cities for Drop down list

if (isset($_POST['locgovt'])) {
	$_SESSION['locgovtid'] = (get_magic_quotes_gpc()) ? $_POST['locgovt'] : addslashes($_POST['locgovt']);
}
else {
	if (isset($_GET['locgovt'])) {
	$_SESSION['locgovtid'] = (get_magic_quotes_gpc()) ? $_GET['locgovt'] : addslashes($_GET['locgovt']);
	}
} 

if($_SESSION['stateid'] == '0') {
	$_SESSION['locgovtid'] = '0';
	$_SESSION['cityid'] = '0';
}
$MM_cities = $_SESSION['locgovtid'];
mysql_select_db($database_swmisconn, $swmisconn);
$query_cities = sprintf("SELECT id, locgovtid, city FROM city WHERE locgovtid = %s ORDER BY city", $MM_cities);
$cities = mysql_query($query_cities, $swmisconn) or die(mysql_error());
$row_cities = mysql_fetch_assoc($cities);
$totalRows_cities = mysql_num_rows($cities);


if (isset($_POST['city'])) {
	$_SESSION['cityid'] = (get_magic_quotes_gpc()) ? $_POST['city'] : addslashes($_POST['city']);
}
else {
	if (isset($_GET['city'])) {
	$_SESSION['cityid'] = (get_magic_quotes_gpc()) ? $_GET['city'] : addslashes($_GET['city']);
	}
} 

if($_SESSION['locgovtid'] == '0') {
	$_SESSION['cityid'] = '0';
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient Info Edit</title>
<link href="../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
	<?php }?>
	<body>
<table width="40%" border="0" align="center">
  <form id="formpie1" name="formpie1" method="post" action="<?php echo $editFormAction; ?>">
    <tr>
      <td colspan="6" align="center" class="BlueBold_18">EDIT PATIENT INFORMATION </td>
    </tr>
    <tr>
      <td colspan="6" align="center" bgcolor="#F8FDCE">Click Country Select to reset</td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#F8FDCE">About</td>
      <td colspan="2" align="center" bgcolor="#F8FDCE" nowrap="nowrap">Residential Address</td>
      <td colspan="2" align="center" bgcolor="#F8FDCE">Emergency Contact Information </td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">*Title:</div></td>
      <td bgcolor="#F8FDCE"><select name="title" type="text">
        <option value="Select" <?php if (!(strcmp("", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="Mr." <?php if (!(strcmp("Mr.", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Mr.</option>
        <option value="Mrs." <?php if (!(strcmp("Mrs.", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Mrs.</option>
        <option value="Miss" <?php if (!(strcmp("Miss", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Miss</option>
        <option value="Dr." <?php if (!(strcmp("Dr.", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Dr.</option>
        <option value="Tor" <?php if (!(strcmp("Tor", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Tor</option>
        <option value="Pastor" <?php if (!(strcmp("Pastor", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Pastor</option>
        <option value="Child" <?php if (!(strcmp("Child", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Child</option>
        <option value="Neonate" <?php if (!(strcmp("Neonate", $row_info['title']))) {echo "selected=\"selected\"";} ?>>Neonate</option>
        <option value="Infant" <?php if (!(strcmp("Infant",$row_info['title']))) {echo "selected=\"selected\"";} ?>>Infant</option>
      </select>      </td>
      <td bgcolor="#F8FDCE" align="right" nowrap="nowrap">*Country</td>
      <td bgcolor="#F8FDCE"><select name="country" id="country" onchange="document.formpie1.submit();">
        <option value="0" <?php if (!(strcmp("", $row_info['country']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
do {  $row_info
?>
        <option value="<?php echo $row_countries['id']?>"<?php if (!(strcmp($row_countries['id'], $_SESSION['countryid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_countries['country']?></option>
        <?php
} while ($row_countries = mysql_fetch_assoc($countries));
  $rows = mysql_num_rows($countries);
  if($rows > 0) {
      mysql_data_seek($countries, 0);
	  $row_countries = mysql_fetch_assoc($countries);
  }
?>
      </select>      </td>
      <td bgcolor="#F8FDCE"><div align="right"> First Name: </div></td>
      <td bgcolor="#F8FDCE"><input type="text" name="em_fname" value="<?php echo $row_info['em_fname'] ?>" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">*Occupation:</div></td>
      <td bgcolor="#F8FDCE"><input name="occup" type="text" value="<?php echo $row_info['occup'] ?>" autocomplete="off"/></td>
      <td bgcolor="#F8FDCE" align="right" nowrap="nowrap">*State</td>
      <td bgcolor="#F8FDCE"><select name="state" id="state" onchange="document.formpie1.submit();">
        <option value="0" <?php if (!(strcmp("", $row_info['state']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
		do {  
		?>
        <option value="<?php echo $row_states['id']?>"<?php if (!(strcmp($row_states['id'], $_SESSION['stateid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_states['state']?></option>
        <?php
} while ($row_states = mysql_fetch_assoc($states));
  $rows = mysql_num_rows($states);
  if($rows > 0) {
      mysql_data_seek($states, 0);
	  $row_states = mysql_fetch_assoc($states);
  }
?>
      </select></td>
      <td bgcolor="#F8FDCE"><div align="right"> Last Name: </div></td>
      <td bgcolor="#F8FDCE"><input type="text" name="em_lname" value="<?php echo $row_info['em_lname'] ?>" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">*Marital Status:</div></td>
      <td bgcolor="#F8FDCE"><select name="married" >
        <option value="Select">Select</option>
        <option value="Married" <?php if (!(strcmp("Married", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Married</option>
        <option value="Single" <?php if (!(strcmp("Single", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Single</option>
        <option value="Widow" <?php if (!(strcmp("Widow", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Widow</option>
        <option value="Widower" <?php if (!(strcmp("Widower", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Widower</option>
        <option value="Engaged" <?php if (!(strcmp("Engaged", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Engaged</option>
        <option value="Child" <?php if (!(strcmp("Child", $row_info['married']))) {echo "selected=\"selected\"";} ?>>Child</option>
      </select></td>
      <td bgcolor="#F8FDCE" align="right" nowrap="nowrap">*Local Govt </td>
      <td bgcolor="#F8FDCE"><select name="locgovt" id="locgovt" onchange="document.formpie1.submit();">
        <option value="0" <?php if (!(strcmp("", $row_info['locgovt']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
do {  
?>
        <option value="<?php echo $row_locgovts['id']?>"<?php if (!(strcmp($row_locgovts['id'], $_SESSION['locgovtid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_locgovts['locgovt']?></option>
        <?php
} while ($row_locgovts = mysql_fetch_assoc($locgovts));
  $rows = mysql_num_rows($locgovts);
  if($rows > 0) {
      mysql_data_seek($locgovts, 0);
	  $row_locgovts = mysql_fetch_assoc($locgovts);
  }
?>
      </select></td>
      <td bgcolor="#F8FDCE"><div align="right"> Relationship: </div></td>
      <td bgcolor="#F8FDCE"><select name="em_rel" >
        <option value="Select">Select</option>
<!--        <option value="" <?php //if (!(strcmp("", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>> </option>  not needed if required-->
        <option value="Husband" <?php if (!(strcmp("Husband", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Husband</option>
        <option value="Wife" <?php if (!(strcmp("Wife", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Wife</option>
        <option value="Father" <?php if (!(strcmp("Father", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Father</option>
        <option value="Mother" <?php if (!(strcmp("Mother", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Mother</option>
        <option value="Son" <?php if (!(strcmp("Son", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Son</option>
        <option value="Daughter" <?php if (!(strcmp("Daughter", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Daughter</option>
        <option value="Brother" <?php if (!(strcmp("Brother", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Brother</option>
        <option value="Sister" <?php if (!(strcmp("Sister", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Sister</option>
        <option value="Aunt" <?php if (!(strcmp("Aunt", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Aunt</option>
        <option value="Uncle" <?php if (!(strcmp("Uncle", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Uncle</option>
        <option value="Niece" <?php if (!(strcmp("Niece", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Niece</option>
        <option value="Nephew" <?php if (!(strcmp("Nephew", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Nephew</option>
        <option value="GrandMother" <?php if (!(strcmp("GrandMother", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>GrandMother</option>
        <option value="GrandFather" <?php if (!(strcmp("GrandFather", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>GrandFather</option>
        <option value="GrandDaughter" <?php if (!(strcmp("GrandDaughter", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>GrandDaughter</option>
        <option value="GrandSon" <?php if (!(strcmp("GrandSon", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>GrandSon</option>
        <option value="Friend" <?php if (!(strcmp("Friend", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Friend</option>
        <option value="Guardian" <?php if (!(strcmp("Guardian", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>Guardian</option>
        <option value="In-law" <?php if (!(strcmp("In-law", $row_info['em_rel']))) {echo "selected=\"selected\"";} ?>>In-law</option>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">Phone 1: </div></td>
      <td bgcolor="#F8FDCE"><input name="phone1" type="text" value="<?php echo $row_info['phone1'] ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#F8FDCE" align="right" nowrap="nowrap">*City</td>
      <td bgcolor="#F8FDCE"><select name="city" id="city" onchange="document.formpie1.submit();">
        <option value="0" <?php if (!(strcmp("", $row_info['city']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
do {  
?>
        <option value="<?php echo $row_cities['id']?>"<?php if (!(strcmp($row_cities['id'], $_SESSION['cityid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cities['city']?></option>
        <?php
} while ($row_cities = mysql_fetch_assoc($cities));
  $rows = mysql_num_rows($cities);
  if($rows > 0) {
      mysql_data_seek($cities, 0);
	  $row_cities = mysql_fetch_assoc($cities);
  }
?>
      </select></td>
      <td bgcolor="#F8FDCE"><div align="right"> Phone1: </div></td>
      <td bgcolor="#F8FDCE"><input name="em_phone1" type="text" value="<?php echo $row_info['em_phone1']?>" size="13" maxlength="15	" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">Phone 2: </div></td>
      <td bgcolor="#F8FDCE"><input name="phone2" type="text" value="<?php echo $row_info['phone2'] ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#F8FDCE" align="right" nowrap="nowrap">*Address:</td>
      <td bgcolor="#F8FDCE"><input name="street" type="text" id="street" autocomplete="off" value="<?php echo $row_info['street'] ?>" /></td>
      <td bgcolor="#F8FDCE"><div align="right">Phone 2:</div></td>
      <td bgcolor="#F8FDCE"><input name="em_phone2" type="text" value="<?php echo $row_info['em_phone2'] ?>" size="13" maxlength="15" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#F8FDCE"><div align="right">Phone 3: </div></td>
      <td bgcolor="#F8FDCE"><input name="phone3" type="text" value="<?php echo $row_info['phone3'] ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#F8FDCE">Comments</td>
      <td colspan="3" bgcolor="#F8FDCE" valign="top"><textarea name="comments" cols="50" rows="1" id="comments" value="<?php echo $row_info['comments']; ?>"><?php echo $row_info['comments']; ?></textarea></td>
    </tr>
    <tr>
      <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_info['medrecnum']; ?>" />
      <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
      <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
      <input name="id" type="hidden" id="id" value="<?php echo $row_info['id']; ?>" />
      <input name="MM_Update" type="hidden" value="formpie1" />
      <td></td>
      <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
      </div></td>
      <td colspan="2"><input name="SubmitAll" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit Patient Address" />      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </form>
</table>
<table>
     <?php if ($_SESSION['countryid']=='0') {?>
	   <tr>
        <form id="formpie2" name="formpie2" method="post" action="PatAddrEdit.php">
          <td colspan="2" align="right" nowrap="nowrap">New Country</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="country2" id="country2" />
			<input type="hidden" name="MM_update" value="formpie2" />
            <input type="submit" name="button2" id="button2" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add" /></td>
        </form>
	   </tr>
     <?php } ?>
	   
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] == '0') {?>
	   <tr>
        <form id="formpie3" name="formpie3" method="post" action="PatAddrEdit.php">
          <td colspan="2" align="right" nowrap="nowrap">New State</td>
          <td colspan="2" nowrap="nowrap"><input type="text" name="state2" id="state2" />
            <input type="hidden" name="MM_update" value="formpie3" />
          <input type="submit" name="button3" id="button3" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add" /></td>
        </form>
	   </tr>
     <?php } ?>
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] != '0' AND $_SESSION['locgovtid'] == '0') {?>
       <tr>
        <form id="formpie4" name="formpie4" method="post" action="PatAddrEdit.php">
          <td colspan="2" align="right">Add Loc Govt</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="locgovt2" id="locgovt2" />
			<input type="hidden" name="MM_update" value="formpie4" />
            <input type="submit" name="button4" id="button4" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add" /></td>
        </form>
       </tr>
     <?php } ?>
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] != '0' AND $_SESSION['locgovtid'] != '0' AND $_SESSION['cityid'] == '0') {?>
      <tr>
        <form id="formpie5" name="formpie5" method="post" action="PatAddrEdit.php">
          <td colspan="2" align="right">Add City</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="city2" id="city2" />
			<input type="hidden" name="MM_update" value="formpie5" />
            <input type="submit" name="button5" id="button5" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add" /></td>
        </form>
      </tr>
     <?php }?>
	 
	  <tr>
	  	<td><!--<input type="submit" name="button6" id="button6" value="Add Address" />--></td>
	  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($states);

mysql_free_result($locgovts);

mysql_free_result($cities);

mysql_free_result($info);

mysql_free_result($countries);
?>
<script  type="text/javascript">
 var frmvalidator = new Validator("formpie1");
 frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("country","dontselect=0", "Please Select Country");
 frmvalidator.addValidation("country","req","Please enter Patient Country");

 frmvalidator.addValidation("state","dontselect=0", "Please Select State");
 frmvalidator.addValidation("state","req","Please enter State");

 frmvalidator.addValidation("locgovt","dontselect=0", "Please Select Locgovt");
 frmvalidator.addValidation("locgovt","req","Please enter Locgovt");

 frmvalidator.addValidation("city","dontselect=0", "Please Select City");
 frmvalidator.addValidation("city","req","Please enter City");


 frmvalidator.addValidation("title","req","Please enter Patient Title");
 frmvalidator.addValidation("title","dontselect=Select", "Please Select Title");

 frmvalidator.addValidation("married","dontselect=Select", "Please Select Marital Status");

 frmvalidator.addValidation("em_rel","req","Please enter Relationship");
 frmvalidator.addValidation("em_rel","dontselect=Select", "Please Select Emergency Relationship");
 
 frmvalidator.addValidation("occup","req","Please enter Patient Occupation");
 frmvalidator.addValidation("occup","maxlen=30", "Max length for Occupation is 30");
 frmvalidator.addValidation("occup","alnum_s", "Occupation: alphabetic or numeric characters only - spaces allowed");

 frmvalidator.addValidation("street","req","Please enter patient Street Address");
 frmvalidator.addValidation("street","maxlen=40", "Max length for Address is 40");
 frmvalidator.addValidation("street","alnum_s", "Address: alphabetic or numeric characters only - spaces allowed");

 frmvalidator.addValidation("em_fname","req","Please enter Emergency Contact First Name");
 frmvalidator.addValidation("em_fname","minlen=3", "Minimum length for FirstName is 3");
 frmvalidator.addValidation("em_fname","maxlen=15", "Max length for FirstName is 15");
 frmvalidator.addValidation("em_fname","alnum_s", "Emerg First Name: alphabetic or numeric characters only - spaces allowed");

 frmvalidator.addValidation("em_lname","req","Please enter Emergency Contact Last Name");
 frmvalidator.addValidation("em_lname","minlen=3", "Minimum length for LastName is 3");
 frmvalidator.addValidation("em_lname","maxlen=15", "Max length for LastName is 15");
 frmvalidator.addValidation("em_lname","alnum_s", "Emerg Last Name: alphabetic or numeric characters only - spaces allowed");

 frmvalidator.addValidation("phone1","req","Please enter patient Phone Number");
 frmvalidator.addValidation("phone1","num", "Phone 1: numeric characters only");
 frmvalidator.addValidation("phone1","maxlen=15", "Maximum Length for Phone 1 Number is 15 numbers");
 frmvalidator.addValidation("phone1","minlen=11", "Minimum Length for Phone 1 Number is 11 numbers");  //OK if required

 frmvalidator.addValidation("phone2","num", "Phone 2: numeric characters only");
 frmvalidator.addValidation("phone2","maxlen=15", "Maximum Length for Phone 2 Number is 15 numbers");
 //frmvalidator.addValidation("phone2","minlen=11", "Minimum Length for Phone 2 Number is 11 numbers"); //(doesn't allow blank)

 frmvalidator.addValidation("phone3","num", "Phone 3: numeric characters only");
 frmvalidator.addValidation("phone3","maxlen=15", "Maximum Length for Phone 3 Number is 15 numbers");
 //frmvalidator.addValidation("phone3","minlen=11", "Minimum Length for Phone 3 Number is 11 numbers"); //(doesn't allow blank)

 frmvalidator.addValidation("em_phone1","req","Please enter Emergency Contact Phone 1 Number");
 frmvalidator.addValidation("em_phone1","num", "Emerg Phone 1: numeric characters only");
 frmvalidator.addValidation("em_phone1","maxlen=15", "Maximum Length for Emergency Contact Phone Number 1 is 15 numbers");
 frmvalidator.addValidation("em_phone1","minlen=11", "Minimum Length for Emergency Contact Phone Number 1 is 11 numbers");  //(ok if required)

 frmvalidator.addValidation("em_phone2","num", "Emerg Phone 2: numeric characters only");
 frmvalidator.addValidation("em_phone2","maxlen=15", "Maximum Length for Emergency Contact Phone Number 2 is 15 numbers");
 //frmvalidator.addValidation("em_phone2","minlen=11", "Minimum Length for Emergency Contact Phone Number 2 is 11 numbers");  //(doesn't allow blank)

 
</script>


<script language="JavaScript" type="text/JavaScript">
<!--

//function openBrWindow(theURL,winName,features) { //v2.0
//  window.open(theURL,winName,features);
//}
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}

//function MM_closeBrWindow() { // this works too
//  window.close(); 
//}

//-->
</script>
