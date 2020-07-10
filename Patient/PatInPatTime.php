<?php
 $minutes = 15;
$endtime = new DateTime('2015-05-30 23:00');
//$now = date("Y-m-d H:i:s");
//modified the start value to get something _before_ the endtime:    date("Y-m-d H:i:s")
//$time = new DateTime('2015-05-30 00:00');
$time = new DateTime('now');
$interval = new DateInterval('PT' . $minutes . 'M');

while($time < $endtime){
    $time->add($interval);
//echo $time->format('Y-m-d H:i')."<br />";
}?>
1******************************<br />
<?php $date = new DateTime("2013-03-05 08:00");
$date->add(new DateInterval('PT15M'));
echo $date->format("Y-m-d H:i")."<br />";
?>
2******************************<br />
<?php //mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )

echo date("M-d-Y H:i", mktime(9, 0, 0, 2, 5, 2015))."<br />";

?>
3******************************<br />
<?php
$colname_schedate = date("Y-m-d H");
echo $colname_schedate.' Start hour'."<br />";
?>
4******************************<br />

<?php // get $mkmonth, $mkday, $mkyear); from $colname_schedate to populate the mktime below
//  $mkhour = date('H',strtotime($colname_schedate)) ;
//  $mkmonth = date('m',strtotime($colname_schedate)) ;
//  $mkday =  date('d',strtotime($colname_schedate)) ;
//  $mkyear = date('Y',strtotime($colname_schedate)) ;
//echo "test date parts:".$mkyear. "-" . $mkmonth . "-" . $mkday . " : " . $mkhour. " : " . $mkmin ."<br />"; 
  $colname_schedate = date("Y-m-d H:i"); 
	echo '$colname_schedate'.$colname_schedate."<br />";
  $mkmin = date('i',strtotime($colname_schedate)) ;   // minutes of current time
  //$mkmin = '58';
    	echo '$mkmin: '.$mkmin."<br />";
  $onhour = strtotime($colname_schedate - (int)$mkmin*60*60);// - ($mkmin*60));
		echo 'onhour # '.$onhour."<br />"; 
		echo 'onhour dt'.date('H:i',$onhour)."<br />";	
	
  if($mkmin >=0 && $mkmin <=15 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(15*60));  //show 15 minutes past hour 
  }
  elseif($mkmin >15 && $mkmin <=30 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(30*60));
  }
  elseif($mkmin >30 && $mkmin <=45 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(45*60));
  }
  elseif($mkmin >45 && $mkmin <=59 ){
  	$onhour = strtotime($colname_schedate) - ((int)($mkmin*60)-(60*60));
  }
//echo strtotime($colname_schedate)."<br />"; 
//$onhour = strtotime($colname_schedate) - (($mkmin*60)-(60*60));
echo 'onhour '.$onhour."<br />"; 
echo date('H:i',strtotime($colname_schedate) - ((int)$mkmin*60))."<br />";
?>
<?php for ($t = $onhour; $t >= $onhour - (2*60*60); $t-=(15*60)) { //$t += $intrvl
	echo $t.'   '.date('Y-m-d h:i A',$t)."<br />";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
