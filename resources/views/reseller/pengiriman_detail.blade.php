@extends('reseller.layouts.layout')

@section('title')
    Detail Paket
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Tujuan Paket</h5>
                    <hr>
                    <p class="text-muted text-small mb-2">Nomor Invoice</p>
                    <p class="mb-3">{{$data['transaksi'][0]->nomor_invoice}}</p>
                    <p class="text-muted text-small mb-2">Nama Penerima</p>
                    <p class="mb-3">{{$data['transaksi'][0]->name}}</p>
                    <p class="text-muted text-small mb-2">Nomor HP</p>
                    <p class="mb-3">{{$data['transaksi'][0]->nomor_hp}}</p>
                    <p class="text-muted text-small mb-2">Total Harga</p>
                    <p class="mb-3">Rp {{number_format($data['detail_barang_transaksi'][0]->total_bayar_akhir,2,',','.')}}</p>
                    @if ($data['detail_barang_transaksi'][0]->total_bayar_akhir-$data['detail_barang_transaksi'][0]->total_bayar>0)
                        <p class="text-muted text-small mb-2">Jumlah yang Belum Dibayar</p>
                        <p class="mb-3">Rp {{number_format($data['detail_barang_transaksi'][0]->total_bayar_akhir-$data['detail_barang_transaksi'][0]->total_bayar,2,',','.')}}</p>
                    @elseif($data['detail_barang_transaksi'][0]->total_bayar_akhir-$data['detail_barang_transaksi'][0]->total_bayar<0)
                        <p class="text-muted text-small mb-2">Jumlah yang Dikembalikan ke Pembeli</p>
                        <p class="mb-3">Rp {{number_format(abs($data['detail_barang_transaksi'][0]->total_bayar_akhir-$data['detail_barang_transaksi'][0]->total_bayar),2,',','.')}}</p>
                    @else
                    @endif
                    <p class="text-muted text-small mb-2">Alamat</p>
                    <p class="mb-3">Jl. {{$data['transaksi'][0]->alamat}}, {{$data['transaksi'][0]->blok_nomor}}, {{$data['transaksi'][0]->daerah}}</p>
                    @if ($data['transaksi'][0]->info_tambahan==null)
                        <p class="mb-3">Informasi Tambahan: Tidak Ada</p>
                    @else
                        <p class="mb-3">Informasi Tambahan: {{$data['transaksi'][0]->info_tambahan}}</p>
                    @endif
                    <p class="text-muted text-small mb-2">Status Pembayaran</p>
                        @if($data['transaksi'][0]->isAlreadyPay != 0)
                            <span class="badge badge-pill badge-success mb-1">Sudah Dibayar</span>
                        @else
                            <span class="badge badge-pill badge-warning mb-1">Belum Dibayar</span>
                        @endif
                    </p>
                    <p class="text-muted text-small mb-2">Status Alamat</p>
                        @if($data['transaksi'][0]->lat != null & $data['transaksi'][0]->long != null)
                            <span class="badge badge-pill badge-success mb-1">Terverifikasi</span>
                        @else
                            <span class="badge badge-pill badge-warning mb-1">Belum Terverifikasi</span>
                        @endif
                    </p>
                    <p class="text-muted text-small mb-2">Kode Pos</p>
                    <p class="mb-3">{{$data['transaksi'][0]->kodepos}}</p>
                    <p class="text-muted text-small mb-2">Keterangan</p>
                    <p class="mb-3">{{$data['transaksi'][0]->keterangan}}</p>
                </div>
            </div>
            <br>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Isi Paket</h5>
                    <hr>
                    <div id="accordion">
                        @foreach($data['detail_barang_transaksi'] as $transaksi)
                            @if($transaksi->jenis == 'Paket')
                            <div class="border">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$transaksi->detail_transaksi_id}}" aria-expanded="false" aria-controls="collapsecollapse{{$transaksi->detail_transaksi_id}}">
                                    {{$transaksi->nama}}
                                </button>
        
                                <div id="collapse{{$transaksi->detail_transaksi_id}}" class="collapse" data-parent="#accordion" style="">
                                    <div class="p-4">
                                        <p class="text-muted text-small mb-2">Jenis</p>
                                        <p class="mb-3">
                                            <span class="badge badge-pill badge-success mb-1">Paket</span>
                                        </p>
                                        <p class="text-muted text-small mb-2">Jumlah yang Dikirim</p>
                                        <p class="mb-3">{{$transaksi->volume_kirim_kurir}}</p>
                                        <p class="text-muted text-small mb-2">Harga Satuan</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon/$transaksi->volume_kirim_kurir,2,',','.')}}</p>
                                        <p class="text-muted text-small mb-2">Harga Sub Total</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon,2,',','.')}}</p>
                                        <p class="text-muted text-small mb-2">Isi Paket</p>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama Barang</th>
                                                    <th scope="col">Volume</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $a=1; ?>
                                                @foreach($data['isi_paket'][$transaksi->detail_transaksi_id] as $isi_paket)
                                                    <tr>
                                                        <th scope="row">{{ $a }}</th>
                                                        <td>{{ $isi_paket['nama'] }}</td>
                                                        <td>{{$transaksi->volume_kirim_kurir}} x {{ $isi_paket['volume'] }} {{ $isi_paket['satuan'] }}</td>
                                                    </tr>
                                                    <?php $a++; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @elseif($transaksi->jenis == 'Kemas')
                            <div class="border">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$transaksi->detail_transaksi_id}}" aria-expanded="false" aria-controls="collapsecollapse{{$transaksi->detail_transaksi_id}}">
                                    {{$transaksi->nama}}
                                </button>
                                <div id="collapse{{$transaksi->detail_transaksi_id}}" class="collapse" data-parent="#accordion" style="">
                                    <div class="p-4">
                                        <p class="text-muted text-small mb-2">Jenis</p>
                                        <p class="mb-3">
                                            <span class="badge badge-pill badge-success mb-1">Kemas</span>
                                        </p>
                                        <p class="text-muted text-small mb-2">Jumlah yang Dikirim</p>
                                        <p class="mb-3">{{$transaksi->volume_kirim_kurir}}</p>
                                        <p class="text-muted text-small mb-2">Harga Satuan</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon/$transaksi->volume_kirim_kurir,2,',','.')}}</p>
                                        <p class="text-muted text-small mb-2">Harga Sub Total</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon,2,',','.')}}</p>
                                    </div>
                                </div>
                            </div>
                            @elseif($transaksi->jenis == 'Timbang')
                            <div class="border">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$transaksi->detail_transaksi_id}}" aria-expanded="false" aria-controls="collapsecollapse{{$transaksi->detail_transaksi_id}}">
                                    {{$transaksi->nama}}
                                </button>
                                <div id="collapse{{$transaksi->detail_transaksi_id}}" class="collapse" data-parent="#accordion" style="">
                                    <div class="p-4">
                                        <p class="text-muted text-small mb-2">Jenis</p>
                                        <p class="mb-3">
                                            <span class="badge badge-pill badge-success mb-1">Timbang</span>
                                        </p>
                                        <p class="text-muted text-small mb-2">Jumlah yang Dikirim</p>
                                        <p class="mb-3">{{$transaksi->volume_kirim_kurir}}</p>
                                        <p class="text-muted text-small mb-2">Harga Satuan</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon/$transaksi->volume_kirim_kurir,2,',','.')}}</p>
                                        <p class="text-muted text-small mb-2">Harga Sub Total</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_akhir_diskon,2,',','.')}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        <div class="border">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_ongkir" aria-expanded="false" aria-controls="collapsecollapse_ongkir">
                                Ongkos Kirim
                            </button>
                            <div id="collapse_ongkir" class="collapse" data-parent="#accordion" style="">
                                <div class="p-4">                                    
                                    <p class="text-muted text-small mb-2">Harga</p>
                                    <p class="mb-3">Rp {{number_format(10000,2,',','.')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <br>
            @if($data['transaksi'][0]->lat != null & $data['transaksi'][0]->long != null)
                <a href="http://maps.google.com/?q=<{{$data['transaksi'][0]->lat}}>,<{{$data['transaksi'][0]->long}}>" class="btn btn-success mb-1">Lihat Di Google Maps</a>
            @endif
            <button type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#modal{{$data['transaksi'][0]->transaksi_id}}">
                Konfirmasi Pengiriman
              </button>              
        </div>
    </div>
</div>
@endsection

@section('modal')
    @foreach($data['transaksi'] as $transaksi)
        <div class="modal fade" id="modal{{$transaksi->transaksi_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Paket Selesai Dikirim {{ $transaksi->nomor_invoice }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{ url('Kurir/Paket/SedangDikirim/KonfirmasiSelesaiDikirim/'.$transaksi->transaksi_id) }}" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div>
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" class="form-control" name="nama_penerima">
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan_penerima">
                            </div>
                            <div class="form-group">
                                <label>Foto</label>
                                <input type="file" class="form-control" name="foto_penerima">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a style="color:white;" class="btn btn-danger" data-dismiss="modal">Tutup</a>
                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                    </div>
                </form>
            </div>
            </div>
        </div>  
    @endforeach
@endsection