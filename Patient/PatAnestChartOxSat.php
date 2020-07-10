<?php  //OXYGEN SATURATION
require_once("../../../ChartDirector/lib/phpchartdir.php");

# The data for the line chart
$ba = $_GET['ba'];
//$ba = 1592411400;  //chart start time
//$value = array(95, 90, 85, 80, 70, 80, 90);
//$schedt = array(1592411400, 1592412900, 1592414700, 1592415600, 1592419200, 1592426400,1592430000); # time of readings

$db = @mysql_connect('localhost', 'root', 'jiloa7');
mysql_select_db('swmisbethany', $db);

$colname_anestid = "64";
if (isset($_GET['aid'])) {
  $colname_anestid = $_GET['aid'];
}
$query_anestid = "SELECT anestid, schedt, vital, value, value2 FROM ipvitals WHERE anestid = '".$colname_anestid."' and vital = 'oxysat' ORDER BY schedt ASC";
$anestid = mysql_query($query_anestid, $db) or die(mysql_error());
$row_anestid = mysql_fetch_assoc($anestid);
// put values into array
$schedt = array();
$value = array();
 do { 
	array_push($schedt, chartTime2($row_anestid['schedt']));
	array_push($value, $row_anestid['value']);
 } while ($row_anestid = mysql_fetch_assoc($anestid));

	
# Create a XYChart object of size 800 x 200 pixels
$c = new XYChart(800, 200);

# Set the plotarea at (50, 20) and of size 700 x 150 pixels
$c->setPlotArea(50, 20, 700, 150);

# Add a title to the chart using 10 points Times Bold Itatic font, with white (ffffff) text on a
# deep blue (000080) background
$textBoxObj = $c->addTitle("OXYGEN SATURATION", "timesbi.ttf", 10, 0xffffff);  //use .Chr(10). for new line
$textBoxObj->setBackground(0x000080);

# Add a line chart layer using the given data
$layer = $c->addLineLayer($value);
$layer->setXData($schedt);
 
# Add a data set to the spline layer, using blue (0000c0) as the line color, with yellow (ffff00)
# circle symbols (or DiamondSymbol).
$dataSetObj = $layer->addDataSet($value, 0x0000c0, "Target Group");
$dataSetObj->setDataSymbol(CircleSymbol, 6, 0xffff00);

# Set the labels on the y axis.
$c->yAxis->setLinearScale(50, 100, 5);

# Set the x-axis scale to be date scale of 8 hours that begins at the begining of anesthesia ($ba) and ends 8 hours later
$c->xAxis->setDateScale(chartTime2($ba), chartTime2($ba) + (8*3600));   #8 hour chart
$c->xAxis->setLabelFormat("{value|hh:nn a}");

# Add green (0x99ff99), yellow (0xffff99) and red (0xff9999) zones to the y axis to represent the
# ranges 40-80, 80-90 and 90-100.
$c->yAxis->addZone(50, 80, 0xff9999);
$c->yAxis->addZone(80, 90, 0xffff99);
$c->yAxis->addZone(90, 100, 0x99ff99);
	
# Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>