@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <form id="form_filter" action="{{url('Penjual/PreOrder/Filter')}}" method="POST">
                @csrf

                    <div class="row">
                        <div class="col-xs-5">
                            <h1 class="card-title"><strong>Daftar Pre Order | {{Carbon\Carbon::parse($data['filter_tanggal'])->format('D, d M Y')}}</strong></h1>
                        </div>
                        <div class="col-xs-4 p-3">
                            <select name="tanggal" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Pre Order</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->tanggal_pre_order == $data['filter_tanggal'])
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} ({{$tanggal->total}})</option>
                                    @else            
                                        <option value="{{url('/Penjual/PreOrder/Tanggal/'.$tanggal->tanggal_pre_order)}}" >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} ({{$tanggal->total}})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3 p-3">
                            {{-- <a class="btn btn-sm btn-outline-primary" href="{{url('/Penjual/PreOrder/Tambah')}}">Pre Order</a> --}}
                            <a class="btn btn-sm btn-success" href="{{url('/Penjual/PreOrder/Akumulasi')}}">Akumulasi</a>
                        </div>
                    </div>
                </div>
                </form>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="data-table data-table-feature">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Invoice</th>
                                    <th>Nama</th>
                                    <th>Alamat | Nomor Telepon</th>
                                    <th>Tanggal Order</th>
                                    
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['preorder'] as $key => $preorder)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$preorder->nomor_invoice}}</td>
                                        <td>{{$preorder->user->name}}</td>
                                        <td>{{$preorder->alamat->alamat}} {{$preorder->alamat->blok_nomor}} | {{$preorder->user->nomor_hp}}</td>
                                        <td>{{$preorder->created_at->format('d M Y | H.i')}}</td>
                                        
                                        <td>
                                            <a class="badge badge-success mb-1 text-white" href="{{url('/Penjual/Pengiriman/Finalisasi/'.$preorder->id)}}">Pre Order</a>
                                            @if($preorder->isAlreadyPay == 1)
                                                <a class="badge badge-info mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Tunggu Konfirmasi</a>
                                            @elseif($preorder->isAlreadyPay == 2)
                                                <a class="badge badge-danger mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Gagal</a>
                                            @elseif($preorder->isAlreadyPay == 3)
                                                <a class="badge badge-success mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Lunas</a>
                                            @else
                                                <a class="badge badge-warning mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Belum Lunas</a>
                                            @endif
                                            {{-- {{strpos($preorder->nomor_invoice, 'VGRESELLER')}} --}}
                                            @if (strpos($preorder->nomor_invoice, 'VGRES') === 0)
                                                <a class="badge badge-info mb-1 text-white" href="{{url('/Penjual/PembeliOffline/'.$preorder->id)}}">Detail</a>
                                            @else
                                                <a class="badge badge-info mb-1 text-white" href="{{url('/Penjual/OrderDetail/'.$preorder->id)}}">Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="pembayaran_modal_{{$preorder->nomor_invoice}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Status Pembayaran</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                              <div id="bukti_transfer_div" class="d-flex justify-content-center">
                                                @if($preorder->bukti_transfer==null)
                                                <p class="text-muted">-- Belum Upload Bukti Transfer --</p>
                                                @else
                                                <img class="card-img-top" src="{{ asset('img/foto_bukti/'.$preorder->bukti_transfer) }}">
                                                @endif
                                              </div>
                                                <form action="{{url('/Penjual/PreOrder/Pembayaran/Update')}}" method="POST">
                                                  @csrf
                                                    <input type="hidden" name="id_transaksi" value="{{$preorder->id}}">
                                                  <div class="form-group">
                                                      <select name="status_transaksi" id="" class="form-control select2-single">
                                                          <option selected disabled>Status Pembayaran</option>
                                                          @foreach ($data['status_pembayaran'] as $status_pembayaran)
                                                            @if ($preorder->isAlreadyPay == $status_pembayaran[1])
                                                                <option value="{{$status_pembayaran[1]}}" selected>{{$status_pembayaran[0]}}</option>
                                                            @else
                                                                <option value="{{$status_pembayaran[1]}}">{{$status_pembayaran[0]}}</option>
                                                            @endif
                                                          @endforeach
                                                      </select>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-xs btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                          </div>
                                        </div>
                                    </div>                                    
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function applyFilter(){
        $('#form_filter').submit()
    }
</script>
    
@endsection
