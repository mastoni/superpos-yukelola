; Script Inno Setup untuk SuperPOS Windows Installer
; Mengompilasi aplikasi SuperPOS menjadi file installer mandiri .exe

#define MyAppName "SuperPOS"
#define MyAppVersion "1.1.4"
#define MyAppPublisher "Yukelola"
#define MyAppURL "https://superpos-yukelola.test"
#define MyAppExeName "SuperPOS.vbs"

[Setup]
; Informasi dasar aplikasi
AppId={{D86A34B1-2E97-4F60-B147-386B0E02D58E}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
AppSupportURL={#MyAppURL}
AppUpdatesURL={#MyAppURL}
DefaultDirName={autopf}\{#MyAppName}
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
; Privasi instalasi
PrivilegesRequired=lowest
PrivilegesRequiredOverridesAllowed=dialog
OutputDir=output
OutputBaseFilename=SuperPOS_Setup_v{#MyAppVersion}
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
UninstallDisplayIcon={app}\build-windows\superpos.ico
ArchitecturesInstallIn64BitMode=x64compatible

[Languages]
Name: "indonesian"; MessagesFile: "compiler:Languages\Indonesian.isl"
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked

[Files]
; Berkas utama aplikasi SuperPOS
Source: "..\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: ".git\*,.github\*,node_modules\*,tests\*,storage\logs\*.log,build-windows\output\*"
; Konfigurasi environment siap pakai jika belum ada .env
Source: ".env.desktop"; DestDir: "{app}"; DestName: ".env"; Flags: onlyifdoesntexist

[Icons]
Name: "{group}\{#MyAppName}"; Filename: "{sys}\wscript.exe"; Parameters: """{app}\build-windows\superpos-launcher.vbs"""; WorkingDir: "{app}"; IconFilename: "{app}\public\favicon.ico"
Name: "{group}\Tutup {#MyAppName}"; Filename: "{app}\build-windows\stop-superpos.bat"; WorkingDir: "{app}"
Name: "{group}\{cm:UninstallProgram,{#MyAppName}}"; Filename: "{uninstallexe}"
Name: "{autodesktop}\{#MyAppName}"; Filename: "{sys}\wscript.exe"; Parameters: """{app}\build-windows\superpos-launcher.vbs"""; WorkingDir: "{app}"; IconFilename: "{app}\public\favicon.ico"; Tasks: desktopicon

[Run]
Filename: "{sys}\wscript.exe"; Parameters: """{app}\build-windows\superpos-launcher.vbs"""; Description: "{cm:LaunchProgram,{#StringChange(MyAppName, '&', '&&')}}"; Flags: nowait postinstall skipifsilent

[UninstallRun]
Filename: "{app}\build-windows\stop-superpos.bat"; Flags: runhidden
