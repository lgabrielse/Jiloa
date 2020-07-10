<?php //ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php // require_once('../../Connections/swmisconn.php'); ?>
<?php $pt = "Msg Compose";  ?>
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
  $saved = ""; // set variable to blank 
if(isset($_GET['reset']) && $_GET['reset'] == 'true'){
	$_SESSION['seldusr'] = '';
    $_SESSION['userid'] = '';
	}
?>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
// if form3 (SEND message) is submitted, save the message in the database
if (isset($_POST['MM_insert']) AND $_POST['MM_insert']  == 'form3')  {

  $insertSQL = sprintf("INSERT INTO usrmsg (fromuser, touser, status, urgent, openflag, reply, msg, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_SESSION['user'], "text"),
                       GetSQLValueString($_SESSION['seldusr'], "text"),
                       GetSQLValueString('new', "text"),
                       GetSQLValueString($_POST['urgent'], "text"),
                       GetSQLValueString('Y', "text"),
                       GetSQLValueString($_POST['reply'], "text"),
                       GetSQLValueString($_POST['message'], "text"),
										   GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

// tis section will set origingal message status to 'read' if is replied to
//The stripos() function finds the position of the first occurrence of a string inside another string...  stripos(string,find,start) start is optional
		if(stripos($_POST['reply'],'Reply') >= 0 && stripos($_POST['reply'],'Reply') < 6 ) {  // automatically set status to read if replying
			 $status = 'read';
		 
		
			$updateSQL = sprintf("UPDATE usrmsg SET status=%s WHERE id=%s",
													 GetSQLValueString($status, "text"),
													 GetSQLValueString($_POST['msgid'], "int"));
		
			mysql_select_db($database_swmisconn, $swmisconn);
			$Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());	
		

			$saved = "true";
		}
}
?>

<?php // if Reply is clicked ELSE if form2 (select user to send message to) is submitted, update values is session variables storing array of user id record number and userid
 if(isset($_GET['touserid'])) {
		$_SESSION['seldusr'] = $_SESSION['seldusr'].'x'.$_GET['idnum'].',';	// for array of user record ids 
		$_SESSION['userid'] = $_SESSION['userid'].$_GET['touserid'].', ';  // for display userid field in compose
 } elseif(isset($_GET['selusr']) && isset($_GET["MM_update"]) && $_GET["MM_update"] == "form2") {
				$_SESSION['seldusr'] = $_SESSION['seldusr'].'x'.$_GET['selusr'].',';  // to save list of user ids
				$_SESSION['userid'] = $_SESSION['userid'].$_GET['userid'].', ';  // for display in compose
 } elseif(isset($_GET['selusrddl']) && isset($_GET["MM_update"]) && $_GET["MM_update"] == "form4") {
				$_SESSION['seldusr'] = $_SESSION['seldusr'].'x'.substr($_POST['users'],0,strpos($_POST['users'],'_')).',';  // to save list of user ids
				$_SESSION['userid'] = $_SESSION['userid'].trim(strrchr($_POST['users'],'_'),'_').',';  // for display in compose
 } ?>

