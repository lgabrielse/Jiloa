<!--You can restore a database using the following line in a php script:
-->
<?php 

$host="localhost"; // database host
$dbuser="root"; // database user name
$dbpswd="jiloa7"; // database password
$mysqldb="swmis"; // name of database
$path = "C:\wamp\www\Len\Jiloa\Backup"; // full server path to the directory where you want the backup files (no trailing slash)
// modify the above values to fit your environment
// replace XX in the backupXX.sql with the day number to be restored   e.g. backup16.sql
$filename = $path . "/backup16.sql";


system( "mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb < $filename",$result);
?>


<!--Note that hosts may have different paths for the mysql commands.  For example /usr/local/bin/mysqldump could be required.
-->