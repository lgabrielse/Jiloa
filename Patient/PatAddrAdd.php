<?php //if(!isset($_SESSION['sysconn'])) {
	//$_SESSION['sysconn'] = $_GET['sysco'];
// } ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
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
  $saved = "";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['SubmitAll']) AND $_POST['SubmitAll']  == 'Add Patient Address')  {
  $insertSQL = sprintf("INSERT INTO patinfo (medrecnum, title, occup, married, phone1, phone2, phone3, street, city, locgovt, `state`, country, em_rel, em_fname, em_lname, em_phone1, em_phone2, comments, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
					   GetSQLValueString($_POST['entryby'], "text"),
					   GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
  
	if(!isset($_SESSION['countryid']))  {$_SESSION['countryid'] = '0';}
	if(!isset($_SESSION['stateid']))    {$_SESSION['stateid'] = '0';}
	if(!isset($_SESSION['locgovtid']))  {$_SESSION['locgovtid'] = '0';}
	if(!isset($_SESSION['cityid']))     {$_SESSION['cityid'] = '0';}
  
  $saved = "true";
 // $insertGoTo = "PatShow1.php?show=PatInfoView.php&mrn=".$_SESSION['mrn'];  
 // header(sprintf("Location: %s", $insertGoTo));
}



$medrecnum = "-1";
if (isset($_GET['mrn'])) {
  $medrecnum = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
  $_SESSION['mrn'] = $medrecnum;
}
if (isset($_GET['user'])) {
  $user = (get_magic_quotes_gpc()) ? $_GET['user'] : addslashes($_GET['user']);
$_SESSION['user'] = $user;
}

if (isset($_POST['street'])) { $street = (get_magic_quotes_gpc()) ? $_POST['street'] : addslashes($_POST['street']); }
	else {$street = "";}
if (isset($_POST['title'])) { $title = (get_magic_quotes_gpc()) ? $_POST['title'] : addslashes($_POST['title']); }
	else {$title = "";}
if (isset($_POST['occup'])) { $occup = (get_magic_quotes_gpc()) ? $_POST['occup'] : addslashes($_POST['occup']); }
	else {$occup = "";}
if (isset($_POST['married'])) { $married = (get_magic_quotes_gpc()) ? $_POST['married'] : addslashes($_POST['married']); }
	else {$married = "";}
if (isset($_POST['phone1'])) { $phone1 = (get_magic_quotes_gpc()) ? $_POST['phone1'] : addslashes($_POST['phone1']); }
	else {$phone1 = "";}
if (isset($_POST['phone2'])) { $phone2 = (get_magic_quotes_gpc()) ? $_POST['phone2'] : addslashes($_POST['phone2']); }
	else {$phone2 = "";}
if (isset($_POST['phone3'])) { $phone3 = (get_magic_quotes_gpc()) ? $_POST['phone3'] : addslashes($_POST['phone3']); }
	else {$phone3 = "";}
if (isset($_POST['em_rel'])) { $em_rel = (get_magic_quotes_gpc()) ? $_POST['em_rel'] : addslashes($_POST['em_rel']); }
	else {$em_rel = "";}
if (isset($_POST['em_fname'])) { $em_fname = (get_magic_quotes_gpc()) ? $_POST['em_fname'] : addslashes($_POST['em_fname']); }
	else {$em_fname = "";}
if (isset($_POST['em_lname'])) { $em_lname = (get_magic_quotes_gpc()) ? $_POST['em_lname'] : addslashes($_POST['em_lname']); }
	else {$em_lname = "";}
if (isset($_POST['em_phone1'])) { $em_phone1 = (get_magic_quotes_gpc()) ? $_POST['em_phone1'] : addslashes($_POST['em_phone1']); }
	else {$em_phone1 = "";}
if (isset($_POST['em_phone2'])) { $em_phone2 = (get_magic_quotes_gpc()) ? $_POST['em_phone2'] : addslashes($_POST['em_phone2']); }
	else {$em_phone2 = "";}
if (isset($_POST['comments'])) { $comments = (get_magic_quotes_gpc()) ? $_POST['comments'] : addslashes($_POST['comments']); }
	else {$comments = "";}
	
//If Add Country is clicked	
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpia2') {
//echo $_POST['MM_update'].$_POST['country'];	
$insertSQL = sprintf("INSERT INTO country (country) VALUES (%s)", GetSQLValueString($_POST['country2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add State is clicked
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpia3') {
//echo $_POST['MM_update'].'   '.$_POST['state'];
$insertSQL = sprintf("INSERT INTO state (countryid, state) VALUES (%s, %s)", GetSQLValueString($_SESSION['countryid'], "int"), GetSQLValueString($_POST['state2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add Locgovt is clicked
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpia4') {
echo $_POST['MM_update'].'   '.$_POST['locgovt2'];
$insertSQL = sprintf("INSERT INTO locgovt (stateid, locgovt) VALUES (%s, %s)", GetSQLValueString($_SESSION['stateid'], "int"), GetSQLValueString($_POST['locgovt2'], "text"));
mysql_select_db($database_swmisconn, $swmisconn);
$Result1 = mysql_query($insertSQL,  $swmisconn) or die(mysql_error());
}

//If Add City is clicked	
if (isset($_POST['MM_update']) AND $_POST['MM_update'] == 'formpia5') {
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
if($_SESSION['countryid'] == '0') {
	$_SESSION['stateid'] = '0';
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
if($_SESSION['stateid'] == '0') {
	$_SESSION['locgovtid'] = '0';
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
if($_SESSION['locgovtid'] == '0') {
	$_SESSION['cityid'] = '0';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient Info Add</title>
<link href="../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>

<body>
<table width="40%" border="0" align="center">
  <form id="formpia1" name="formpia1" method="post" action="<?php echo $editFormAction; ?>">
    <?php $showid = 'true';
		if($showid == 'true') {?>
    <!--    <tr>
      <td nowrap="nowrap"><?php if(isset($_SESSION['countryid']))  {?>
          <?php echo $_SESSION['countryid']; } ?></td>
      <td nowrap="nowrap"><?php if(isset($_SESSION['stateid']))  {?>
          <?php echo $_SESSION['stateid']; }?></td>
      <td nowrap="nowrap"><?php if(isset($_SESSION['locgovtid']))  {?>
          <?php echo $_SESSION['locgovtid']; }?></td>
      <td nowrap="nowrap"><?php if(isset($_SESSION['cityid']))  {?>
          <?php echo $_SESSION['cityid'] ; }?></td>
    </tr>
-->
    <?php }?>
    <tr>
      <td colspan="6" align="center" class="BlueBold_18">ADD PATIENT INFORMATION </td>
    </tr>
    <tr>
      <td colspan="6" align="center" bgcolor="#BCFACC">* = Required&nbsp;&nbsp;&nbsp;&nbsp;Click Country Select to reset</td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#BCFACC" class="BlackBold_14">About</td>
      <td colspan="2" align="center" bgcolor="#BCFACC" nowrap="nowrap">Residential Address</td>
      <td colspan="2" align="center" bgcolor="#BCFACC">Emergency Contact Information </td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">*Title:</div></td>
      <td bgcolor="#BCFACC"><select type="text" name="title">
        <option value="Select" <?php if (!(strcmp("", $title))) {echo "selected=\"selected\"";} ?>>Select</option>
        <option value="Mr." <?php if (!(strcmp("Mr.", $title))) {echo "selected=\"selected\"";} ?>>Mr.</option>
        <option value="Mrs." <?php if (!(strcmp("Mrs.", $title))) {echo "selected=\"selected\"";} ?>>Mrs.</option>
        <option value="Miss" <?php if (!(strcmp("Miss", $title))) {echo "selected=\"selected\"";} ?>>Miss</option>	
        <option value="Dr." <?php if (!(strcmp("Dr.", $title))) {echo "selected=\"selected\"";} ?>>Dr.</option>
        <option value="Tor" <?php if (!(strcmp("Tor", $title))) {echo "selected=\"selected\"";} ?>>Tor</option>
        <option value="Pastor" <?php if (!(strcmp("Pastor", $title))) {echo "selected=\"selected\"";} ?>>Pastor</option>
        <option value="Child" <?php if (!(strcmp("Child", $title))) {echo "selected=\"selected\"";} ?>>Child</option>
        <option value="Neonate" <?php if (!(strcmp("Neonate", $title))) {echo "selected=\"selected\"";} ?>>Neonate</option>
        <option value="Infant" <?php if (!(strcmp("Infant", $title))) {echo "selected=\"selected\"";} ?>>Infant</option>
        </select>      </td>
      <td bgcolor="#BCFACC" align="right" nowrap="nowrap">*Country</td>
      <td bgcolor="#BCFACC"><select name="country" id="country" onchange="document.formpia1.submit();">
        <option value="0" <?php if (!(strcmp("", $_SESSION['countryid']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
do {  
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
      <td bgcolor="#BCFACC"><div align="right"> First Name: </div></td>
      <td bgcolor="#BCFACC"><input type="text" name="em_fname" value="<?php echo $em_fname ?>" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">*Marital Status:</div></td>
      <td bgcolor="#BCFACC"><select name="married" data-validation="length" data-validation-length="min3" >
        <option value="Select">Select</option>
        <option value="Married" <?php if (!(strcmp("Married", $married))) {echo "selected=\"selected\"";} ?>>Married</option>
        <option value="Single" <?php if (!(strcmp("Single", $married))) {echo "selected=\"selected\"";} ?>>Single</option>
        <option value="Widow" <?php if (!(strcmp("Widow", $married))) {echo "selected=\"selected\"";} ?>>Widow</option>
        <option value="Widower" <?php if (!(strcmp("Widower", $married))) {echo "selected=\"selected\"";} ?>>Widower</option>
        <option value="Engaged" <?php if (!(strcmp("Engaged", $married))) {echo "selected=\"selected\"";} ?>>Engaged</option>
        <option value="Child" <?php if (!(strcmp("Child", $married))) {echo "selected=\"selected\"";} ?>>Child</option>
      </select></td>
      <td bgcolor="#BCFACC" align="right" nowrap="nowrap">*State</td>
      <td bgcolor="#BCFACC"><select name="state" id="state" onchange="document.formpia1.submit();">
        <option value="0" <?php if (!(strcmp("", $_SESSION['stateid']))) {echo "selected=\"selected\"";} ?>>Select</option>
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
      <td bgcolor="#BCFACC"><div align="right"> Last Name: </div></td>
      <td bgcolor="#BCFACC"><input type="text" name="em_lname" value="<?php echo $em_lname ?>" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">*Occupation:</div></td>
      <td bgcolor="#BCFACC"><input type="text" name="occup" autocomplete="off" data-validation="required" value="<?php echo $occup ?>" /></td>
      <td bgcolor="#BCFACC" align="right" nowrap="nowrap">*Local Govt </td>
      <td bgcolor="#BCFACC"><select name="locgovt" id="locgovt" onchange="document.formpia1.submit();">
        <option value="0" <?php if (!(strcmp("", $_SESSION['locgovtid']))) {echo "selected=\"selected\"";} ?>>Select</option>
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
      <td bgcolor="#BCFACC"><div align="right"> Relationship: </div></td>
      <td bgcolor="#BCFACC"><select name="em_rel" >
	  	<option value="Select">Select</option>
        <option value="" <?php if (!(strcmp("", $em_rel))) {echo "selected=\"selected\"";} ?>> </option>
        <option value="Husband" <?php if (!(strcmp("Husband", $em_rel))) {echo "selected=\"selected\"";} ?>>Husband</option>
        <option value="Wife" <?php if (!(strcmp("Wife", $em_rel))) {echo "selected=\"selected\"";} ?>>Wife</option>
        <option value="Father" <?php if (!(strcmp("Father", $em_rel))) {echo "selected=\"selected\"";} ?>>Father</option>
        <option value="Mother" <?php if (!(strcmp("Mother", $em_rel))) {echo "selected=\"selected\"";} ?>>Mother</option>
        <option value="Son" <?php if (!(strcmp("Son", $em_rel))) {echo "selected=\"selected\"";} ?>>Son</option>
        <option value="Daughter" <?php if (!(strcmp("Daughter", $em_rel))) {echo "selected=\"selected\"";} ?>>Daughter</option>
        <option value="Brother" <?php if (!(strcmp("Brother", $em_rel))) {echo "selected=\"selected\"";} ?>>Brother</option>
        <option value="Sister" <?php if (!(strcmp("Sister", $em_rel))) {echo "selected=\"selected\"";} ?>>Sister</option>
        <option value="Aunt" <?php if (!(strcmp("Aunt", $em_rel))) {echo "selected=\"selected\"";} ?>>Aunt</option>
        <option value="Uncle" <?php if (!(strcmp("Uncle", $em_rel))) {echo "selected=\"selected\"";} ?>>Uncle</option>
        <option value="Niece" <?php if (!(strcmp("Niece", $em_rel))) {echo "selected=\"selected\"";} ?>>Niece</option>
        <option value="Nephew" <?php if (!(strcmp("Nephew", $em_rel))) {echo "selected=\"selected\"";} ?>>Nephew</option>
        <option value="GrandMother" <?php if (!(strcmp("GrandMother", $em_rel))) {echo "selected=\"selected\"";} ?>>GrandMother</option>
        <option value="GrandFather" <?php if (!(strcmp("GrandFather", $em_rel))) {echo "selected=\"selected\"";} ?>>GrandFather</option>
        <option value="GrandDaughter" <?php if (!(strcmp("GrandDaughter", $em_rel))) {echo "selected=\"selected\"";} ?>>GrandDaughter</option>
        <option value="GrandSon" <?php if (!(strcmp("GrandSon", $em_rel))) {echo "selected=\"selected\"";} ?>>GrandSon</option>
        <option value="Friend" <?php if (!(strcmp("Friend", $em_rel))) {echo "selected=\"selected\"";} ?>>Friend</option>
        <option value="Guardian" <?php if (!(strcmp("Guardian", $em_rel))) {echo "selected=\"selected\"";} ?>>Guardian</option>
        <option value="In-law" <?php if (!(strcmp("In-law", $em_rel))) {echo "selected=\"selected\"";} ?>>In-law</option>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">Phone 1: </div></td>
      <td bgcolor="#BCFACC"><input name="phone1" type="text" value="<?php echo $phone1 ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#BCFACC" align="right" nowrap="nowrap">*City</td>
      <td bgcolor="#BCFACC"><select name="city" id="city" onchange="document.formpia1.submit();">
        <option value="0" <?php if (!(strcmp("", $_SESSION['cityid']))) {echo "selected=\"selected\"";} ?>>Select</option>
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
      <td bgcolor="#BCFACC"><div align="right"> Phone1: </div></td>
      <td bgcolor="#BCFACC"><input name="em_phone1" type="text" value="<?php echo $em_phone1 ?>" size="13" maxlength="15" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">Phone 2: </div></td>
      <td bgcolor="#BCFACC"><input name="phone2" type="text" value="<?php echo $phone2 ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#BCFACC" align="right" nowrap="nowrap">*Address:</td>
      <td bgcolor="#BCFACC"><input name="street" type="text" id="street" autocomplete="off" value="<?php echo $street ?>" /></td>
      <td bgcolor="#BCFACC"><div align="right">Phone 2:</div></td>
      <td bgcolor="#BCFACC"><input name="em_phone2" type="text" value="<?php echo $em_phone2 ?>" size="13" maxlength="15" autocomplete="off" /></td>
    </tr>
    <tr>
      <td bgcolor="#BCFACC"><div align="right">Phone 3: </div></td>
      <td bgcolor="#BCFACC"><input name="phone3" type="text" value="<?php echo $phone3 ?>" size="13" maxlength="15" autocomplete="off" /></td>
      <td bgcolor="#BCFACC" class="Link">Comments</td>
	  <td colspan="4" bgcolor="#BCFACC" valign="top"><textarea name="comments" cols="50" rows="1" id="comments" value="<?php echo $comments ?>"><?php echo $comments ?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="Link"><div align="center">
        <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
      </div></td>
      <input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
      <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
      <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
      <td><input name="SubmitAll" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Patient Address" /></td>
  </form>
      <?php /* if(isset($_SESSION['countryid']) AND isset($_SESSION['stateid']) AND isset($_SESSION['locgovtid']) AND isset($_SESSION['cityid'])) {?>
      <input name="country" type="hidden" id="country" value="<?php echo $_SESSION['countryid']; ?>" />
      <input name="state" type="hidden" id="state" value="<?php echo $_SESSION['stateid']; ?>" />
      <input name="locgovt" type="hidden" id="locgovt" value="<?php echo $_SESSION['locgovtid']; ?>" />
      <input name="city" type="hidden" id="city" value="<?php echo $_SESSION['cityid']; ?>" />
      <input name="MM_Insert" type="hidden" value="form7" />
      <td><input name="Submit" type="submit" value="Add Patient Address" /></td>
  <?php }*/?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<table>
     <?php if ($_SESSION['countryid']=='0') {?>
	   <tr>
        <form id="formpia2" name="formpia2" method="post" action="PatAddrAdd.php">
          <td colspan="2" align="right" nowrap="nowrap">New Country</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="country2" id="country2" />
			<input type="hidden" name="MM_update" value="formpia2" />
            <input type="submit" name="button2" id="button2" value="Add" /></td>
        </form>
	   </tr>
     <?php } ?>
	   
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] == '0') {?>
	   <tr>
        <form id="formpia3" name="formpia3" method="post" action="PatAddrAdd.php">
          <td colspan="2" align="right" nowrap="nowrap">New State</td>
          <td colspan="2" nowrap="nowrap"><input type="text" name="state2" id="state2" />
            <input type="hidden" name="MM_update" value="formpia3" />
          <input type="submit" name="button3" id="button3" value="Add" /></td>
        </form>
	   </tr>
     <?php } ?>
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] != '0' AND $_SESSION['locgovtid'] == '0') {?>
       <tr>
        <form id="formpia4" name="formpia4" method="post" action="PatAddrAdd.php">
          <td colspan="2" align="right">Add Loc Govt</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="locgovt2" id="locgovt2" />
			<input type="hidden" name="MM_update" value="formpia4" />
            <input type="submit" name="button4" id="button4" value="Add" /></td>
        </form>
       </tr>
     <?php } ?>
     <?php if ($_SESSION['countryid'] != '0' AND $_SESSION['stateid'] != '0' AND $_SESSION['locgovtid'] != '0' AND $_SESSION['cityid'] == '0') {?>
      <tr>
        <form id="formpia5" name="formpia5" method="post" action="PatAddrAdd.php">
          <td colspan="2" align="right">Add City</td>
          <td colspan="2" nowrap="nowrap">
		  	<input type="text" name="city2" id="city2" />
			<input type="hidden" name="MM_update" value="formpia5" />
            <input type="submit" name="button5" id="button5" value="Add" /></td>
        </form>
      </tr>
     <?php }?>
	 
	  <tr>
	  	<td><!--<input type="submit" name="button6" id="button6" value="Add Address" />--></td>
	  </tr>
</table>
<script  type="text/javascript">
 var frmvalidator = new Validator("formpia1");
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

 frmvalidator.addValidation("street","req","Please enter Patient Street Address");
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

<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>
  $.validate({
/*  validateOnBlur : false, // disable validation when input looses focus
    errorMessagePosition : 'top', // Instead of 'element' which is default
    scrollToTopOnError : false // Set this property to true if you have a long form*/
  });
</script>
</body>
</html>
<?php
mysql_free_result($states);

mysql_free_result($locgovts);

mysql_free_result($cities);

mysql_free_result($countries);
?>

<script language="JavaScript" type="text/JavaScript">
<!--

function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}

function MM_closeBrWindow() { // this works too
  window.close(); 
}

//-->
</script>
