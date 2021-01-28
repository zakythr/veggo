@extends('penjual.layouts.app')
@section('title','Stok')
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

                    <form action="{{url('/Penjual/Stok')}}" method="POST">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <th>Barang</th>
                                <th>Ketersediaan</th>
                            </thead>
                            <tbody>
                                @foreach ($data['barang'] as $key => $barang)
                                <tr>
                                    <td>{{$barang->nama}}</td>
                                    <td>
                                        <fieldset id="{{$key}}">
                                            <div class="form-inline">
                                                <input type="hidden" name="id_barang_{{$key}}" value="{{$barang->id}}">
                                                <div class="custom-control custom-radio m-2">
                                                    <input type="radio" id="customRadio1_{{$key}}" name="ketersediaan_{{$key}}"
                                                        class="custom-control-input" value="0" {{$barang->ketersediaan == 0 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="customRadio1_{{$key}}">Habis</label>
                                                </div>
                                                <div class="custom-control custom-radio m-2">
                                                    <input type="radio" id="customRadio2_{{$key}}" name="ketersediaan_{{$key}}"
                                                        class="custom-control-input" value="1" {{$barang->ketersediaan == 1 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="customRadio2_{{$key}}">Sedikit</label>
                                                </div>
                                                <div class="custom-control custom-radio m-2">
                                                    <input type="radio" id="customRadio3_{{$key}}" name="ketersediaan_{{$key}}"
                                                        class="custom-control-input" value="2" {{$barang->ketersediaan == 2 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="customRadio3_{{$key}}">Sedang</label>
                                                </div>
                                                <div class="custom-control custom-radio m-2">
                                                    <input type="radio" id="customRadio4_{{$key}}" name="ketersediaan_{{$key}}"
                                                        class="custom-control-input" value="3" {{$barang->ketersediaan == 3 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="customRadio4_{{$key}}">Banyak</label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </td>                                        
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <button class="btn btn-sm btn-success" type="submit">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
