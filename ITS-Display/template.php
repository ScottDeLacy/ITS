<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!-- PHP INCLUDES - DO NOT CHANGE THE ORDERING! -->
<?php include 'settings.php'; ?>
<?php include 'datafetch.php'; ?>
<?php include 'googlemaps.php'; ?>
<?php include 'mapchanger.php'; ?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print $title; ?></title>
<link href="Level2_Verdana_Text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="date_time.js"></script>
<script type="text/javascript" src="Launcher.js"></script>
<!-- This <meta http-equiv="refresh" content="5" > refreshes the page every x seconds -->
<meta http-equiv="refresh" content="120" >

</head>




<body class=body>
<!-- Total site container DIV for sizing? --><div class="site">
<div class=title><H2><p><?php print $title; ?></p></H2></div><div class=header>

<div class=logo>
  <!-- Logo goes here --></div>

<div class=datetime><p><span id="date_time"></span>
            <script type="text/javascript">date_time('date_time');</script></p></div>

</div>

<div>
  <div class=leftpane>
<div class=section1>
<div class=Sub-Heading><h3><p>Previous Jobs</p></h3></div>
<div class=NonActivePage>
<div class=NonActivePageText>

<?php foreach ($prepage as $prepagetext) {
   echo "<p>" . $prepagetext . "</p>";
   }
?>

  </div>
</div>

<div class=Section4>
<div class=Sub-Heading><h3><p>Current Assignments</p></h3></div>
<div class=Assignments>
<table class="AssignmentsData">
<?php print $curassignheading ?>
<?php foreach ($curassign as $curassigndata) {
   echo $curassigndata;
   }
?>
</table>
</div>
</div>
</div>
</div>

<div class=middlepane>
<div class=Section2>
<div class=Sub-Heading><h3><p>Latest Job</p></h3></div>
<div class=ActivePage><br />
<div class=ActivePageText><?php print $curpage; ?></div> 
</div>
<div class=googleapi>
    <div class="googlemaps" align="center"><img src="http://<?php print $googleurl; ?>" width="800" height="600" align="middle" /></div><div class="googlemaps" align="center"><img src="http://<?php print $googlecrossstreets; ?>" width="350" height="250" /><img src="http://<?php print $googlestreetviewurl; ?>" width="350" height="250" /></div>
</div>
</div>

<div id=footer>
<div class=footer>Turnout Information System (C)2014, By Scott De Lacy</div>
</div>

</div>

<div class=rightpane>
<div class=Section3>
<div class=Sub-Heading><h3><p>Warnings and Alerts</p></h3></div>
<div class=BOM>
  
    <div class="BOM img"><?php include_once 'BomRadar.php'; ?>  <!-- <img src="images/bomplaceholder.JPG" width=500 height=450 align="middle" /> --> </div>

</div>
<div class=BOM>
<p>Other Data?
  </p>

</div>
</div>
</div>
</div>

<!-- End of SITE container div --></div>


</body>
</html>