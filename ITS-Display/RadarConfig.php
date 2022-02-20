<?php
// Display a title above radar
$ShowTitle = 0;
// Locations of radar - Default is melbourne, 128km
$DefaultLocation = array("IDR023","IDR02I");

$SelectedLocation = $DefaultLocation;
/*
// Legacy code
foreach (array_keys($LocName) as $index)
{
  print "<option";
  if ($SelectedLocation[0] == $index)
  {
    print " selected=\"yes\"";
  }

  if ($SelectedLocation[1] == $index)
  {
    print " selected=\"yes\"";
  }

  print " value=\"$index\">$LocState[$index] $LocName[$index] ($LocRange[$index]Km)</option>\n";
}
*/

?>