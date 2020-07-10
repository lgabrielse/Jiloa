<?php   $pt = "View Patient Info"; ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php $_SESSION['today'] = date("Y-m-d");  // H:i:s ?>

<?php
$colname_patinfoview = "-1";
if (isset($_GET['mrn'])) {
  $colname_patinfoview = (get_magic_quotes_gpc()) ? $_GET['mrn'] : addslashes($_GET['mrn']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_patinfoview = sprintf("SELECT p.id, medrecnum, title, occup, married, street, ci.id ciid, ci.city, lg.id lgid, lg.locgovt, st.id stid, st.state, co.id coid, co.country, phone1, phone2, phone3, em_rel, em_fname, em_lname, em_phone1, em_phone2, entrydt, entryby, comments FROM patinfo p left outer join country co on p.country = co.id left outer join state st on p.state = st.id left outer join locgovt lg on p.locgovt = lg.id left outer join city ci on p.city = ci.id WHERE medrecnum = %s", $colname_patinfoview);
$patinfoview = mysql_query($query_patinfoview, $swmisconn) or die(mysql_error());
$row_patinfoview = mysql_fetch_assoc($patinfoview);
$totalRows_patinfoview = mysql_num_rows($patinfoview);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=100,top=100,screenX=100,screenY=100';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}

//Popup window function
//	function basicPopup(url) {
//   popupWindow = window.open(url,'statusview','height=400,width=1000,left=500,top=500,screenX=500,screenY=500,  //resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
//	}

</script>
</head>
<body>
<table width="100%" align="center">
  <caption align="top" class="subtitlebl">
    View Patient Information
  </caption>
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%" border="0">
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
		  
            <a href="javascript:void(0)" onclick="MM_openBrWindow('PatAddrEdit.php?id=<?php echo $row_patinfoview['id']; ?>&country=<?php echo $row_patinfoview['coid'] ?>&state=<?php echo $row_patinfoview['stid'] ?>&locgovt=<?php echo $row_patinfoview['lgid'] ?>&city=<?php echo $row_patinfoview['ciid'] ?>','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Edit</a></td>
			   <?php }  ?>         
           <?php if($totalRows_patinfoview <= 0 and allow(21,3) == 1) { ?>

	     <td><a href="javascript:void(0)" onclick="MM_openBrWindow('PatAddrAdd.php?mrn=<?php echo $_SESSION['mrn']; ?>&user=<?php echo $_SESSION['user']; ?>&country=0','StatusView','scrollbars=yes,resizable=yes,width=850,height=350')">Add</a></td> 
            <?php } ?>
          <td> &nbsp;
					<a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>">Close</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comments:</td>
          <td colspan="2" valign="top"><textarea name="comments" rows="1" cols="30" id="comments" readonly="readonly"><?php echo $row_patinfoview['comments']; ?></textarea></td>
          </tr>
      </table>
        </form>
    </td>
  </tr>
</table>
<script src="../../jquery-1.11.1.js"></script>
<script src="../../jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js"></script>
<script>
  $.validate({
  validateOnBlur : false, // disable validation when input looses focus
    errorMessagePosition : 'top', // Instead of 'element' which is default
    scrollToTopOnError : false // Set this property to true if you have a long form
  });
</script>
<?php  //echo $totalRows_patinfoview ?>
</body>
</html>
<?php
mysql_free_result($patinfoview);
?>
