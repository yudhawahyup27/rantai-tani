<?php

namespace App\Http\Controllers;

use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;

class DashboardmitraController extends Controller
{
    public function index() {
        $data = ProductPriceHistory::latest()-> with('product')->take(6)->get();
        return view("page.mitra.index", compact('data'));
    }
}