<?php 
$user_search = "zzzz";  //initial select find no users
if (isset($_GET['user'])  && strlen($_GET['user'])>1 && isset($_GET["MM_update"]) && ($_GET["MM_update"] == "form1")) {
	$user_search = (get_magic_quotes_gpc()) ? $_GET['user'] : addslashes($_GET['user']);
	
mysql_select_db($database_swmisconn, $swmisconn);
$query_msgto = "SELECT id, userid, login, lastname, firstname FROM users WHERE (concat(lastname, ' ',firstname, ' ', userid)) like '%".$user_search."%' and active = 'Y' ORDER BY lastname";
$msgto = mysql_query($query_msgto, $swmisconn) or die(mysql_error());
$row_msgto = mysql_fetch_assoc($msgto);
$totalRows_msgto = mysql_num_rows($msgto);
}?>
<?php 
mysql_select_db($database_swmisconn, $swmisconn);
$query_token = "SELECT t.id tid, t.userid, t.token, t.entrydt, u.id uid FROM token t join users u on t.userid = u.userid WHERE t.userid != '".$_SESSION['user']."'";
$token = mysql_query($query_token, $swmisconn) or die(mysql_error());
$row_token = mysql_fetch_assoc($token);
$totalRows_token = mysql_num_rows($token);
?> 

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Compose Message</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>
<script rel="text/javascript" src="../../jquery-1.11.1.js"></script>
<script src="../../autosaveform.js">
//***********************************************
//* Auto Save Form script (c) Dynamic Drive (www.dynamicdrive.com)
//* This notice MUST stay intact for legal use
//* Visit http://www.dynamicdrive.com/ for this script and 100s more.
//**********************************************
//http://www.dynamicdrive.com/dynamicindex16/autosaveform.htm
</script>
<script>
var formsave1=new autosaveform({
formid: 'form3',
pause: 1000 //<--no comma following last option!
})
</script>
<script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
<script src="../../tinymce/tinymce-emoji-master/dist/tinymce-emoji/plugin.min.js"></script>
<script src="../../tinymce/tinymce-line-height-plugin-master/lineheight/plugin.min.js"></script>
<script>
tinymce.init({ 
  	//selector:'textarea#diagnosis', 
	mode : 'textareas',
	editor_selector : 'msg',   //textarea  must have class="msg"
	content_css : '../../CSS/content.css',
	force_p_newlines : true,
	min_height: 0,
	width: 600,
  autoresize_max_height: 400,
	autoresize_min_height: 200,
	autoresize_bottom_margin: 1,
	menubar: false,
	statusbar: false,
	emoji_add_space: false, // emoji are quite wide, so a space is added automatically after each by default; this disables that extra space
  emoji_show_groups: false,   // hides the tabs and dsiplays all emojis on one page
  emoji_show_subgroups: true,    // hides the subheadings
  emoji_show_tab_icons: true, // hides the icon on each tab label

	toolbar_items_size : 'small',
	toolbar: 'tinymceEmoji | bold italic underline | bullist  numlist | indent outdent | alignleft aligncenter alignright | superscript subscript | lineheightselect',
    plugins: 'tinymceEmoji, autoresize, autosave,tinymceEmoji, lineheight',
		autosave_ask_before_unload: false,
		autosave_interval: "20s",
		autosave_restore_when_empty: true,
		autosave_retention: "60m"
	 });
</script>

</head>
<?php if($saved == "true") {?>
<body onload="out()">
<?php }?>

<body>
<div align="center">
<table>
	<tr>
	  <td class="BlackBold_16">BMC Message System</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="BlueBold_16"> Send Message</td>
  </tr>
</table>  
<!--<?php //echo $_POST['users'] ?><br>seldusr<?php //echo $_SESSION['seldusr'] ?><br>userid:<?php //echo $_SESSION['userid'] ?>--></div>
<table align="center">
	<tr>
    <td>    	<!-- form 4 -->
			<table>
      <form name="form4" id="form4" method="post" action="MsgCompose.php?selusrddl=selusrddl&MM_update=form4">
      	<tr>
        	<td><strong>CURRENT USERS<br>Click on user below<br>to add to 'Send To:'</strong></td>
        </tr>
      	<tr>
        	<td><?php echo str_repeat('&nbsp',2) ?> User_Last Activity</td>
        </tr>
        <tr>
      	  <td>
        	<select name="users" size="10" onChange="document.form4.submit();"> 
<?php
$i = 0;
 do {
	$i = $i + 1;  
	if($i <= $totalRows_token ) {   ?>
				   <!-- value users will be concatenation of uid, _, userid    both needed in send to-->
        	  <option title="<?php echo date('D, M d@ h:i A',$row_token['entrydt']) ?>" value="<?php echo $row_token['uid']?>_<?php echo $row_token['userid']?>"><?php echo $row_token['userid']?>_<?php echo date('h:i A',$row_token['entrydt']); ?></option>
      <?php 	}
				} while ($row_token = mysql_fetch_assoc($token));
					$rows = mysql_num_rows($token);
					if($rows > 0) {
							mysql_data_seek($token, 0);
						$row_token = mysql_fetch_assoc($token);
					}
 ?>
        	</select>
          <input type="hidden" name="MM_update" value="form4" />
          </td>

        </tr>
      </form>  
      </table>
    </td>
    <td>	
			<table width="600px">
      	<tr>
          <td width="50px">From:</td>
          <td width="30px" bgcolor="#00FFFF">Name: </td>
          <td width="50px" bgcolor="#00FFFF"><input name="sender" type="text" size="10" maxlength="20" readonly  value="<?php echo $_SESSION['user'] ?>"/></td>
          <td width="50px">&nbsp;</td>
          <td>&nbsp;</td>
	    		<td><div align="center"><input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" /></a><?php //echo $_SESSION['seldusr'] ?></div></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
    			<td colspan="8"><div>
    			  <h3>Search for recipient by typing at least 2 characters of<br>
