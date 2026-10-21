@echo off
set "PORT=8765"
echo Menghentikan layanan SuperPOS...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":%PORT% "') do (
    taskkill /F /PID %%a >nul 2>&1
)
echo SuperPOS telah dinonaktifkan.
timeout /t 1 /nobreak >nul
