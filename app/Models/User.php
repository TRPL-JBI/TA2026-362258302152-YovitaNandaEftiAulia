<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * Nama tabel yang digunakan model.
     */
    protected $table = 'users';

    /**
     * Primary key tabel users.
     */
    protected $primaryKey = 'id';

    /**
     * Tabel users memiliki created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh diisi menggunakan create() atau update().
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'id_unit_kerja',
        'status',
    ];

    /**
     * Kolom yang disembunyikan saat model diubah menjadi array atau JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Relasi lama:
     * satu user memiliki satu Unit Kerja utama.
     *
     * Relasi ini sementara tetap dipertahankan karena beberapa bagian
     * aplikasi lama masih menggunakan users.id_unit_kerja.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            UnitKerja::class,
            'id_unit_kerja',
            'id'
        );
    }

    /**
     * Relasi baru:
     * satu user Auditee atau Kepala Unit dapat menangani
     * lebih dari satu Unit Kerja.
     *
     * Hubungan disimpan pada kolom unit_kerja.id_user.
     */
    public function unitKerjaKepala(): HasMany
    {
        return $this->hasMany(
            UnitKerja::class,
            'id_user',
            'id'
        );
    }

    /**
     * Memeriksa apakah user merupakan Admin.
     */
    public function isAdmin(): bool
    {
        return strtolower((string) $this->role) === 'admin';
    }

    /**
     * Memeriksa apakah user merupakan Auditee.
     */
    public function isAuditee(): bool
    {
        return strtolower((string) $this->role) === 'auditee';
    }

    /**
     * Memeriksa apakah user merupakan Auditor.
     */
    public function isAuditor(): bool
    {
        return strtolower((string) $this->role) === 'auditor';
    }

    /**
     * Memeriksa apakah akun user masih aktif.
     */
    public function isAktif(): bool
    {
        return strtolower((string) $this->status) === 'aktif';
    }

    /**
     * Mendapatkan semua Unit Kerja yang ditangani user.
     *
     * Untuk user Auditee, sumber utama menggunakan
     * relasi unitKerjaKepala.
     */
    public function getDaftarUnitKerjaAttribute()
    {
        return $this->unitKerjaKepala;
    }
}