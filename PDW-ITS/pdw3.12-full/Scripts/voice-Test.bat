rem The user decides what to convert here
 :input
 cls
 echo Type in what you want the computer to say and then press the enter key.
 echo.
 set /p text=

 rem Making the temp file
 :num
 set num=%random%
 if exist temp%num%.vbs goto num
 echo ' > "temp%num%.vbs"
 echo set speech = Wscript.CreateObject("SAPI.spVoice") >> "temp%num%.vbs"
 echo speech.speak "%text%" >> "temp%num%.vbs"
 start temp%num%.vbs
 pause
 del temp%num%.vbs
 goto input



pause