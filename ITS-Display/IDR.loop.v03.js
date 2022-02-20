<!--
// radar.loop.v03.js
// Below is the Radar Loop code plus cursor Pointer & Origin code.
// v3 2003-03-17 Clive Edington, fix typo bug for Mac (thanks Nik).
// v3 2003-03-11 Clive Edington, Opera v7 does mouse distances like IE.
//                               Adjusted NS and IE mouse offsets (+1).
//				 Fixed '-0km' to '0km'.
// v3 2003-03-04 (Clive Edington) Nik Hamptons Mac IE mouse fixes.
// v3 2003-01-02 Clive Edington, Always do loops. Selectively do mouse.
// v2 2002-02-20 Clive Edington, "km" (not "Km").
// 2001-05-07 Clive Edington, Better logic for browser version vs actions.
// 2000-12-07 Clive Edington, No html here, just js.  Allow No images.
// 2000-07-28 Clive Edington, generalised the code for www.bom.gov.au
// Thanks to Alf West and the Radar Section for the original code.


// Usage:
// (1) Call 'launch()' once (e.g. from BODY onload="..") to start this code.
// (2) Assumes that these Image dependent variables have already been defined:
//
// Km = nn;	// Standard 64km, 128km, 256km or 512km radar picture.
// nImages = n;
// theImageNames = new Array();
// theImageNames[0] = "filename1.gif";
// theImageNames[1] = "filename2.gif";
// ...etc...
// -----------------------------------------------------------------

// If there are some images, loop them.
// else when no images are available, exit now.
//	Assume that the enclosing html will provide a message.

// 
// Microsoft vs. Netscape.  See below for safer definitions.
// If either of these are true, then the mouse-distances (cursor) shows.
// If both false, then we get simple loops, but no mouse.
  isMS  = false;
  isNN4 = false;
  isNN6 = false;
  isMac = false;
  doMouse = false;
  if (debug) {
    document.writeln ("<br> Browser = " + navigator.appName );
    document.writeln ("<br> Version = " + navigator.appVersion);
  }
  browser_version = parseFloat(navigator.appVersion)
  if (!browser_version) browser_version = 0.
  //browser_version = 0.  // only do this during testing.
  if (debug) {
    document.writeln ("<br> extracted Browser version = " + browser_version );
  }

  if (navigator.appName.indexOf("Microsoft") != -1) {
    if ( browser_version >= 4.) isMS = true;
    // IE on Mac has different Mouse calculations.
    if ( navigator.appVersion.indexOf("Mac") != -1) isMac = true;
  }

  if (navigator.appName.indexOf("Netscape") != -1) {
    // Netscape 6 reports v5 !!!
    if  ( (browser_version >= 4.) && (browser_version < 5.) ) isNN4 = true;
    if  (  browser_version >= 5.                            ) isNN6 = true;
  }   

  if (navigator.appName.indexOf("Opera") != -1) {
    // Opera v7 is MS compatible for mouse distances.    
    if  (  browser_version >= 7. ) isMS = true;
  }   
  if (isMS || isNN4) doMouse = true;

  if (debug) {
    document.writeln ("<br> isNN4 = " + isNN4 + ", isNN6=" + isNN6)
    document.writeln ("<br> isMS = "  + isMS  + ", isMac=" + isMac)
    document.writeln ("<br> doMouse = " + doMouse);
    document.writeln ("<br>")
  }

if (nImages>0) { 

  var doc;	// Holds a pointer to MS or NN document (see launch).
  
// Now the general Looping code.
//
//============================================================
//                >> jsImagePlayer 1.0 <<
//            for Netscape3.0+, September 1996
//                  by (c)BASTaRT 1996
//             Praha, Czech Republic, Europe
// feel free to copy and use as long as the credits are given
//          by having this header in the code
//          contact: xholecko@sgi.felk.cvut.cz
//          http://sgi.felk.cvut.cz/~xholecko
//     modified by D. Watson and A. Earnhart (CIRA/CSU), 7/30/97
//     and Greg Thompson (NCAR/RAP) Dec. 11 1997
//============================================================

// step 1: define the images
//		See "theImageNames" set above.
 
//
// step 2: define variables used to control images
//

image_href = "";
first_image = 0;
last_image = nImages-1;

//
// step 3: define dimensions of image (would be nice if this were interactively done)
//         Presently these ARE NOT used below. See step 9
//

animation_height  = 524;
animation_width  = 564;
 
//**************************************************************************
 
//=== THE CODE STARTS HERE - no need to change anything below ===
 
//=== global variables ====

theImages = new Array();      //holds the images
imageNum = new Array();       //keeps track of which images to omit from loop
normal_delay = 300;
delay = normal_delay;         //delay between frames in 1/100 seconds
delay_step = 50;
delay_max = 4000;
delay_min = 50;
dwell_multipler = 3;
dwell_step = 1;
end_dwell_multipler   = dwell_multipler;
start_dwell_multipler = dwell_multipler - 1;

current_image = first_image;     //number of the current image
timeID = null;
looping = 0;                      // 0-stopped, 1-playing
play_mode = 0;                   // 0-normal, 1-loop, 2-sweep
size_valid = 0;
 
}  // (end of if there are images to loop)

