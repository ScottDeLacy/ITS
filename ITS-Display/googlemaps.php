<?php
// Lets get the names sorted
// $curpage will be the variable for CURRENT pager msgs (raw msg from SQL server)
// $addresses will be the regex matches for $curpage msg (contains all addresses found)
// $crossstreets will be the cross streets found, from the REGEX match of $crossstreets_match, using $addresses as input
// $googleurl will be the google maps URL web address, this is the final string to fetch the map
// $jobaddress will be the double filtered regex address match from the page.
// $googleaddress will be the $jobaddress string replaced to be input into $googleaddress to format the " " as "+" (google maps url compliant).
// $LHQADDRESS will be the LHQ coordinates to be used in googleurl.
// Finds addresses in pager messages


// Address regex pattern
$addressregex = '/(\b[0-9-]{0,7} \b)?(\b[A-Za-z-]{3,}\b )+(\bCNR|ST|RD|ROAD|LN|LNE|CT|COURT|HWY|MWY|AV|CL|B?VD|PWY\b)+(\b [A-Za-z-]{5,}\b ?|\b[A-Za-z]{5,}\b)*(\b|NORTH|SOUTH|EAST|WEST\b)/';

// LHQ Address/Coordinates
$LHQADDRESS = "-37.990363,145.193897";

// Cross Streets identify cleanup / removal regex
$address_crossstreets_regex = '/([\/]{1,}[A-Za-z- ]{1,})/';

$addresses_match = preg_match($addressregex,$curpage,$addresses);

// removes the cross streets from the addresses

$crossstreets_match = preg_match($address_crossstreets_regex,$addresses[0],$crossstreets);
$jobaddress = str_replace($crossstreets[0],"",$addresses);


$googleaddress = str_replace(" ","+",$jobaddress[0]);

// Google Maps URL
$googleurl = "maps.googleapis.com/maps/api/staticmap?center=".$googleaddress."&size=600x400&maptype=roadmap&markers=color:blue%7Clabel:H%7C".$LHQADDRESS."&markers=color:red%7Clabel:S%7C".$googleaddress.$googleapi;
$googlecrossstreets = "maps.googleapis.com/maps/api/staticmap?center=".$googleaddress."&size=350x250&maptype=roadmap&markers=color:red%7Clabel:S%7C".$googleaddress.$googleapi;
$googlestreetviewurl = "maps.googleapis.com/maps/api/streetview?size=350x250&location=".$googleaddress."&fov=90&heading=235&pitch=10".$googleapi;

/* TEST
// $googleurl= http://maps.googleapis.com/maps/api/staticmap?center=20+Napier+St,+Dandenong+VIC+3175&size=600x400&maptype=roadmap&markers=color:blue%7Clabel:H%7C-37.990363,145.193897&markers=color:red%7Clabel:S%7C20+Napier+St,+Dandenong+VIC+3175 ;
Street view test?
http://maps.googleapis.com/maps/api/staticmap?center=20+PRINCES+HWY+DANDENONG&size=300x200&maptype=roadmap&markers=color:red%7Clabel:S%7C20+PRINCES+HWY+DANDENONG
$googlestreetviewurl = "http://maps.googleapis.com/maps/api/streetview?size=300x200&location=".$googleaddress."&fov=90&heading=235&pitch=10";

*/

?>






