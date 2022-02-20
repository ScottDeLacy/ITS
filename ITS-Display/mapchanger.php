<?php

if ($nomaps == 1) {

IF ($SHOWPHOTOS == 1) {

$googleurl = $LHQPHOTO;
$googlecrossstreets = $LHQPHOTO2;
$googlestreetviewurl = $LHQPHOTO3;

} ELSE  {

$googleurl = "maps.googleapis.com/maps/api/staticmap?center=".$LHQADDRESS."&size=600x400&maptype=roadmap&markers=color:blue%7Clabel:H%7C".$LHQADDRESS;
$googlecrossstreets = "maps.googleapis.com/maps/api/staticmap?center=".$LHQADDRESS."&size=350x250&maptype=roadmap&markers=color:blue%7Clabel:H%7C".$LHQADDRESS;
$googlestreetviewurl = "maps.googleapis.com/maps/api/streetview?size=350x250&location=".$LHQADDRESS."&fov=90&heading=235&pitch=10";
	}

}

?>