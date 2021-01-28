@extends('penjual.layouts.app')
@section('title','Pengiriman')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title">Tambah Pengiriman | <strong>{{$data['current_date']}}</strong></h1>
                        </div>
                        <div class="col-3">
                            <select name="" id="tanggal_kirim" class="form-control">
                                <option value=" " selected disabled>Tanggal Kirim</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if($data['current_date'] == $tanggal->tanggal_pre_order)
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected>{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('d M Y')}}</option>
                                    @else
                                        <option value="{{$tanggal->tanggal_pre_order}}">{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('d M Y')}}</a></option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            @if($data['current_kurir']==null || strlen($data['current_kurir'])<7)
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm float-right dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                Simpan
                            </button>
                            @else
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm float-right dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Simpan
                            </button>
                            @endif
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" onclick="submitForm()">Yakin</a>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                    
                        <label for="">Pilih Kurir/Ojol</label>
                        <select name="" id="pilih_kurir" class="form-control select2-single">
                            <option value="" selected disabled>Pilih Kurir</option>
                            @foreach ($data['kurir'] as $kurir)
                                @if($data['current_kurir'] == $kurir->id)
                                    <option selected value="{{$kurir->id}}">{{$kurir->name}}</option>
                                @else
                                    <option value="{{$kurir->id}}">{{$kurir->name}}</option>
                                @endif
                            @endforeach
                            <option value="ojol" >Ojol</option>
                            
                        </select> 
                    
                    </div>
                    <br>
                    <div id="plat-form-div">
                    </div> 
                    <br>
                    <div class="form-group">                  
                        <label for="">Pilih Reseller</label>
                        <select name="" id="pilih_reseller" class="form-control select2-single">
                            <option value="" selected disabled>Pilih Reseller</option>
                            @foreach ($data['reseller'] as $reseller)
                                @if($data['current_reseller'] == $reseller->id)
                                    <option selected value="{{$reseller->id}}">{{$reseller->name}}</option>
                                @else
                                    <option value="{{$reseller->id}}">{{$reseller->name}}</option>
                                @endif
                            @endforeach                            
                        </select> 
                    </div>                                        
                    {{-- <div class="form-group">
                        <input type="text" class="form-control" readonly placeholder="Nama Kurir" value="">
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="form_pengiriman" action="{{url('Penjual/Pengiriman/Tambah')}}" method="POST">
                        @csrf
                        <input type="hidden" id="current_kurir" name="current_kurir" value="{{$data['current_kurir']}}">
                        <input type="hidden" id="current_reseller" name="current_reseller" value="{{$data['current_reseller']}}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="display:none">id</th>
                                    <th>Pembeli</th>
                                    <th>Invoice</th>
                                    <th>Alamat</th>
                                    <th hidden="true">Jarak</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['preorder'] as $preorder)
                                    <tr>
                                        <td style="display:none"><input type="hidden" name="transaksi[]" value="{{$preorder->id}}"></td>
                                        <td>{{$preorder->user->name}}</td>
                                        <td>{{$preorder->nomor_invoice}}</td>
                                        <td>{{$preorder->alamat->alamat}}, {{$preorder->alamat->blok_nomor}}</td>
                                        <td hidden="true">jarak juga</td>
                                        <td>
                                            <div class="custom-switch custom-switch-primary mb-2 custom-switch-small">
                                                <input class="custom-switch-input" id="switchS_{{$preorder->nomor_invoice}}" type="checkbox" value="{{$preorder->id}}" name="pengiriman[]">
                                                <label class="custom-switch-btn" for="switchS_{{$preorder->nomor_invoice}}"></label>
                                            </div>                                        
                                        </td>
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
<div style="display:none">
    <form id="filter_form" action="{{url('Penjual/Pengiriman/Tambah/Filter')}}" method="POST">
        @csrf
        <input type="hidden" id="input_tanggal" name="tanggal" value="{{$data['current_date']}}">
        <input type="hidden" id="input_kurir" name="kurir" value="{{$data['current_kurir']}}">
        <input type="hidden" id="input_reseller" name="reseller" value="{{$data['current_reseller']}}">
    </form>
</div>
@endsection
@section('script')
<script>
    $("#pilih_kurir").change(function () {
        tanggal = $('#tanggal_kirim option:selected').val();
        kurir = $('#pilih_kurir option:selected').val();
        reseller = $('#pilih_reseller option:selected').val();
        // console.log(kurir.length)
        if(kurir=="ojol"){
            // console.log(kurir)
            $('#input_tanggal').val(tanggal)
            $('#input_kurir').val(kurir)
            $('#current_kurir').val(kurir)
            $('#input_reseller').val(reseller)
            $('#current_reseller').val(reseller)
            var fieldHTML = '<div id="plat-div"><label for="">Plat Nomor</label><input type="text" id="plat_nomor" class="form-control" name="plat_nomor" placeholder="Plat Nomor" onkeyup="getPlat()" required></div>'; //New input field html 
            $('#plat-form-div').append(fieldHTML);
            $('#btnGroupDrop1').prop('disabled', true);
        }
        else{
            $('#plat-div').remove();
            $('#input_tanggal').val(tanggal)
            $('#input_kurir').val(kurir)
            $('#current_kurir').val(kurir)
            $('#input_reseller').val(reseller)
            $('#current_reseller').val(reseller)
            // $('#filter_form').submit()
            $('#btnGroupDrop1').prop('disabled', false);
        }
    });

    $("#pilih_reseller").change(function () {
        tanggal = $('#tanggal_kirim option:selected').val();
        reseller = $('#pilih_reseller option:selected').val();
        // console.log(kurir.length)
        $('#input_tanggal').val(tanggal)
        $('#input_reseller').val(reseller)
        $('#current_reseller').val(reseller)
        // $('#filter_form').submit()
    });

    $("#tanggal_kirim").change(function () {
        tanggal = $('#tanggal_kirim option:selected').val();
        kurir = $('#pilih_kurir option:selected').val();
        $('#input_tanggal').val(tanggal)
        $('#input_kurir').val(kurir)
        $('#filter_form').submit()
    });

    function submitForm(){
        $('#form_pengiriman').submit()
    }
    function getPlat(){
        $('#input_tanggal').val(tanggal)
        $('#input_kurir').val($('#plat_nomor').val())
        $('#current_kurir').val($('#plat_nomor').val())
        // var plat = new RegExp('^([A-Z]{1,3})(\s|-)*([1-9][0-9]{0,3})(\s|-)*([A-Z]{0,3}|[1-9][0-9]{1,2})$');
        if($('#plat_nomor').val().length >6){
            $('#btnGroupDrop1').prop('disabled', false);
        }
        else{
            $('#btnGroupDrop1').prop('disabled', true);
        }
    }

</script>    
@endsection
