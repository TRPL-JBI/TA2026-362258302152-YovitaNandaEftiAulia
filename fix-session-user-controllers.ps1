# ============================================================
# PERBAIKAN SESSION USER PADA SELURUH CONTROLLER
# ============================================================
#
# Script ini akan:
# 1. Membuat backup controller.
# 2. Mengganti $user = session('user').
# 3. Mengganti $sessionUser = session('user').
# 4. Mengambil ulang user dari database berdasarkan user_id.
# 5. Mengganti penyebutan session('user') pada komentar.
#
# Jalankan dari folder utama Laravel yang berisi file artisan.
# ============================================================

$projectRoot = Get-Location

$controllersPath = Join-Path `
    $projectRoot `
    "app\Http\Controllers"

$backupName = "backup-controller-session-" + `
    (Get-Date -Format "yyyyMMdd-HHmmss")

$backupPath = Join-Path `
    $projectRoot `
    "storage\app\$backupName"

# ============================================================
# PERIKSA FOLDER CONTROLLER
# ============================================================

if (-not (Test-Path $controllersPath)) {
    Write-Host ""
    Write-Host "Folder app\Http\Controllers tidak ditemukan." `
        -ForegroundColor Red

    Write-Host "Jalankan script dari folder utama Laravel." `
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

Write-Host "PERBAIKAN SESSION USER PADA CONTROLLER" `
    -ForegroundColor Cyan

Write-Host "==================================================" `
    -ForegroundColor Cyan

Write-Host ""
Write-Host "Folder Controller : $controllersPath"
Write-Host "Folder Backup     : $backupPath"
Write-Host ""

# ============================================================
# AMBIL SEMUA FILE CONTROLLER
# ============================================================

$controllerFiles = Get-ChildItem `
    -Path $controllersPath `
    -Recurse `
    -Filter "*.php" `
    -File

if ($controllerFiles.Count -eq 0) {
    Write-Host "Tidak ditemukan file controller." `
        -ForegroundColor Yellow

    exit 0
}

$updatedCount = 0
$unchangedCount = 0

$utf8NoBom = New-Object `
    System.Text.UTF8Encoding($false)

# ============================================================
# PROSES SETIAP CONTROLLER
# ============================================================

foreach ($file in $controllerFiles) {

    $relativePath = $file.FullName.Substring(
        $controllersPath.Length
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

    $originalContent = [System.IO.File]::ReadAllText(
        $file.FullName
    )

    $newContent = $originalContent

    # ========================================================
    # GANTI $user = session('user');
    # ========================================================

    $patternUserSingle = `
        "(?m)^([ \t]*)\`$user\s*=\s*session\('user'\);\s*$"

    $replacementUser = @'
${1}$user = request()->attributes->get('auth_user')
${1}    ?? \App\Models\User::find(session('user_id'));

${1}abort_unless(
${1}    $user && $user->status === 'aktif',
${1}    403,
${1}    'Akun tidak ditemukan atau sudah dinonaktifkan.'
${1});
'@

    $newContent = [regex]::Replace(
        $newContent,
        $patternUserSingle,
        $replacementUser
    )

    # ========================================================
    # GANTI $user = session("user");
    # ========================================================

    $patternUserDouble = `
        '(?m)^([ \t]*)\$user\s*=\s*session\("user"\);\s*$'

    $newContent = [regex]::Replace(
        $newContent,
        $patternUserDouble,
        $replacementUser
    )

    # ========================================================
    # GANTI $sessionUser = session('user');
    # ========================================================

    $patternSessionUserSingle = `
        "(?m)^([ \t]*)\`$sessionUser\s*=\s*session\('user'\);\s*$"

    $replacementSessionUser = @'
${1}$sessionUser = request()->attributes->get('auth_user')
${1}    ?? \App\Models\User::find(session('user_id'));

${1}abort_unless(
${1}    $sessionUser && $sessionUser->status === 'aktif',
${1}    403,
${1}    'Akun tidak ditemukan atau sudah dinonaktifkan.'
${1});
'@

    $newContent = [regex]::Replace(
        $newContent,
        $patternSessionUserSingle,
        $replacementSessionUser
    )

    # ========================================================
    # GANTI $sessionUser = session("user");
    # ========================================================

    $patternSessionUserDouble = `
        '(?m)^([ \t]*)\$sessionUser\s*=\s*session\("user"\);\s*$'

    $newContent = [regex]::Replace(
        $newContent,
        $patternSessionUserDouble,
        $replacementSessionUser
    )

    # ========================================================
    # UBAH PENYEBUTAN SESSION LAMA PADA KOMENTAR
    # ========================================================

    $newContent = $newContent.Replace(
        "session('user')",
        "session('user_id')"
    )

    $newContent = $newContent.Replace(
        'session("user")',
        "session('user_id')"
    )

    # ========================================================
    # SIMPAN JIKA ADA PERUBAHAN
    # ========================================================

    if ($newContent -ne $originalContent) {

        [System.IO.File]::WriteAllText(
            $file.FullName,
            $newContent,
            $utf8NoBom
        )

        Write-Host "[DIUBAH] $relativePath" `
            -ForegroundColor Green

        $updatedCount++

    } else {

        $unchangedCount++

    }
}

# ============================================================
# HASIL PROSES
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

Write-Host ""
Write-Host "php artisan optimize:clear" `
    -ForegroundColor White

Write-Host ""