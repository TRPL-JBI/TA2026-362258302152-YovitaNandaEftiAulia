<?php

namespace App\Services;

class AuditValidationService
{
    public function penerapanLengkap(
        ?string $deskripsiHasil,
        ?string $linkBukti
    ): bool {
        return $this->teksTerisi($deskripsiHasil)
            && $this->teksTerisi($linkBukti);
    }

    public function linkBuktiValid(?string $linkBukti): bool
    {
        if (!$this->teksTerisi($linkBukti)) {
            return false;
        }

        return filter_var(
            trim($linkBukti),
            FILTER_VALIDATE_URL
        ) !== false;
    }

    public function statusTemuanValid(?string $status): bool
    {
        if ($status === null) {
            return false;
        }

        return in_array(
            strtolower(trim($status)),
            ['open', 'closed'],
            true
        );
    }

    public function periodeBerjalan(?string $status): bool
    {
        if ($status === null) {
            return false;
        }

        return strtolower(trim($status)) === 'berjalan';
    }

    public function memilikiRole(
        ?string $rolePengguna,
        string $roleDiperlukan
    ): bool {
        if ($rolePengguna === null) {
            return false;
        }

        return strtolower(trim($rolePengguna))
            === strtolower(trim($roleDiperlukan));
    }

    public function dapatMembuatTemuan(
        ?string $rolePengguna,
        ?string $deskripsiHasil,
        ?string $linkBukti
    ): bool {
        return $this->memilikiRole($rolePengguna, 'auditor')
            && $this->penerapanLengkap(
                $deskripsiHasil,
                $linkBukti
            )
            && $this->linkBuktiValid($linkBukti);
    }

    private function teksTerisi(?string $nilai): bool
    {
        return $nilai !== null && trim($nilai) !== '';
    }
}