//==============================================================
//== All previous statements are performed as the page loads. ==
//== The following functions are also defined at this time.   ==
//==============================================================
 
//===> Load and initialize everything once page is downloaded 
//	(called from 'onLoad' in <BODY>)

function launch()
{
  doc = document;
  if (isNN4) doc = document.animationlayer.document;
  
  if (nImages==0) {
     doc.animation.src = "IDR.no.images.gif"	// Scrub the 'Please wait' image.
     return;
  }
  if (doMouse)  startxy ();	// Start pointer-origin code.

  // If there is only 1 image, show it, but dont start the loop.
  if (nImages==1) {
    current_image = 0;
    theImages[current_image] = new Image();
    theImages[current_image].src = image_href + theImageNames[current_image];
    imageNum[current_image] = true;   // pretend it is ready.
    display_current_image();
    return;
  }
    
  
  //
  // step 5: construct filenames for all images 
  //
  for (var i = first_image; i <= last_image; i++)
  {
    current_image = i;
    theImages[current_image] = new Image();
    theImages[current_image].src = image_href + theImageNames[current_image];
    imageNum[current_image] = false;  // image is not ready yet.
  }
  current_image = first_image;
  imageNum[current_image] = true;   // pretend it is ready.
 
  // this needs to be done to set the right mode when the page is manually reloaded
  change_mode (1);
  fwd();
}
 
//==> Display the 'current_image'.

function display_current_image()
{
   //display image onto screen
   doc.animation.src = theImages[current_image].src;
   //display image number
   document.control_form.frame_nr.value = current_image+1;
}
 
//===> Stop the animation

function stop()
{
//== cancel animation (timeID holds the expression which calls the fwd or bkwd function) ==
  if (looping == 1) {
    clearTimeout (timeID);
  }
  looping = 0;
  return;
}

//===> Display animation in fwd direction in either loop or sweep mode

function animate_fwd()
{
   if (nImages<=1) return;
   current_image++;                      //increment image number
 
  //== check if current image has exceeded loop bound ==
  if (current_image > last_image) {
    if (play_mode == 1) {              //fwd loop mode - skip to first image
      current_image = first_image;
    }
    if (play_mode == 2) {              //sweep mode - change directions (go bkwd)
      current_image = last_image;
      animate_rev();
      return;
    }
  }
 
  //== check to ensure that current image has not been deselected from the loop ==
  //== if it has, then find the next image that hasn't been ==
  while (imageNum[current_image] == false) {
    if (theImages[current_image].complete) {
        imageNum[current_image] = true;
        break;
    }
    current_image++;
    if (current_image > last_image) {
      if (play_mode == 1)
        current_image = first_image;
      if (play_mode == 2) {
        current_image = last_image;
        animate_rev();
        return;
      }
    }
  }
 
  display_current_image();

  delay_time = delay;
  if (current_image == first_image)  delay_time = start_dwell_multipler*delay;
  if (current_image == last_image)   delay_time =   end_dwell_multipler*delay;
 
  //== call "animate_fwd()" again after a set time (delay_time) has elapsed ==
  timeID = setTimeout("animate_fwd()", delay_time);
}
 
 
//===> Display animation in reverse direction

