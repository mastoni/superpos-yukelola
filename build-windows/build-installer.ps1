# ==============================================================================
# Script Pembuatan Installer / Paket Windows SuperPOS
# ==============================================================================

$ErrorActionPreference = "Stop"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$ProjectRoot = (Resolve-Path "$ScriptDir\..").Path
$OutputDir = "$ScriptDir\output"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "         BUILDER WINDOWS INSTALLER & PORTABLE            " -ForegroundColor Cyan
Write-Host "                      SuperPOS                            " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

Set-Location $ProjectRoot

# 1. Pastikan folder output ada
if (-not (Test-Path $OutputDir)) {
    New-Item -ItemType Directory -Path $OutputDir -Force | Out-Null
}

# 2. Compile frontend assets dengan Vite
Write-Host "`n[1/5] Mengompilasi frontend Vite assets..." -ForegroundColor Yellow
npm run build
Write-Host "Frontend berhasil dikompilasi." -ForegroundColor Green

# 3. Setup database SQLite template
Write-Host "`n[2/5] Menyiapkan database SQLite template..." -ForegroundColor Yellow
$sqlitePath = "$ProjectRoot\database\database.sqlite"
if (-not (Test-Path $sqlitePath)) {
    New-Item -ItemType File -Path $sqlitePath -Force | Out-Null
    php artisan migrate --force --no-interaction
}
Write-Host "Database siap." -ForegroundColor Green

# 4. Generate Production APP_KEY jika diperlukan
Write-Host "`n[3/5] Mengoptimalkan cache Laravel..." -ForegroundColor Yellow
php artisan optimize:clear
php artisan view:cache
Write-Host "Cache Laravel teroptimasi." -ForegroundColor Green

# 5. Cek apakah Inno Setup Compiler (ISCC.exe) tersedia
Write-Host "`n[4/5] Memeriksa Inno Setup Compiler..." -ForegroundColor Yellow
$isccLocations = @(
    "ISCC.exe",
    "${env:ProgramFiles(x86)}\Inno Setup 6\ISCC.exe",
    "${env:ProgramFiles}\Inno Setup 6\ISCC.exe",
    "${env:LOCALAPPDATA}\Programs\Inno Setup 6\ISCC.exe"
)

$isccPath = $null
foreach ($loc in $isccLocations) {
    if (Get-Command $loc -ErrorAction SilentlyContinue) {
        $isccPath = (Get-Command $loc).Source
        break
    } elseif (Test-Path $loc) {
        $isccPath = $loc
        break
    }
}

if ($isccPath) {
    Write-Host "Inno Setup Compiler ditemukan: $isccPath" -ForegroundColor Green
    Write-Host "`n[5/5] Mengompilasi Installer Setup .exe..." -ForegroundColor Yellow
    
    $issFile = "$ScriptDir\superpos-installer.iss"
    & "$isccPath" "$issFile"
    
    Write-Host "`n==========================================================" -ForegroundColor Green
    Write-Host " SUKSES! Installer Windows telah berhasil dibuat:" -ForegroundColor Green
    Write-Host " Lokasi: $OutputDir\SuperPOS_Setup_v1.1.4.exe" -ForegroundColor Cyan
    Write-Host "==========================================================" -ForegroundColor Green
} else {
    Write-Host "Inno Setup Compiler tidak terdeteksi di sistem." -ForegroundColor DarkYellow
    Write-Host "Membuat paket Standalone Portable (.zip) sebagai alternatif langsung..." -ForegroundColor Yellow
    
    $zipOutput = "$OutputDir\SuperPOS_Windows_Portable_v1.1.4.zip"
    if (Test-Path $zipOutput) { Remove-Item $zipOutput -Force }

    # Salin template env jika belum ada .env di root
    if (-not (Test-Path "$ProjectRoot\.env")) {
        Copy-Item "$ScriptDir\.env.desktop" "$ProjectRoot\.env"
    }

    Write-Host "Mengepak berkas aplikasi ke: $zipOutput" -ForegroundColor Cyan
    $excludeItems = @(
        ".git", ".github", "node_modules", "tests", "storage\logs\*", "build-windows\output"
    )

    # Buat direktori staging sementara
    $stagingDir = "$OutputDir\SuperPOS_Portable"
    if (Test-Path $stagingDir) { Remove-Item $stagingDir -Recurse -Force }
    New-Item -ItemType Directory -Path $stagingDir -Force | Out-Null

    # Copy files
    Get-ChildItem -Path $ProjectRoot -Exclude ".git", "node_modules", "tests" | ForEach-Object {
        Copy-Item -Path $_.FullName -Destination $stagingDir -Recurse -Force
    }

    Compress-Archive -Path "$stagingDir\*" -DestinationPath $zipOutput -Force
    Remove-Item $stagingDir -Recurse -Force

    Write-Host "`n==========================================================" -ForegroundColor Green
    Write-Host " SUKSES! Paket Portable Windows telah dibuat:" -ForegroundColor Green
    Write-Host " Lokasi: $zipOutput" -ForegroundColor Cyan
    Write-Host ""
    Write-Host " *Catatan*: Untuk membuat file .exe installer resmi:" -ForegroundColor Yellow
    Write-Host " Unduh Inno Setup gratis di: https://jrsoftware.org/isdl.php" -ForegroundColor Yellow
    Write-Host " Setelah terpasang, jalankan kembali script ini untuk membuat Setup.exe." -ForegroundColor Yellow
    Write-Host "==========================================================" -ForegroundColor Green
}
