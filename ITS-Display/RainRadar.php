<script LANGUAGE="JavaScript" type="text/javascript">
<!--
nImages=0
debug=0 // 0=silent, 1=Print debug msgs
// -->
</script>
<?php

include_once("RainRadarConfig.php");

// Connect to FTP host
$user="anonymous";
$pass="anonymouse@";
$host="ftp2.bom.gov.au";
$remote_dir="/anon/gen/radar/";

$conn = ftp_connect($host, $port) or die("Could not connect to {$host}\n");
 
if(ftp_login($conn, $user, $pass)) 
{
  $files = ftp_nlist($conn, $remote_dir);
}
      
ftp_close($conn);
$count=0;
?>
<SCRIPT LANGUAGE="JavaScript"> 
<!--
theImageNames = new Array();
<!-- get Pictures --> 

<?php
// get the number of images...
foreach($files as $file)
{
    if(preg_match("/$SelectedLocation.T/",$file))
    {
      $ImageCount++;
    }
}

$StartImage=$ImageCount - 8;

$ImageCount = 0;
$Count = 0;
foreach($files as $file)
{
    $file = basename($file);
    if(preg_match("/$SelectedLocation.T/",$file))
    {
    if ($Count > $StartImage)
    {
      print "theImageNames[$ImageCount] = \"http://www.bom.gov.au/radar/$file\";\n";
      $ImageCount++;
    } 
      $Count++;
    }
}
print "nImages = $ImageCount;";
?>
<!-- end get Pictures --> 
Km = 128
//-->
</SCRIPT> 
<script LANGUAGE="JavaScript" src="IDR.loop.v03.js" type="text/javascript"> 
</script>
        </p>
        <?php
        if ($ShowTitle == "1")
        {
        ?>
        <p align="center">
        <font size=+1 color="#FF0000">Rain Radar for <?php print $LocName[$SelectedLocation].", range of $LocRange[$SelectedLocation]Km"; ?></font></p>
        <?php
        }
        ?>
        
        <TABLE BORDER=3 align="center" CELLPADDING=0 CELLSPACING=0 
       summary="radar images and loop controls" NAME=table1> 
          <TR>
            <TD height="512" ALIGN=LEFT VALIGN=TOP>
<div id="backgroundDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.background.png);  position: relative; left: 0px; top: 0px;"/> 
<div id="waterwaysDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.waterways.png); background-color: transparent; position: absolute; left: 0px; top: 0px;"/> 
<div id="topographyDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.topography.png); background-color: transparent; position: absolute; left: 0px; top: 0px;"/> 
<div id="riverBasinsDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.riverBasins.png); background-color: transparent; position: absolute; left: 0px; top: 0px;"/><div id="loop" style="width: 512px; height: 512px; background-color: transparent; position: absolute; left: 0px; top: 0px;"/>
	<img name="animation" border=0 height=514 width=512 src="http://mirror.bom.gov.au/products/IDR.please.wait.gif" alt="Radar Image is *MISSING*"> 
<div id="rangeDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.range.png); background-color: transparent; position: absolute; left: 0px; top: 0px;"/> 

<div id="locationsDiv"  style="width: 512px; height: 512px; background-image: url(http://mirror.bom.gov.au/products/radar_transparencies/<?php print "$SelectedLocation"; ?>.locations.png); background-color: transparent; position: absolute; left: 0px; top: 0px;"/> 
<img src="http://hamlynheightsweather.com/images/logo.jpg?<?php print $_SERVER['SERVER_NAME']; ?>" height=1px width=1px">
  </TD>
<script type="text/javascript">
  <!--
 
 
 
   if (nImages==1) {
       document.writeln ('\
      <FONT COLOR="#3300CC"><FONT SIZE=+1>Only 1 image.</FONT></FONT>\
      ');
   }
   if (nImages==0) {
       document.writeln ('\
      <FONT COLOR="#3300CC"><FONT SIZE=+1>No images.</FONT></FONT>\
      ');
   }
 
  if (nImages>1) {
       document.writeln ('\
    <FORM METHOD="POST" NAME="control_form">\
      <INPUT TYPE="hidden" NAME="frame_nr" SIZE="2" READONLY>\
    </FORM>\
      ');
  }
 
if (nImages>0) {
    if (doMouse) {
       document.writeln ('\
    <form name="myForm">\
		<input type="hidden" id="x">\
		<input type="hidden" id="y">\
		<input type="hidden" id="z">\
		<input type="hidden" id="a">\
    </form>\
\
    <form name="offsets">\
         <input type="hidden" name="xo" >\
         <input type="hidden" name="yo" >\
    </form>\
\
    ');
    }
}
// -->
</script> 
</div>
</table>

<!-- START OF FOOTER -->
<!-- TO USE THE BOM DATA THIS FOOTER MUST REMAIN INTACT, leagalise rubbish etc-->
<center>
<font size=-2>
<p id=x>Data Sourced from Commonwealth of Australia <a id=x href="http://www.bom.gov.au">Bureau of Meteorology</a>.<br> 
</p>
</center>
<!-- END OF FOOTER -->
