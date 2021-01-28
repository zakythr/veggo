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
                            <h1 class="card-title">Tambah Klaim | <strong>{{session('filter_tambah_klaim_invoice')}}</strong></h1>
                        </div>
                        <div class="col-6">
                            <div>
                                <select id="nomor_invoice_dropdown" class="form-control select2-single">
                                    <option selected disabled>Pilih Nomor Invoice</option>
                                    @foreach ($data['list_invoice'] as $list_invoice)
                                        @if($list_invoice->nomor_invoice == session('filter_tambah_klaim_invoice'))
                                            <option value="{{$list_invoice->nomor_invoice}}" selected>{{$list_invoice->nomor_invoice}}</option>
                                        @else
                                            <option value="{{$list_invoice->nomor_invoice}}">{{$list_invoice->nomor_invoice}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form action="{{url('/Penjual/OrderPetani/Konfirmasi/Klaim')}}" method="POST" enctype='multipart/form-data' >
                        @csrf
                        <input type="hidden" name="nomor_invoice" value="{{$data['transaksi']->nomor_invoice}}">
                        <input type="hidden" name="id_petani" value="{{$data['transaksi']->id_user}}">
                        <input type="hidden" name="id_transaksi" value="{{$data['transaksi']->id}}">
                        <input type="hidden" name="tanggal_kirim" value="{{$data['transaksi']->tanggal_pengiriman}}">
                        <div id="klaim-form-div">

                        </div>
                        <hr>
                        @if(session('filter_tambah_klaim_invoice'))
                            <button class="btn btn-sm btn-primary mt-3 float-right" type="submit">Simpan</button>
                            <button class="btn btn-sm btn-info mt-3 mr-2 float-right" id="tambah_row" type="button">Tambah</button>
                        @else
                            <button class="btn btn-sm btn-primary mt-3 float-right" type="button" disabled>Pilih Nomor Invoice</button>
                        @endif
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<form id="form_filter" action="{{url('/Penjual/Klaim/Tambah/Filter')}}" method="POST" style="display:none">
    @csrf
    <input id="form_filter_value" type="text" style="display:none" name="nomor_invoice" value="{{session('filter_tambah_klaim_invoice')}}">
</form>
@foreach ($data['detailTransaksi'] as $item)
<div style="display:none">
    <input  id="hidden_harga_{{$item->id}}" value="{{$item->bobot_terima * $item->volume_terima}}">
    <input  id="hidden_id_{{$item->id}}" value="{{$item->id_barang}}">
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
            // var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-3"><select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="nama[]"></select></div><div class="col-2"><input type="text" class="form-control" id="form_harga_'+x+'" name="harga[]" placeholder="Harga" readonly></div><div class="col-2"><input type="number" onkeyup="getBiaya('+x+')" id="form_volume_'+x+'" class="form-control" name="volume[]" placeholder="Volume" required></div><div class="col-2"><input type="text" class="form-control" id="form_biaya_'+x+'" name="biaya[]" placeholder="Biaya" readonly required></div><div class="col-2"><input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan" required></div><div class="col-1"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
            var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-3"> <select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="id_detail_transaksi[]" required></select></div><div class="col-2"><input type="number" id="form_volume_'+x+'" class="form-control" name="volume[]" placeholder="Volume" onkeyup="getBiaya('+x+')" readonly required></div><div class="col-2"><input type="text" class="form-control" name="keterangan[]" id="form_keterangan_'+x+ '" placeholder="Keterangan" readonly required></div> <div class="col-2"><input type="file" class="form-control" name="bukti[]" id="form_bukti_'+x+ '" placeholder="Foto Bukti" readonly required></div><div style="display:none" class="col-2"><input type="text" id="form_id_detail_transaksi_'+x+'" class="form-control" name="nama[]" placeholder="id_detail_transaksi" readonly required></div> <div class="col-1"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
            $(wrapper).append(fieldHTML);
            
            data = '{!! $data['detailTransaksi_json'] !!}';
            data = JSON.parse(data)
            html = '<option value="">Pilih Barang</option>'
            $('#dropdown_nama_'+x).append(html)
            for(i=0;i<data.length;i++){
                console.log(data[i]);
                html = '<option value="'+data[i]['id']+'">'+data[i]['nama_barang']+' '+data[i]['bobot_kemasan']+'gram'+'</option>'
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
        val = $(selector).val()
        harga_gram = $("#hidden_harga_"+val).val()
        id_dt_trx = $("#hidden_id_"+val).val()
        form_harga = $("#form_volume_"+key).val(harga_gram)
        form_id=$("#form_id_detail_transaksi_"+key).val(id_dt_trx)
        
        if(val==""){
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
        val = $(selector).val()
        harga_gram = $("#hidden_harga_"+val).val()
        form_volume = $("#form_volume_"+key).val()
        min=harga_gram-form_volume

        console.log(harga_gram)
        console.log(form_volume)
        
        if(min<0 || form_volume < 0){
            $("#form_volume_"+key).val(harga_gram)
        }  
    }

    $('#nomor_invoice_dropdown').change(function(){
        invoice = $('#nomor_invoice_dropdown option:selected').val();
        $('#form_filter_value').val(invoice);
        $('#form_filter').submit();
    });

</script>
@endsection
