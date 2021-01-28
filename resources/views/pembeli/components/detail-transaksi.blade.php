<div class="row">
    <div class="col-12 list" data-check-all="checkAll" id="cart-modal-body">
        
        <table class="table table-responsive">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Nama Item</th>
                    <th scope="col">Jenis</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Harga Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail['transaksi'] as $detailTransaksi)
                    <tr>
                        <td>
                            {{ $detailTransaksi->nama }}
                        </td>
                        <td>
                            {{ $detailTransaksi->jenis }}
                        </td>
                        <td>
                            {{ $detailTransaksi->volume }} {{ $detailTransaksi->satuan }}
                        </td>
                        <td>
                            Rp. {{ number_format($detailTransaksi->harga_diskon,0,',','.') }},-
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
