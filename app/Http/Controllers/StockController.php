<?php

namespace App\Http\Controllers;

use App\Models\newStock;
use App\Models\Product;
use App\Models\Stocks;
use App\Models\Tossa;
use Illuminate\Http\Request;

class StockController extends Controller
{
   public function index (){
   $data = Tossa::all();
    return view('page.superadmin.Stocks.index', compact('data'));
   }
   public function detail ($id){

      $data = Stocks::with('product')->where('tossa_id', $id)->get();

    return view('page.superadmin.Stocks.detail', compact('data'));
   }

   public function manage ($id =  null){
    $product = Product::all();
    $data = $id ? Stocks::findOrFail($id) : new Stocks();
    $tossa = Tossa::all();
    return view('page.superadmin.Stocks.manage', compact('data','product', 'tossa'));
    }

public function store(Request $request)
{
    $request->validate([
        'tossa_id' => 'required|exists:tossas,id',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required',
    ]);

    foreach ($request->products as $product) {
        $product_id = $product['product_id'];
        $quantityRaw = $product['quantity'];

        // Convert koma ke titik untuk desimal
        $quantity = floatval(str_replace(',', '.', $quantityRaw));

        if ($quantity < 0) {
            return back()->withErrors(['Jumlah stok tidak boleh negatif.'])->withInput();
        }

        // Cegah duplikat
        $exists = Stocks::where('product_id', $product_id)
                        ->where('tossa_id', $request->tossa_id)
                        ->exists();
        if ($exists) {
            return back()->withErrors(['Produk sudah ada di jaringan ini.'])->withInput();
        }

        Stocks::create([
            'product_id' => $product_id,
            'tossa_id' => $request->tossa_id,
            'quantity' => $quantity,
        ]);
    }

    return redirect()->route('admin.stock')->with('success', 'Stok berhasil ditambahkan.');
}



public function update(Request $request, $id)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'tossa_id' => 'required|exists:tossas,id',
        'quantity' => 'required',
        'quantity_new' => 'nullable',
    ]);

    $stock = Stocks::findOrFail($id);

    $quantityRaw = $request->input('quantity');
    $quantityNewRaw = $request->input('quantity_new');

    // Konversi format angka (koma ke titik)
    $quantity = floatval(str_replace(',', '.', $quantityRaw));
    $quantityNew = floatval(str_replace(',', '.', $quantityNewRaw));

    if ($quantity < 0 || $quantityNew < 0) {
        return back()->withErrors(['Jumlah stok tidak boleh negatif.'])->withInput();
    }

    // Tambahkan stok baru ke total (jika ada)
    if ($quantityNew > 0) {
        $stock->quantity += $quantityNew;

        newStock::create([
            'stock_id' => $stock->id,
            'quantity_added' => $quantityNew,
        ]);
    }

    $stock->quantity_new = 0;
    $stock->quantity = $quantity;
    $stock->save();

    return redirect()->route('admin.stock')->with('success', 'Stok berhasil diperbarui.');
}




            public function destroy (Request $request, $id)
            {
                $stock = Stocks::findOrFail($id);
                $stock->
                delete();
                return redirect()->route('admin.stock')->with('success', 'Stok berhasil Dihapus.');
}

public function newStockHistory($stock_id)
{
    $stock = Stocks::with('product', 'tossa', 'newStock')->findOrFail($stock_id);

    return view('page.superadmin.Stocks.newStockHistory', compact('stock'));
}

}
