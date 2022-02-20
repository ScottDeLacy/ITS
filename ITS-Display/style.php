<?php
header("Content-type: text/css");

?>

body {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	width:100%;
	height:100%;
}
/* Image resizer - auto size based on parent element */
img {
	max-width: 100%;
    height: auto;
    width: auto\9; /* ie8 */
}

h3 {
	font-size:1.5em;
}

/* This section can be deleted most likely
td {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

th {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
*/
.bodystyle {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

.small {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 9px;
}

.medium {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

.big {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 16px;
}

.xbig {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 24px;
}

.expanded {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 16px;
	letter-spacing: 2px;
}

.justified {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	text-align: justify;
}

.footer {
	font-family: "Times New Roman", Times, serif;
	font-size: 9px;
	color: #999999;
	float:left;
	vertical-align:bottom;
	text-align:center;
	left:55%;
	width: 300px;
	display:block;
	position:relative;
	margin-bottom:2px;	
}

.box1 {
	padding: 3px;
	border-width: thin;
	border-style: solid;
	border-color: #CCCCCC #666666 #666666 #CCCCCC;
}

.box2 {
	font-style: italic;
	word-spacing: 2pt;
	padding: 3px;
	border-width: thin;
	border-style: solid;
}

.logo {
	background-image:url(images/gpsicon.png);
	/* background-size: 45px 45px; */
	background-position:center;
	margin: 0px;
	width:50px;
	height:50px;
}
.datetime {
	display:block;
	text-align:left;
	
	line-height:12px;
	position:absolute;
	float: right;
	width:13%;
	top:20px;
	left: 85%;
	
}

.title {
	vertical-align:middle;
	text-align:center;
	float:left;
	line-height:12px;
	width: 25%;
	display: block;
	position: absolute;
	left: 35%;
	top: 3px;
}

.header {
	border-bottom:double 4px;
	border-bottom-color:#CCC;
	padding: 3px;
}

.ActivePageText {
	font-size:2.2em;
	padding:3px;
}

.NonActivePageText {
	font-size:1.2em;
	padding:3px;
}

.AssignmentsData {
	/*border-collapse:collapse;
	padding:15px; */
	border-spacing: 10px 5px;
	font-size:1.03em;
	line-height: 1.04em;
	text-align:justify;
	width:100%;
	
}

table.AssignmentsData td:first-child {
	text-align:left;
}
table.AssignmentsData td:last-child {
	text-align:right;
}

/* Entire site container div */
.site {
	size:100%;
	width:100%;
}

/* Layout of Left, middle, Right sections */
.leftpane {
	size:30%;
	width:29%;
	min-height:100%;
	height:100%;
	display:block;
	vertical-align:middle;
	float:left;
/*	border-right: 1px;
	border-right-style: double;
	border-right-color: #CCC; */
	padding: 3px;
}

.middlepane {
	size:40%;
	width:39%;
	vertical-align:middle;
	border-right: 1px;
	border-right-style: double;
	border-right-color: #CCC;
	border-left: 1px;
	border-left-style: double;
	border-left-color: #CCC;
	padding: 3px;
	float:left;
	display: block;
}

.rightpane {
	width:30%;
	size:30%;
	vertical-align:middle;
	float:left;
	display:block;
	padding:3px;
	padding-bottom:1px;
}

#footer {
	display:block;
	vertical-align:middle;
	float:left;
}

.NonActivePage {
	border:2px;
	border-style:ridge;
	min-height:175px;
	
}

.ActivePage {
	border:2px;
	border-style:ridge;
	min-height: 175px;
	padding-bottom:2px;
}

.googleapi {
	border:2px;
	border-style:ridge;
	min-height: 400px;
	padding:2px;
}
/* can this be put in googleapi img instead?? test
*/
.googlemaps img {
	max-width: 100%;
    height: auto;
    width: auto\9; /* ie8 */
}

.BOM {
	border:2px;
	border-style:ridge;
	padding:2px;
	vertical-align:middle;
}
.BOM img {
	max-width: 100%;
    height: auto;
    width: auto\9; /* ie8 */
}

.Assignments {
	border:2px;
	border-style:ridge;
	min-height: 280px;
	font-size: medium;
	padding:3px;
}
	
