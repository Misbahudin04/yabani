<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $barangs = Barang::paginate(20);
        //dd($barangs); //menampilkan sudah masuk atau belum
        return view('home', compact('barangs'));
    }
}
