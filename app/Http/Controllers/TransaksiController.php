<?php

namespace App\Http\Controllers;

use App\Models\DailyRevenue;
use App\Models\MitraStock;
use App\Models\newStock;
use App\Models\Product;
use App\Models\Stocks;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
public function index()
{
    $user = auth()->user();

    // Debug: Cek apakah user memiliki tossa_id
    if (!$user->id_tossa) {
        return redirect()->back()->with('error', 'Tossa ID tidak ditemukan');
    }

    $products = Stocks::with(['product', 'newStock' => function($query) {
        $query->whereDate('created_at', Carbon::today())
              ->orderBy('created_at', 'desc');
    }])
    ->where('tossa_id', $user->id_tossa)
    ->get();

    // Debug: Cek total produk
    logger('Total products found: ' . $products->count());

    // Buat konstanta data stok hari ini
    $stokHariIni = [];
    $latestAddedStocks = [];

    foreach ($products as $product) {
        $todayStock = $product->newStock->first();
        $stokHariIni[$product->id] = $todayStock ? $todayStock->quantity_added : 0;
        $latestAddedStocks[$product->id] = $todayStock ? $todayStock->quantity_added : 0;
    }

    // Definisikan jenis dan kategori yang valid
    $validJenis = ['sayur', 'buah', 'garingan'];
    $validCategories = ['beli', 'titipan'];

    // Grouping produk berdasarkan jenis dan kategori
    $productsByCategory = [];
    $categoryCount = [];
    $debugInfo = []; // Untuk debugging

    foreach ($products as $product) {
        // Debug: Cek apakah relasi product ada
        if (!$product->product) {
            logger('Product relation not found for stock ID: ' . $product->id);
            continue;
        }

        $jenis = strtolower(trim($product->product->jenis ?? ''));
        $category = strtolower(trim($product->product->category ?? ''));

        // Debug: Log jenis dan kategori
        $debugInfo[] = [
            'product_id' => $product->product->id,
            'name' => $product->product->name,
            'jenis' => $jenis,
            'category' => $category,
            'original_jenis' => $product->product->jenis,
            'original_category' => $product->product->category
        ];

        // Validasi jenis dan kategori
        if (!in_array($jenis, $validJenis) || !in_array($category, $validCategories)) {
            logger('Invalid jenis or category: ' . $jenis . ' - ' . $category);
            continue;
        }

        // Buat struktur array berdasarkan jenis dan kategori
        if (!isset($productsByCategory[$jenis])) {
            $productsByCategory[$jenis] = [];
        }

        if (!isset($productsByCategory[$jenis][$category])) {
            $productsByCategory[$jenis][$category] = [];
        }

        $productsByCategory[$jenis][$category][] = $product;
    }

    // Debug: Log hasil grouping
    logger('Debug info for products:', $debugInfo);
    foreach ($productsByCategory as $jenis => $categories) {
        foreach ($categories as $category => $items) {
            logger("Products in {$jenis} - {$category}: " . count($items));
        }
    }

    // Hitung jumlah produk per jenis dan kategori
    foreach ($productsByCategory as $jenis => $categories) {
        foreach ($categories as $category => $items) {
            $categoryCount[$jenis][$category] = count($items);
        }
    }

    // Pastikan semua jenis dan kategori yang diperlukan tersedia
    $requiredJenis = ['sayur', 'buah', 'garingan'];
    $requiredCategories = ['beli', 'titipan'];

    foreach ($requiredJenis as $jenis) {
        if (!isset($productsByCategory[$jenis])) {
            $productsByCategory[$jenis] = [];
        }

        foreach ($requiredCategories as $category) {
            if (!isset($productsByCategory[$jenis][$category])) {
                $productsByCategory[$jenis][$category] = [];
                $categoryCount[$jenis][$category] = 0;
            }
        }
    }

    $shift = $user->workShift->name ?? '-';

    return view('page.mitra.transaksi.index', compact(
        'products',
        'productsByCategory',
        'categoryCount',
        'stokHariIni',
        'shift',
        'latestAddedStocks'
    ));
}

