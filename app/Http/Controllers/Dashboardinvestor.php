<?php

namespace App\Http\Controllers;

use App\Models\investor;
use App\Models\Mastersaham;
use Illuminate\Http\Request;

class Dashboardinvestor extends Controller
{
    public function index(Request $request) {

        // cek login id

        $userId = $request->user()->id;

        $investments = Investor::with('tossa')
        ->where('user_id', $userId)
        ->get();


        $totalPorto = $investments->sum("total");

        $totaldeviden = $investments->sum('Deviden');

        $totalLot = $investments->sum('perlot');


    // Calculate totals
    $totalPortfolio = $investments->sum('total');
    $totalLot = $investments->sum('perlot');

    // Initialize $companyData as an array
    $companyData = [];

    // Group by company for charts
    foreach ($investments as $investment) {
        $companyName = $investment->tossaName();

        if (!isset($companyData[$companyName])) {
            $companyData[$companyName] = [
                'money' => 0,
                'lots' => 0
            ];
        }

        $companyData[$companyName]['money'] += $investment->total;
        $companyData[$companyName]['lots'] += $investment->perlot;
    }

    // Check if $companyData is empty and provide default data if needed
    if (empty($companyData)) {
        $companyData = [
            'No Data' => [
                'money' => 0,
                'lots' => 0
            ]
        ];
    }

        return view('page.investor.index', compact(
            'companyData','totaldeviden', 'totalPorto', 'totalLot'
        ));
    }

    public function detail(){
        $investments = Investor::with('tossa')->where('user_id', auth()->id())->get();

        return view('page.investor.detail-saham.index', compact('investments'));
    }

    public function beli(){
        $data = Mastersaham::with('tossa')->get();

        return view('page.investor.beli-saham.index', compact('data'));
    }
}
