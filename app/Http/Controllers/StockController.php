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

    $errors = [];

    foreach ($request->products as $item) {
        $exists = Stocks::where('product_id', $item['product_id'])
                        ->where('tossa_id', $request->tossa_id)
                        ->exists();

        if ($exists) {
            $productName = \App\Models\Product::find($item['product_id'])?->name ?? 'Produk ID '.$item['product_id'];
            $errors[] = "Produk '$productName' sudah ada di jaringan ini.";
            continue;
        }

        Stocks::create([
            'product_id' => $item['product_id'],
            'tossa_id'   => $request->tossa_id,
            'quantity'   => $item['quantity'],
        ]);
    }

    if ($errors) {
        return back()->withErrors($errors)->withInput();
    }

    return redirect()->route('admin.stock')->with('success', 'Stok berhasil ditambahkan.');
}


    public function update (Request $request, $id)
    {
        $request->validate(
            [
                'product_id'=> 'required',
                'tossa_id'=> 'required',
                ]
                );
               $stock = Stocks::findOrFail($id);
            // Cek apakah ada quantity baru ditambahkan
    $quantityNew = $request->input('quantity_new', 0);
    if ($quantityNew > 0) {
        // Tambahkan ke stock
        $stock->quantity += $quantityNew;

        // Simpan riwayat ke tabel new_stocks
        newStock::create([
            'stock_id' => $stock->id,
            'quantity_added' => $quantityNew,
        ]);
    }

    // Update field lain
    $stock->quantity_new = 0; // reset
    $stock->product_id = $request->input('product_id');
    $stock->tossa_id = $request->input('tossa_id');
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
