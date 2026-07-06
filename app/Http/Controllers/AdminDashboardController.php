<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Beasiswa;
use App\Models\BeasiswaRule;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard index page.
     */
    public function index()
    {
        $total_prodi = Prodi::count();
        $total_beasiswa = Beasiswa::count();
        $total_rules = BeasiswaRule::count();

        // Get some quick lists to display on dashboard
        $prodis = Prodi::orderBy('created_at', 'desc')->take(5)->get();
        $beasiswas = Beasiswa::withCount('rules')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('total_prodi', 'total_beasiswa', 'total_rules', 'prodis', 'beasiswas'));
    }
}
