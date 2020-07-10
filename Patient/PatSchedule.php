<?php require_once('../../Connections/mrbs.php'); ?>
<?php $colname_schedate = date("Y-m-d"); //"1427720400"; "1427727600";?>
<?php $colname_area = '1'; // use this   to set by VISIT 
if (isset($_GET['loc'])) {
  $loc = (get_magic_quotes_gpc()) ? $_GET['loc'] : addslashes($_GET['loc']);
	if($loc == 'Clinic'){
		$colname_area = '1';}
	if($loc == 'Antenatal'){
		$colname_area = '2';}
	if($loc == 'Surgery'){
		$colname_area = '3';}
}
//	if($_GET['loc']= 'Outpatient'){
//		$colname_area = '1';}
?>
<?php // get $mkmonth, $mkday, $mkyear); from $colname_schedate to populate the mktime below
  $mkmonth = date('m',strtotime($colname_schedate)) ;
  $mkday =  date('d',strtotime($colname_schedate)) ;
  $mkyear = date('Y',strtotime($colname_schedate)) ;
//echo "test date parts:". $mkmonth . "-" . $mkday . "-" . $mkyear ; 
?>
<?php if(isset($colname_schedate) AND isset($colname_area))
// IF(isset($_POST['area_id']) AND isset($_POST['schedate']))
{?>
<?php // set variables
  $ar = 1;
  $at = 1;
//  $mkmonth = 3 ;
//  $mkday =  30 ;
//  $mkyear = 2015 ;
//  $My_Array[] = array();  
//  $RoomArray[] = array();  
//  $AppArray[] = array();  
?>

<?php // select Area and scheduling parameters for this area and Rooms for this area from Post Form variable 'area' 
if (isset($_POST['area_id'])) {
  $colname_area = (get_magic_quotes_gpc()) ? $_POST['area_id'] : addslashes($_POST['area_id']);
}
  if($colname_area == '1'){  //Clinic
  	$bgrd = "DFEFFC";}
  elseif ($colname_area == '2'){ // Antenatal
  	$bgrd = "FFE4E1";}
  elseif ($colname_area == '3'){ // Surgery
  	$bgrd = "FFFDDA";}
//  elseif ($colname_area == '4'){ // Physical Therapy
//  	$bgrd = "FFFDDA";}

mysql_select_db($database_mrbs, $mrbs);
$query_AreaRoom = "SELECT a.id, area_name, resolution, default_duration, morningstarts, morningstarts_minutes, eveningends, eveningends_minutes, r.id room_id, room_name, sort_key, description FROM mrbs_area a join mrbs_room r on r.area_id = a.id WHERE a.id = '" . $colname_area . "' order by sort_key";
$AreaRoom = mysql_query($query_AreaRoom, $mrbs) or die(mysql_error());
$row_AreaRoom = mysql_fetch_assoc($AreaRoom);
$totalRows_AreaRoom = mysql_num_rows($AreaRoom);
//echo "area (id): ". $colname_area. "<br>";
//echo "area (area): ". $row_AreaRoom["area_name"]. "<br>";

// Select the start times of scheduled appointments for the selected day  

if (isset($_POST['schedate'])) {
  $colname_schedate = (get_magic_quotes_gpc()) ? $_POST['schedate'] : addslashes($_POST['schedate']);
  $mkmonth = date('m',strtotime($colname_schedate)) ;
  $mkday =  date('d',strtotime($colname_schedate)) ;
  $mkyear = date('Y',strtotime($colname_schedate)) ;
//echo "test date parts:". $mkmonth . "-" . $mkday . "-" . $mkyear ;   
}
mysql_select_db($database_mrbs, $mrbs);
$query_vday = "SELECT e.id, start_time, from_unixtime(start_time, '%Y-%m-%d') as startdate, end_time, room_id, `timestamp`, create_by, name, e.description, r.area_id FROM mrbs_entry e join mrbs_room r on e.room_id = r.id where from_unixtime(start_time, '%Y-%m-%d') = '" . $colname_schedate . "' AND r.area_id = '" . $colname_area . "' order by start_time"; 
$vday = mysql_query($query_vday, $mrbs) or die(mysql_error());
$row_vday = mysql_fetch_assoc($vday);
$totalRows_vday = mysql_num_rows($vday);

do 
   {

//echo "area (room): ". $row_AreaRoom["room_name"]. "<br>";
// Set the header row id and name in $My_Array -- room_id is not normally displayed, but is needed for locating appointments
	$My_Array[1][1] = $row_AreaRoom["area_name"];  //$row_AreaRoom["id"].' - '.
    $ar = $ar + 1;
	$My_Array[$ar][0] = $row_AreaRoom['room_id'];  // set the top row of array to rooms  $ar = # of rooms
	$My_Array[$ar][1] = $row_AreaRoom['room_name'];  // set the top row of array to rooms  $ar = # of rooms

	// Use one of the rows of the query to get time slots data in this query
 	if($ar == 2)
      {
		//$b = UTS (Unix time stamp) of time of 1st schedule opening of the day
		$b = mktime($row_AreaRoom['morningstarts'], $row_AreaRoom['morningstarts_minutes'], 0, $mkmonth, $mkday, $mkyear); //   $row_AreaRoom['morningstarts']);
			////echo "begin: ".$b . " - ".date("Y-m-d H:i:s", $b)."<br>";
		//$e = UTS (Unix time stamp) of time of last schedule opening of the day
		$e = mktime($row_AreaRoom['eveningends'], $row_AreaRoom['eveningends_minutes'], 0, $mkmonth, $mkday, $mkyear); //   $row_AreaRoom['morningstarts']);
			//echo "end: ".$e . " - ".date("Y-m-d H:i:s", $e)."<br>";
			//echo "resolution/interval: ".$row_AreaRoom['resolution']."****"."<br>";
		$intrvl = $row_AreaRoom['resolution'];
		//for (initialization; condition; increment) 
		//	$b is begin UTS, $e is end UTS (Unix Time Stamp), Interval is in seconds, UTS = seconds since 01/01/1970
		for ($t = $b; $t <= $e; $t += $intrvl)
         {  
		  //// echo '$t = '. $t . "<br>"; 
			 $at += 1;  // critical part -$at = # of appt times
		//	$My_Array[0][$at] = date("Y-m-d H:i:s", $t). " --" .date("H:i", $t)."***".strtotime(date("H:i", $t));   //  assign appt time to array value
			$My_Array[0][$at] = $t; // - set the appt times (in UTS) used for compare/set patient appointments
			$My_Array[1][$at] = date("m-d H:i", $t); // set the the appointment times to be displayed in the MyArray
		////	echo '$My_Array[0][$at] = '.$My_Array[0][$at]. '$at = '. $at. "<br>";
         }
      } //end of getting setup data
		  
  } while ($row_AreaRoom = mysql_fetch_assoc($AreaRoom));
		//	echo "totalRows_AreaRoom".$totalRows_AreaRoom. "<br>";
		//	echo "number of slots: ".$at. "<br>";
	do
	{
	//echo "startime:".$row_vday['start_time']. "<br>";
	//echo "name:".$row_vday['name']. "<br>";
	//echo "roomid:".$row_vday['room_id']. "<br>";
			$x = 0;
			$y = 0;			
			$j = 2;
			$k = 1;
			//for (initialization; condition; increment)
			for ($j = 2; $j <= $totalRows_AreaRoom +1; $j++)
			{
			   if(!empty($My_Array[$j][0])) 
			   {
				     // echo "roomids: ". $My_Array[$j][0] . "<br>";
				      if($My_Array[$j][0] == $row_vday['room_id'])
					  {
					     $x = $j;
					////	 echo "x Param: ".$x . "<br>";
					  }
			   }
			}

			for ($k = 1; $k <= $at; $k++)
			{
			   if(!empty($My_Array[0][$k])) 
			   {
				     //// echo "slots: " . $My_Array[0][$k] . '--'. $row_vday["start_time"]."<br>";
				      if($My_Array[0][$k] == $row_vday["start_time"])
					  {
					     $y = $k;
						//// echo "y Param: ".$y . "<br>";
					  }
			   }
			}
	 		$My_Array[$x][$y] = $row_vday['name'];
	 
//	echo "Start Time: ". $row_vday["start_time"] . "---". strtotime(date("H:i", $t)). "---" . $row_vday["startdate"]."<br>";
//	 if($row_vday["start_time"] == strtotime(date("H:i", $t)))
//	   {
//		 $My_Array[1][$at] = $row_vday['name'];
//		 echo $My_Array[1][$at];
//	   }
	} while ($row_vday = mysql_fetch_assoc($vday));


////echo "Schedule Date: ". $colname_schedate. "<br>";
//do {
//echo "Start Time: ". $row_vday["start_time"] . "---" . $row_vday["startdate"]. "<br>";
// } while ($row_vday = mysql_fetch_assoc($vday));

}

