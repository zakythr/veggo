@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-xs-6">
                            <h3>Konfirmasi Penerimaan Barang | <strong>{{$data['invoice']}}</strong></h3>
                        </div>
                        <div class="col-xs-6" align="right" style="color: white;">
                            @if($data['orderPetani']->status == 2)
                                <button id="kirim_btn" class="btn btn-sm btn-success float-right ml-2">Konfirmasi</button>
                            @elseif($data['orderPetani']->status == 3)
                                <button class="btn btn-sm btn-primary float-right ml-2" disabled>Telah dikonfirmasi</button>
                                <a class="btn btn-sm btn-success float-right" href="{{url('/Penjual/OrderPetani/Konfirmasi/Klaim/'.$data['invoice'])}}">Klaim</a>
                            @else
                                <button class="btn btn-sm btn-primary float-right ml-2" disabled>Petani Belum Mengirim</button>
                            @endif
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <form id="form_konfirmasi" action="{{url('/Penjual/OrderPetani/Konfirmasi/Terima')}}" method="POST">         
                        @csrf         
                        <input type="hidden" name="id_transaksi" value="{{$data['orderPetani']->id}}">
                        <table class="table table-bordered"">
                            <thead>
                                <tr>
                                    <th style="display:none">id</th>
                                    <th>Produk</th>
                                    <th>Volume x Bobot Pesan </th>
                                    <th hidden="true">Volume Pesan </th>
                                    <th hidden="true">Bobot Pesan (Gram)</th>
                                    <th hidden="true">Volume Kirim </th>
                                    <th hidden="true">Bobot Kirim (Gram)</th>
                                    <th>Volume x Bobot Kirim </th>
                                    <th>Selisih Kirim (Gram)</th>
                                    @if($data['orderPetani']->status == 3)
                                    <th>Bobot Terima x Volume Terima</th>
                                    <th>Selisih Terima (Gram)</th>
                                    <th>Volume Klaim (Gram)</th>
                                    <th>Volume Bersih (Gram)</th>
                                    @else
                                    <th>Volume Terima </th>
                                    <th>Bobot Terima (Gram)</th>
                                    <th>Selisih Terima (Gram)</th>
                                    @endif
                                   
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($data['orderPetani']->detailTransaksi as $key => $item)
                                        <tr>
                                            <td style="display:none"><input type="text" class="form-control" name="id_detail_transaksi[]" value="{{$item->id}}" readonly></td>
                                            <td style="display:none"><input type="text" class="form-control" name="id_barang[]" value="{{$item->id_barang}}" readonly></td>
                                            <td><input style="width:150px" type="text" class="form-control" value="{{$item->barang->nama}}" readonly></td>
                                            <td><input style="width:100px" type="text" class="form-control" id="volumexb_pesan_{{$key}}" value="{{$item->volume}} x {{$item->bobot_kemasan}}" readonly></td>
                                            <td hidden="true"><input  type="number" class="form-control" id="volume_pesan_{{$key}}" value="{{$item->volume}}" readonly></td>
                                            <td hidden="true"><input type="number" class="form-control" id="bobot_pesan_{{$key}}" value="{{$item->bobot_kemasan}}" readonly></td>
                                            <td hidden="true"><input type="number" class="form-control" name="volume_kirim[]" placeholder="Volume Kirim" value="{{$item->volume_kirim_petani}}" readonly></td>
                                            <td hidden="true"><input type="number" class="form-control" name="bobot_kirim[]" placeholder="Bobot Kirim" value="{{$item->bobot_kirim_petani}}" readonly></td>
                                            <td><input style="width:100px" type="text" class="form-control" value="{{$item->volume_kirim_petani ?? 0}} x {{$item->bobot_kirim_petani ?? 0}}" readonly></td>
                                            <td><input style="width:100px" type="text" class="form-control" placeholder="bobot_selisih" name="selisih_kirim[]" id="bobot_selisih_kirim_{{$key}}" value="{{$item->selisih_kirim ?? 0}} " readonly></td>
                                            @if($data['orderPetani']->status == 3)
                                                <td><input style="width:100px" type="text" class="form-control" name="volume_terima[]" placeholder="Volume Kirim" id="volume_terima_{{$key}}" value="{{$item->volume_terima}} x {{$item->bobot_terima}}"  readonly required></td>
                                                <td><input style="width:100px" type="text" class="form-control" placeholder="bobot_selisih" name="selisih_terima[]" id="bobot_selisih_{{$key}}" value="{{$item->selisih_terima}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" placeholder="klaim" name="klaim[]" id="klaim_{{$key}}" value="{{$item->klaim}}" readonly></td>
                                                <td><input style="width:100px" type="text" class="form-control" placeholder="klaim" name="klaim[]" id="klaim_{{$key}}" value="{{($item->volume_terima * $item->bobot_terima)-$item->klaim}}" readonly></td>
                                            @else
                                                <td><input style="width:100px" type="number" class="form-control" name="volume_terima[]" placeholder="Volume Kirim" id="volume_terima_{{$key}}" value="{{$item->volume_kirim_petani}}" onkeyup="getSelisih({{$key}})" required></td>
                                                <td><input style="width:100px" type="number" class="form-control" name="bobot_terima[]" placeholder="Bobot Kirim" id="bobot_terima_{{$key}}" value="{{$item->bobot_kirim_petani}}"  onkeyup="getSelisih({{$key}})" required></td>
                                                <td><input style="width:100px" type="text" class="form-control" placeholder="bobot_selisih" name="selisih_terima[]" id="bobot_selisih_{{$key}}" value="0" readonly></td>
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
        
        pesanSelector = "#volume_pesan_"+$key
        terimamSelector = "#volume_terima_"+$key
        selisihSelector = "#bobot_selisih_"+$key
        bterimamSelector = "#bobot_terima_"+$key
        bpesanSelector = "#bobot_pesan_"+$key


        pesanVal = $(pesanSelector).val()
        terimaVal = $(terimamSelector).val()
        bpesanVal = $(bpesanSelector).val()
        bterimaVal = $(bterimamSelector).val()
        
        hasil=(bterimaVal*terimaVal)-(bpesanVal*pesanVal)
        
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
