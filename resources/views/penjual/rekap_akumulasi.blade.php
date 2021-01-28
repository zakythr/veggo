@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
@php
function toKg($volume){
    $volume = (int) $volume;
    return $volume/1000;
}
@endphp
<div class="container-fluid" style="overflow-x:auto;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row p-3">
                        <div class="col-6">
                            <h5 class="card-title">Kirim Order Ke Petani | <strong>{{Carbon\Carbon::parse($data['current_date'])->format('D, d M Y')}}</strong></h5>
                        </div>
                        <div class="col-6">
                            <form action="{{url('/Penjual/PreOrder/Akumulasi/Rekap')}}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{$data['current_date']}}">
                                <button type="submit" class="btn btn-success btn-sm float-right">
                                    Kirim
                                </button>  
                            </form>
                        </div>
                    </div>                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-6">
                            <h3><strong>Pesanan Pembeli</strong></h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        
                                        <th>Produk</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['rekap'] as $key => $rekap)
                                    <tr>
                                        
                                        <td>{{$rekap->nama}} | {{$rekap->jenis}}                                             
                                        </td>
                                        <td>
                                            @if($rekap->jenis == "Kemas")
                                                {{$rekap->volume}} x {{$rekap->bobot_kemasan}} {{$rekap->satuan}}
                                            @else
                                                {{$rekap->volume}} {{$rekap->satuan}}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>        
                        </div>
                        <div class="col-xs-6">
                            <h3><strong>Dikirim ke Petani</strong></h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        
                                        <th>Produk</th>
                                        <th>Bobot</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['rekap_sayur'] as $key => $rekap_sayur)
                                    <tr>
                                        
                                        <td>{{$rekap_sayur->nama_barang}}</td>
                                        <td>
                                            {{$rekap_sayur->bobot}} gram
                                        </td>
                                        <td>
                                            {{$rekap_sayur->volume}}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>                                        
                                </tbody>
                            </table>        
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Daftar Pembeli</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_detail">
          

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>    

@section('script')
<script>
    function getDetail(id_barang,date){
        $.ajax({
            type: 'GET',
            url: '/Penjual/PreOrder/Akumulasi/Barang/Detail/'+id_barang+'/'+date,
            success: function (data) {
                data.forEach(element => {
                    $("#modal_detail").html("")
                    html = "<li>"+element['name']+"</li>"
                    $("#modal_detail").append(html)
                });
            }
        });        
    }
</script>

@endsection

@endsection
