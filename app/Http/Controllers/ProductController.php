<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Satuan;
use App\Models\ProductPriceHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProductPriceChanged;

class ProductController extends Controller
{
  public function index(Request $request)
{
    $perPage = $request->input('perpage', 10);
    $sort = $request->input('sort', 'asc');
    $search = $request->input('search');

    // Produk beli
    $productsBuy = Product::where('jenis', 'beli')
        ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
        ->orderBy('name', $sort)
        ->paginate($perPage, ['*'], 'buy_page');

    // Produk titipan
    $productsTitip = Product::where('jenis', 'titip')
        ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
        ->orderBy('name', $sort)
        ->paginate($perPage, ['*'], 'titip_page');

    return view('page.superadmin.Product.index', compact('productsBuy', 'productsTitip'));
}


    public function manage($id = null){
        $data = $id ? Product::findOrFail($id) : new Product();
        $satuan = Satuan::all();
        return view('page.superadmin.Product.manage', compact('data','satuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'laba' => 'required|numeric',
            'category' => 'required|string',
            'id_satuan' => 'required|exists:satuans,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $total = $request->input('price') + $request->input('laba');

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'price_sell' => $total,
            'laba' => $request->laba,
            'jenis' => $request->jenis,
            'pemilik' => $request->pemilik,
            'category' => $request->category,
            'id_satuan' => $request->id_satuan,
            'image' => $imagePath,
        ]);

        ProductPriceHistory::create([
            'product_id' => $product->id,
            'old_price_sell' => null,
            'new_price_sell' => $total,
        ]);

        return redirect()->route('admin.product')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'category' => 'required|string',
            'id_satuan' => 'required|exists:satuans,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        if ($request->has('remove_image') && $request->remove_image == 1) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = null;
        }

        $oldPriceSell = $product->price_sell;

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_sell' => $request->price_sell,
            'category' => $request->category,
             'jenis' => $request->jenis,
            'pemilik' => $request->pemilik,
            'id_satuan' => $request->id_satuan,
            'image' => $product->image,
        ]);

        if ($oldPriceSell != $request->price_sell) {
            ProductPriceHistory::create([
                'product_id' => $product->id,
                'old_price_sell' => $oldPriceSell,
                'new_price_sell' => $request->price_sell,
            ]);


  $mitras = User::where('id_role', 2)->get();
            foreach ($mitras as $mitra) {
                $mitra->notify(new ProductPriceChanged($product->name, $oldPriceSell, $request->price_sell));
            }
        }

        return redirect()->route('admin.product')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.product')->with('success', 'Produk berhasil dihapus.');
    }

    public function priceHistory($id)
    {
        $product = Product::findOrFail($id);
        $histories = ProductPriceHistory::where('product_id', $id)->orderByDesc('created_at')->get();

        return view('page.superadmin.Product.price-history', compact('product', 'histories'));
    }
}
