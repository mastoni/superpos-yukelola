Set WshShell = CreateObject("WScript.Shell")
Set FSO = CreateObject("Scripting.FileSystemObject")

' Get current script folder
strScriptDir = FSO.GetParentFolderName(WScript.ScriptFullName)
strBatPath = strScriptDir & "\start-superpos.bat"

' Run start-superpos.bat with window hidden (0 = vbHide)
WshShell.Run Chr(34) & strBatPath & Chr(34), 0, False
