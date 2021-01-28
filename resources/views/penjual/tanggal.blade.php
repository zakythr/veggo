@extends('penjual.layouts.app')
@section('title','Atur Tanggal')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="overflow-x:auto;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {!! $data !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

