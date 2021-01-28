@extends('reseller.layouts.layout')

@section('title')
    Profil Akun
@endsection

@section('content')
    <div class="col-12 list">

        <div class="mb-2">
            <h1>Profil</h1>
            <div class="text-zero top-right-button-container">
                <a href="/Reseller/Profil/Edit" class="btn btn-success mb-1">Ubah Profil</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-3">
                <div class="card mb-4">
                    <img src="{{ asset('img/T8ILvBp.png') }}" alt="Detail Picture" class="card-img-top">

                    <div class="card-body">
                        <p class="text-muted text-small mb-2">Nama</p>
                        <p class="mb-3">
                            <b>{{ $data['user']->name }}</b>
                        </p>

                        <p class="text-muted text-small mb-2">Email</p>
                        <p class="mb-3">
                            <b>{{ $data['user']->email }}</b>
                        </p>

                        <p class="text-muted text-small mb-2">Nomor Handphone</p>
                        <p class="mb-3">
                            <span class="badge badge-pill badge-success mb-1">
                                {{ $data['user']->nomor_hp }}
                            </span>
                        </p>

                        <p class="text-muted text-small mb-2">Bergabung Sejak</p>
                        <p class="mb-3">
                            <span class="badge badge-pill badge-success mb-1">
                                {{ $data['user']->created_at }}
                            </span>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection