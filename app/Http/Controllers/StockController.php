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
        'products.*.quantity' => 'required|numeric|min:0',
    ]);

    foreach ($request->products as $product) {
        $exists = Stocks::where('product_id', $product['product_id'])
                        ->where('tossa_id', $request->tossa_id)
                        ->exists();

        if ($exists) {
            return back()->withErrors(['Produk ' . $product['product_id'] . ' sudah ada di stok network ini.'])->withInput();
        }

        Stocks::create([
            'product_id' => $product['product_id'],
            'tossa_id' => $request->tossa_id,
            'quantity' => $product['quantity'],
        ]);
    }

    return redirect()->route('admin.stock')->with('success', 'Stok berhasil ditambahkan.');
}



public function update(Request $request, $id)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'tossa_id' => 'required|exists:tossas,id',
        'quantity' => 'required|numeric|min:0',
        'quantity_new' => 'nullable|numeric|min:0',
    ]);

    $stock = Stocks::findOrFail($id);

    $quantityNew = $request->input('quantity_new');
    if ($quantityNew && $quantityNew > 0) {
        $stock->quantity += $quantityNew;

        newStock::create([
            'stock_id' => $stock->id,
            'quantity_added' => $quantityNew,
        ]);
    }

    $stock->quantity_new = 0;
    $stock->product_id = $request->input('product_id');
    $stock->tossa_id = $request->input('tossa_id');
    $stock->quantity = $request->input('quantity');
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
