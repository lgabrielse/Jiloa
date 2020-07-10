<?php require_once("../../../ChartDirector/lib/phpchartdir.php");

$db = @mysql_connect('localhost', 'root', 'jiloa7');
mysql_select_db('swmisbethany', $db);

$colname_anestdrugs = "4";
if (isset($_GET['aid'])) {
  $colname_anestdrugs = $_GET['aid'];
}
$query_anestdrugs = "SELECT ad.id, ad.druglistid, ad.anestid, ad.begindrug, ad.enddrug, al.drug  FROM anestdrug ad join anestdruglist al on ad.druglistid = al.id WHERE anestid = '".$colname_anestdrugs."' ORDER BY begindrug ASC";
$anestdrugs = mysql_query($query_anestdrugs, $db) or die(mysql_error());
$row_anestdrugs = mysql_fetch_assoc($anestdrugs);

$num = 0;
$bd1 = $bd2 = $bd3 = $bd4 = $bd5 = $bd6 = $bd7 = $bd8 = $bd9 = $bd10 = $bd11 = $bd12 = $bd13 = $bd14 = $bd15 = 0;
$ed1 = $ed2 = $ed3 = $ed4 = $ed5 = $ed6 = $ed7 = $ed8 = $ed9 = $ed10 = $ed11 = $ed12 = $ed13 = $ed14 = $ed15 = 0;
$dd1 = $dd2 = $dd3 = $dd4 = $dd5 = $dd6 = $dd7 = $dd8 = $dd9 = $dd10 = $dd11 = $dd12 = $dd13 = $dd14 = $dd15 = '';
 do { 
  $num = $num + 1;
	if($row_anestdrugs['begindrug'] == NULL) {
	  ${'bd'.$num} = time();
  } else {
	  ${'bd'.$num} = $row_anestdrugs['begindrug'];  //https://stackoverflow.com/questions/6234864/how-to-change-php-variable-name-in-a-loop
	}
  if($row_anestdrugs['enddrug'] == NULL)  {
	  ${'ed'.$num} = time();
  } else {
	  ${'ed'.$num} = $row_anestdrugs['enddrug'];
  }

	 ${'dd'.$num} = $row_anestdrugs['drug'];
 } while ($row_anestdrugs = mysql_fetch_assoc($anestdrugs));

if(isset($_GET['ba']))  {$ba=$_GET['ba'];}
if(isset($_GET['ea']))  {$ea=$_GET['ea'];} //strtotime(date('m-d-Y h i a'));  // current date/time
if(isset($_GET['bs']))  {$bs=$_GET['bs'];}
if(isset($_GET['es']))  {$es=$_GET['es'];}
//   if($es == null) {$es= strtotime(date('m-d-Y h i a')); } // current date/time

// find anesthesia interval (duration) and change it to houres and minutes
$anseconds = ($ea - $ba) ;
$anhours = floor($anseconds / 3600);
$anseconds -= $anhours * 3600;
$anminutes = floor($anseconds / 60);
$anseconds -= $anminutes * 60;
$andur = $anhours.':'.$anminutes; //.' H:m'; //24:0:1

// find surgery interval (duration) and change it to houres and minutes
$suseconds = ($es - $bs) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$sudur = $suhours.':'.$suminutes; //24:0:1

// find drug 1 interval (duration) and change it to houres and minutes
$suseconds = ($ed1 - $bd1) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d1dur = $suhours.':'.$suminutes; //24:0:1

// find drug 2 interval (duration) and change it to houres and minutes
$suseconds = ($ed2 - $bd2) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d2dur = $suhours.':'.$suminutes; //24:0:1

// find drug 3 interval (duration) and change it to houres and minutes
$suseconds = ($ed3 - $bd3) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d3dur = $suhours.':'.$suminutes; //24:0:1

// find drug 4 interval (duration) and change it to houres and minutes
$suseconds = ($ed4 - $bd4) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d4dur = $suhours.':'.$suminutes; //24:0:1

