@echo off
setlocal enabledelayedexpansion
title YUKelola Launcher

:: Set current directory to application root
cd /d "%~dp0.."
set "APP_DIR=%CD%"
set "PORT=8765"
set "APP_URL=http://127.0.0.1:%PORT%"

:: Detect PHP runtime (prefer bundled php, fallback to system PATH)
if exist "%APP_DIR%\php\php.exe" (
    set "PHP_BIN=%APP_DIR%\php\php.exe"
) else (
    set "PHP_BIN=php"
)

:: Ensure storage and database exist
if not exist "%APP_DIR%\storage\logs" mkdir "%APP_DIR%\storage\logs"
if not exist "%APP_DIR%\storage\framework\cache" mkdir "%APP_DIR%\storage\framework\cache"
if not exist "%APP_DIR%\storage\framework\sessions" mkdir "%APP_DIR%\storage\framework\sessions"
if not exist "%APP_DIR%\storage\framework\views" mkdir "%APP_DIR%\storage\framework\views"

if not exist "%APP_DIR%\database\database.sqlite" (
    type nul > "%APP_DIR%\database\database.sqlite"
    "%PHP_BIN%" artisan migrate --force --seed --no-interaction > "%APP_DIR%\storage\logs\setup.log" 2>&1
)

:: Kill any lingering PHP server on this port
for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":%PORT% "') do (
    taskkill /F /PID %%a >nul 2>&1
)

:: Start Laravel PHP Built-in Server in background
start /B "" "%PHP_BIN%" artisan serve --host=127.0.0.1 --port=%PORT% --no-reload >nul 2>&1

:: Wait for server to start
timeout /t 2 /nobreak >nul

:: Detect Edge or Chrome to run in clean standalone app window (no address bar)
set "BROWSER="
if exist "%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER=%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER=%ProgramFiles%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER=%ProgramFiles%\Google\Chrome\Application\chrome.exe"
) else if exist "%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER=%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"
)

if defined BROWSER (
    "%BROWSER%" --app=%APP_URL% --name="YUKelola" --user-data-dir="%APP_DIR%\storage\browser-data"
) else (
    start "" "%APP_URL%"
)

:: When browser window is closed, clean up PHP server process
for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":%PORT% "') do (
    taskkill /F /PID %%a >nul 2>&1
)

exit /b 0