?>
<?php
mysql_select_db($database_mrbs, $mrbs);
$query_areas = "SELECT id, area_name FROM mrbs_area";
$areas = mysql_query($query_areas, $mrbs) or die(mysql_error());
$row_areas = mysql_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" />   
<script src="../../jquery-1.11.1.js"></script>
<script src="../../jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
<script>
$(document).ready(function(){
$.datepicker.setDefaults({ 
 dateFormat: 'yy-mm-dd'
});
 dateFormat: "yy-mm-dd";
	  $( "#schedate" ).datepicker();
   });
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
   var win_position = ',left=300,top=400,screenX=300,screenY=400';
   var newWindow = window.open(theURL,winName,features+win_position);
   newWindow.focus();
}
//-->
</script>
<title>View Schedule</title>
</head>

<body>

<table border="1" align="center">
  <tr>
  	<td><?php if (allow(29,1) == 1) {	?>
	  <div align="center"><a href="../mrbs-1.4.10/web/index.php" target="_blank">=&gt; MRBS<br />
	    Scheduling </a> 
      </div>
    <?php }?></td>

    <td>
	<form id="form1" name="form1" method="post" action="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=sched&pge=PatSchedule.php">
		<table width="100%" border="1" align="center">
		  <tr>
			<td><select name="area_id" >&nbsp;
			    <?php