public function store(Request $request, $productId)
{
    $request->validate([
        'stock' => 'required|integer|min:0',
        'shift' => 'required|in:pagi,sore',
    ]);

    $user = auth()->user();

    $stokAwal = Stocks::where('product_id', $productId)
        ->where('tossa_id', $user->id_tossa)
        ->first();

    if (!$stokAwal) {
        return redirect()->back()->with('error', 'Stok awal tidak ditemukan.');
    }

    $stockEnd = $request->stock;

    if ($stockEnd > $stokAwal->quantity) {
        return redirect()->back()->with('error', 'Stok tersisa melebihi stok awal.');
    }

    $soldQuantity = max(0, $stokAwal->quantity - $stockEnd);

    // Cek apakah sudah ada record untuk produk ini hari ini
    $existingRecord = MitraStock::where('user_id', $user->id)
        ->where('product_id', $productId)
        ->whereDate('created_at', today())
        ->first();

    if ($existingRecord) {
        // Update record yang sudah ada
        $existingRecord->update([
            'stock_end' => $stockEnd,
            'sold_quantity' => $soldQuantity,
        ]);
    } else {
        // Buat record baru
        MitraStock::create([
            'user_id'       => $user->id,
            'tossa_id'      => $user->id_tossa,
            'product_id'    => $productId,
            'stock_start'   => $stokAwal->quantity,
            'stock_end'     => $stockEnd,
            'sold_quantity' => $soldQuantity,
            'shifts'        => $request->shift,
        ]);
    }

    $stokAwal->update(['quantity' => $stockEnd]);

    return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:Pending,Lunas,Batal',
    ]);

    $omset = DailyRevenue::findOrFail($id);
    $omset->status = $request->status;
    $omset->save();

    return back()->with('success', 'Status berhasil diperbarui.');
}

public function submitOmset()
{
    $user = auth()->user();
    $tossaId = $user->id_tossa;
    $today = Carbon::today();

    // Ambil semua stok mitra hari ini, dikelompokkan berdasarkan shift dan product_id
    $stocksToday = MitraStock::with('product')
        ->where('tossa_id', $tossaId)
        ->where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->get()
        ->groupBy(['shifts', 'product_id']);

    // Loop per shift dan per product
    foreach ($stocksToday as $shift => $groupedByProduct) {
        foreach ($groupedByProduct as $productId => $stocks) {

            // Hitung jumlah kuantitas dan nilai jual
            $stockStart = $stocks->sum('stock_start');
            $soldQuantity = $stocks->sum('sold_quantity');
            $stockEnd = $stocks->sum('stock_end');

            // Hitung nilai rupiah berdasarkan harga jual masing-masing produk
            $startValue = $stocks->sum(function ($s) {
                return $s->stock_start * ($s->product->price_sell ?? 0);
            });

            $soldValue = $stocks->sum(function ($s) {
                return $s->sold_quantity * ($s->product->price_sell ?? 0);
            });

            $endValue = $stocks->sum(function ($s) {
                return $s->stock_end * ($s->product->price_sell ?? 0);
            });

            // Simpan atau update ke daily_revenues
            DailyRevenue::updateOrCreate(
                [
                    'tossa_id'   => $tossaId,
                    'id_user'    => $user->id,
                    'product_id' => $productId,
                    'shift'      => $shift,
                    'date'       => $today,
                ],
                [
                    'stock_start'   => $stockStart,
                    'sold_quantity' => $soldQuantity,
                    'stock_end'     => $stockEnd,
                    'revenue'       => $soldValue,

                    // Nilai total rupiah
                    'start_value'   => $startValue,
                    'sold_value'    => $soldValue,
                    'end_value'     => $endValue,
                ]
            );
        }
    }

    return redirect()->back()->with('success', 'Omset hari ini berhasil disimpan per produk.');
}




public function Omset(Request $request)
{
    $user = auth()->user();
    $tossaId = $user->id_tossa;

    $filter = $request->get('filter', 'hari');
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    $dateFrom = Carbon::today();
    $dateTo = Carbon::today();

    if ($filter === 'minggu') {
        $dateFrom = Carbon::now()->startOfWeek();
        $dateTo = Carbon::now()->endOfWeek();
    } elseif ($filter === 'bulan') {
        $dateFrom = Carbon::now()->startOfMonth();
        $dateTo = Carbon::now()->endOfMonth();
    } elseif ($filter === 'range' && $startDate && $endDate) {
        $dateFrom = Carbon::parse($startDate);
        $dateTo = Carbon::parse($endDate);
    }

    $revenues = DailyRevenue::with('product')
        ->where('tossa_id', $tossaId)
        ->whereBetween('date', [$dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')])
        ->orderBy('date')
        ->get();

    $grouped = $revenues->groupBy('product.name');


   $chartLabels = ['pagi', 'sore'];
   $chartData = [
    'pagi' => $revenues->where('shift', 'pagi')->sum('revenue'),
    'sore' => $revenues->where('shift', 'sore')->sum('revenue'),
];


    return view('page.mitra.omset.index', [
        'revenues' => $revenues,
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        'filter' => $filter,
        'startDate' => $dateFrom->format('Y-m-d'),
        'endDate' => $dateTo->format('Y-m-d'),
    ]);
}



}
