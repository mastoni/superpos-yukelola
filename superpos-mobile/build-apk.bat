@echo off
echo ========================================================
echo       SUPERPOS MOBILE - BUILD APK ANDROID RELEASE
echo ========================================================
echo.
cd /d %~dp0

echo [1/3] Memeriksa dependensi Flutter...
call flutter pub get

echo.
echo [2/3] Memulai proses kompilasi APK (Release)...
call flutter build apk --release

echo.
if exist "build\app\outputs\flutter-apk\app-release.apk" (
    echo ========================================================
    echo  SUKSES! File APK telah berhasil dibuat di:
    echo  %~dp0build\app\outputs\flutter-apk\app-release.apk
    echo ========================================================
    echo.
    explorer /select,"%~dp0build\app\outputs\flutter-apk\app-release.apk"
) else (
    echo [GALAT] Kompilasi APK belum selesai atau terjadi error.
)
pause
