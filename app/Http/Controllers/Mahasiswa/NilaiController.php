<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class NilaiController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $nilai = DB::table('view_nilai_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('nama_mk')
            ->get();

        return Inertia::render('mahasiswa/nilai/Index', [
            'nilai'     => $nilai,
            'mahasiswa' => $mahasiswa,
        ]);
    }
}