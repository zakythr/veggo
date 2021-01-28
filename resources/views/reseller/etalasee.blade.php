@extends('reseller.layouts.layout')

@section('title')
    Checkout Pemesanan    
@endsection

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Reseller/Checkout/Purchase') }}">
    @csrf
    <div class="col-12">
        <div class="alert alert-success" role="alert">
            Pembayaran dapat dilakukan setelah pemesanan.
        </div>
    </div>
              
    <br>
    <br>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">                
                    <div class="row">
                        <div class="col-6" align="left">
                            <h3>Tanggal Pengiriman</h3>
                        </div>
                    </div>            
                </div>
                <hr>
                <div class="mb-4">                
                        <select class="form-control" name="tanggal_pengiriman">
                            @foreach($data['tanggal_pengiriman'] as $tanggal)
                            <option value="{{$tanggal->tanggal_value}}">{{$tanggal->tanggal}}</option>
                            @endforeach
                        </select>
                
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Alamat Pemesan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <select class="form-control" name="alamat">
                        @foreach($data['alamat'] as $alamat)
                        <option value="{{$alamat->id}}">
                        <div>
                            <a href="#">
                                <div class="row">                                
                                    <p class="list-item-heading mb-1 color-theme-1">{{ $alamat->nama_alamat }}</p>
                                    <br>
                                    <a class="mb-4 text-small">Jalan {{ $alamat->alamat}}, {{$alamat->blok_nomor }}, {{ $alamat->kodepos }}</a>
                                    <br>
                                    <a class="mb-1 text-muted text-small">{{$alamat->daerah}}</a>
                                </div>
                            </a>
                        </div>
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Pesanan</h2>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div id="pesanan-form-div">
                        <div id="row_ke_1" class="form-row mb-3">
                            <div class="col-8"> 
                                <select onchange="getDetail(1)" class="form-control" id="dropdown_nama_1" name="id_usr[]" required>
                                    <option>Pilih Pembeli Offline</option>
                                    @foreach($data['user'] as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2" align="right">
                                <button type="button" id="add_product_btn" class="btn btn-sm btn-info default">
                                    Tambah Produk
                                </button>
                            </div>
                            <div id="produk-form-div">

                            </div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-info mt-3 mr-2 float-right" id="tambah_row" type="button">Tambah</button>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Keterangan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan keterangan" name="keterangan">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div align="center">
        <div align="center">
            @if($data['alamat']->count()>0)
            <button style="color:white;" class="btn btn-success" type="submit">Pesan</button>
            @else
            <button style="color:white;" class="btn btn-success" type="submit" disabled>Pesan</button>
            @endif
        </div>
    </div>
</form>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function(){
    var addButton = $('#tambah_row');
    var addProductButton= $('#add_product_button');
    var wrapper = $('#pesanan-form-div');
    var x = 2;

    $(addButton).click(function(){
        x++; 
        // var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-3"><select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="nama[]"></select></div><div class="col-2"><input hidden="true" type="text" class="form-control" id="form_harga_'+x+'" name="harga[]" placeholder="Harga" readonly></div><div class="col-2"><input type="number" onkeyup="getBiaya('+x+')" id="form_volume_'+x+'" class="form-control" name="volume[]" placeholder="Volume" required></div><div class="col-2"><input hidden="true" type="text" class="form-control" id="form_biaya_'+x+'" name="biaya[]" placeholder="Biaya" readonly required></div><div class="col-2"><input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan" required></div><div class="col-1"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
        var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-8"><select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="id_usr[]" required></select></div><div class="col-2" align="right"><button type="button" id="add_product_btn"onclick="addProduct('+x+')" class="btn btn-sm btn-info default">Tambah Produk</button></div><div class="col-2"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
        $(wrapper).append(fieldHTML);
        
        data = '{!! $data['user_json'] !!}';
        data = JSON.parse(data)
        html = '<option value="">Pilih Pembeli Offline</option>'
        $('#dropdown_nama_'+x).append(html)
        for(i=0;i<data.length;i++){
            console.log(data[i]);
            html = '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>'
            $('#dropdown_nama_'+x).append(html)
                        
        }
    });

    $('#add_product_btn').click(function(){
        x++; 
        var fieldHTML = '<div id="row_ke_'+x+'" class="form-row mb-3"><div class="col-8"><select onchange="getDetail('+x+')" class="form-control" id="dropdown_nama_'+x+'" name="id_usr[]" required></select></div><div class="col-2" align="right"><button type="button" id="add_product_btn"onclick="addProduct('+x+')" class="btn btn-sm btn-info default">Tambah Produk</button></div><div class="col-2"><button type="button" id="delete_row_btn" onclick="deleteRow('+x+')" class="btn btn-sm btn-danger default"><i class="simple-icon-ban text-white"></i></button></div></div>'; //New input field html 
        $(wrapper).append(fieldHTML);
        
        data = '{!! $data['user_json'] !!}';
        data = JSON.parse(data)
        html = '<option value="">Pilih Pembeli Offline</option>'
        $('#dropdown_nama_'+x).append(html)
        for(i=0;i<data.length;i++){
            console.log(data[i]);
            html = '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>'
            $('#dropdown_nama_'+x).append(html)
                        
        }
    });
});



function deleteRow(param){
        selector = '#row_ke_'+param;
        $(selector).remove();
    }

function deleteRow(param){
    selector = '#row_ke_'+param;
    $(selector).remove();
}
</script>
@endsection