<?php

namespace App\Http\Controllers;

use App\Models\DailyRevenue;
use App\Models\Product;
use App\Models\sewas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardadminCntroller extends Controller
{

public function index(Request $request)
{

    $from = $request->input('from');
    $to = $request->input('to');

    // Default ke 30 hari terakhir jika tidak ada input
    if (!$from && !$to) {
        $from = Carbon::now()->subDays(30)->toDateString();
        $to = Carbon::now()->toDateString();
    }

    // Ambil data sewa dengan relasi user dan tossa
// Ambil data dengan relasi user → userTossa
$sewas = Sewas::with(['user.userTossa'])
    ->whereBetween('created_at', [$from, $to])
    ->get();

// Group berdasarkan nama tossa dari relasi user → userTossa + username user
$grouped = $sewas->groupBy(function ($item) {
    $tossaName = optional($item->user->userTossa)->name ?? 'Tossa Tidak Diketahui';
    $userName = optional($item->user)->username ?? 'User Tidak Diketahui';
    return "$tossaName ($userName)";
});

    // Format untuk chart
    $chartData = $grouped->map(function ($items, $label) {
        return [
            'label' => $label,
            'total' => $items->sum('hargaSewa'),
        ];
    })->values();

    // Hitung total semua harga sewa
    $totalHargaSewa = $chartData->sum('total');
    // Titipan
    $tanggal = $request->input('date') ?? Carbon::now()->toDateString();

    $query = DailyRevenue::with('product')
        ->whereDate('created_at', $tanggal) // FILTER tanggal langsung di sini
        ->whereHas('product', function ($q) {
            $q->where('category', 'titipan');
        });

    $data = $query->get(); // Jangan ambil data sebelum semua kondisi diterapkan

    // Grouping berdasarkan tanggal dibuat dan pemilik produk
    $grouped = $data->groupBy(function ($item) {
        $date = $item->created_at->format('Y-m-d');
        $pemilik = $item->product->pemilik ?? 'Tidak Diketahui';
        return "{$date}|{$pemilik}";
    });

    $result = [];

    foreach ($grouped as $key => $items) {
        [$date, $pemilik] = explode('|', $key);
        $formattedDate = Carbon::parse($date)->translatedFormat('d F Y');

        $products = $items->map(function ($item) {
            return [
                'product_name' => $item->product->name ?? '-',
                'sold_quantity' => $item->sold_quantity,
                'revenue' => $item->revenue,
            ];
        });

        $result[] = [
            'date' => $date,
            'date_formatted' => $formattedDate,
            'pemilik' => $pemilik,
            'total_revenue' => $items->sum('revenue'),
            'products' => $products->toArray(),
        ];
    }

    return view('page.superadmin.index', [
        'data' => $result,
        'sewas' => $sewas,
        'selectedDate' => $tanggal,
        'chartData' => $chartData,
        'totalHargaSewa' => $totalHargaSewa,
        'from' => $from,
        'to' => $to,
    ]);
}

}
