@extends('petani.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row p-3">
                        <div class="col-6">
                            <h5 class="card-title">Monitoring</h5>
                        </div>
                        <div class="col-6">
                            <select name="" id="">
                            </select>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Permintaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>a</td>
                                <td>c</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
