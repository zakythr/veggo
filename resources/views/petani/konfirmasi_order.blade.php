@extends('petani.layouts.app')
@section('title','Order Veggo')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title">Konfirmasi Order Veggo | <strong>{{$data['order']->nomor_invoice}}</strong></h1>
                        </div>
                        <div class="col-6">
                            @if($data['order']->status == 1)
                                <button id="kirim_btn" class="btn btn-sm btn-success float-right">Kirim</button>
                            @else
                            @endif
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="overflow-x:auto;">
                        <form id="form_konfirmasi" action="{{url('/Petani/Order/Konfirmasi')}}" method="POST">         
                        @csrf         
                        <input type="hidden" name="id_transaksi" value="{{$data['order']->id}}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="display:none">id</th>
                                    <th>Produk</th>
                                    <th>Volume x Bobot Pesan </th>
                                    <th hidden="true">Volume Pesan </th>
                                    <th hidden="true">Bobot Pesan (Gram)</th>
                                    <th>Volume Kirim </th>
                                    <th>Bobot Kirim (Gram)</th>
                                    <th>Selisih Kirim (Gram)</th>
                                    {{-- <th>Volume x Bobot Terima Veggo </th>
                                    <th>Selisih Terima Veggo (Gram)</th> --}}
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($data['order']->detailTransaksi as $key => $item)
                                        <tr>
                                            @if($item->status == 0)
                                                <td style="display:none"><input type="text" class="form-control" name="id_detail_transaksi[]" value="{{$item->id}}" readonly></td>
                                                <td ><input style="width:150px" type="text" class="form-control" value="{{$item->barang->nama}}" readonly></td>
                                                <td hidden="true"><input type="number" class="form-control" id="volume_pesan_{{$key}}" value="{{$item->volume}}" readonly></td>
                                                <td hidden="true"><input type="number" class="form-control" id="bobot_pesan_{{$key}}" value="{{$item->bobot_kemasan}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" id="bobotxv_pesan_{{$key}}" value="{{$item->volume}} x {{$item->bobot_kemasan}}" readonly></td>
                                                <td><input style="width:100px" type="number" class="form-control" name="volume_kirim[]" placeholder="Volume Kirim" id="volume_kirim_{{$key}}" value="{{$item->volume}}" onkeyup="getSelisih({{$key}})"  required></td>
                                                <td><input style="width:100px" type="number" class="form-control" name="bobot_kirim[]" placeholder="Bobot Kirim" id="bobot_kirim_{{$key}}" value="{{$item->bobot_kemasan}}" onkeyup="getSelisih({{$key}})" required></td>
                                                <td><input style="width:100px" type="text" class="form-control" name="selisih_kirim[]" placeholder="bobot_selisih" id="bobot_selisih_{{$key}}" value="{{$item->bobot - $item->bobot}} " readonly ></td>
                                                {{-- <td><input style="width:100px" type="text" class="form-control" id="bobotxv_pesann_{{$key}}" value="{{$item->volume_terima ?? 0}} x {{$item->bobot_terima ?? 0}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" id="bobotxv_pesannn_{{$key}}" value="{{$item->selisih_terima ?? 0}}" readonly></td> --}}
                                                <td><input style="width:200px" type="textarea" class="form-control" placeholder="Keterangan" name="keterangan[]"></td>
                                            @else
                                                <td style="display:none"><input type="text" class="form-control" name="id_detail_transaksi[]" value="{{$item->id}}" readonly></td>
                                                <td><input type="text" class="form-control" value="{{$item->barang->nama}}" readonly></td>
                                                <td hidden="true"><input type="number" class="form-control" id="volume_pesan_{{$key}}" value="{{$item->volume}}" readonly></td>
                                                <td hidden="true"><input type="number" class="form-control" id="bobot_pesan_{{$key}}" value="{{$item->bobot_kemasan}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" id="volumexb_pesan_{{$key}}" value="{{$item->volume}} x {{$item->bobot_kemasan}}" readonly></td>
                                                <td><input style="width:100px" type="number" class="form-control" name="volume_kirim[]" placeholder="Volume Kirim" id="volume_kirim_{{$key}}" value="{{$item->volume_kirim_petani}}" required readonly></td>                                                
                                                <td><input style="width:100px" type="number" class="form-control" name="bobot_kirim[]" placeholder="Bobot Kirim" id="bobot_kirim_{{$key}}" value="{{$item->bobot_kirim_petani}}" required readonly></td>
                                                <td><input style="width:100px" type="number" class="form-control" placeholder="bobot_selisih" id="bobot_selisih_{{$key}}" value="{{$item->selisih_kirim}}" readonly></td>
                                                {{-- <td><input style="width:100px" type="text" class="form-control" id="bobotxv_pesann_{{$key}}" value="{{$item->volume_terima}} x {{$item->bobot_terima}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" id="bobotxv_pesannn_{{$key}}" value="{{$item->selisih_terima}}" readonly></td> --}}
                                                <td><input style="width:200px" type="text" class="form-control" placeholder="Keterangan" name="keterangan[]" value="{{$item->keterangan}}" readonly></td>
                                            @endif
                                        </tr>                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
<script>
    function getSelisih($key){
        
        pesanSelector = "#bobot_pesan_"+$key
        kirimSelector = "#bobot_kirim_"+$key
        volpesanSelector = "#volume_pesan_"+$key
        volkirimSelector = "#volume_kirim_"+$key
        selisihSelector = "#bobot_selisih_"+$key

        pesanVal = $(pesanSelector).val()
        kirimVal = $(kirimSelector).val()
        volpesanVal = $(volpesanSelector).val()
        volkirimVal = $(volkirimSelector).val()

        hasil=(volkirimVal*kirimVal)-(volpesanVal*pesanVal)
        if (hasil<0){
            $(selisihSelector).css("color", "red")
        }
        else if (hasil>0){
            $(selisihSelector).css("color", "green")
        }
        else{
            $(selisihSelector).css("color", "black")
        }
        
        $(selisihSelector).val(hasil)

    }

    $('#kirim_btn').click(function(){
        $('#form_konfirmasi').submit()
    })
    
</script>
    
@endsection
@endsection
