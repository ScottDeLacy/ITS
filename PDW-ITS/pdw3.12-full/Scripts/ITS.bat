@ECHO OFF
TITLE Incident Turnout System - By Scott De Lacy

:: Incident Turnout System - PDW Export script. Version 1.2
:: Written by Scott De Lacy - Springvale SES Unit 2014-05-23
:: Copyright (C) 2014, For use with permission only, all rights reserved, not to be modified, repacked or reproduced.
::
:: READ ME:
:: This script will expect you to be outputting PDW arguments EXACTLY as: %1 %3 %2 %4 %5 %6 %7
:: IF you do not configure your filter like this, then your Data export to SQL will be incorrect and fail - But if you want, go ahead and completely change your SQL table or script to accomodate your personal preference. !?

:: This script will do Four things.
:: 1. Declare variables and process data for:
:: 2. [Optional] Text to Speech - To read out the MESSAGE portion of the PDW output to text / OR alternatively read out a smaller - Set message (default)
:: 3. [Optional] Output A pre-formated template with the MESSAGE portion of PDW output to your local printer, Automatically
:: 4. convert the PDW output to a CSV/SQL string and output to SQL Database (for use with application / incident management systems)
:: To use these optional components, uncomment/comment out the relevent sections and change settings as applicable. Follow all instructions carefully. Backup this file before making ANY changes.

:: ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

:: *** 1 VARIABLES ************************************************************************************************************************************************************************************************************
:: Standard String VARIABLES
:: Declares the input arguments as a variable
SET Input=%*
:: Standard Input variables [Breaksdown the input text into variable segments]
SET Capcode=%1
SET Date=%2
SET Time=%3
SET Mode=%4
SET Type=%5
SET Bitrate=%6
:: Declares MSG as the pager msg contents which is all arguments, -50 characters of the CAP code, Date, Time, Mode etc and the MSG type which is the first 2 characters of the pager msg.
SET MSG=%Input:~50%
:: Declares a variable for the Alert type which is @@ = Emergency, Hb = Non-Emergency, QD = Admin Page. This just happens to also be part of the first word of the message which is accessible as argument %7
:: With variable declared you can take that string and apply it for text to speech / printing etc.
SET AlertTypeInput=%7
SET AlertType=%AlertTypeInput:~0,2%
:: Translates AlertType to a word variable as listed in the comment above - You can adjust the wording if so you so wish
IF "%AlertType%"=="@@" SET JobType=EMERGENCY
IF "%AlertType%"=="Hb" SET JobType=Non-Emergency
IF "%AlertType%"=="QD" SET JobType=Administration Page
:: Variable creation for Full job number (used for printing) where the job number position is the first or second word of the page.
SET JobNumberInput=%7
SET JobNumber=%JobNumberInput:~2%
SET JobNumberInput2=%8
IF %JobNumber%==ALERT SET JobNumber=%JobNumberInput2%