do {  
?>
			    <option value="<?php echo $row_areas['id']?>"<?php if (!(strcmp($row_areas['id'], $colname_area))) {echo "selected=\"selected\"";} ?>><?php echo $row_areas['area_name']?></option>
			    <?php
} while ($row_areas = mysql_fetch_assoc($areas));
  $rows = mysql_num_rows($areas);
  if($rows > 0) {
      mysql_data_seek($areas, 0);
	  $row_areas = mysql_fetch_assoc($areas);
  }
?>
			</select></td>
				<td><input name="schedate" type="text" id="schedate" value="<?php echo $colname_schedate; ?>" size="10" maxlength="12" /></td>
			    <td><input name="button" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="GO" /></td>
		  </tr>
		</table>
    </form>    </td>
  </tr>
</table>

<?php  if(isset($colname_schedate) AND isset($colname_area))
//IF(isset($_POST['area_id']) AND isset($_POST['schedate']))
{?>

<table border="1" align="center">
  <tr>
<?php  $My_Array[0][0] = "go";
	   for ($appt = 1; $appt <= $at; $appt++) {   //$at = # of appt times
	     for ($room = 1; $room <= $ar; $room++) {  //$ar = # of rooms
		    if (!empty($My_Array[$room][$appt])){?>
				<td bgcolor="#FFFFFF"><?php echo $My_Array[$room][$appt];?></td>
<?php 		} else
			{ ?>
					<td align="center" bgcolor="<?php echo $bgrd ?>">&nbsp;</td>
<?php      		 }
	 		}//for ($room = 1; $room <= $ar; $room++) ?>
  </tr>
<?php 	} //($appt = 1; $appt <= $at; $appt++) ?>
<?php } //IF(isset($_POST['area_id']) AND isset($_POST['schedate'])?>
</table>

</body>
</html>
