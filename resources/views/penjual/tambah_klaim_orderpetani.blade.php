@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title">Tambah Klaim | <strong>{{$data['transaksi']->nomor_invoice}}</strong></h1>
                        </div>
                        <div class="col-6">
                            {{-- <a href="{{url('/Penjual/Produk/Tambah')}}" class="btn btn-sm btn-primary  float-right">Tambah</a> --}}
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form action="{{url('/Penjual/OrderPetani/Konfirmasi/Klaim')}}" method="POST" enctype='multipart/form-data'>
                        @csrf
                        <input type="hidden" name="id_petani" value="{{$data['transaksi']->id_user}}">
                        <input type="hidden" name="nomor_invoice" value="{{$data['transaksi']->nomor_invoice}}">
                        <input type="hidden" name="id_transaksi" value="{{$data['transaksi']->id}}">
                        <input type="hidden" name="tanggal_kirim" value="{{$data['transaksi']->tanggal_pengiriman}}">
                        <div id="klaim-form-div" style="overflow-x:auto;" >

                        </div>
                        <hr>
                        <button class="btn btn-sm btn-success mt-3 float-right" type="submit">Simpan</button>
                        <button class="btn btn-sm btn-info mt-3 mr-2 float-right" id="tambah_row" type="button">Tambah</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@foreach ($data['detailTransaksi'] as $item)
<div style="display:none">
<input type="hidden" id="hidden_harga_{{$item->nama_barang}} {{$item->bobot_kemasan}}gram" value="{{$item->bobot_terima * $item->volume_terima}}">
<input type="hidden" id="hidden_id_{{$item->nama_barang}} {{$item->bobot_kemasan}}gram" value="{{$item->id}}">
</div>
@endforeach
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        var addButton = $('#tambah_row');
        var wrapper = $('#klaim-form-div');
        var x = 1;
    
        $(addButton).click(function(){
            x++; 
            // var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-3"><select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="nama[]"></select></div><div class="col-2"><input hidden="true" type="text" class="form-control" id="form_harga_'+x+'" name="harga[]" placeholder="Harga" readonly></div><div class="col-2"><input type="number" onkeyup="getBiaya('+x+')" id="form_volume_'+x+'" class="form-control" name="volume[]" placeholder="Volume" required></div><div class="col-2"><input hidden="true" type="text" class="form-control" id="form_biaya_'+x+'" name="biaya[]" placeholder="Biaya" readonly required></div><div class="col-2"><input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan" required></div><div class="col-1"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
            var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-xs-3"> <select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="nama[]" required></select></div><div class="col-xs-2"><input type="number" id="form_volume_'+x+'" class="form-control" name="volume[]" placeholder="Volume" onkeyup="getBiaya('+x+')" readonly required></div><div class="col-xs-2"><input type="text" class="form-control" name="keterangan[]" id="form_keterangan_'+x+ '" placeholder="Keterangan" readonly required></div> <div class="col-xs-3"><input type="file" class="form-control" name="bukti[]" id="form_bukti_'+x+ '" placeholder="Foto Bukti" readonly required></div><div style="display:none" class="col-xs-2"><input type="text" id="form_id_detail_transaksi_'+x+'" class="form-control" name="id_detail_transaksi[]" placeholder="id_detail_transaksi" readonly required></div> <div class="col-xs-1"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
            $(wrapper).append(fieldHTML);
            
            data = '{!! $data['detailTransaksi_json'] !!}';
            data = JSON.parse(data)
            html = '<option value="">Pilih Barang</option>'
            $('#dropdown_nama_'+x).append(html)
            for(i=0;i<data.length;i++){
                console.log(data[i]);
                html = '<option value="'+data[i]['id_barang']+'">'+data[i]['nama_barang']+' '+data[i]['bobot_kemasan']+'gram'+'</option>'
                $('#dropdown_nama_'+x).append(html)
                            
            }
        });
    
    });
    
    function deleteRow(param){
        selector = '#row_ke_'+param;
        $(selector).remove();
    }

    function getDetail(key){
        selector = "#dropdown_nama_"+key+" option:selected"
        val = $(selector).html()
        vall="hidden_harga_"+val
        vallid="hidden_id_"+val
        harga_gram = $("[id='" + vall + "']").val()
        id_dt_trx = $("[id='" + vallid + "']").val()
        form_harga = $("#form_volume_"+key).val(harga_gram)
        form_id=$("#form_id_detail_transaksi_"+key).val(id_dt_trx)
        if(val=="Pilih Barang"){
            $("#form_volume_"+key).attr("readonly", true); 
            $("#form_keterangan_"+key).attr("readonly", true);  
            $("#form_bukti_"+key).attr("readonly", true);        
        }
        else{
            $("#form_volume_"+key).attr("readonly", false); 
            $("#form_keterangan_"+key).attr("readonly", false); 
            $("#form_bukti_"+key).attr("readonly", false);     
        }
        
    }

    function getBiaya(key){
        selector = "#dropdown_nama_"+key+" option:selected"
        val = $(selector).html()
        vall="hidden_harga_"+val
        harga_gram = $("[id='" + vall + "']").val()
        form_volume = $("#form_volume_"+key).val()
        min=harga_gram-form_volume
        
        if(min<0 || form_volume < 0){
            $("#form_volume_"+key).val(harga_gram)
        }  
    }
</script>
@endsection
