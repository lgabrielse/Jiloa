<?php
$emailaddress = "lgabrielse@verizon.net";
$host="localhost"; // database host
$dbuser="root"; // database user name
$dbpswd="jiloa7"; // database password
$mysqldb="swmis"; // name of database
$path = "C:\wamp\www\Len\Jiloa\Backup"; // full server path to the directory where you want the backup files (no trailing slash)
// modify the above values to fit your environment
$filename = $path . "/backup" . date("d") . ".sql";
if ( file_exists($filename) ) unlink($filename);
system("mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb > $filename",$result);
$size = filesize($filename);
switch ($size) {
  case ($size>=1048576): $size = round($size/1048576) . " MB"; break;
  case ($size>=1024): $size = round($size/1024) . " KB"; break;
  default: $size = $size . " bytes"; break;
}
$message = "The database backup for " . $mysqldb . " has been run.\n\n";
$message .= "The return code was: " . $result . "\n\n";
$message .= "The file path is: " . $filename . "\n\n";
$message .= "Size of the backup: " . $size . "\n\n";
$message .= "Server time of the backup: " . date(" F d h:ia") . "\n\n";


mail($emailaddress, "Database Backup Message" , $message, "From: Website <>"); 
?>

<!--You can backup all the databases for your user in one backup using the following line in the above script:

system( "mysqldump --all-databases --user=$dbuser --password=$dbpswd --host=$host > $filename",$result);

You can restore a database using the following line in a php script:

system( "mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb < $filename",$result);

Note that hosts may have different paths for the mysql commands.  For example /usr/local/bin/mysqldump could be required.

If you want the backup file to be compressed, then change two lines (the $filename variable and the system() command) as follows:

$filename = "/full_server_path_to_file_goes_here/backup" . $day . ".sql.gz";
system( "mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb | gzip > $filename",$result); -->