<?php //require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); //used when login is used to connect to swmisbethany database using mysql ?>
<?php require_once('../../Connections/i_swmisconn.php'); // used to connect to wmisbethany database using mysqli ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>

<?php // verify there is an id and set value to a variable
$colname_id = 261;
if (isset($_GET['vfeeid'])) {
  $colname_id = (get_magic_quotes_gpc()) ? $_GET['vfeeid'] : addslashes($_GET['vfeeid']);
}
?>
  
<?php  //****************** query to get list of beds for a location from patbed table in swmisbethany database
mysqli_select_db($swmisconn, $database_swmisconn);
$mysqli_bedlist_query = "SELECT b.id, b.feeid, f.name, b.bed, b.active, b.medrecnum FROM patbed b JOIN fee f on f.id=b.feeid where b.active = 'Y' AND b.feeid = '".$colname_id."' ORDER BY b.bed";
$bedlist = mysqli_query($swmisconn, $mysqli_bedlist_query) or die(mysqli_error($swmisconn));
$row_bedlist = mysqli_fetch_assoc($bedlist);
$totalRows_bedlist = mysqli_num_rows($bedlist);
?>
<?php // check for message
$message = "";
if (isset($_GET['message'])) {
  $message = (get_magic_quotes_gpc()) ? $_GET['message'] : addslashes($_GET['message']);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Patient Bed</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="40%" border="1" align="center" cellpadding="1" cellspacing="1" bgcolor="fff8f8" style="border-collapse: collapse;">
  <caption>
    <strong>INPATIENT BEDS</strong>
  </caption>
  <tr>
    <td align="center">Bed</td>
    <td align="center">Location</td>
    <td align="center">MRN</td>
<?php if(!empty($message)){ ?>
    <td colspan="2" class="flagWhiteonRed"><?php echo $message ?></td>
<?php } else {
	if(isset($user)) { ?>
    <td colspan="2"><?php echo $user ?></td>
<?php	} else { ?> 
		<td colspan="2">&nbsp;</td>   
<?php	}
	 }?>    
  </tr>
  
<?php do {  //function which fetches a result row as an associative array - parameter is the 'result'?>
  <tr>
    <td align="center" title="RecordID: <?php echo $row_bedlist['id'] ?>&#10;location feeid: <?php echo $row_bedlist['feeid'] ?>&#10;Active: <?php echo $row_bedlist['active'] ?>"><?php echo $row_bedlist['bed'] ?></td>
    <td align="center"><?php echo $row_bedlist['name'] ?></td>
    <td align="center"><?php echo $row_bedlist['medrecnum'] ?></td>
    <?php if(!empty($row_bedlist['medrecnum'] )){ ?>
<?php mysqli_select_db($swmisconn, $database_swmisconn);
			$mysqli_patname_query = "SELECT lastname,firstname,othername FROM patperm where medrecnum = ".$row_bedlist['medrecnum']; 
			$patname = mysqli_query($swmisconn, $mysqli_patname_query) or die(mysqli_error($swmisconn));
		  $row_patname = mysqli_fetch_assoc($patname);
			$totalRows_patname = mysqli_num_rows($patname);
?>    
      <td nowrap><?php echo  $row_patname['lastname'].', '.$row_patname['firstname'].', '.$row_patname['othername'] ?></td>
      <td><a href="PatBedAR.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_GET['vid'] ?>&bedact=release&bedid=<?php echo $row_bedlist['id'] ?>&vfeeid=<?php echo $row_bedlist['feeid'] ?>">Release</td>

    <?php } else { ?>
      <td>&nbsp;</td>
    <td><a href="PatBedAR.php?mrn=<?php echo $_SESSION['mrn']; ?>&bedact=assign&bedid=<?php echo $row_bedlist['id'] ?>&vfeeid=<?php echo $row_bedlist['feeid'] ?>">Assign</td>
    <?php } ?>
  </tr>
  <?php } while($row_bedlist = mysqli_fetch_assoc($bedlist)) ?>

</table>
</body>
</html>