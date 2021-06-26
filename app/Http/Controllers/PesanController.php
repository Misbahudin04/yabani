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

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id)
    {
        $barang = Barang::where('id', $id)->first();
        return view('pesan.index', compact('barang'));
    }
    public function pesan(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
        $tanggal = Carbon::now();

        //validasi apakah stok masih ada
        if ($request->jumlah_pesan > $barang->stok) {
            return redirect('pesan/' . $id);
        }
        //untuk cek apakah pesanan yang sama id sebelumnya sudah buat db atau blm
        $cek_pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //kemudian jika belum tebuat db maka dibuat dbnya dahulu, tapi jika sudah aka dilewatkan proses simpan
        if (empty($cek_pesanan)) {
            //simpan kedatabase simpanan
            $pesanan = new Pesanan;
            $pesanan->user_id = Auth::user()->id;
            $pesanan->tanggal = $tanggal;
            $pesanan->status = 0;
            $pesanan->jumlah_harga = 0;
            $pesanan->kode = mt_rand(100, 999);
            $pesanan->save();
        }

        //untuk dapetin pesanan id 
        $pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //cek pesanan detail sudah ada atau belum, agar tidak menumpuk pesanan yang sama
        $cek_pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();
        if (empty($cek_pesanan_detail)) {
            //simpan kedatabase detail
            $pesanan_detail = new PesananDetail;
            $pesanan_detail->barang_id = $barang->id;
            $pesanan_detail->pesanan_id  = $pesanan_baru->id;
            $pesanan_detail->jumlah = $request->jumlah_pesan; //request dari data yang dikirim di form
            $pesanan_detail->jumlah_harga = $barang->harga * $request->jumlah_pesan;
            $pesanan_detail->save();
        } else {
            $pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();

            $pesanan_detail->jumlah = $pesanan_detail->jumlah + $request->jumlah_pesan; //request dari data yang dikirim di form
            //harga sekarang setelah ditambahkan ke database dengan atribut ayng sama
            $harga_pesanan_detail_baru = $barang->harga * $request->jumlah_pesan;
            $pesanan_detail->jumlah_harga = $pesanan_detail->jumlah_harga + $harga_pesanan_detail_baru;
            $pesanan_detail->update();
        }
        //jumlah total
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan->jumlah_harga = $pesanan->jumlah_harga + $barang->harga * $request->jumlah_pesan;
        $pesanan->update();

        alert()->success('Pesanan Berhasil dimasukkan', 'SUKSES');
        return redirect('check-out');
    }

    public function check_out()
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        if (!empty($pesanan)) {
            $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
            return view('pesan.check_out', compact('pesanan', 'pesanan_details'));
        }
        return view('pesan.check_out');
    }

    public function delete($id)
    {
        $pesanan_detail = PesananDetail::where('id', $id)->first();
        $pesanan = Pesanan::where('id', $pesanan_detail->pesanan_id)->first();
        $pesanan->jumlah_harga = $pesanan->jumlah_harga - $pesanan_detail->jumlah_harga;
        $pesanan->update();


        $pesanan_detail->delete();

        alert()->error('Pesanan Sukses Dihapus', 'Hapus');
        return redirect('check-out');
    }

    public function konfirmasi()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if (empty($user->alamat)) {
            //alert('Mohon Lengkapi Data Diri Anda');
            alert()->error('Mohon Lengkapi Data Diri Anda', 'Gagal');
            return redirect('profile');
        }
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan_id = $pesanan->id;
        $pesanan->status = 1;
        $pesanan->update();

        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan_id)->get();
        foreach ($pesanan_details as $pesanan_detail) {
            $barang = Barang::where('id', $pesanan_detail->barang_id)->first();
            $barang->stok = $barang->stok - $pesanan_detail->jumlah;
            $barang->update();
        }
        alert()->success('Pesanan Berhasil Sudah Dicheck Out Lakukan Pembayaran!', 'SUKSES');
        return redirect('history/' . $pesanan_id);
    }
}
