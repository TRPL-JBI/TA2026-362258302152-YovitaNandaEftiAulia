# ============================================================
# PERBAIKAN PEMANGGILAN USER PADA SELURUH FILE BLADE
# ============================================================
#
# Script ini akan:
#
# 1. Membuat backup seluruh file Blade.
# 2. Mengganti session('user') menjadi $authUser.
# 3. Mengganti session lama seperti role, nama, dan email.
# 4. Menampilkan file yang berhasil diperbarui.
#
# Jalankan dari folder utama Laravel.
# ============================================================

$projectRoot = Get-Location

$viewsPath = Join-Path `
    $projectRoot `
    "resources\views"

$backupName = "backup-blade-auth-" + `
    (Get-Date -Format "yyyyMMdd-HHmmss")

$backupPath = Join-Path `
    $projectRoot `
    "storage\app\$backupName"

# ============================================================
# PERIKSA FOLDER VIEWS
# ============================================================

if (-not (Test-Path $viewsPath)) {
    Write-Host ""
    Write-Host "Folder resources\views tidak ditemukan." `
        -ForegroundColor Red

    Write-Host "Jalankan script dari folder utama proyek Laravel." `
        -ForegroundColor Yellow

    exit 1
}

# ============================================================
# BUAT FOLDER BACKUP
# ============================================================

New-Item `
    -ItemType Directory `
    -Path $backupPath `
    -Force | Out-Null

Write-Host ""
Write-Host "==================================================" `
    -ForegroundColor Cyan

Write-Host "PERBAIKAN SESSION USER PADA FILE BLADE" `
    -ForegroundColor Cyan

Write-Host "==================================================" `
    -ForegroundColor Cyan

Write-Host ""
Write-Host "Folder Views  : $viewsPath"
Write-Host "Folder Backup : $backupPath"
Write-Host ""

# ============================================================
# AMBIL SEMUA FILE BLADE
# ============================================================

$bladeFiles = Get-ChildItem `
    -Path $viewsPath `
    -Recurse `
    -Filter "*.blade.php" `
    -File

if ($bladeFiles.Count -eq 0) {
    Write-Host "Tidak ditemukan file Blade." `
        -ForegroundColor Yellow

    exit 0
}

$updatedCount = 0
$unchangedCount = 0

# ============================================================
# PROSES SETIAP FILE
# ============================================================

foreach ($file in $bladeFiles) {

    $relativePath = $file.FullName.Substring(
        $viewsPath.Length
    ).TrimStart(
        [System.IO.Path]::DirectorySeparatorChar
    )

    $backupFile = Join-Path `
        $backupPath `
        $relativePath

    $backupDirectory = Split-Path `
        $backupFile `
        -Parent

    New-Item `
        -ItemType Directory `
        -Path $backupDirectory `
        -Force | Out-Null

    Copy-Item `
        -Path $file.FullName `
        -Destination $backupFile `
        -Force

    $originalContent = Get-Content `
        -Path $file.FullName `
        -Raw

    $newContent = $originalContent

    # ========================================================
    # GANTI OBJECT USER DARI SESSION
    # ========================================================

    $newContent = $newContent.Replace(
        "session('user')",
        '$authUser'
    )

    $newContent = $newContent.Replace(
        'session("user")',
        '$authUser'
    )

    # ========================================================
    # GANTI DATA SESSION LAMA
    # ========================================================

    $newContent = $newContent.Replace(
        "session('role')",
        '($authUser->role ?? null)'
    )

    $newContent = $newContent.Replace(
        'session("role")',
        '($authUser->role ?? null)'
    )

    $newContent = $newContent.Replace(
        "session('nama')",
        '($authUser->nama ?? null)'
    )

    $newContent = $newContent.Replace(
        'session("nama")',
        '($authUser->nama ?? null)'
    )

    $newContent = $newContent.Replace(
        "session('email')",
        '($authUser->email ?? null)'
    )

    $newContent = $newContent.Replace(
        'session("email")',
        '($authUser->email ?? null)'
    )

    $newContent = $newContent.Replace(
        "session('id_unit_kerja')",
        '($authUser->id_unit_kerja ?? null)'
    )

    $newContent = $newContent.Replace(
        'session("id_unit_kerja")',
        '($authUser->id_unit_kerja ?? null)'
    )

    # ========================================================
    # SIMPAN JIKA TERDAPAT PERUBAHAN
    # ========================================================

    if ($newContent -ne $originalContent) {

        Set-Content `
            -Path $file.FullName `
            -Value $newContent `
            -Encoding UTF8 `
            -NoNewline

        Write-Host "[DIUBAH] $relativePath" `
            -ForegroundColor Green

        $updatedCount++

    } else {

        $unchangedCount++

    }
}

# ============================================================
# HASIL
# ============================================================

Write-Host ""
Write-Host "==================================================" `
    -ForegroundColor Cyan

Write-Host "PROSES SELESAI" `
    -ForegroundColor Cyan

Write-Host "==================================================" `
    -ForegroundColor Cyan

Write-Host ""
Write-Host "File diperbarui : $updatedCount" `
    -ForegroundColor Green

Write-Host "Tidak berubah   : $unchangedCount"
Write-Host "Backup tersedia : $backupPath"

Write-Host ""
Write-Host "Lanjutkan dengan menjalankan:" `
    -ForegroundColor Yellow

Write-Host "php artisan optimize:clear" `
    -ForegroundColor White

Write-Host ""