// find drug 5 interval (duration) and change it to houres and minutes
$suseconds = ($ed5 - $bd5) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d5dur = $suhours.':'.$suminutes; //24:0:1

// find drug 6 interval (duration) and change it to houres and minutes
$suseconds = ($ed6 - $bd6) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d6dur = $suhours.':'.$suminutes; //24:0:1

// find drug 7 interval (duration) and change it to houres and minutes
$suseconds = ($ed7 - $bd7) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d7dur = $suhours.':'.$suminutes; //24:0:1

// find drug 8 interval (duration) and change it to houres and minutes
$suseconds = ($ed8 - $bd8) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d8dur = $suhours.':'.$suminutes; //24:0:1

// find drug 9 interval (duration) and change it to houres and minutes
$suseconds = ($ed9 - $bd9) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d9dur = $suhours.':'.$suminutes; //24:0:1

// find drug 10 interval (duration) and change it to houres and minutes
$suseconds = ($ed10 - $bd10) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d10dur = $suhours.':'.$suminutes; //24:0:1

// find drug 11 interval (duration) and change it to houres and minutes
$suseconds = ($ed11 - $bd11) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d11dur = $suhours.':'.$suminutes; //24:0:1

// find drug 12 interval (duration) and change it to houres and minutes
$suseconds = ($ed12 - $bd12) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d12dur = $suhours.':'.$suminutes; //24:0:1

// find drug 13 interval (duration) and change it to houres and minutes
$suseconds = ($ed13 - $bd13) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d13dur = $suhours.':'.$suminutes; //24:0:1

// find drug 14 interval (duration) and change it to houres and minutes
$suseconds = ($ed14 - $bd14) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d14dur = $suhours.':'.$suminutes; //24:0:1

// find drug 15 interval (duration) and change it to houres and minutes
$suseconds = ($ed15 - $bd15) ;
$suhours = floor($suseconds / 3600);
$suseconds -= $suhours * 3600;
$suminutes = floor($suseconds / 60);
$suseconds -= $suminutes * 60;
$d15dur = $suhours.':'.$suminutes; //24:0:1

# data for the gantt chart, representing the start date, end date and names for various activities
$startDate = array(chartTime2($ba),chartTime2($bs),chartTime2($bd1),chartTime2($bd2),chartTime2($bd3),chartTime2($bd4),chartTime2($bd5),chartTime2($bd6),chartTime2($bd7),chartTime2($bd8),chartTime2($bd9),chartTime2($bd10),chartTime2($bd11),chartTime2($bd12),chartTime2($bd13),chartTime2($bd14),chartTime2($bd15));
//$startDate = array(chartTime2($ba),chartTime2($bs),chartTime2($bd1),chartTime2($bd2),chartTime2($bd3),chartTime2($bd4),chartTime2($bd5),chartTime2($bd6),chartTime2($bd7));

$endDate = array(chartTime2($ea),chartTime2($es),chartTime2($ed1),chartTime2($ed2),chartTime2($ed3),chartTime2($ed4),chartTime2($ed5),chartTime2($ed6),chartTime2($ed7),chartTime2($ed8),chartTime2($ed9),chartTime2($ed10),chartTime2($ed11),chartTime2($ed12),chartTime2($ed13),chartTime2($ed14),chartTime2($ed15));
//$endDate = array(chartTime2($ea),chartTime2($es),chartTime2($ed1),chartTime2($ed2),chartTime2($ed3),chartTime2($ed4),chartTime2($ed5),chartTime2($ed6),chartTime2($ed7));

