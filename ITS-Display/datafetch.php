<?php
/* Copyright 2014 By Scott De Lacy
This code fetches the current page and 2 previous page messages from the database for display and manipulation
*/

// Remove once tested
//include_once 'settings.php';


$link = @mysql_connect($sqlserver,$sqluser,$sqlpass); //fix the $sqlport variable
@mysql_select_db($sqldb) or die(mysql_error() . "<br>You may want to double check your SQL Login details in settings.php</br>");
if (!$link) {
    
$curpage = "<p>Connection Error - Unable to read latest Page data</p>";
$prepage = "<p>Connection Error - Unable to read Previous Page data</p>";
$curassign = "<p>Connection Error - Unable to read the current assignments</p>";
die('Could not connect: ' . mysql_error());

}

// mysql_close($link);

// Fetch Page information

@mysql_connect($link);


$curpagesql = @mysql_query("SELECT `message` from `pages` WHERE (select MAX(`PageID`) AND TIMESTAMP(`date`,`time`) >= NOW() - INTERVAL 2 HOUR) order by `PageID` DESC LIMIT 1");

$prepagesql = @mysql_query("SELECT `message` FROM (select * from `pages` WHERE TIMESTAMP(`date`,`time`) >= NOW() - INTERVAL 2 HOUR order BY `PageID` DESC limit 4) SUB order by `PageID` ASC LIMIT 3");

$curassignsql = @mysql_query("SELECT GROUP_CONCAT(DISTINCT `jobnumber`) as `jobnumber`, GROUP_CONCAT(DISTINCT `resource` SEPARATOR', ') AS `resource`, MAX(`assigntime`) AS `assigntime` from `assignments` where `jobclear` = 0 group by `jobnumber` ORDER BY `assigntime`  DESC");


// set the curpage variable
if (mysql_num_rows($curpagesql) > 0 ) {

$curpage = @mysql_result($curpagesql, 0);

} Else {

	$nomaps = 1;
	$curpage = "<p align=middle>There have been no Jobs in the last 2 hours</p>";
}


// set the prepage variable
while($row=mysql_fetch_array($prepagesql)) {

       $prepage[] = $row['message'];		   

}

// Remove current page from previous page results

if (($prepagefilter = array_search($curpage, $prepage)) !== false) {
unset($prepage[$prepagefilter]);
}
// If there are no previous pages, display a notification (as an array entry)
if (!$prepage) {
$prepage[] = "<p align=middle>There have been no other Jobs within the last 2 hours</p>";
}
// Reverse the array so it is fetched and displayed Newer to older
if ($prepage) {
	$prepage = array_reverse($prepage);
}

// CURRENT ASSIGNMENTS
if (mysql_num_rows($curassignsql) > 0 ) {

$curassignheading = "<tr><td><b>Job Number</b></td><td><b>Resources</b></td><td><b>Assignment Time</b></td></tr>";

while($row=mysql_fetch_array($curassignsql)) {
$curassign[] = "<tr><td>".$row['jobnumber']."</td><td>".$row['resource']."</td><td>".$row['assigntime']."</td></tr>";
}

} Else {
	$currassignheading = "";
	$curassign = "";
}
   
   
?>