<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitKerjaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $data = UnitKerja::query()
            ->orderBy('nama')
            ->get();

        return view(
            'unit.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('unit.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'nama' => [
                    'required',
                    'string',
                    'max:255',

                    Rule::unique(
                        'unit_kerja',
                        'nama'
                    ),
                ],

                'kategori_unit_kerja' => [
                    'required',
                    'string',

                    Rule::in([
                        'akademik',
                        'non akademik',
                    ]),
                ],
            ],
            [
                'nama.required' =>
                    'Nama unit kerja wajib diisi.',

                'nama.string' =>
                    'Nama unit kerja harus berupa teks.',

                'nama.max' =>
                    'Nama unit kerja maksimal 255 karakter.',

                'nama.unique' =>
                    'Nama unit kerja tersebut sudah digunakan.',

                'kategori_unit_kerja.required' =>
                    'Kategori unit kerja wajib dipilih.',

                'kategori_unit_kerja.string' =>
                    'Kategori unit kerja tidak valid.',

                'kategori_unit_kerja.in' =>
                    'Kategori hanya boleh Akademik atau Non Akademik.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = request()
            ->attributes
            ->get('auth_user');

        if (!$user instanceof User) {
            $user = User::query()->find(
                session('user_id')
            );
        }

        abort_unless(
            $user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $statusUser = strtolower(
            trim(
                (string) $user->status
            )
        );

        abort_unless(
            $statusUser === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN UNIT KERJA
        |--------------------------------------------------------------------------
        */

        UnitKerja::create([
            'nama' =>
                trim($validated['nama']),

            'kategori_unit_kerja' =>
                $validated['kategori_unit_kerja'],

            'id_user' =>
                $user->id,
        ]);

        return redirect()
            ->route('unit-kerja.index')
            ->with(
                'success',
                'Unit kerja berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): View {
        $unitKerja = UnitKerja::with('user')
            ->findOrFail($id);

        return view(
            'unit.show',
            compact('unitKerja')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        int $id
    ): View {
        $unitKerja = UnitKerja::findOrFail($id);

        return view(
            'unit.edit',
            compact('unitKerja')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $unitKerja = UnitKerja::findOrFail($id);

        $validated = $request->validate(
            [
                'nama' => [
                    'required',
                    'string',
                    'max:255',

                    Rule::unique(
                        'unit_kerja',
                        'nama'
                    )->ignore(
                        $unitKerja->id
                    ),
                ],

                'kategori_unit_kerja' => [
                    'required',
                    'string',

                    Rule::in([
                        'akademik',
                        'non akademik',
                    ]),
                ],
            ],
            [
                'nama.required' =>
                    'Nama unit kerja wajib diisi.',

                'nama.string' =>
                    'Nama unit kerja harus berupa teks.',

                'nama.max' =>
                    'Nama unit kerja maksimal 255 karakter.',

                'nama.unique' =>
                    'Nama unit kerja tersebut sudah digunakan.',

                'kategori_unit_kerja.required' =>
                    'Kategori unit kerja wajib dipilih.',

                'kategori_unit_kerja.string' =>
                    'Kategori unit kerja tidak valid.',

                'kategori_unit_kerja.in' =>
                    'Kategori hanya boleh Akademik atau Non Akademik.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE UNIT KERJA
        |--------------------------------------------------------------------------
        */

        $unitKerja->update([
            'nama' =>
                trim($validated['nama']),

            'kategori_unit_kerja' =>
                $validated['kategori_unit_kerja'],
        ]);

        return redirect()
            ->route('unit-kerja.index')
            ->with(
                'success',
                'Unit kerja berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): RedirectResponse {
        $unitKerja = UnitKerja::findOrFail($id);

        try {
            $unitKerja->delete();
        } catch (QueryException $exception) {
            return redirect()
                ->route('unit-kerja.index')
                ->with(
                    'error',
                    'Unit kerja tidak dapat dihapus karena masih digunakan pada data lain.'
                );
        }

        return redirect()
            ->route('unit-kerja.index')
            ->with(
                'success',
                'Unit kerja berhasil dihapus.'
            );
    }
}