Incident Turnout System 
- By Scott De Lacy
- scott.delacy@members.ses.vic.gov.au
- State Emergency Service Victoria, Springvale unit
- Copyright 2014

This script is useable with permission by the copyright owner.
It is not to be distributed, altered or sold without express written consent.

This script is a single component of a overall Incident Turnout System.
This component can be used in conjunction with additional components or on its own.

This script is designed to take your filtered decoded pager messages from PDW (Free GPL open software), http://www.discriminator.nl/pdw/index-en.html
And process the pager message in the following way

1. Extracting useful information from the page msg such as Date, Time, and message contents
2. Reading the page message or pre defined message from text to speech
3. Print out the pager message using the pre-build template or a template of your own. (Plain text or HTML if required)
4. Output the page message to an SQL Server, and/OR a CSV file

This script has been designed for use with SES pager messages from ESTA Dispatch, however has been designed to also be compatable with CFA and Ambulence pages.
This script should also work for other pager messages, however no testing has been done and as such, no claims are made.

This script may also be compatible with other pager software, provided that the output string sent to the script matches PDW, or the script variables are adjusted accordingly.


INSTALLATION:

Requirements
This script requires an standard user account to run, with permission to create and execute VBS scripts.
PDW decoding requires at least a dual core machine. This script and PDW was tested on an i7.

Software required:
1. PDW - install by downloading PDW from http://www.discriminator.nl/pdw/index-en.html or with the script, extract and run the program folder
2. ITS.bat (script)
3. saystatic.exe (Optional text to speech program if required
4. Mysql-ODBC Connector 5.3 (included with script)

ITS Scripts located in \Scripts\ folder.

Configuration
1. Review the script contents. Additional instructions and options are embedded inside the script. Configure and Turn ON/OFF printing, SQL and CSV output and text to voice from within the script.
2. Open PDW and set view options to desired.
3. Create filters based on CAP CODE or TEXT required. Note you can also create rejection filters. Refer to the inbuild PDW manual.
4. Configure the PDW filter command output to "ITS.BAT" with full path, set the output arguments to %1 %3 %2 %4 %5 %6 %7
5. Configure your audio input to your Pager descriminator (Refer to PDW Manual and see below)
6. Set pager mode to POCSAGS/FLEX


PAGER DESCRIMINATORS:

You have two viable options.

1. Pager descriminator / Scanner / radio reciever hardware
Connect your device to your line in on your audio card and configure PDW for this audio input

2. SDR USB Radio
Purchase an software defined radio (USB) and install. (Google instructions on how to do this using ZADIG).
Use an SDR compatable software such as SDR# (SDR Sharp) (the best), or HDSDR or any other free software.

Using an SDR radio requires that the audio output of the program be pipped into PDW as normal. You may do this via physical cable or via an Software Audio Cable.
Try http://www.vb-audio.com for a free one.


FREQUENCIES:
Find the frequencies that you require.
ESTA / SES / CFA use 148.912mhz, narrow band, wide bandwidth.
A Baofeng VHF/UHF Radio will recieve this station on 148.910 on 2.5k stepping.


TROUBLESHOOTING:

Text to Speech:
1. Included is a Test - Voice.bat file. Run this in a command prompt window.
It will prompt you to type text, type in some text and press enter. 
If you are unable to run the script due to an error, your VBS installation is incorrect. Check your windows updates.
If you hear no sound, check your speakers / Audio output, and Control panel text to speech default output. It may not be your speakers.

Script fails / Errors
1. Did you alter the script? 
YES: Try the original copy, if it works - you broke it.
NO: Try to identify the error / Rule out VBS or your configuration as the cause.
Email Scott.Delacy@members.ses.vic.gov.au with full details of your error, including output logs. Limited support available.

2. Are files being output correctly?
NO: Most errors occur due to variables capturing bad/incompatible characters for filenames. Try setting forced %RANDOM% Variable for filename creation and test again.
Yes: Something else has gone wrong. Limited support available.


END