:: ************************************************************************************ JOB NUMBER CLEANUP SCRIPT *********************************************************************************************
:: This strips bad characters from the portion of the message which can not be used as a filename /job number. This prevents script crashing if the filename is invalid or message is not decoded properly.
:: This script also provides LIMITED compatability between SES, CFA, Hospital/Ambo pager messages.
:: The script replaces the bad characters with "x" - This character can be changed to suit requirement as per the following
:: Changing the string [ set "str1=!str1:%%a=x!" ] repalcing the x with the desired character or Variable, eg
:: Change character to Z would be [ set str1=!str1:%%a=Z!" ]
:: Change Character to auto-generated random [ set "str1=!str1:%%a=%RANDOM%!" ]

:: START OF JOB NUMBER TEXT CLEANUP SCRIPT

call :purge "%JobNumber%" JobNumber
echo (%JobNumber%)
goto :eof

:purge StrJobNumber  [RtnJobNumber]
setlocal disableDelayedExpansion
set "str1=%~1"
setlocal enableDelayedExpansion
echo Processing element "!str1!"...

for %%a in (- ! @ # $ % ^^ ^& + \ / ^< ^> . ' [ ] { } ` ^| ^") do (
   set "str1=!str1:%%a=x!"
 )

 set "temp_str=" 
 for %%e in (%str1%) do (
  set "temp_str=!temp_str!%%e"
 )
endlocal & set "str1=%temp_str%"
setlocal disableDelayedExpansion
set "str1=%str1::=x%"
set "str1=%str1:^^~=x%"
set "str1=%str1:@@=Z%"
for /f "tokens=* delims=~" %%w in ("%str1%") do set "str1=%%w"
endlocal & set "str1=%str1%"
endlocal &  if "%~2" neq "" (set %~2=%str1%) else echo %str1%
::goto :eof
::goto :eof comment out otherwise it will repeat the script and move to the end of the batch file skipping the rest of the code.

:: END OF JOB NUMBER TEXT CLEANUP SCRIPT
:: ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
:: ***** TEXT TO SPEECH VARIABLES ***************************************************************************************************************************************************
:: Phonetic identification of F = Foxtrot, S = Sierra, E = Emergency (Medical)
IF "%JobNumber:~0,1%"=="S" SET PhonType=C-Erra
IF "%JobNumber:~0,1%"=="F" SET PhonType=Fox-Trot
IF "%JobNumber:~0,1%"=="A" SET PhonType=Alert
IF "%JobNumber:~0,1%"=="E" SET PhonType=Medical or Life Threatening Emergency
:: Variable for Sierra Job number, without the S and only the last 4 digets - used for text to speech
SET ShortSierraNumber=%PhonType%, %JobNumberInput:~-4,1%; %JobNumberInput:~-3,1%; %JobNumberInput:~-2,1%; %JobNumberInput:~-1,1%;

:: *** SQL / CSV DATA EXPORT VARIABLES **************************************************************************************************************************************************
:: Creates a standard CSV compliant String and SQL export string, which also interprets the Australian date format and reformats the date to YYYY-MM-DD for SQL servers
:: Note - SQLEXPORT string REQUIRES double-Double quotes! VBS engine sees ' or " as end of script, which forms part of the text of pager messages and strings - Double quotes are safer. 
:: Note: The output to MySQL will be single double quotes.

SET CSVEXPORT='%Capcode%','%Date%','%Time%','%Mode%','%Type%','%Bitrate%','%JobType%','%MSG%'%
SET SQLEXPORT="""",""%Capcode%"",STR_TO_DATE(""%Date%"",""%%d-%%m-%%Y""),""%Time%"",""%Mode%"",""%Type%"",""%Bitrate%"",""%JobType%"",""%MSG%""
:: Variable for temporary SQL VBS Script filename
:: --------------*
SET SQLINSERT=SQLINSERT%JobNumber%.VBS
:: --------------*
::
:: ***** Print file output filename *****
:: To enable a temporary filename comment out SET PrintOutputFile="%JobNumber%.txt" and remove the comment for SET PrintOutputFile=PagePrint%RANDOM%.txt
:: Temporary Printer text file
:: *******************************************|
::SET PrintOutputFile=PagePrint%RANDOM%.txt
SET PrintOutputFile="%JobNumber%.txt"
:: *******************************************|
::
:: **** TEXT TO SPEECH VARIABLES *********************************************************************************************************************************************************
:: Text to Speech VBS Script Filename
SET TextToSpeechFile="TextToSpeech%JobNumber%.vbs"
:: Text to Speech Words, Phrases [ Can be customised ]
:: Option 1. Read out the message in FULL - which may mean you have more than one msg being read at a time if multiple messages come in
:: Option 2. Read out short messages which are configureable below.
:: Usage: Set "SpeakFullMessage=1" to turn on, or =0 to turn off.
:: ***********************************|
SET SpeakFullMessage=0
:: ***********************************|
IF %SpeakFullMessage%==1 GOTO FULLMSG
IF "%JobType%"=="EMERGENCY" SET TextToSpeech=ALERT, ALERT, ALERT. Emergency %ShortSierraNumber%. Immediate acknowlegement required.
IF "%JobType%"=="Non-Emergency" SET TextToSpeech=New Job. %ShortSierraNumber%. Acknowlegement required.
IF "%JobType%"=="Administration Page" SET TextToSpeech=New Administration message recieved
GOTO TextToSpeech
:FULLMSG
SET TextToSpeech=%MSG%

:: *** FUNCTIONS ***************************************************************************************************************************************************************************
::
:: *** 2. TEXT TO SPEECH *******************************************************************************************************************************************************************
:: This Function reads out either a pre-defined set of messages based on the JobType (See the previous variable section) OR the entire message from TEXT TO SPEECH.
:: The default script creates a VBS Script dynamically and runs using the INBUILT windows text to speech engine.
:: Changes to the voice used, including speed, accent etc are from the system voice settings from control panel (Win7/vista). 
:: Otherwise, alternatively you can use the included SayStatic.exe application instead. (its Ordinary). In order to do this you will need to remove or comment out the VBS script, or insert a GOTO to bypass this script.
::*******************************************|
:: start "SayStatic.exe" %TextToSpeech%
::*******************************************|

:TextToSpeech
 ECHO ' > "%TextToSpeechFile%"
 ECHO set speech = Wscript.CreateObject("SAPI.spVoice") >> "%TextToSpeechFile%"
ECHO speech.WaitUntilDone(60000) >> "%TextToSpeechFile%"
ECHO speech.speak "%TextToSpeech%" >> "%TextToSpeechFile%"
cscript %TextToSpeechFile% 

del %TextToSpeechFile%

:: *** 3. PRINTING PAGE OUTPUT [ Optional ] *************************************************************************************************************************************************
:: This script first creates a text file using the %JobNumber% variable ((eg the Sierra number for SES, FoxTrot for CFA etc where it is the first word))
:: as the document name. This will also be used by notepad as the document title.
:: If required you can use a random generated name (declared in variables as PrintOutputFile).
:: This script will Export the layout below with the page details to the file, then print that text file, then delete it.
:: NOTE: THIS LAYOUT CAN BE CUSTOMISED
:: You may include any text before or after a message, such as special instructions or unit SOPs for example and you can include any of the declared variables, eg Date and Time as shown below.

:: IF REQUIRED; you could instead create a HTML formated file, with Rich-Text styling, pictures & logos etc - but be mindful that this would create a lot more code, and will limit your ability to print to ANY printer, 
:: using standard raw/text printing drivers, whichis universaly supported.
:: 
:: To disable this function, change [ SET EnablePrinting=1 ] below to [ SET EnablePrinting=0 ] see below the after the message template.
:: NOTE: there is no need comment out the text file code as it is created/deleted as part of the script.

:: BEGIN PREFORMATED PRINT LAYOUT

ECHO. >> %PrintOutputFile%
ECHO ************************************************* >> %PrintOutputFile%
ECHO Job Number:          %JobNumber% >> %PrintOutputFile%
ECHO Job Type/Urgency:    %JobType% >> %PrintOutputFile%
ECHO Dispatch Alert Date: %Date% >> %PrintOutputFile%
ECHO Dispatch Alert Time: %Time% >> %PrintOutputFile%
ECHO ************************************************* >> %PrintOutputFile%
ECHO ------------------------------------------------- >> %PrintOutputFile%
ECHO. >> %PrintOutputFile%
ECHO. >> %PrintOutputFile%
ECHO ------------------------------------------------- >> %PrintOutputFile%
ECHO Pager Message: >> %PrintOutputFile%
ECHO ------------------------------------------------- >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO %MSG%  >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO ------------------------------------------------- >> %PrintOutputFile%
ECHO Job Notes: >> %PrintOutputFile%
ECHO ------------------------------------------------- >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO Arrival Time:   _________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO Job Clear time: _________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO Distance KM's:  _________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO Equipment Left: _________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO _________________________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO _________________________________________________ >> %PrintOutputFile%
ECHO.  >> %PrintOutputFile%
ECHO Notes/Other: >> %PrintOutputFile%

:: End of Print Layout

::TEST

::GOTO SKIPPRINT

:: *************************************|
:: DISABLE PRINTING
:: Print command - [ SET EnablePrinting=0 ] If you do not want to use this function.
SET EnablePrinting=1
:: *************************************|
IF %EnablePrinting%==0 GOTO SKIPPRINT

:Print
IF Exist %PrintOutputFile%  (
REM print command
REM Use - [ /p ]flag to print to default system printer (which can be network/local) 
REM or use[ /PT <filename> <printername> <driverdll> <port> ] to print file to a designated printer
REM Note: Loop required, due to a delay in VBS voice over script completing and responding back to cmd.com, the text file takes a few milliseconds to 'create' the text file and it needs to exist so it can be printed and deleted.
REM ************************************************|
notepad /p %PrintOutputFile%
REM ************************************************|
) Else (
GOTO :Print
)
ECHO Printing

:: Delete Text file - Removes the PrintOutputFile, comment this command if you want to keep printer output files as logs.
Del %PrintOutputFile% /F
ECHO Print File removed.

:SKIPPRINT

:: *** 4. SQL / CSV Output ****************************************************************************************************************************************************************************************
:: WARNING: Alteration of this script requires knowledge of your database!!!
:: 
:: Three options here.
:: #1 Output to a .SQL/CSV file and use your own import (eg those whom use MSSQL/Access). NOTE: Date output will be in DD-MM-YY.
:: #2 The recommended, script that exports to an MySQL database. Note: Date is converted to MM-DD-YY (American) format for SQL Server (requirement).
:: #3 Both #1 and #2 combined
:: Note - using this script requires you instal the correct ODBC MySQL driver mysql-connector-odbc-5.3.2-winx64 (or x86) (or you can try use the microsoft one... but just dont) and your server has to allow connections from the PDW servers IP or from any local IP for that user.

:: Enable the SQL and CSV Outputs here
:: To enable the SQL Connection set the value to =1
:: *************************************************|
SET EnableSQL=1
:: *************************************************|
IF %EnableSQL%==0 GOTO SKIPSQL
:: To Enable CSV, set the value to =1
:: Set the desired filename for CSV as file.csv
:: *************************************************|
SET EnableCSV=1
SET CSVFilename=PagerLog.CSV
:: *************************************************|



:: ************* SQL SERVER CONFIGURATION (VBS SCRIPT) ********************|
:: Set your SQL Server details, Server, Database, SQL User, SQL Password. Values are case sensitive. DO NOT USE QUOTES!
:: Your SQL Server Hostname or IP Address
SET SQLSERVER=10.1.1.14
:: Your SQL Database Name
SET SQLDATABASE=testITS
:: Your SQL Username
SET SQLUSER=root
:: Your SQL User Password
SET SQLPASS=Password
:: ************************************************************************|

:: Start of VBS Script

ECHO  set objConn = CreateObject("ADODB.Connection") >> %SQLINSERT%
::ECHO  objConn.Open "Driver={MySQL ODBC 5.3 ANSI Driver};Server=10.1.1.14;Database=testITS;UID=root;PWD=Password;" >> %SQLINSERT%
ECHO  objConn.Open "Driver={MySQL ODBC 5.3 ANSI Driver};Server=%SQLSERVER%;Database=%SQLDATABASE%;UID=%SQLUSER%;PWD=%SQLPASS%;" >> %SQLINSERT%
ECHO  set objRS = CreateObject("ADODB.Recordset") >> %SQLINSERT%
ECHO  objRS.Open "pages", objConn >> %SQLINSERT%
ECHO. >> %SQLINSERT%
ECHO       commandText = "INSERT INTO pages(PageID, address, date, time, mode, type, bitrate, alerttype, message) VALUES(%SQLEXPORT%)" >> %SQLINSERT%
ECHO. >> %SQLINSERT%
ECHO       objConn.Execute commandText >> %SQLINSERT%
ECHO		set objRS = Nothing >> %SQLINSERT%
ECHO		objConn.Close >> %SQLINSERT%
ECHO		set objConn = Nothing >> %SQLINSERT%

:: End of VBS script
:: Run the VBS Script

cscript %SQLINSERT%

:: Delete VBS script
:SQLIMPORT
IF Exist %SQLINSERT%  (
::SQLIMPORT DELETE SCRIPT
del %SQLINSERT%
) Else (
GOTO SQLIMPORT
)
ECHO Deleting SQL import script


ECHO SQL export ran.
:SKIPSQL

IF %EnableCSV%==1 ECHO %CSVEXPORT% >> %CSVFilename%

ECHO Exiting