function animate_rev()
{
  if (nImages<=1) return;
  current_image--;                      //decrement image number
 
  //== check if image number is before lower loop bound ==
  if (current_image < first_image) {
    if (play_mode == 1) {               //rev loop mode - skip to last image
       current_image = last_image;
    }
    if (play_mode == 2) {
      current_image = first_image;     //sweep mode - change directions (go fwd)
      animate_fwd();
      return;
    }
  }
 
  //== check to ensure that current image has not been deselected from the loop ==
  //== if it has, then find the next image that hasn't been ==
  while (imageNum[current_image] == false) {
    if (theImages[current_image].complete) {
        imageNum[current_image] = true;
        break;
    }
    current_image--;
    if (current_image < first_image) {
      if (play_mode == 1)
        current_image = last_image;
      if (play_mode == 2) {
        current_image = first_image;
        animate_fwd();
        return;
      }
    }
  }
  
  display_current_image();

  delay_time = delay;
  if (current_image == first_image)  delay_time = start_dwell_multipler*delay;
  if (current_image == last_image)   delay_time =   end_dwell_multipler*delay;
 
  //== call "animate_rev()" again after a set amount of time (delay_time) has elapsed ==
  timeID = setTimeout("animate_rev()", delay_time);
}
 
 
//===> Changes playing speed by adding to or substracting from the delay between frames

function change_speed(dv)
{
  delay+=dv;
  //== check to ensure max and min delay constraints have not been crossed ==
  if(delay > delay_max) delay = delay_max;
  if(delay < delay_min) delay = delay_min;
}
 
//===> functions that changed the dwell rates.

function change_end_dwell(dv) {
  end_dwell_multipler+=dv;
  if ( end_dwell_multipler < 1 ) end_dwell_multipler = 0;
}
 
function change_start_dwell(dv) {
  start_dwell_multipler+=dv;
  if ( start_dwell_multipler < 1 ) start_dwell_multipler = 0;
}
 
//===> Increment to next image

function incrementImage()
{
  var number;
  if (nImages<=1) return;
  stop();
  current_image++;
  number = current_image;

  //== if image is last in loop, increment to first image ==
  if (number > last_image) number = first_image;

  //== check to ensure that image has not been deselected from loop ==
  while (imageNum[number] == false) {
    if (theImages[number].complete) {
        imageNum[number] = true;
        break;
    }
    number++;
   if (number > last_image) number = first_image;
  }
 
  current_image = number;
  display_current_image();
}
 
//===> Decrement to next image

function decrementImage()
{
  var number;
  if (nImages<=1) return;
  stop();
  current_image--;
  number = current_image;
 
  //== if image is first in loop, decrement to last image ==
  if (number < first_image) number = last_image;
 
  //== check to ensure that image has not been deselected from loop ==
  while (imageNum[number] == false) {
    if (theImages[number].complete) {
        imageNum[number] = true;
        break;
    }
    number--;
   if (number < first_image) number = last_image;
  }
 
  current_image = number;
  display_current_image();
}
 
//===> "Play forward"

function fwd()
{
  stop();
  looping = 1;
  play_mode = 1;
  animate_fwd();
}
 
//===> "Play reverse"

function rrev()
{
  stop();
  looping = 1;
  play_mode = 1;
  animate_rev();
}

//===> "play sweep"

function sweep() {
  stop();
  looping = 1;
  play_mode = 2;
  animate_fwd();
}
 
//===> Change play mode (normal, loop, swing)

function change_mode(mode)
{
   play_mode = mode;
}
 
//===> Check selection status of image in animation loop
//function checkImage(status,i)
//{
//  if (status == true)
//    imageNum[i] = false;
//  else imageNum[i] = true;
//}
 
//==> Empty function - used to deal with image buttons rather than HTML buttons
function func()
{
}
 
<!-- Below is the Radar moving cursor Pointer & Origin code.-->
<!-- 2000-07-28 Clive Edington, generalised the code to NN4.-->
<!-- Thanks to Alf West and the Radar Section for the original IE code.-->

  // Usage:
  // (1) Call 'startxy()' once (e.g. from BODY onload="..") to start this code.
  // (2) Assumes that these Image dependent variables have already been defined:
  //
  // GifFileName = "??.gif";
  // Km = nn;	// Standard 64km, 128km, 256km or 512km radar picture.
  // -----------------------------------------------------------------
  //
  // This code assumes a TABLE with a radar image and X, Y outputs, etc,
  // and then does the logic of updating X & Y whenever the mouse moves.

if (nImages>0) {
  // Compute some internal globals.
  var maxKm = Km-1;
  //  128km is .5 KmPerPixel,  512km is 2 KmPerPixel
  var KmPerPixel = Km/256;
 
  // Internal Global variables.
  var xx=0;
  var yy=0;
  var zz=0;
  var aa=0;
  var xKm=0;
  var yKm=0;
  var xKmOrigin=0;
  var yKmOrigin=0;
}

