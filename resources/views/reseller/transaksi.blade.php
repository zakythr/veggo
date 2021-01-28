@extends('reseller.layouts.layout')

@section('title')
    Transaksi
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                <select onChange="window.location.href=this.value" id="inputState" class="form-control" name="tanggal_pre_order">
                    @foreach($data['alltype'] as $pengiriman)
                    @if ($data['tipe']==$pengiriman['status'])
                    <option value="{{url('Reseller/Transaksi/Tipe/'.$pengiriman['status'])}}" selected>{{$pengiriman['status2']}}</option>    
                    @else
                    <option value="{{url('Reseller/Transaksi/Tipe/'.$pengiriman['status'])}}">{{$pengiriman['status2']}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>Transaksi {{$data['tipe2']}}</h2>
            </div>
            <div class="card-body">
                @if($data['transaksi']->count()>0)
                <table class="data-table data-table-feature">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Total Bayar</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Status</th>
                            <th>Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['transaksi'] as $key => $transaksi)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$transaksi->nomor_invoice}}</td>
                                @if ($transaksi->total_bayar_akhir==null)
                                    <td>Rp. {{ number_format($transaksi->total_bayar,0,',','.') }},-</td>    
                                @else
                                    <td>Rp. {{ number_format($transaksi->total_bayar_akhir,0,',','.') }},-</td>
                                @endif
                                
                                <td>{{$transaksi->tanggal_pre_order}}</td>
                                <td>
                                    @if($transaksi->status == 1)
                                        <a class="badge badge-info mb-1 text-white">Pre Order</a>
                                    @elseif($transaksi->status == 2)
                                        <a class="badge badge-info mb-1 text-white">Tunggu Konfirmasi</a>
                                    @elseif($transaksi->status == 4)
                                        <a class="badge badge-info mb-1 text-white">Terverifikasi</a>
                                    @elseif($transaksi->status == 5)
                                        <a class="badge badge-info mb-1 text-white">Proses Kirim</a>
                                    @elseif($transaksi->status==6)
                                        <a class="badge badge-info mb-1 text-white">Dikirim</a>
                                    @else
                                        <a class="badge badge-info mb-1 text-white">Selesai</a>
                                    @endif
                                </td>
                                <td>
                                    @if($data['tipe']=="BelumDibayar")
                                        <a href="{{url('/Reseller/Transaksi/Detail/'.$transaksi->id)}}" class="badge badge-primary mb-1 text-white" >Detail</a>
                                        @if($transaksi->isAlreadyPay == 1)
                                            <a class="badge badge-info mb-1 text-white">Tunggu Konfirmasi</a>
                                        @elseif($transaksi->isAlreadyPay == 2)
                                            <a class="badge badge-danger mb-1 text-white">Gagal</a>
                                        @else
                                            <a class="badge badge-warning mb-1 text-white" data-toggle="modal" data-target="#modal{{$transaksi->id}}">Bayar</a>
                                        @endif
                                    @elseif($data['tipe']=="Selesai")
                                        <a class="badge badge-info mb-1 text-white" data-toggle="modal" data-target=".detail-transaksi" onclick="lihatItem('{{$transaksi->id}}')">Detail</a>
                                        @if($transaksi->is_confirm_finish_byuser==0)
                                        <a class="badge badge-primary mb-1 text-white" href="{{ url('Reseller/Transaksi/Konfirmasi/'.$transaksi->id) }}">Konfirmasi</a>
                                        @endif
                                    @else
                                        <a href="{{url('/Reseller/Transaksi/Detail/'.$transaksi->id)}}" class="badge badge-primary mb-1 text-white" >Detail</a>
                                    @endif
                                </td>
                            </tr> 
                            
                            <div class="modal fade" id="modal{{$transaksi->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Lakukan Pembayaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    
                                    <form method="POST" action="{{ url('Reseller/Transaksi/Bayar/'.$transaksi->id) }}" enctype='multipart/form-data'>
                                        @csrf
                                        <div class="modal-body">
                                            <div>
                                                <p>Lakukan pembayaran dengan transfer ke:</p>
                                                <p>Nomor Rekening: {{$data['rekening']['nomor_rek']}}</p>
                                                <p>Bank: {{$data['rekening']['bank']}}</p>
                                                <p>Atas Nama: {{$data['rekening']['atas_nama']}}</p>
                                            </div>
                                            <div>
                                                <div class="form-group">
                                                <label>Upload Foto Bukti Transfer</label>
                                                    <input type="file" class="form-control" name="foto_bukti" required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a style="color:white;" class="btn btn-danger" data-dismiss="modal">Tutup</a>
                                            <button type="submit" class="btn btn-success">Krim</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div> 
                        @endforeach    
                    </tbody>
                </table>
                @else
                <div align="center">
                    <h5>Kosong</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>                               
@endsection

@section('modal')
    <div class="modal fade detail-transaksi" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="tutup_lihat_keranjang">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader" align="center">
                        <br><br><br><br>
                        <div class="loading"></div>
                        <a>Harap Tunggu</a>
                    </div>
                    <div id="detail-transaksii">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function lihatItem(transaksi)
    {
        $('#detail-transaksii').empty();
        $('.loader').show();
        $.ajax({
            type: 'GET',
            url: '/Reseller/Transaksi/Detail/'.concat(transaksi),
            success: function (data) {
                $('#detail-transaksii').append(data);
                $('.loader').hide();
                console.log(data)
            }
        });
    }
</script>