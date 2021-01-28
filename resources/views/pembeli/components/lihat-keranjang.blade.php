<form method="POST" action="{{ url('Pembeli/Checkout') }}">
    @csrf
    <div class="form-group">
        <input hidden name="tanggal_pre_order" value="{{$data['tanggal_pengiriman']}}">
    </div>
    <div class="row" style="overflow-x:auto;">
        <div class="col-12 list" data-check-all="checkAll" id="cart-modal-body">
                <div class="form-group">
                    <label for="inputState">Tanggal Pengiriman</label>
                    <p>{{Carbon\Carbon::parse($data['tanggal_pengiriman'])->format('D, d M Y')}}</p>
                </div>
                @if($data['detail_keranjang']->count() > 0)
                <table class="table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Nama Item</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Harga Total</th>
                            <th scope="col">Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['detail_keranjang'] as $detailKeranjang)
                            <tr>
                                <td>
                                    {{ $detailKeranjang->nama }}
                                </td>
                                <td>
                                    {{ $detailKeranjang->jenis }}
                                </td>
                                <td>
                                    {{ $detailKeranjang->volume }} {{ $detailKeranjang->satuan }}
                                </td>
                                <td>
                                    Rp. {{ number_format($detailKeranjang->harga_diskon,0,',','.') }},-
                                </td>
                                <td style="color:white;">
                                    <span class="badge badge-pill badge-success mb-1" onclick="ubahItem('{{ $detailKeranjang->id }}')">
                                        Ubah
                                    </span>
                                    <span class="badge badge-pill badge-danger mb-1" onclick="hapusItem('{{ $detailKeranjang->id }}')">
                                        Hapus
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h4 align="center">Total = Rp. {{ number_format($data['total'],0,',','.') }},- </h4><br>
                @else
                <div class="alert alert-danger">
                    <a class="kkosong">Keranjang Kosong !</a>
                </div>
                @endif
        </div>
    </div>
    @if($data['detail_keranjang']->count() > 0)
        <div align="center">
            <button id="checkout" style="color:white;" class="btn btn-success" type="submit">Checkout Keranjang</button>
        </div>
    @else
        <div align="center">
            <button id="checkout" style="color:white;" class="btn btn-success" disabled>Checkout Keranjang</button>
        </div>
    @endif
</form>


