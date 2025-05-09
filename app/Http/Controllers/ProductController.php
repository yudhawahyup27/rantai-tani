<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $sort = $request->input('sort', 'asc');
        $perPage = $request->input('perpage', 5);

        $data =Product::with('satuan') // Load relasi satuan
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('price', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', $sort)
        ->paginate($perPage);

        return view('page.superadmin.product.index', compact('data'));
    }

    public function manage($id = null){
        $data = $id ? Product::findOrFail($id) : new Product();
        $satuan = Satuan::all();
        return view('page.superadmin.product.manage', compact('data','satuan'));
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

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'price_sell' =>$total,
            'laba' => $request->laba,
            'category' => $request->category,
            'id_satuan' => $request->id_satuan,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.product')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Update data produk
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required|string',
            'id_satuan' => 'required|exists:satuans,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Jika ada file baru, simpan dan hapus yang lama
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Jika checkbox hapus gambar dicentang
        if ($request->has('remove_image') && $request->remove_image == 1) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = null;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_sell' => $request->price_sell,
            'description' => $request->description,
            'category' => $request->category,
            'id_satuan' => $request->id_satuan,
            'image' => $product->image, // Tetap menyimpan gambar terbaru atau null jika dihapus
        ]);

        return redirect()->route('admin.product')->with('success', 'Produk berhasil diperbarui.');
    }


     public function destroy($id)
     {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.product')->with('success', 'Produk berhasil dihapus.');
    }

}
