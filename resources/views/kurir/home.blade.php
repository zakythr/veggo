@extends('kurir.layouts.layout')

@section('title')
    Kurir
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                Selamat Datang ! Anda Login Sebagai Kurir
            </div>
        </div>
        <div class="col-12">
            <br>
            <div class="row sortable">
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Akan Dikirim</h6>
                            <h6 class="mb-0">60%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Sedang Dikirim</h6>
                            <h6 class="mb-0">60%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Selesai Dikirim</h6>
                            <h6 class="mb-0">60%</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
    </div>
</div>
@endsection
