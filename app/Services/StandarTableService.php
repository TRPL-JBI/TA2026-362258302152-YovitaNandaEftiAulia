<?php

namespace App\Services;

use App\Models\StandarMutu;

class StandarTableService
{
    /**
     * Membentuk tabel standar mutu.
     *
     * Satu baris mewakili satu jalur hierarki yang sama:
     *
     * Standar Pendidikan
     * → Standar Input
     * → Dosen
     * → seluruh indikator pada jalur tersebut
     */
    public function generateTable($id): array
    {
        $standar = StandarMutu::with([
            'isiStandar' => function ($query) {
                $query
                    ->whereNull('parent_standar_id')
                    ->orderBy('id');
            },

            'isiStandar.indikator',

            'isiStandar.recursiveChildrenWithIndikator',
        ])->findOrFail($id);

        $rawRows = [];

        foreach ($standar->isiStandar as $root) {
            $this->collectRows(
                $root,
                [],
                $rawRows,
                $standar->nama_standar_mutu
            );
        }

        $rows = $this->groupRowsByPath($rawRows);

        return [
            'standar' => $standar,
            'rows' => $rows,
            'maxLevel' => 3,
        ];
    }

    /**
     * Mengambil seluruh indikator beserta jalur hierarkinya.
     */
    private function collectRows(
        $node,
        array $path,
        array &$rows,
        string $namaStandarMutu
    ): void {
        $path[] = [
            'id' => $node->id,
            'nama' => $node->nama_standar,
        ];

        /*
         * Setiap indikator dicatat bersama jalur lengkapnya.
         */
        foreach ($node->indikator as $indikator) {
            $rows[] = [
                'standar' => $namaStandarMutu,
                'path' => $path,
                'indikator' => $indikator,
            ];
        }

        /*
         * Lanjut membaca semua child secara rekursif.
         */
        foreach ($node->recursiveChildrenWithIndikator as $child) {
            $this->collectRows(
                $child,
                $path,
                $rows,
                $namaStandarMutu
            );
        }
    }

    /**
     * Mengelompokkan indikator berdasarkan jalur hierarki yang sama.
     *
     * Contoh:
     * Pendidikan → Input → Dosen
     *
     * Semua indikator pada jalur tersebut menjadi satu baris.
     */
    private function groupRowsByPath(array $rawRows): array
    {
        $groupedRows = [];

        foreach ($rawRows as $rawRow) {
            $pathIds = collect($rawRow['path'])
                ->pluck('id')
                ->implode('-');

            $groupKey = $pathIds;

            if (!isset($groupedRows[$groupKey])) {
                $groupedRows[$groupKey] = [
                    'standar' => $rawRow['standar'],

                    /*
                     * Ubah path menjadi daftar nama:
                     *
                     * [
                     *     'Standar Pendidikan',
                     *     'Standar Input',
                     *     'Dosen'
                     * ]
                     */
                    'level' => collect($rawRow['path'])
                        ->pluck('nama')
                        ->values()
                        ->all(),

                    'indikator' => [],
                ];
            }

            $groupedRows[$groupKey]['indikator'][] =
                $rawRow['indikator'];
        }

        return array_values($groupedRows);
    }
}