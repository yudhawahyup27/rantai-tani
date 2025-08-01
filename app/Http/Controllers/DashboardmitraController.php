<?php

namespace App\Http\Controllers;

use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;

class DashboardmitraController extends Controller
{
    public function index() {
    $data = ProductPriceHistory::with('product')->take(30)->latest()->paginate(6);
    return view("page.mitra.index", compact('data'));
}

}
