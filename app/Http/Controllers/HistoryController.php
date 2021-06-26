<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SweetAlert;


class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $pesanans = Pesanan::where('user_id', Auth::user()->id)->where('status', '!=', 0)->get(); //yang status tidak sama dengan 0 masuk ke history
        //get itu menampilkan lebih dari satu kalau view banyak
        return view('history.index', compact('pesanans'));
    }
    public function detail($id)
    {
        $pesanan = Pesanan::where('id', $id)->first();
        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();

        return view('history.detail', compact('pesanan', 'pesanan_details'));
    }
}
