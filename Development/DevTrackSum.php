<?php  $pt = "Dev Summary"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/Len/Jiloa/Master/Header.php'); ?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_SORT = "id";  //"AssignedTo, Priority, DevType";
if (isset($_REQUEST['SORT'])) {
  $colname_SORT = (get_magic_quotes_gpc()) ? $_REQUEST['SORT'] : addslashes($_REQUEST['SORT']);
}?>
<?php
$colname_projid = "0";
if (isset($_REQUEST['projid'])) {
  $colname_projid = (get_magic_quotes_gpc()) ? $_REQUEST['projid'] : addslashes($_REQUEST['projid']);
}
//echo $colname_projid;
//exit;

$colname_assignedto = "%";
if (isset($_REQUEST['assignedto'])  && strlen($_REQUEST['assignedto'])>0) {
  $colname_assignedto = (get_magic_quotes_gpc()) ? $_REQUEST['assignedto'] : addslashes($_REQUEST['assignedto']);
}

$colname_status = "InComplete";
if (isset($_REQUEST['status'])  && strlen($_REQUEST['status'])>0 ) {
  $colname_status = (get_magic_quotes_gpc()) ? $_REQUEST['status'] : addslashes($_REQUEST['status']);
}

$colname_devtype = '%';
if (isset($_REQUEST['devtype'])  && strlen($_REQUEST['devtype'])>0) {
  $colname_devtype = (get_magic_quotes_gpc()) ? $_REQUEST['devtype'] : addslashes($_REQUEST['devtype']);
}

$colname_priority = "%";
if (isset($_REQUEST['priority'])  && strlen($_REQUEST['priority'])>0 ) {
  $colname_priority = (get_magic_quotes_gpc()) ? $_REQUEST['priority'] : addslashes($_REQUEST['priority']);
}

mysql_select_db($database_swmisconn, $swmisconn);

if($colname_status == 'All') {
$query_DevSumList = "SELECT id, projid, SUBSTR(DevType,1,3) DevType, EstHrs, ActHrs, SUBSTR(Priority,1,1) Priority, Status, AssignedTo, Summary, Description, entryby, entrydt, comments FROM development where AssignedTo like '%".$colname_assignedto."%' and DevType like '%".$colname_devtype."%' and Priority like '%".$colname_priority."%' and projid = '".$colname_projid."' order by ".$colname_SORT;
$DevSumList = mysql_query($query_DevSumList, $swmisconn) or die(mysql_error());
$row_DevSumList = mysql_fetch_assoc($DevSumList);
$totalRows_DevSumList = mysql_num_rows($DevSumList);

} elseif($colname_status == 'Complete') {
$query_DevSumList = "SELECT id, projid, SUBSTR(DevType,1,3) DevType, EstHrs, ActHrs, SUBSTR(Priority,1,1) Priority, Status, AssignedTo, Summary, Description, devsys, train, test, live, entryby, entrydt, comments FROM development where AssignedTo like '%".$colname_assignedto."%' and Status = 'Complete' and DevType like '%".$colname_devtype."%' and Priority like '%".$colname_priority."%' and projid = '".$colname_projid."' order by ".$colname_SORT;
$DevSumList = mysql_query($query_DevSumList, $swmisconn) or die(mysql_error());
$row_DevSumList = mysql_fetch_assoc($DevSumList);
$totalRows_DevSumList = mysql_num_rows($DevSumList);

} elseif($colname_status == 'InComplete') {
$query_DevSumList = "SELECT id, projid, SUBSTR(DevType,1,3) DevType, EstHrs, ActHrs, SUBSTR(Priority,1,1) Priority, Status, AssignedTo, Summary, Description, devsys, train, test, live, entryby, entrydt, comments FROM development where AssignedTo like '%".$colname_assignedto."%' and Status != 'Complete' and DevType like '%".$colname_devtype."%' and Priority like '%".$colname_priority."%' and projid = '".$colname_projid."' order by ".$colname_SORT;
$DevSumList = mysql_query($query_DevSumList, $swmisconn) or die(mysql_error());
$row_DevSumList = mysql_fetch_assoc($DevSumList);
$totalRows_DevSumList = mysql_num_rows($DevSumList);

} else {
$query_DevSumList = "SELECT id, projid, SUBSTR(DevType,1,3) DevType, EstHrs, ActHrs, SUBSTR(Priority,1,1) Priority, Status, AssignedTo, Summary, Description, devsys, train, test, live, entryby, entrydt, comments FROM development where AssignedTo like '%".$colname_assignedto."%' and Status like '%".$colname_status."%' and DevType like '%".$colname_devtype."%' and Priority like '%".$colname_priority."%' and projid = '".$colname_projid."' order by ".$colname_SORT;
$DevSumList = mysql_query($query_DevSumList, $swmisconn) or die(mysql_error());
$row_DevSumList = mysql_fetch_assoc($DevSumList);
$totalRows_DevSumList = mysql_num_rows($DevSumList);
}
 //set variables to be used in url when display parameters are being used
