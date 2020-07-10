<?php error_reporting(E_ALL ^ E_DEPRECATED);?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
   session_start(); }?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/Len/functions/functions.php'); ?>

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO patcall (medrecnum, entrydt, entryby, `comment`) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['comment'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "CashPaylater.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_patperm = "-1";
if (isset($_GET['mrn'])) {
  $colname_patperm = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}  // Patient Perm data
mysql_select_db($database_swmisconn, $swmisconn);
$query_patperm = "SELECT medrecnum, hospital, active, entrydt, entryby, lastname, firstname, othername, gender, ethnicgroup, DATE_FORMAT(dob,'%d %b %Y') dob, DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age, est FROM patperm WHERE medrecnum = '". $colname_patperm."'";
$patperm = mysql_query($query_patperm, $swmisconn) or die(mysql_error());
$row_patperm = mysql_fetch_assoc($patperm);
$totalRows_patperm = mysql_num_rows($patperm);

mysql_select_db($database_swmisconn, $swmisconn);
$query_paylater = "SELECT o.id oid, o.medrecnum, o.visitid, o.feeid, o.item, o.ofee, o.rate, o.amtdue, o.status, o.doctor, o.comments, o.entryby, o.entrydt, o.amtpaid, v.pat_type, v.location, v.discharge, p.lastname, p.firstname, p.othername, p.gender, p.dob, f.section, f.name FROM orders o join patvisit v on o.visitid = v.id join patperm p on o.medrecnum = p.medrecnum join fee f on o.feeid = f.id WHERE o.medrecnum = '". $colname_patperm."' and billstatus = 'paylater' ORDER BY v.id, o.entrydt DESC";
$paylater = mysql_query($query_paylater, $swmisconn) or die(mysql_error());
$row_paylater = mysql_fetch_assoc($paylater);
$totalRows_paylater = mysql_num_rows($paylater);

mysql_select_db($database_swmisconn, $swmisconn);
$query_PLcomments = "SELECT id, medrecnum, entrydt, entryby, comment FROM patcall WHERE medrecnum = '". $colname_patperm."' ORDER BY entrydt DESC";
$PLcomments = mysql_query($query_PLcomments, $swmisconn) or die(mysql_error());
$row_PLcomments = mysql_fetch_assoc($PLcomments);
$totalRows_PLcomments = mysql_num_rows($PLcomments);
?>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_patinfoview = sprintf("SELECT p.id, medrecnum, title, occup, married, street, ci.id ciid, ci.city, lg.id lgid, lg.locgovt, st.id stid, st.state, co.id coid, co.country, phone1, phone2, phone3, em_rel, em_fname, em_lname, em_phone1, em_phone2, entrydt, entryby, comments FROM patinfo p left outer join country co on p.country = co.id left outer join state st on p.state = st.id left outer join locgovt lg on p.locgovt = lg.id left outer join city ci on p.city = ci.id WHERE medrecnum = %s", $colname_patperm);
$patinfoview = mysql_query($query_patinfoview, $swmisconn) or die(mysql_error());
$row_patinfoview = mysql_fetch_assoc($patinfoview);
$totalRows_patinfoview = mysql_num_rows($patinfoview);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Paylater Calls</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=100,top=100, screenX=100,screenY=100';  //these control location on screen for 2 different browsers
   var newWindow = window.open(theURL,winName,features); //+win_position);
   newWindow.focus();
}

