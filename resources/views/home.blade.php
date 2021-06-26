@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="mt-5" style="margin-left: 70px;">Black Ethnic <br>Edition</h2>
        </div>
        <div class="col-md-4">
            <div class="owl-carousel owl-theme" style="margin-top:-100px">
                <div class="item">
                    <h4><img src="{{url('katalog/1.png')}}" width="280" alt="" class="rounded mx-auto d-block"></h4>
                </div>
                <div class="item">
                    <h4><img src="{{url('katalog/2.png')}}" width="280" alt="" class="rounded mx-auto d-block"></h4>
                </div>
                <div class="item">
                    <h4><img src="{{url('katalog/3.png')}}" width="280" alt="" class="rounded mx-auto d-block"></h4>
                </div>
            </div>
            <!--<img src="{{url('katalog/3.png')}}" width="220" alt="" class="rounded mx-auto d-block">-->
        </div>
        <div class="col-md-4">
            <h5 style="margin-top: 100px;">Material Oxford Premium Cotton <br>Make You So Comfortable</h5>
            <a href="{{url('pesan')}}/1" class="btn btn-dark"><i class="fa fa-shopping-cart"></i> Pesan Sekarang</a>
        </div>
    </div>

    <h4 class="mt-5" style="text-align:center">Katalog</h4>
    <hr>
</div>
<div class="container">
    <div class="row justify-content-center" id="katalog">
        @foreach($barangs as $barang)
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="{{url('katalog')}}/{{$barang->gambar}}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{$barang->nama_barang}}</h5>
                    <p class="card-text">
                        <strong>Harga:</strong> Rp.{{number_format($barang->harga)}}<br>
                        <strong>Stok:</strong> {{$barang->stok}}<br>
                        <hr>
                        <strong>Keterangan:</strong><br>
                        {{$barang->keterangan}}
                    </p>
                    <a href="{{url('pesan')}}/{{$barang->id}}" class="btn btn-dark "><i class="fa fa-shopping-cart"></i> Pesan</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<hr class="mt-4">
<img src="{{url('images/logo1.png')}}" width="220" alt="" class="rounded mx-auto d-block mt-5">
<div class="row justify-content-center rounded mx-auto mt-4" style="width: 200px; text-align:center" id="katalog">
    <a href="" style="color: black;"><i class="fab fa-instagram col-md-3"></i></a>
    <a href="" style="color: black;"><i class="fab fa-facebook-square col-md-3"></i></a>
    <a href="" style="color: black;"><i class="fab fa-whatsapp col-md-3"></i></a>
    <a href="" style="color: black;"><i class="fas fa-envelope col-md-3"></i></a>
</div>
@endsection