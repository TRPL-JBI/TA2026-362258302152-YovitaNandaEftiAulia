<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\TimAmi;

class TimAuditorController extends Controller
{
    public function index($id)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = TimAmi::with('user')
            ->where('id_periode_ami',$id)
            ->get();

        return view(
            'auditor.periode.tim.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    public function show($id)
    {
        $tim = TimAmi::with([
            'user',
            'periode'
        ])->findOrFail($id);

        $periodeAmi = $tim->periode;

        return view(
            'auditor.periode.tim.show',
            compact(
                'tim',
                'periodeAmi'
            )
        );
    }
}