//Popup window function
	function basicPopup(url) {
   popupWindow = window.open(url,'statusview','height=400,width=1000,left=100,top=100,screenX=100,screenY=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
</script>

</head>

<body>

<!-- Display PATIENT PERMANENT Data  -->
<table width="50%">
	  <tr>
		  <td nowrap="nowrap" class="BlueBold_16"><?php echo $row_patperm['hospital']; ?> Medical Center</td>
	    <td colspan="2" align="center" nowrap="nowrap" class ="BlueBold_18">Cash Pay Later Calls</td>
	    <td colspan="2" align="right" class ="BlueBold_12">Date:<?php echo date("d-M-Y") ?></td>
    </tr>
	  <tr>
	    <td class ="BlueBold_18">&nbsp;</td>
			<td nowrap="nowrap" Title="Entry Date: <?php echo $row_patperm['entrydt']; ?>&#10; Entry By: <?php echo $row_patperm['entryby']; ?>&#10;Active: <?php echo $row_patperm['active']; ?>">MRN:<span class="BlueBold_16"><?php echo $row_patperm['medrecnum']; ?></span></td>
			<td bgcolor="#FFFFFF" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name:<span class="BlueBold_20" ><?php echo $row_patperm['lastname']; ?></span>,<span class="BlueBold_18"><?php echo $row_patperm['firstname']; ?></span>(<span class="BlueBold_18"><?php echo $row_patperm['othername']; ?></span>)</td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:<span class="BlueBold_16"><?php echo $row_patperm['gender']; ?></span></td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ethnic Group: <span class="BlueBold_16"><?php echo $row_patperm['ethnicgroup']; ?></span></td>
			<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age: <span class="BlueBold_16"><?php echo $row_patperm['age']; ?></span></td>
	  </tr>
</table>

<div><a href="CashPaylater.php"><span class="navLink"><<< Pay Later Orders</span></a></div>
<p>&nbsp;</p>
<div><a href="CashierReptMenu.php" class="navLink"><<< Cashier Report Menu</a></div>
<p><!--begin pat info view--></p>
<table width="50%">
  <caption align="top" class="subtitlebl">
    View Patient Information
  </caption>
  <tr>
    <td height="200"><form id="form1" name="form1" method="post" action="">
      <table width="50%" border="0">
        <tr>
          <td><div align="right">Title:</div></td>
          <td><input name="title" type="text" readonly="readonly" id="title" value="<?php echo $row_patinfoview['title']; ?>" /></td>
          <td><div align="right">Country:</div></td>
          <td><input name="country" type="text" readonly="readonly" id="country" value="<?php echo $row_patinfoview['country']; ?>" /></td>
          <td><div align="right"><span style="font-size:9px; color:red;">Emergency:</span> <br /> First Name: </div></td>
          <td><input name="em_fname" type="text" readonly="readonly" value="<?php echo $row_patinfoview['em_fname']; ?>" /></td>
        </tr>
        <tr>
          <td><div align="right">Occupation:</div></td>
          <td><input name="occup" type="text" readonly="readonly" value="<?php echo $row_patinfoview['occup']; ?>" /></td>
          <td><div align="right">State:</div></td>
          <td ><input name="state" type="text" id="state" readonly="readonly" value="<?php echo $row_patinfoview['state']; ?>" /></td>
          <td><div align="right"><span style="font-size:9px; color:red;">Emergency:</span> <br />Last Name: </div></td>
          <td><input name="em_lname" type="text" readonly="readonly" value="<?php echo $row_patinfoview['em_lname']; ?>" /></td>
        </tr>
        <tr>
          <td><div align="right">Married:</div></td>
          <td><input name="married" type="text" readonly="readonly" id="married" value="<?php echo $row_patinfoview['married']; ?>" /></td>
          <td><div align="right">Local Gov't: </div></td>
          <td><input name="locgovt" type="text" readonly="readonly" id="locgovt" value="<?php echo $row_patinfoview['locgovt']; ?>" /></td>
          <td><div align="right"><span style="font-size:9px; color:red;">Emergency:</span> <br /> Relationship: </div></td>
          <td><input name="em_rel" type="text" id="em_rel" readonly="readonly" value="<?php echo $row_patinfoview['em_rel']; ?>" /></td>
        </tr>
        <tr>
          <td><div align="right">Phone 1: </div></td>
	  <?php if(strlen($row_patinfoview['phone1']) > 0 AND strlen($row_patinfoview['phone1']) < 11) { $bgd = "#FFCCFF"; } else { $bgd = ""; }?>
          <td  bgcolor=<?php echo $bgd ?>><input name="phone1" type="text" value="<?php echo $row_patinfoview['phone1']; ?>" size="13" maxlength="15" readonly="readonly" /></td>
          <td><div align="right">City:</div></td>
          <td><input name="city" type="text" readonly="readonly" id="city" value="<?php echo $row_patinfoview['city']; ?>" /></td>
          <td><div align="right"><span style="font-size:9px; color:red;">Emergency:</span> <br /> Phone1: </div></td>
	  <?php if(strlen($row_patinfoview['em_phone1']) > 0 AND strlen($row_patinfoview['em_phone1']) < 11) { $bgd = "#FFCCFF"; } else { $bgd = ""; }?>
	  
	      <td bgcolor=<?php echo $bgd ?>><input name="em_phone1" type="text" value="<?php echo $row_patinfoview['em_phone1']; ?>" size="13" maxlength="15" readonly="readonly" /></td>
        </tr>
        <tr>
          <td><div align="right">Phone 2: </div></td>
	  <?php if(strlen($row_patinfoview['phone2']) > 0 AND strlen($row_patinfoview['phone2']) < 11) { $bgd = "#FFCCFF"; } else { $bgd = ""; }?>
          <td bgcolor=<?php echo $bgd ?>><input name="phone2" type="text" value="<?php echo $row_patinfoview['phone2']; ?>" size="13" maxlength="15" readonly="readonly" /></td>
          <td><div align="right">Street:</div></td>
          <td><input name="street" type="text" readonly="readonly" value="<?php echo $row_patinfoview['street']; ?>" /></td>
	  <?php if(strlen($row_patinfoview['em_phone2']) > 0 AND strlen($row_patinfoview['em_phone2']) < 11) { $bgd = "#FFCCFF"; } else { $bgd = ""; }?>
          <td><div align="right"><span style="font-size:9px; color:red;">Emergency:</span> <br />Phone 2:</div></td>
          <td bgcolor=<?php echo $bgd ?>><input name="em_phone2" type="text" value="<?php echo $row_patinfoview['em_phone2']; ?>" size="13" maxlength="15" /></td>
        </tr>
        <tr>
	  <?php if(strlen($row_patinfoview['phone3']) > 0 AND strlen($row_patinfoview['phone3']) < 11) { $bgd = "#FFCCFF"; } else { $bgd = ""; }?>
          <td><div align="right">Phone 3: </div></td>
          <td bgcolor=<?php echo $bgd ?>><input name="phone3" type="text" value="<?php echo $row_patinfoview['phone3']; ?>" size="13" maxlength="15" readonly="readonly" /></td>
			<!-- <td>&nbsp;</td>-->
            <?php if ($totalRows_patinfoview > 0 and allow(21,2) == 1) { ?>
          <td>
                 	<a href="javascript:void(0)" onclick="basicPopup('../Patient/PatAddrEdit.php?id=<?php echo $row_patinfoview['id']; ?>&country=<?php echo $row_patinfoview['coid'] ?>&state=<?php echo $row_patinfoview['stid'] ?>&locgovt=<?php echo $row_patinfoview['lgid'] ?>&city=<?php echo $row_patinfoview['ciid'] ?>')">Edit</a></td>

<!--            <a href="javascript:void(0)" onclick="MM_openBrWindow('PatAddrEdit.php?id=<?php echo $row_patinfoview['id']; ?>&country=<?php echo $row_patinfoview['coid'] ?>&state=<?php echo $row_patinfoview['stid'] ?>&locgovt=<?php echo $row_patinfoview['lgid'] ?>&city=<?php echo $row_patinfoview['ciid'] ?>','StatusView','scrollbars=yes,resizable=yes,left=30,top=30, screenX=30,screenY=30',width=800,height=350)">Edit</a></td>
-->            <!--,width=800,height=350-->
			   <?php }  ?>
 <td>        
           <?php if($totalRows_patinfoview <= 0 and allow(21,3) == 1) { ?>
				<!--	<a href="PatAddrAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&country=0" onclick="basicPopup(this.href);return false">Add</a>-->
</td>
	      <td>
        					<!--<a href="javascript:void(0)" onclick="basicPopup('PatAddrAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&country=0')">Add</a>--></td>
        <!-- ,width=600,height=300 -->
<!--					<a href="javascript:void(0)" onclick="MM_openBrWindow('PatAddrAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&country=0','StatusView','scrollbars=yes,resizable=yes,width=850,height=350,left=300,top=300, screenX=300,screenY=500')">Add</a></td>
-->        <!-- ,width=600,height=300 -->
            <?php } ?>
          <td> &nbsp;
					<!--<a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>">Close</a>-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comments:</td>
          <td colspan="2" valign="top"><textarea name="comments" rows="1" cols="30" id="comments" readonly="readonly"><?php echo $row_patinfoview['comments']; ?></textarea></td>
        </tr>
      </table>
        </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Orders marked as Paylater</p>

  <!--end patinfo view-->
  
<table border="1" cellpadding="1" cellspacing="1">
  <?php  $_SESSION['MRN'] = '';
				 $total = 0; ?>
	<tr>
    <td align="center" class="BlueBold_14">Patient*</td>
    <td align="center" class="BlueBold_14">item</td>
    <td align="center" class="BlueBold_14">amtdue*</td>
    <td align="center" class="BlueBold_14">doctor</td>
  </tr>
    <?php do {  ?>
    <tr>
      <td title="MRN: <?php echo $row_paylater['medrecnum']; ?>&#10;VisitID: <?php echo $row_paylater['visitid']; ?>&#10;Order ID: <?php echo $row_paylater['oid']; ?>&#10;FeeID: <?php echo $row_paylater['feeid'] ?>"><?php echo $row_paylater['lastname'].', '.$row_paylater['firstname'].', '.$row_paylater['othername'] ?></td>
			<td><?php echo $row_paylater['section']; ?>: <?php echo $row_paylater['name']; ?></td>      
      <td title="Rate: <?php echo $row_paylater['rate']; ?>&#10;AmtPaid: <?php echo $row_paylater['amtpaid']; ?>&#10;EntryBy: <?php echo $row_paylater['entryby']; ?>&#10;EntryBy: <?php echo $row_paylater['entrydt']; ?>"&#10;Order Status: <?php echo $row_paylater['status']; ?>><?php echo $row_paylater['amtdue']; ?></td>
      <td><?php echo $row_paylater['doctor']; ?></td>
    </tr>
    <?php //$_SESSION['MRN'] = $row_paylater['medrecnum']; 
					$total = $total + $row_paylater['amtdue'] ;?>
    <?php } while ($row_paylater = mysql_fetch_assoc($paylater)); ?>
		 <tr>
     		<td>&nbsp;</td>
   		  <td>&nbsp;</td>
     		<td class="flagWhiteonRed"><?php echo $total ?></td>
 		   <td>&nbsp;</td>
     </tr>			
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>History of Pay Later Calls</p>
<table border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td>id</td>
    <td>medrecnum</td>
    <td>entrydt</td>
    <td>entryby</td>
    <td>comment</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_PLcomments['id']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_PLcomments['medrecnum']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_PLcomments['entrydt']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_PLcomments['entryby']; ?></td>
      <td bgcolor="#FFFFFF"><textarea name="callcomment" rows="1" cols="80" readonly="readonly" > <?php echo $row_PLcomments['comment']; ?> </textarea></td>
    </tr>
    <?php } while ($row_PLcomments = mysql_fetch_assoc($PLcomments)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>New Call </p>
<table width="50%" border="1" cellspacing="1" cellpadding="1">
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <tr>
    <td scope="row">Date    
    <td>Caller</td>
    <td>Comment</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="text" id="entrydt" name="entrydt" value="<?php echo Date("Y-m-d H:i"); ?>"/></td>
    <td><input type="text" id="entryby" name="entryby" value="<?php echo $_SESSION['user'] ?>"/></td>
    <td><textarea name="comment" cols="80" rows="2" id="comment"></textarea>
    <td><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
    <input type="hidden" id="medrecnum" name="medrecnum" value="<?php echo $colname_patperm ?>"/>
  </tr>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</table>

&nbsp;
</body>
</html>
<?php
mysql_free_result($patperm);

mysql_free_result($PLcomments);
?>