$projid = $row_DevSumList['projid']; 
$devtype = $row_DevSumList['DevType']; 
$priority = $row_DevSumList['Priority']; 
$status = $row_DevSumList['Status']; 
$assgnto = $row_DevSumList['AssignedTo']; 
$sort = $colname_SORT 
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
<?php mysql_select_db($database_swmisconn, $swmisconn);  //select Sytem admins and Lab admins
//$query_User = "SELECT u.userid FROM users u join user_role ur on u.id = ur.userid WHERE ur.roleid = '1' or ur.roleid = '26' ORDER BY u.userid ASC";
$query_User = "SELECT u.userid FROM users u where u.id IN('1','72')";
$User = mysql_query($query_User, $swmisconn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
?>
<?php if(isset($_REQUEST['chkdescr'])) {
	$_SESSION['chkdescr'] = $_REQUEST['chkdescr'];}
  else {
	$_SESSION['chkdescr'] = 'no';}
    
?>
<?php if(isset($_REQUEST['chkcomm'])) {
	$_SESSION['chkcomm'] = $_REQUEST['chkcomm'];}
  else {
	$_SESSION['chkcomm'] = 'no';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Project Summary</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=100,top=600,screenX=100,screenY=600';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
</head>

<body>
<table width="70%" align="center" cellpadding="1" cellspacing="1">
<form name="formdev" method="post" id="formdev" action="DevTrackSum.php" >  
  <tr>
    <td>&nbsp;</td>
    <td><a href="../Setup/SetUpMenu.php">Setup Menu </a></td>
    <td nowrap="nowrap" ><a href="DevProjectView.php">Project List</a></td>
    <td>&nbsp;</td>
    <td class="flagWhiteonBlue">&nbsp;</td>
    <td colspan="7"><div align="center" class="flagWhiteonBlue">Development Tracking Report </div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" nowrap="nowrap"><input name="chkdescr" type="checkbox" <?php if (!(strcmp($_SESSION['chkdescr'],"yes"))) {echo "checked=\"checked\"";} ?> value="yes"  onchange="document.formdev.submit();"/>
    Description  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="chkcomm" type="checkbox" <?php if (!(strcmp($_SESSION['chkcomm'],"yes"))) {echo "checked=\"checked\"";} ?> value="yes" onchange="document.formdev.submit();"/>comments&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td>&nbsp;</td>
   <td><div align="right">Assigned To: </div></td>
    <td><select name="assignedto" id="assignedto" onchange="document.formdev.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_assignedto))) {echo "selected=\"selected\"";} ?>>All</option>
        <?php do { ?>
	        <option value="<?php echo $row_User['userid']?>"<?php if (!(strcmp($row_User['userid'], $colname_assignedto))) {echo "selected=\"selected\"";} ?>><?php echo $row_User['userid']?></option>
        <?php } while ($row_User = mysql_fetch_assoc($User));
			  $rows = mysql_num_rows($User);
			  if($rows > 0) {
				  mysql_data_seek($User, 0);
				  $row_User = mysql_fetch_assoc($User);
			  } ?>
    </select>	</td>
    <td><div align="right">Status:</div></td>
    <td><select name="status" id="status" onchange="document.formdev.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_status))) {echo "selected=\"selected\"";} ?>>All</option>
        <?php do { ?>
	        <option value="<?php echo $row_DevStatus['name']?>"<?php if (!(strcmp($row_DevStatus['name'], $colname_status))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevStatus['name']?></option>
        <?php } while ($row_DevStatus = mysql_fetch_assoc($DevStatus));
			  $rows = mysql_num_rows($DevStatus);
			  if($rows > 0) {
				  mysql_data_seek($DevStatus, 0);
				  $row_DevStatus = mysql_fetch_assoc($DevStatus);
			  } ?>
    </select>	</td>
    <td><div align="right">Priority: </div></td>
    <td><select name="priority" id="priority" onchange="document.formdev.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_priority))) {echo "selected=\"selected\"";} ?>>All</option>
        <?php do { ?>
	        <option value="<?php echo $row_DevPriority['name']?>"<?php if (!(strcmp($row_DevPriority['name'], $colname_priority))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevPriority['name']?></option>
        <?php } while ($row_DevPriority = mysql_fetch_assoc($DevPriority));
			  $rows = mysql_num_rows($DevPriority);
			  if($rows > 0) {
				  mysql_data_seek($DevPriority, 0);
				  $row_DevPriority = mysql_fetch_assoc($DevPriority);
			  } ?>
    </select>	</td>
    <td><div align="right">Dev Type: </div></td>
    <td><select name="devtype" id="devtype" onchange="document.formdev.submit();">
            <option value="%" <?php if (!(strcmp("%", $colname_devtype))) {echo "selected=\"selected\"";} ?>>All</option>
        <?php do { ?>
	        <option value="<?php echo $row_DevType['name']?>"<?php if (!(strcmp($row_DevType['name'], $colname_devtype))) {echo "selected=\"selected\"";} ?>><?php echo $row_DevType['name']?></option>
        <?php } while ($row_DevType = mysql_fetch_assoc($DevType));
			  $rows = mysql_num_rows($DevType);
			  if($rows > 0) {
				  mysql_data_seek($DevType, 0);
				  $row_DevType = mysql_fetch_assoc($DevType);
			  } ?>
    </select>
    	</td>
  </tr>
  					<input name="projid" type="hidden" value="<?php echo $row_DevSumList['projid'] ?>" />
            <input type="hidden" name="MM_update" value="formdev" />		 </td>
</table>
<table align="center">
  <tr>
    <?php if(isset($_SESSION['user']) AND (stripos($_SESSION['user'],'BRIELSE')> 0 or stripos($_SESSION['user'],'ADMIN')> 0)) {?>
    <td><a href="DevTrackAdd.php">ADD</a></td>
    <td>&nbsp;</td>
    <?php  } else {?>
    <td>&nbsp;</td>
    <?php }?>
    <td>&nbsp;</td>
    <td>User: <?php echo $_SESSION['user'] ?></td>
    <td colspan="7"><div align="center"></div></td>
  </tr>
  <tr>
    <td class="flagWhiteonBlue">&nbsp;</td>
<!--SORT by Assigned TO-->
    <td align="center" nowrap="nowrap" class="flagWhiteonBlue">Proj<br />id</td>
    <td align="center" nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=id&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">Task &#8595<br />
      id</td>
          <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=AssignedTo&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">Assigned &#8595;<br />To</a></div></td>

<!--SORT by Status-->            
         <td nowrap="nowrap"  class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=status&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">Status &#8595;</a></div></td>
         
<!--SORT by Priority-->            
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=Priority&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">&nbsp;&nbsp;Pri- &#8595;<br />
      ority</a></div></td>
      
<!--SORT by DevType-->            
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=DevType&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">&nbsp;&nbsp;Dev   &#8595;<br />
      Type</a></div></td>
      
<!--SORT by Priority-->            
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=EstHrs&assignedto=<?php echo $colname_assignedto ?>&SORT=Priority&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">&nbsp;&nbsp;Est   &#8595;<br />
      Hrs</a></div></td>
      
<!--SORT by ActHours-->            
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center"><a href="DevTrackSum.php?SORT=ActHrs&assignedto=<?php echo $colname_assignedto ?>&projid=<?php echo $colname_projid ?>&status=<?php echo $colname_status ?>&Priority=<?php echo $colname_priority ?>&DevType=<?php echo $colname_devtype ?>" class="flagWhiteonBlue">&nbsp;&nbsp;Act   &#8595;<br />
      Hrs</a></div></td>
      
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center">&nbsp;</div></td>
    <td nowrap="nowrap" class="flagWhiteonBlue"><div align="center">Summary - Description - comments</div></td>
    <td>L<br />G</td>
    <td>T<br />R</td>
    <td>T<br />E</td>
    <td>L<br />I</td>
  </tr>
  <?php $i=1;?>
  <?php do { 
  if($i%2==0)
 {
     $bkg="#fdfbf0";
	 $tac="textarea_ltyel";
 }
 else
 {
     $bkg="#f0f9fd";
	 $tac="textarea_ltblu";
 }
	$i++;
  ?>
  <tr bgcolor="<?php echo $bkg; ?>">
<!--  Only Len or Grace can Edit-->
    <?php if(isset($_SESSION['user']) AND stripos($_SESSION['user'],'BRIELSE')> 0 or stripos($_SESSION['user'],'ADMIN')>0) {?>
    <td valign="center" title="Entry Date: <?php echo $row_DevSumList['entrydt'] ?>" bgcolor="<?php echo $bkg; ?>"><a href="DevTrackEdit.php?id=<?php echo $row_DevSumList['id']; ?>&projid=<?php echo $row_DevSumList['projid']; ?>">EDIT</a></td>
    <?php  } else {?>
    <td>&nbsp;</td>
    <?php }?>
    <td align="center"><?php echo $row_DevSumList['projid'] ?></td>
    <td align="center"><?php echo $row_DevSumList['id']; ?></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="AssignedTo" style="text-align: center;" readonly="readonly" size="12" value="<?php echo $row_DevSumList['AssignedTo']; ?>"/></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="Status" style="text-align: center;" readonly="readonly" size="6" value="<?php echo $row_DevSumList['Status']; ?>"/></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="Priority" style="text-align: center;" readonly="readonly" size="3" value="<?php echo $row_DevSumList['Priority']; ?>"/></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="DevType" style="text-align: center;" readonly="readonly" size="3" value="<?php echo $row_DevSumList['DevType']; ?>"/></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="EstHrs" style="text-align: center;" readonly="readonly" size="3" value="<?php echo $row_DevSumList['EstHrs']; ?>"/></td>
    <td valign="center" bgcolor="<?php echo $bkg; ?>"><input type="text" name="ActHrs" style="text-align: center;" readonly="ActHrs" size="3" value="<?php echo $row_DevSumList['ActHrs']; ?>"/></td>
    <td valign="top"><div align="right" ><strong>Summary</strong></div></td>
    <td><div class="<?php echo $tac; ?>"><strong><?php echo $row_DevSumList['Summary']; ?></strong></div></td>
<?php $bkg="#FFFFFF";
if($row_DevSumList['devsys'] == 'Y') { $bkg="#ccffcc";} ?>
    <td bgcolor="<?php echo $bkg ?>"><?php echo $row_DevSumList['devsys']; ?></td>
<?php $bkg="#FFFFFF";
if($row_DevSumList['train'] == 'Y') { $bkg="#ccffcc";} ?>
    <td bgcolor="<?php echo $bkg ?>"><?php echo $row_DevSumList['train']; ?></td>
<?php $bkg="#FFFFFF";
if($row_DevSumList['test'] == 'Y') { $bkg="#ccffcc";} ?>
    <td bgcolor="<?php echo $bkg ?>"><?php echo $row_DevSumList['test']; ?></td>
<?php $bkg="#FFFFFF";
if($row_DevSumList['live'] == 'Y') { $bkg="#ccffcc";} ?>
    <td bgcolor="<?php echo $bkg ?>"><?php echo $row_DevSumList['live']; ?></td>
  </tr>
  <?php if(isset($_SESSION['chkdescr']) AND $_SESSION['chkdescr'] == 'yes'){ ?>
  <tr bgcolor="<?php echo $bkg; ?>">
    <td bgcolor="#DCDCDC"><?php // echo $_SESSION['chkdescr'] ?></td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td valign="top"><div align="right"><strong>Description</strong></div></td>
    <td><div class="<?php echo $tac; ?>"><?php echo $row_DevSumList['Description']; ?></div></td>
  </tr>
  <?php }?>
  <?php if(isset($_SESSION['chkcomm']) AND $_SESSION['chkcomm'] == 'yes'){ ?>
  <tr bgcolor="<?php echo $bkg; ?>">
    <td bgcolor="#DCDCDC"><?php // echo $_SESSION['chkcomm'] ?></td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC">&nbsp;</td>
    <td bgcolor="#DCDCDC"><div align="center" class="navLink"><a href="DevCommAdd.php"><a href="javascript:void(0)" onclick="MM_openBrWindow('DevCommAdd.php?id=<?php echo $row_DevSumList['id'] ?>','StatusView','scrollbars=yes,resizable=yes,width=600,height=300')">ADD</a></div></td>
    <td valign="top"><div align="right"><strong>Comments</strong></div></td>
    <td><div class="<?php echo $tac; ?>">
      <table>
<?php
mysql_select_db($database_swmisconn, $swmisconn);
$query_source = "Select id, comments, entryby, entrydt from develcomnts where devdocid =  '".$row_DevSumList['id']."'";
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
  <?php }?>
  <?php } while ($row_DevSumList = mysql_fetch_assoc($DevSumList)); ?>
</form>
</table>
</body>
</html>
<?php
mysql_free_result($DevSumList);
?>