$labels = array("(".$andur.")"."Anesthesia","(".$sudur.")"."Surgery", "(".$d1dur.")".$dd1, "(".$d2dur.")".$dd2,"(".$d3dur.")".$dd3, "(".$d4dur.")".$dd4, "(".$d5dur.")".$dd5, "(".$d6dur.")".$dd6, "(".$d7dur.")".$dd7, "(".$d8dur.")".$dd8, "(".$d9dur.")".$dd9, "(".$d10dur.")".$dd10, "(".$d11dur.")".$dd11, "(".$d12dur.")".$dd12, "(".$d13dur.")".$dd13, "(".$d14dur.")".$dd14, "(".$d15dur.")".$dd15);
//$labels = array("Anesthesia", "Surgery", $dd1, $dd2, $dd3, $dd4, $dd5, $dd6, $dd7);
$colors = array(0x00ff00, 0xff5347, 0xee82ee, 0x4b0082, 0x0000FF, 0x008000, 0xffff00, 0x00ff00, 0xff5347, 0xee82ee, 0x4b0082, 0x0000FF, 0x008000, 0xffff00,0x00ff00);

# Create a XYChart object of size 620 x 280 pixels. Set background color to light blue (ccccff),
# with 1 pixel 3D border effect. 1220,300
$c = new XYChart(980, 320, 0xccccff, 0x000000, 1);

# Add a title to the chart using 15 points Times Bold Itatic font, with white (ffffff) text on a
# deep blue (000080) background
$textBoxObj = $c->addTitle("Anesthesia Time Chart                           See (duration) For Anesthesia, Surgery & Drugs"	, "timesbi.ttf", 15, 0xffffff);  //use .Chr(10). for new line
$textBoxObj->setBackground(0x000080);

# Set the plotarea at (140, 55) and of size 460 x 200 pixels. Use alternative white/grey background.
# Enable both horizontal and vertical grids by setting their colors to grey (c0c0c0). Set vertical
# major grid (represents month boundaries) 2 pixels in width 140, 75, 860, 200
$plotAreaObj = $c->setPlotArea(150, 55, 680, 250, 0xffffff, 0xeeeeee, LineColor, 0xc0c0c0, 0xc0c0c0) ;
#Thickness of lkines and ticks
$plotAreaObj->setGridWidth(2, 2, 1, 1);

# Add a legend box at (480, 20) using vertical layout and 12pt Arial font. Set background and border
# to transparent and key icon border to the same as the fill color.             1000, 75, true, "arial.ttf",12
$b = $c->addLegend(830, 55, true, "arial.ttf", 8);
$b->setBackground(0xfffdda, 0x000000);
$b->setKeyBorder(SameAsMainColor);


# swap the x and y axes to create a horziontal box-whisker chart
$c->swapXY();

# Set the y-axis scale to be date scale from Aug 16, 2004 to Nov 22, 2004, with ticks every 7 days
# (1 week)
$c->yAxis->setDateScale(chartTime2($ba), chartTime2($ba + (8*3600)));
#
$c->yAxis->setLabelFormat("{value|hh:nn a}");

# Set multi-style axis label formatting. Month labels are in Arial Bold font in "mmm d" format.
# Weekly labels just show the day of month and use minor tick (by using '-' as first character of
# format string).
//$c->yAxis->setMultiFormat(StartOfMonthFilter(), "<*font=arialbd.ttf*>{value|mmm d}",
//    StartOfDayFilter(), "-{value|d}");

# Set the y-axis to shown on the top (right + swapXY = top)   10,12
$c->setYAxisOnRight();
$c->xAxis->setLabelStyle("arial.ttf", 9);
$c->yAxis->setLabelStyle("arial.ttf", 10);

# Set the labels on the x axis
$c->xAxis->setLabels($labels);

# Reverse the x-axis scale so that it points downwards.
$c->xAxis->setReverse();

# Set the horizontal ticks and grid lines to be between the bars
$c->xAxis->setTickOffset(0.5);

# Add a multi-color box-whisker layer to represent the gantt bars
$layer = $c->addBoxWhiskerLayer2($startDate, $endDate, null, null, null, $colors, null, $labels);   

# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>