name in 'Search' box and then press 'Enter'.&nbsp; &nbsp; &nbsp; <a href="MsgCompose.php?reset=true">Reset</a></h3></div></td>
        </tr>

    	<!-- 2 form2 for To;  for input of search characters-->
      <form  name="form2" id=<"form2" method="GET">	
				<tr>
          <td>To:</td>
          <td bgcolor="#00FFFF">Search:</td> 
          <td><input name="user" type="text" size="10" maxlength="30" autocomplete="off" /></td>
          <td bgcolor="#00FFFF">Send To: </td>
          <td bgcolor="#FFFFFF"> <?php echo $_SESSION['userid'] ?></td>
          </tr>
          <input type="hidden" name="MM_update" value="form1" />
        </form>

  <?php if(isset($row_msgto['userid'])) { 
			 do { ?>
        <tr>  <!-- in next line we must use 'str_replace to pass the userid value so that userids wth spaces in them will be processed correctly-->
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td bgcolor="#FFFFFF" class="BlackBold_11"><a href=MsgCompose.php?selusr=<?php echo $row_msgto['id']; ?>&userid=<?php echo str_replace(" ", "%20", $row_msgto['userid'])?>&MM_update=form2><?php echo $row_msgto['userid']; ?></a></td>
          <td bgcolor="#FFFFFF" class="BlackBold_11"><a href=MsgCompose.php?selusr=<?php echo $row_msgto['id']; ?>&userid=<?php echo str_replace(" ", "%20", $row_msgto['userid'])?>&MM_update=form2><?php echo $row_msgto['lastname']; ?></a></td>
          <td bgcolor="#FFFFFF" class="BlackBold_11"><a href=MsgCompose.php?selusr=<?php echo $row_msgto['id']; ?>&userid=<?php echo str_replace(" ", "%20", $row_msgto['userid'])?>&MM_update=form2><?php echo $row_msgto['firstname']; ?></a></td>
      
        </tr>
      <?php } while ($row_msgto = mysql_fetch_assoc($msgto)); 
	 } ?>
            <input type="hidden" name="MM_update" value="form2" />
<p>&nbsp;</p>    
 <!-- display list of 'add' link and users names to select users -->
        <tr>
          <td>&nbsp;</td>
          <td colspan="8"><div style="color:blue;"><h3>Complete 'Send to:' selection before entering message, and urgency.<br>
                               Message, and urgency will be removed when a recipient is added.</h3></div></td>
        </tr>
        <tr>
          <td colspan="7">
            
      <!-- form 3 - enter message-->
            <table width="50%" border="0" align="center" cellpadding="1" cellspacing="0" border-collapse="collapse">
            <form  name="form3" method="POST"  action="<?php echo $editFormAction; ?>">
            <?php $reply = ''; 
              if(isset($_GET['subj']) && $_GET['subj'] == 'Reply') {
                $reply = $_GET['subj'] ?>
            <?php }?>
                <input type="hidden" name="reply" id"reply" value="<?php echo $reply?>" />
              <tr>
                <td width="50px" align="right">Message: <br><?php echo $reply ?></td>
                <td colspan="6"><textarea class="msg" name="message" cols="61.5" rows="5"></textarea></td>
              </tr>
              <tr>
                <td>Urgency:</td>
                <td nowrap>
                           <div style="background-color:lightgreen; "><input type="radio" name="urgent" value="R" checked >Routine</div>
                           <div style="background-color:lightyellow;"><input type="radio" name="urgent" value="A">ASAP</div>
                           <div style="background-color:#ff6666;"><input type="radio" name="urgent" value="U">Urgent  </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
                </td>  <!-- Sets $_POST['urgent'] to value... selected = checked -->
                <td  colspan="6" nowrap><div align="center" style="background-color:#fffdda;"><font size="2">&nbsp; </font></div> 
                           <div align="center" style="background-color:#fffdda;"><font size="2">Use ASAP and Urgent judiciously. </font></div>
                           <div align="center" style="background-color:#fffdda;"><font size="2">Abuse will make them meaningless. </font></div>
                           <div align="center" style="background-color:#fffdda;"><font size="2">&nbsp; </font></div>
                </td>
                <td align="right"><input type="submit" name="Send" id="Send" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="SEND" /></td>
              </tr>
              <tr>
      
      <?php $msgid = '';
			 if(isset($_GET['msgid'])){$msgid = $_GET['msgid']; ?>   
      <?php }?>          
           <input name="msgid" type="hidden" id="msgid" value="<?php echo $msgid; ?>" /> 
           <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
           <input type="hidden" name="MM_insert" value="form3" />
      
              </tr>
            </form>
            </table>
          </td>
        </tr>
      </table>  
		</td>
  </tr>
</table  
><?php
		//mysql_free_result($msgto);
?>

<script>
function change(){
    document.getElementById("form1").submit();
}
</script>

</body>
</html>
<?php
ob_end_flush();
?>
