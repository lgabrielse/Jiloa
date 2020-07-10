
<?php // include($_SERVER['DOCUMENT_ROOT'].'/Len/functions/FileList.php');  (it is decalred in PatShow1) ?>

<?PHP //http://www.the-art-of-web.com/php/dirlist/

/* function getFileList($dir) 
 { // array to hold return value
  $retval = array(); // add trailing slash if missing
  if(substr($dir, -1) != "/") $dir .= "/"; // open pointer to directory and read list of files
  $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
  while(false !== ($entry = $d->read())) { // skip hidden files
	  if($entry[0] == ".") continue;
	  if(is_dir("$dir$entry")) {
	    $retval[] = array( 
			"name" => "$dir$entry/", 
			"type" => filetype("$dir$entry"), 
			"size" => 0, 
			"lastmod" => filemtime("$dir$entry") );
	 }
	  elseif(is_readable("$dir$entry")) {
		  $retval[] = array( 
		  "name" => "$dir$entry", 
		  "type" => filetype("$dir$entry"), 
		  "size" => filesize("$dir$entry"), 
		  "lastmod" => filemtime("$dir$entry") );
	  }
   } 
    $d->close();
    return $retval;
 }
 */
?>

<?PHP // examples for scanning the current directory
  //$dirlist = getFileList(".");
  //$dirlist = getFileList("./"); // examples for scanning a directory called images
  //$dirlist = getFileList("images");
  //$dirlist = getFileList("images/");
  //$dirlist = getFileList("./images"); 
  //$dirlist = getFileList("./images/");
  $dirlist = getFileList("../../data_swmis/scansnap/"); 
?>
<?php function between($src,$start,$end){  // find MRN
    $txt=explode($start,$src);
    $txt2=explode($end,$txt[1]);
    return trim($txt2[0]);
  }
?>
<?php 
 if(isset($_SESSION['mrn'])) {
  $patmrn = (get_magic_quotes_gpc()) ? $_SESSION['mrn'] : addslashes($_SESSION['mrn']); 
 }
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div align="center" class="BlackBold_24">Patient Record PDF Files View</div>
<table border="1"> 
 <thead>
  <tr>
   <th>Name</th>
   <th>Type</th>
   <th>Size</th>
   <th>Last Modified</th>
  </tr>
 </thead>
 <tbody>
 <?PHP // output file list as table rows
  foreach($dirlist as $file) {
   //if($file['type'] == 'file') continue;
   	//$pdfmrn = between(basename($file['name']), "x", ".");  // used for 20140730214324x28.pdf format
	$pdfmrn = explode('x',basename($file['name']));
    if($pdfmrn[0] == $patmrn) {
    echo "<tr>\n";
 	//echo basename($file['name']);
	echo "<td class=\"BlueBold_1414\"><a href=\"{$file['name']} \"  target=\"_blank\">",basename($file['name']),"</a></td>\n";
	echo "<td>{$file['type']}</td>\n";
	echo "<td>{$file['size']}</td>\n";
	echo "<td>",date('d-M-Y', $file['lastmod']),"</td>\n";
	echo "</tr>\n";
   }
  }
 ?>
 </tbody>
</table>
</body>
</html>