// startxy is called once after the BODY has been loaded.
//	It initalises x-y stuff and sets up mouse-event-callbacks.
//
function startxy ()
{
  // Setup mouse-move & mouse-click(down) callbacks.
  if (isMS) {
    document.body.onmousemove=move;
    document.body.onmousedown=down;
    //document.animation.src = GifFileName;
  }
  if (isNN4) {
    document.captureEvents(Event.MOUSEMOVE);
    document.onMouseMove=move;
    document.captureEvents(Event.MOUSEDOWN);
    document.onMouseDown=down;
    //window.onresize = resize;  // Not needed because the loop will reload it.
    //document.animationlayer.document.animation.src = GifFileName;
  }
  resetOrigin ();
}

// Netscape-only (maybe Xterms only), reload the image after a resize.
function resize () {
      document.animationlayer.document.animation.src = GifFileName;
}

function move(e)	// When the mouse moves, update the pointer boxes.
{
    if (isMS) {
	e = window.event;
        // X-Y relative to the container (i.e. the image).
        xKm=9999
        yKm=9999
        if (e.srcElement && e.srcElement.name) {
          if (e.srcElement.name == "animation") {
	    // 2003-03-11 reduce +2 to +1.
            xPixels = window.event.offsetX-262+1
            yPixels = window.event.offsetY-262+1
            // Mac IE forgets to adjust for the scroll bars.
            if (isMac) {
              xPixels += document.body.scrollLeft
              yPixels += document.body.scrollTop
            }
            xKm= xPixels*KmPerPixel
            yKm=-yPixels*KmPerPixel
          }
        } 
    }
    if (isNN4) {
        xKm=9999
        yKm=9999
        if (e.target.name) {
          if (e.target.name == "animation") {
            xKm= (e.pageX-document.animationlayer.pageX-262+1)*KmPerPixel
            yKm=-(e.pageY-document.animationlayer.pageY-262+1)*KmPerPixel
          }
        } 
    }

    if (Math.abs(xKm)>maxKm || Math.abs(yKm)>maxKm) {
      document.myForm.x.value="" 
      document.myForm.y.value=""
      document.myForm.z.value=""
      document.myForm.a.value=""        
    }
    else {
      xx=xKm-xKmOrigin
      yy=yKm-yKmOrigin
      zz=Math.round(Math.sqrt(xx*xx+yy*yy)-.01)
      aa=450-Math.round(Math.atan2(yy,xx)*57.29)
      if (aa>359) { aa=aa-360 }
      if (zz<1)   { aa="0"    }
      xx=Math.round(xx) 
      yy=Math.round(yy)
      // Turn -0 into +0
      if (xx == 0) { xx=0; }
      if (yy == 0) { yy=0; }
      if (zz == 0) { zz=0; }
      if (xx>=0) document.myForm.x.value = xx + ' km East'
      else       document.myForm.x.value =-xx + ' km West'
      if (yy>=0) document.myForm.y.value = yy + ' km North'
      else       document.myForm.y.value =-yy + ' km South'
      document.myForm.z.value = zz + ' km Away'
      document.myForm.a.value = aa + ' Degrees'        
    }
}

function down()		// A left-click (mouse-down) to move the Origin. 
{
    // xKm & yKm are the distances from the centre of the radar image.
    // They were already computed in (mouse) move() above.
    // (or are zero at startup.)
    
    // Maybe set the new origin.
    if (Math.abs(xKm)<maxKm && Math.abs(yKm)<maxKm) {
        xKmOrigin=xKm
        yKmOrigin=yKm
    }
    if (xKmOrigin>=0) document.offsets.xo.value =  Math.round(xKmOrigin) + ' km East' 
    else              document.offsets.xo.value = -Math.round(xKmOrigin) + ' km West' 
    if (yKmOrigin>=0) document.offsets.yo.value =  Math.round(yKmOrigin) + ' km North' 
    else              document.offsets.yo.value = -Math.round(yKmOrigin) + ' km South' 
}

function resetOrigin () 
{
   xKm = 0;
   yKm = 0;
   down ();
}
 
// Empty function - used to deal with image buttons rather than HTML buttons
function xynullfunc()
{
}

//-->
